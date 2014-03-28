package tests;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import main.TfIdf;

public class TfIdfTest {

	public static void main(String[] args) throws ClassNotFoundException, SQLException {
		String input = "Anyone who reads Old and Middle English literary texts will be familiar with the mid-brown volumes of the EETS, with the symbol of Alfred's jewel embossed on the front cover. Most of the works attributed to King Alfred or to Aelfric, along with some of those by bishop Wulfstan and much anonymous prose and verse from the pre-Conquest period, are to be found within the Society's three series; all of the surviving medieval drama, most of the Middle English romances, much religious and secular prose and verse including the English works of John Gower, Thomas Hoccleve and most of Caxton's prints all find their place in the publications. Without EETS editions, study of medieval English texts would hardly be possible.";
		 Connection c = null;
		 String dbPath = "C:/Users/Matthijs Brouns/Documents/GitHub/crowd-summary/app/webroot/crowdsum";
		 Class.forName("org.sqlite.JDBC");
		 c = DriverManager.getConnection("jdbc:sqlite:" + dbPath);
		 c.setAutoCommit(false);
		 System.out.println("Database connection established");
		 
		 
		List<String[]> allTerms = new ArrayList<String[]>();
		PreparedStatement sqlGetAllDocFulltext = c
				.prepareStatement(
						"SELECT fulltext FROM documents WHERE id != ?;");
		sqlGetAllDocFulltext.setInt(1, 3);
		ResultSet rsGetAllDocFullText = sqlGetAllDocFulltext.executeQuery();
		
		while (rsGetAllDocFullText.next()){
			String[] termsInDoc = rsGetAllDocFullText.getString("fulltext").split("\\s+");
			allTerms.add(termsInDoc);
		}
		
		String[] termsInDoc = input.split("\\s+");
		allTerms.add(termsInDoc);
		
		TfIdf tfidf = new TfIdf(allTerms);
		
		for (Map.Entry<String, Double> entry : tfidf.getTfIdfList(allTerms.size()-1).entrySet()) {
			String key = entry.getKey();
			double value = entry.getValue();
			
			System.out.println(key + " => " + value);
		}
	}
}
