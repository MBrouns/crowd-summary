package main;

/**
 * 
 */
import java.io.Reader;
import java.io.StringReader;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;

import edu.stanford.nlp.process.CoreLabelTokenFactory;
import edu.stanford.nlp.process.DocumentPreprocessor;
import edu.stanford.nlp.process.PTBTokenizer;
import edu.stanford.nlp.process.TokenizerFactory;
import edu.stanford.nlp.util.StringUtils;

/**
 * @author mbrouns
 * 
 */
public class Summarizer {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		String input = null;
		int docID = 0;
		String dbPath = null;
		String connectionType = "sqlite";
		if (args.length > 1) {
			try {
				docID = Integer.parseInt(args[0]);
			} catch (NumberFormatException e) {
				System.err.println("DocID must be an integer");
				System.exit(1);
			}
			try {
				dbPath = args[1];
			} catch (Exception e) {
				System.err.println("dbPath must be a string");
				System.exit(1);
			}
			try {
				if(args.length > 2){
					connectionType = args[2];
				}
			} catch (Exception e) {
				System.err.println("connectionType must be a string");
				System.exit(1);
			}
		} else {
			System.err
					.println("No DocID or dbPath specified. Please provide it through a command line argument");
			System.exit(1);
		}

		Connection c = null;
		try {
			if(connectionType.toLowerCase().equals("mysql")){
				Class.forName("com.mysql.jdbc.Driver");
			      // setup the connection with the DB.
			      c = DriverManager
			          .getConnection("jdbc:mysql://localhost/database?"
			              + "user=sqluser&password=sqluserpw");
			}else{		      
				Class.forName("org.sqlite.JDBC");
				c = DriverManager.getConnection("jdbc:sqlite:" + dbPath);
			}
			c.setAutoCommit(false);
			System.out.println("Database connection established");

			
			//Check if a summary already exists for the given document ID.
			PreparedStatement sqlCheckDocumentSummarized = c
					.prepareStatement("Select [document_id] FROM sentences WHERE document_id = ?;");
			sqlCheckDocumentSummarized.setInt(1, docID);

			ResultSet rsDocumentSummarized = sqlCheckDocumentSummarized
					.executeQuery();
			while (rsDocumentSummarized.next()) {
				System.out.println("Document already summarized, aborting");
				System.exit(1);
			}

			//Get fulltext as input for summarizer
			PreparedStatement sqlSelectDocumentText = c
					.prepareStatement("Select [fulltext] FROM documents WHERE id = ?;");
			sqlSelectDocumentText.setInt(1, docID);

			ResultSet rs = sqlSelectDocumentText.executeQuery();
			while (rs.next()) {
				input = rs.getString("fulltext");
			}

			// No. of lines is the sqrt of the number of sentences
			String[] allSentences = getSentencesStanford(input);
			int noOfLines = (int) Math.max((Math.ceil(Math.sqrt(allSentences.length))),0.1*allSentences.length);
			System.out.println("noOfLines: " + noOfLines);
			
			
			//Add individual sentences to database sentences table
			for (String s : allSentences) {
				PreparedStatement sqlAddSentence = c
						.prepareStatement(
								"INSERT INTO sentences (document_id, sentence) VALUES (?, ?);",
								Statement.RETURN_GENERATED_KEYS);
				sqlAddSentence.setInt(1, docID);
				sqlAddSentence.setString(2, s.replaceAll("\\*NL\\*", "\n\n"));
				sqlAddSentence.execute();

			}

			System.out.println("Database insertion complete");

			System.out.println("Start generating keywords for document");
			
			
			//Get fulltext for all documents for use in tf-idf calculation
			List<String[]> allTerms = new ArrayList<String[]>();
			PreparedStatement sqlGetAllDocFulltext = c
					.prepareStatement(
							"SELECT fulltext FROM documents WHERE id != ?;");
			sqlGetAllDocFulltext.setInt(1, docID);
			ResultSet rsGetAllDocFullText = sqlGetAllDocFulltext.executeQuery();
			
			while (rsGetAllDocFullText.next()){
				String[] termsInDoc = rsGetAllDocFullText.getString("fulltext").split("\\s+");
				allTerms.add(termsInDoc);
			}
			
			String[] termsInDoc = input.split("\\s+");
			allTerms.add(termsInDoc);
			
			//calculate tf-idf
			TfIdf tfidf = new TfIdf(allTerms);
			TreeMap<Double, String> keywordList = new TreeMap<Double, String>(
					new Comparator<Double>() {
						public int compare(Double a, Double b) {
							if (a > b) {
								return -1;
							} else if (a < b) {
								return 1;
							} else
								return 0;
						}
					});
			
			//Sort words based on tf-idf and select 5 highest rated words
			for (Map.Entry<String, Double> entry : tfidf.getTfIdfList(allTerms.size()-1).entrySet()) {
				String key = entry.getKey();
				double value = entry.getValue();
				keywordList.put(value, key);
				
			}
			int keywordIterator = 0;
			ArrayList<String> stopWordList = StopWordProvider.newDefaultStopWordProvider();
			for (Map.Entry<Double, String> entry : keywordList.entrySet()) {
				if(entry.getValue().length() > 5 && !stopWordList.contains(entry.getValue())){
					if(keywordIterator > 5){
						break;
					}
					PreparedStatement sqlAddKeyword = c
							.prepareStatement("INSERT INTO keywords (document_id, keyword) VALUES (?, ?)");
					sqlAddKeyword.setInt(1, docID);
					sqlAddKeyword.setString(2, entry.getValue());
					sqlAddKeyword.execute();
					keywordIterator++;
					System.out.println(entry.getKey() + " => " + entry.getValue());
				}
			}
			System.out.println("Keywords stored in database");
			c.commit();

			PreparedStatement sqlSelectNewDocSentences = c
					.prepareStatement("Select * FROM sentences WHERE document_id = ?;");
			sqlSelectNewDocSentences.setInt(1, docID);

			ResultSet rsGetNewDocSentences = sqlSelectNewDocSentences
					.executeQuery();
			TreeMap<Double, Integer> ratedSentences = new TreeMap<Double, Integer>(
					new Comparator<Double>() {
						public int compare(Double a, Double b) {
							if (a > b) {
								return -1;
							} else if (a < b) {
								return 1;
							} else
								return 0;
						}
					});

			
			//make new classifier instance and use it to put relevant sentences into the database
			Classifier classifier = new Classifier(c);

			while (rsGetNewDocSentences.next()) {
				int sentenceID = rsGetNewDocSentences.getInt("id");
				ClassifierSentence sentence = new ClassifierSentence(
						sentenceID, c);

				ratedSentences.put(classifier.getSentenceRelevancy(sentence),
						sentenceID);
			}
			int i = 0;
			for (Map.Entry<Double, Integer> entry : ratedSentences.entrySet()) {
				double key = entry.getKey();
				int value = entry.getValue();
				
				System.out.println(key + " => " + value);
				if(i <= noOfLines){
					PreparedStatement sqlAddUsersSentences = c
							.prepareStatement("INSERT INTO users_sentences (user_id, sentence_id, ranking) VALUES (0, ?, 1)");
					sqlAddUsersSentences.setInt(1, value);
					sqlAddUsersSentences.execute();
					System.out.println("added sentence to db");
				}
				
				i++;
			}
			c.commit();

			c.close();
			System.out.println("Database connection closed");
		} catch (Exception e) {
			System.err.println(e.getClass().getName() + ": " + e.getMessage());
			System.exit(0);
		}

	}

	/**
	 * Gets sentences from input using the StanfordNLP DocumentPreprocessor
	 * 
	 * @param input
	 *            A String which may contain many sentences
	 * @return Sentences from input split into an array of strings
	 */
	@SuppressWarnings({ "rawtypes", "unchecked" })
	public static String[] getSentencesStanford(String input) {
		Reader reader = new StringReader(input);
		final TokenizerFactory tf = PTBTokenizer
				.factory(new CoreLabelTokenFactory(),
						"normalizeParentheses=false,normalizeOtherBrackets=false,invertible=true,tokenizeNLs=true");
		DocumentPreprocessor preProcessor = new DocumentPreprocessor(reader);
		preProcessor.setTokenizerFactory(tf);

		List<String> sentenceList = new ArrayList<String>();
		for (List sentence : preProcessor) {
			sentenceList.add(StringUtils.joinWithOriginalWhiteSpace(sentence));
		}

		return sentenceList.toArray(new String[0]);
	}
	



}
