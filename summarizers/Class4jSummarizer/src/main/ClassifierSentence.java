package main;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class ClassifierSentence {

	private int sentenceID;
	private String content;
	private int length;
	private int posInDocument;
	private double titleSimilarity;
	private double keywordSimilarity;
	private boolean hasAnaphora;
	private boolean hasProperNouns;
	
	

	@Override
	public String toString() {
		return "ClassifierSentence [sentenceID=" + sentenceID + ", content="
				+ content + ", length=" + length + ", posInDocument="
				+ posInDocument + ", keywordSimilarity=" + keywordSimilarity
				+ "]";
	}


	public ClassifierSentence(int sentenceID, Connection c) {
		super();
		//TODO: Calculate this stuff
		this.sentenceID = sentenceID;
		this.content = content;
		this.length = length;
		this.posInDocument = posInDocument;
		this.titleSimilarity = titleSimilarity;
		this.keywordSimilarity = keywordSimilarity;
		this.hasAnaphora = hasAnaphora;
		this.hasProperNouns = hasProperNouns;
		try{
			PreparedStatement sqlGetSentence = c
					.prepareStatement("SELECT id, document_id, sentence FROM sentences WHERE id = ?");
			sqlGetSentence.setInt(1, sentenceID);
			ResultSet rsGetSentence = sqlGetSentence.executeQuery();
			while (rsGetSentence.next()) {
				String sentenceContent = rsGetSentence.getString("sentence");
				this.content = sentenceContent;
				this.length = sentenceContent.split("\\s+").length;
				this.keywordSimilarity = 0;
			
				PreparedStatement sqlGetDocStartEnd = c
						.prepareStatement("SELECT MIN(id) AS begin, MAX(id) AS end, document_id FROM sentences WHERE document_id = ? GROUP BY document_id ");
				sqlGetDocStartEnd.setInt(1, rsGetSentence.getInt("document_id"));
				ResultSet rsGetDocStartEnd = sqlGetDocStartEnd.executeQuery();
				while (rsGetDocStartEnd.next()) {
					
					double position = ((sentenceID - rsGetDocStartEnd.getInt("begin"))*100)/(rsGetDocStartEnd.getInt("end") - rsGetDocStartEnd.getInt("begin"));
					//System.out.println("Pos In Doc: " + sentenceID + " begin: " + rsGetDocStartEnd.getInt("begin") + "end: " + rsGetDocStartEnd.getInt("end") + "pos: " + position); 
					this.posInDocument = (int) position;
				}
				
				PreparedStatement sqlGetDocKeywords = c
						.prepareStatement("SELECT id, document_id, keyword FROM keywords WHERE document_id = ?");
				sqlGetDocKeywords.setInt(1, rsGetSentence.getInt("document_id"));
				ResultSet rsGetDocKeywords = sqlGetDocKeywords.executeQuery();
				while (rsGetDocKeywords.next()) {
					if(sentenceContent.contains(rsGetDocKeywords.getString("keyword"))){
						this.keywordSimilarity++;
					}
				}
			}
		}catch(Exception e){
			e.printStackTrace();
		}
	}


	/**
	 * @return the sentenceID
	 */
	public int getSentenceID() {
		return sentenceID;
	}


	/**
	 * @return the content
	 */
	public String getContent() {
		return content;
	}


	/**
	 * @return the length
	 */
	public int getLength() {
		return length;
	}


	/**
	 * @return the posInDocument
	 */
	public int getPosInDocument() {
		return posInDocument;
	}


	/**
	 * @return the titleSimilarity
	 */
	public double getTitleSimilarity() {
		return titleSimilarity;
	}


	/**
	 * @return the keywordSimilarity
	 */
	public double getKeywordSimilarity() {
		return keywordSimilarity;
	}


	/**
	 * @return the hasAnaphora
	 */
	public boolean isHasAnaphora() {
		return hasAnaphora;
	}


	/**
	 * @return the hasProperNouns
	 */
	public boolean isHasProperNouns() {
		return hasProperNouns;
	}

	
	
}
