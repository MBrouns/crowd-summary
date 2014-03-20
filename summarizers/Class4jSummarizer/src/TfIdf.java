
import java.util.List;


public class TfIdf {
    
	/**
	 * Caluclate tf of termToCheck
	 * @param totalterms - array of words in doc
	 * @param termToCheck - term for which tf must be calcluated
	 * @return tf - term frequency of termTocheck
	 */
    public double tfCalculator(String[] totalterms, String termToCheck) {
        double count = 0;  //to count the overall occurrence of the term termToCheck
        for (String s : totalterms) {
            if (s.equalsIgnoreCase(termToCheck)) {
                count++;
            }
        }
        return count / totalterms.length;
    }

    /**
     * calculate idf of termToCheck
     * @param allTerms - all terms in all documents
     * @param termToCheck - term of which idf should 
     * @return idf - idf score
     */
    public double idfCalculator(List<String[]> allTerms, String termToCheck) {
        double count = 0;
        for (String[] ss : allTerms) {
            for (String s : ss) {
                if (s.equalsIgnoreCase(termToCheck)) {
                    count++;
                    break;
                }
            }
        }
        return Math.log(allTerms.size() / count);
    }
//
}
//