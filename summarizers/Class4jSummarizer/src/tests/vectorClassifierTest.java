/**
 * 
 */
package tests;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import net.sf.classifier4J.ClassifierException;
import net.sf.classifier4J.vector.HashMapTermVectorStorage;
import net.sf.classifier4J.vector.TermVectorStorage;
import net.sf.classifier4J.vector.VectorClassifier;

/**
 * @author Matthijs Brouns
 * 
 * Small test to see how Classifier4J's vector classifier works.
 * 
 * It seems that it is not possible to train the classifier on more than one sentence, making it completely useless for our application
 *
 */
public class vectorClassifierTest {

	/**
	 * @param args
	 * @throws ClassifierException 
	 * @throws ClassNotFoundException 
	 * @throws SQLException 
	 */
	public static void main(String[] args) throws ClassifierException, ClassNotFoundException, SQLException {
		
		String dbPath = null;
		if (args.length > 0) {
			try {
				dbPath = args[0];
			} catch (Exception e) {
				System.err.println("dbPath must be a string");
				System.exit(1);
			}
		} else {
			System.err
					.println("No DocID or dbPath specified. Please provide it through a command line argument");
			System.exit(1);
		}
		
		
		 TermVectorStorage storage = new HashMapTermVectorStorage();
		 VectorClassifier vc = new VectorClassifier(storage);
		 
		 Connection c = null;
		 Class.forName("org.sqlite.JDBC");
		 c = DriverManager.getConnection("jdbc:sqlite:" + dbPath);
		 c.setAutoCommit(false);
		 System.out.println("Database connection established");

		 PreparedStatement sqlUserSentences = c
					.prepareStatement("Select sentence FROM users_sentences LEFT JOIN sentences ON sentences.id = users_sentences.sentence_id;");
		 
		 ResultSet rsUserSentences = sqlUserSentences
					.executeQuery();
		 
		 
		 while (rsUserSentences.next()) {
			 try{
			 System.out.println(rsUserSentences.getString("sentence"));
			 vc.teachMatch(rsUserSentences.getString("sentence"));
			 }catch (Exception e){
				 e.printStackTrace();
			 }
			 
		 }	
		 
	
		 double result = vc.classify("Wikipedia® is a registered trademark of the Wikimedia Foundation, Inc., a non-profit organization");
		 System.out.println(result);
	}

}
