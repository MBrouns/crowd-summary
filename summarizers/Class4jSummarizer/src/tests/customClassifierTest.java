package tests;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

import main.Classifier;
import main.ClassifierSentence;

public class customClassifierTest {

	public static void main(String[] args) throws ClassNotFoundException, SQLException {
		 Connection c = null;
		 String dbPath = "C:/xampp/htdocs/crowd-summary/app/webroot/crowdsum";
		 Class.forName("org.sqlite.JDBC");
		 c = DriverManager.getConnection("jdbc:sqlite:" + dbPath);
		 c.setAutoCommit(false);
		 System.out.println("Database connection established");
		 
		Classifier classifier = new Classifier(c);
		ClassifierSentence sentence = new ClassifierSentence(669, c);
		System.out.println("Sentence score = " + classifier.getSentenceRelevancy(sentence));
		System.out.println("Sentence is = " + classifier.isRelevantSentence(sentence));
	}

}
