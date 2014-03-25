import java.util.ArrayList;
import java.util.List;
import java.util.TreeMap;

import net.sf.classifier4J.Utilities;

public class TfIdf {

	private List<TreeMap<String, Double>> termFrequencies;
	private List<String[]> allTermLists;

	/**
	 * Construct new TfIdf calculator for the given document set
	 * 
	 * @param allterms
	 *            - list of documents: documents represented as a String-array
	 *            of their terms
	 */
	public TfIdf(List<String[]> allTerms) {
		allTermLists = allTerms;
		termFrequencies = new ArrayList<TreeMap<String, Double>>();
		this.calculateTermFrequencies();
	}

	/**
	 * Gets the TF-IDF list for document at location 'index' in allTermLists
	 * 
	 * @param i
	 * @return A treemap with the tf-idf values for all strings in the given
	 *         document
	 */
	public TreeMap<String, Double> getTfIdfList(int index) {
		TreeMap<String, Double> tfidf = new TreeMap<String, Double>();
		String[] doc = allTermLists.get(index);

		String currentTerm = "";
		double tfidfValue = 0;
		// Fill treemap with tf-idf values
		for (int i = 0; i < doc.length; i++) {
			currentTerm = doc[i];

			// don't calculate for terms we have already seen
			if (!tfidf.containsKey(currentTerm)) {
				tfidfValue = getTermFrequency(index, currentTerm) * calculateIDF(currentTerm);
				tfidf.put(currentTerm, tfidfValue);
			}
		}

		return tfidf;
	}

	/**
	 * Returns the TF of term in the document with ID docid in allTermLists
	 * 
	 * @param docid
	 *            The document ID in allTermLists
	 * @param term
	 *            the term to get TF for
	 * @return
	 */
	public double getTermFrequency(int docid, String term) {
		return termFrequencies.get(docid).get(term);
	}

	/**
	 * Fills the termFrequencies map list with values for all terms in each
	 * document
	 */
	public void calculateTermFrequencies() {
		String[] currentDoc = null;
		TreeMap<String, Double> currentMap = null;

		// First get document and its corresponding map
		for (int i = 0; i < allTermLists.size(); i++) {
			currentDoc = allTermLists.get(i);
			currentMap = termFrequencies.get(i);

			// Then fill the TF map for that document
			for (int j = 0; j < currentDoc.length; j++) {
				currentMap.put(currentDoc[j],
						calculateTF(currentDoc, currentDoc[j]));
			}
		}
	}

	/**
	 * Calculate TF of termToCheck in totalTerms
	 * 
	 * @param totalterms
	 *            - array of words in doc
	 * @param termToCheck
	 *            - term for which TF must be calculated
	 * @return TF - term frequency of termTocheck
	 */
	public double calculateTF(String[] totalTerms, String termToCheck) {
		// TODO rework this to use Utilities.getWordFrequency??
		double count = Utilities.countWords(termToCheck, totalTerms);
		return count / totalTerms.length;
	}

	/**
	 * Calculate IDF of termToCheck compared to allTermLists in this TF-IDF
	 * instance. IDF = (number of documents) / (number of documents containing
	 * termToCheck)
	 * 
	 * @param termToCheck
	 *            - term of which IDF should be calculated
	 * @return IDF - inverse document frequency of termToCheck
	 */
	public double calculateIDF(String termToCheck) {
		double count = 0;
		for (int i = 0; i < termFrequencies.size(); i++) {
			if (termFrequencies.get(i).get(termToCheck) > 0) {
				count++;
			}
		}
		return Math.log(allTermLists.size() / count);
	}
	//
}
//