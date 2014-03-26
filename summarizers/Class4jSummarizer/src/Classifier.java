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
	
	
	/**
	 * Constructs a new classifier and trains it using training data from the database
	 */
	public Classifier() {
		createTrainingData();
		LinearClassifierFactory<String, String> factory = new LinearClassifierFactory<String, String>();
		factory.useConjugateGradientAscent();
		// Turn on per-iteration convergence updates
		factory.setVerbose(true);
		// Small amount of smoothing
		factory.setSigma(10.0);
		// Build a classifier
		this.classifier =  factory.trainClassifier(this.trainingData);
	}
	
	/**
	 * @return 
	 * 
	 */
	protected void createTrainingData(){
		//TODO: Get relevant sentences from db and add to trainingData
		
		//TODO: Get irrelevent sentences from db and add to trainingData
		
		//this.trainingData.add(makeSentence(, true));
	}
	
	/**
	 * Creates Datum of string that is to be classified
	 * 
	 * @param sentence
	 * @return
	 */
	protected  static Datum<String, String> makeSentence(classifierSentence sentence) {
		return makeSentence(sentence, true);
	}
	
	
	/**
	 * Creates Datum of string that is to be used for training
	 * 
	 * @param sentence
	 * @param relevant
	 * @return
	 */
	protected  static Datum<String, String> makeSentence(classifierSentence sentence, boolean relevant) {
		List<String> features = new ArrayList<String>();
		// Create content feature
		features.add("CONTENT=" + sentence);
		
		//TODO: Add more features here
		// Create the label
		String label = (relevant ? RELEVANT : NOT_RELEVANT);
		return new BasicDatum<String, String>(features, label);
		
	}
	
	
	/**
	 * Returns whether sentence is relevant to the summary
	 * @param sentence
	 * @return
	 */
	public boolean isRelevantSentence(classifierSentence sentence){
		if(this.classifier.classOf(makeSentence(sentence)).equals(RELEVANT)){
			return true;
		}else{
			return false;
		}	
	}

}
