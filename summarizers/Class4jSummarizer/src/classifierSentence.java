public class classifierSentence {

	private int sentenceID;
	private String content;
	private int length;
	private int posInDocument;
	private double titleSimilarity;
	private double keywordSimilarity;
	private boolean hasAnaphora;
	private boolean hasProperNouns;
	
	
	public classifierSentence(int sentenceID) {
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
