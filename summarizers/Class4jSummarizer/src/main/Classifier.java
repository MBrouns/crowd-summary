package main;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.List;

import edu.stanford.nlp.classify.LinearClassifier;
import edu.stanford.nlp.classify.LinearClassifierFactory;
import edu.stanford.nlp.ling.BasicDatum;
import edu.stanford.nlp.ling.Datum;

public class Classifier {

	private LinearClassifier<String, String> classifier;
	private List<Datum<String, String>> trainingData = new ArrayList<Datum<String, String>>();
	protected static final String RELEVANT = "relevant";
	protected static final String NOT_RELEVANT = "notrelevant";
	private Connection c;

	/**
	 * Constructs a new classifier and trains it using training data from the
	 * database
	 */
	public Classifier(Connection c) {
		this.c = c;
		createTrainingData();
		System.out.println("training data created");
		LinearClassifierFactory<String, String> factory = new LinearClassifierFactory<String, String>();
		factory.useConjugateGradientAscent();
		// Turn on per-iteration convergence updates
		factory.setVerbose(true);
		// Small amount of smoothing
		factory.setSigma(10.0);
		// Build a classifier
		this.classifier = factory.trainClassifier(this.trainingData);
	}

	/**
	 * @return
	 * 
	 */
	protected void createTrainingData() {
		// TODO: ignore sentences from user_id = 0
		try {
			Class.forName("org.sqlite.JDBC");
			c.setAutoCommit(false);
			System.out.println("Database connection in classifier established");

			PreparedStatement sqlGetDocuments = c
					.prepareStatement("SELECT documents.id, COUNT(sentences.id) AS noOfSentences FROM documents LEFT JOIN sentences ON documents.id = sentences.document_id GROUP BY documents.id");
			ResultSet rsGetDocuments = sqlGetDocuments.executeQuery();
			while (rsGetDocuments.next()) {

				int noOfRelevantSentences = (int) Math.ceil(Math.sqrt(rsGetDocuments
						.getInt("noOfSentences")));
				PreparedStatement sqlGetSentences = c
						.prepareStatement("SELECT COUNT(users_sentences.id) AS times, sentences.id, sentences.sentence, sentences.document_id FROM sentences LEFT JOIN users_sentences ON sentences.id = users_sentences.sentence_id WHERE sentences.document_id = ? AND users_sentences.user_id != 0 GROUP BY sentences.id ORDER BY times DESC");
				sqlGetSentences.setInt(1, rsGetDocuments.getInt("id"));

				ResultSet rsGetSentences = sqlGetSentences.executeQuery();
				int i = 0;
				while (rsGetSentences.next()) {
					boolean relevant;
					if(i < noOfRelevantSentences){
						relevant = true; 
					}else{
						relevant = false;
					}
					ClassifierSentence sentence = new ClassifierSentence(rsGetSentences.getInt("id"), this.c);
					this.trainingData.add(makeSentence(sentence, relevant));
					if (relevant){
						System.out.println("Added relevant sentence to training: " + sentence.toString());
					}else{
						System.out.println("Added non relevant sentence to training: " + sentence.toString());
					}
					i++;
				}
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
	 * Creates Datum of string that is to be classified
	 * 
	 * @param sentence
	 * @return
	 */
	protected static Datum<String, String> makeSentence(
			ClassifierSentence sentence) {
		return makeSentence(sentence, true);
	}

	/**
	 * Creates Datum of string that is to be used for training
	 * 
	 * @param sentence
	 * @param relevant
	 * @return
	 */
	protected static Datum<String, String> makeSentence(
			ClassifierSentence sentence, boolean relevant) {
		List<String> features = new ArrayList<String>();
		// Create content feature
		features.add("CONTENT=" + sentence.getContent());
		features.add("LENGTH=" + sentence.getLength());
		features.add("KEYWORDSIM=" + sentence.getKeywordSimilarity());
		features.add("POSINDOC=" + sentence.getPosInDocument());
		features.add("HASNOTE=" + sentence.isHasNote());
		// TODO: Add more features here
		// Create the label
		String label = (relevant ? RELEVANT : NOT_RELEVANT);
		return new BasicDatum<String, String>(features, label);

	}

	/**
	 * Returns whether sentence is relevant to the summary
	 * 
	 * @param sentence
	 * @return
	 */
	public boolean isRelevantSentence(ClassifierSentence sentence) {
		if (this.classifier.classOf(makeSentence(sentence)).equals(RELEVANT)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Returns a relevancy score of the sentence
	 * @param sentence
	 * @return
	 */
	public double getSentenceRelevancy(ClassifierSentence sentence){
		return (double) this.classifier.scoresOf(makeSentence(sentence)).values().toArray()[1];
	}

}
