/**
 * 
 */
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Arrays;

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
		}else{
			System.err.println("No DocID or dbPath specified. Please provide it through a command line argument");
	        System.exit(1);
		}
		
		Connection c = null;
		try{
			Class.forName("org.sqlite.JDBC");
			c = DriverManager.getConnection("jdbc:sqlite:"+dbPath);
			c.setAutoCommit(false);
			System.out.println("Database connection established");
			
			PreparedStatement sqlCheckDocumentSummarized = c.prepareStatement("Select [document_id] FROM sentences WHERE document_id = ?;");
			sqlCheckDocumentSummarized.setInt(1, docID);
			
			ResultSet rsDocumentSummarized = sqlCheckDocumentSummarized.executeQuery();
			while (rsDocumentSummarized.next()){
				System.out.println("Document already summarized, aborting");
				System.exit(1);
			}
			
			PreparedStatement sqlSelectDocumentText = c.prepareStatement("Select [fulltext] FROM documents WHERE id = ?;");
			sqlSelectDocumentText.setInt(1, docID);
			
			ResultSet rs = sqlSelectDocumentText.executeQuery();
			while (rs.next()){
				input = rs.getString("fulltext");
			}
		
			CustomSummarizer summariser = new CustomSummarizer();
			int noOfLines = (int) Math.floor(CustomSummarizer.getSentences(input).length * 0.1);
			String result = summariser.summarise(input, noOfLines);
			String[] resultSentences = CustomSummarizer.getSentences(result);
			String[] allSentences = CustomSummarizer.getSentences(input);
			System.out.println("Summary sentences found, inserting into database");
			
			for(String s: allSentences){
				PreparedStatement sqlAddSentence = c.prepareStatement("INSERT INTO sentences (document_id, sentence) VALUES (?, ?);", Statement.RETURN_GENERATED_KEYS);
				sqlAddSentence.setInt(1, docID);
				sqlAddSentence.setString(2, s);				
				sqlAddSentence.execute();
				if(Arrays.asList(resultSentences).contains(s)){
					ResultSet generatedKeys = sqlAddSentence.getGeneratedKeys();
					if(generatedKeys.next()){
						try{
						PreparedStatement sqlAddUserSentence = c.prepareStatement("INSERT INTO users_sentences (user_id, sentence_id, ranking) VALUES (0, ?, 1)");
						sqlAddUserSentence.setInt(1, generatedKeys.getInt(1));
						sqlAddUserSentence.execute();
						}catch (Exception e){
							e.printStackTrace();
						}
					}else{
						throw new SQLException("Adding sentence failed");
					}
				}
				
				
			}
			System.out.println("Database insertion complete");
			
			System.out.println("Start generating keywords for document");
			
			for(String s: summariser.getKeywords(input, 3)){
				PreparedStatement sqlAddKeyword = c.prepareStatement("INSERT INTO keywords (document_id, keyword) VALUES (?, ?)");
				sqlAddKeyword.setInt(1, docID);
				sqlAddKeyword.setString(2,  s);
				sqlAddKeyword.execute();
			}
			System.out.println("Keywords stored in database");
		    c.commit();
		    c.close();
		    System.out.println("Database connection closed");
		}catch( Exception e){
			System.err.println( e.getClass().getName() + ": " + e.getMessage() );
		    System.exit(0);
		}
		
	}

}
