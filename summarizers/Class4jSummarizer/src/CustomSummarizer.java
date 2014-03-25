/*
 * ====================================================================
 * 
 * The Apache Software License, Version 1.1
 *
 * Copyright (c) 2003-2005 Nick Lothian. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer. 
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *
 * 3. The end-user documentation included with the redistribution, if
 *    any, must include the following acknowlegement:  
 *       "This product includes software developed by the 
 *        developers of Classifier4J (http://classifier4j.sf.net/)."
 *    Alternately, this acknowlegement may appear in the software itself,
 *    if and wherever such third-party acknowlegements normally appear.
 *
 * 4. The name "Classifier4J" must not be used to endorse or promote 
 *    products derived from this software without prior written 
 *    permission. For written permission, please contact   
 *    http://sourceforge.net/users/nicklothian/.
 *
 * 5. Products derived from this software may not be called 
 *    "Classifier4J", nor may "Classifier4J" appear in their names 
 *    without prior written permission. For written permission, please 
 *    contact http://sourceforge.net/users/nicklothian/.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED.  IN NO EVENT SHALL THE APACHE SOFTWARE FOUNDATION OR
 * ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 * USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 * ====================================================================
 */

import java.io.Reader;
import java.io.StringReader;
import java.text.BreakIterator;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Iterator;
import java.util.LinkedHashSet;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Set;
import java.util.TreeMap;

import edu.stanford.nlp.process.CoreLabelTokenFactory;
import edu.stanford.nlp.process.DocumentPreprocessor;
import edu.stanford.nlp.process.PTBTokenizer;
import edu.stanford.nlp.process.TokenizerFactory;
import edu.stanford.nlp.util.StringUtils;
import net.sf.classifier4J.Utilities;
import net.sf.classifier4J.ITokenizer;
import net.sf.classifier4J.summariser.ISummariser;

public class CustomSummarizer implements ISummariser {

	@SuppressWarnings("unchecked")
	protected Set<String> getMostFrequentWords(int count,
			Map<?, ?> wordFrequencies) {
		return Utilities.getMostFrequentWords(count, wordFrequencies);
	}

	/**
	 * @see net.sf.classifier4J.summariser.ISummariser#summarise(java.lang.String)
	 */
	public String summarise(String input, int numSentences) {
		return summariseInternal(input, numSentences, 5, null);
	}

	@SuppressWarnings("rawtypes")
	public TreeMap tfIdfCalculator() {
		TreeMap tfidf = new TreeMap();

		return tfidf;
	}

	/**
	 * 
	 * @param input
	 * @param numSentences
	 * @param minWordsInSentence
	 * @param tokenizer
	 * @return
	 */
	protected String summariseInternal(String input, int numSentences,
			int minWordsInSentence, ITokenizer tokenizer) {
		// get the frequency of each word in the input
		@SuppressWarnings("rawtypes")
		Map wordFrequencies = Utilities.getWordFrequency(input);

		// now create a set of the X most frequent words
		Set<String> mostFrequentWords = getMostFrequentWords(100,
				wordFrequencies);
		// break the input up into sentences
		// workingSentences is used for the analysis, but
		// actualSentences is used in the results so that the
		// capitalisation will be correct.
		String[] workingSentences = getSentencesRegex(input.toLowerCase());

		String[] actualSentences = getSentencesRegex(input);
		/*
		 * System.err.println("Sentences"); for (int i = 0; i <
		 * actualSentences.length; i++) {
		 * System.err.println(actualSentences[i]); }
		 */
		// iterate over the most frequent words, and add the first sentence
		// that includes each word to the result
		Set<String> outputSentences = new LinkedHashSet<String>();
		Iterator<String> it = mostFrequentWords.iterator();
		while (it.hasNext()) {
			String word = it.next();
			for (int i = 0; i < workingSentences.length; i++) {
				if (workingSentences[i].indexOf(word) >= 0
						&& workingSentences[i].split(" ").length >= minWordsInSentence) {
					outputSentences.add(actualSentences[i]);
					break;
				}
				if (outputSentences.size() >= numSentences) {
					break;
				}
			}
			if (outputSentences.size() >= numSentences) {
				break;
			}

		}
		List<String> reorderedOutputSentences = reorderSentences(
				outputSentences, input);
		StringBuffer result = new StringBuffer("");
		it = reorderedOutputSentences.iterator();
		while (it.hasNext()) {
			String sentence = it.next();
			result.append(sentence);
			result.append("."); // This isn't always correct - perhaps it should
								// be whatever symbol the sentence finished with
			if (it.hasNext()) {
				result.append(" ");
			}
		}
		return result.toString();
	}

	/**
	 * @param outputSentences
	 * @param input
	 * @return
	 */
	private List<String> reorderSentences(Set<String> outputSentences,
			final String input) {
		// reorder the sentences to the order they were in the
		// original text
		ArrayList<String> result = new ArrayList<String>(outputSentences);

		Collections.sort(result, new Comparator<Object>() {
			public int compare(Object arg0, Object arg1) {
				String sentence1 = (String) arg0;
				String sentence2 = (String) arg1;

				int indexOfSentence1 = input.indexOf(sentence1.trim());
				int indexOfSentence2 = input.indexOf(sentence2.trim());
				int result = indexOfSentence1 - indexOfSentence2;

				return result;
			}

		});
		return result;
	}

	/**
	 * Splits the string input into sentences using a basic regex.
	 * 
	 * @param input
	 *            a String which may contain many sentences
	 * @return an array of Strings, each element containing a sentence
	 */
	public static String[] getSentencesRegex(String input) {
		if (input == null) {
			return new String[0];
		} else {
			// split on a ".", a "!", a "?" followed by a space or EOL
			return input.split("((\\.|!|\\?)+(\\s|\\z))|((\r\n)|(\n))");
		}

	}

	/**
	 * Splits the string input into sentences using the java BreakIterator.
	 * Locale is set to US.
	 * 
	 * @param input
	 *            A String which may contain many sentences
	 * @return Sentences from input split into an array of strings
	 */
	public static String[] getSentencesBI(String input) {
		BreakIterator iterator = BreakIterator.getSentenceInstance(Locale.US);
		iterator.setText(input);
		int start = iterator.first();
		ArrayList<String> sentences = new ArrayList<String>();
		for (int end = iterator.next(); end != BreakIterator.DONE; start = end, end = iterator
				.next()) {
			sentences.add(input.substring(start, end));
		}
		return sentences.toArray(new String[0]);
	}

	/**
	 * Gets sentences from input using the StanfordNLP DocumentPreprocessor
	 * 
	 * @param input
	 *            A String which may contain many sentences
	 * @return Sentences from input split into an array of strings
	 */
	public static String[] getSentencesStanford(String input) {
		Reader reader = new StringReader(input);
		final TokenizerFactory tf = PTBTokenizer
				.factory(new CoreLabelTokenFactory(),
						"normalizeParentheses=false,normalizeOtherBrackets=false,invertible=true");
		DocumentPreprocessor preProcessor = new DocumentPreprocessor(reader);
		preProcessor.setTokenizerFactory(tf);

		List<String> sentenceList = new ArrayList<String>();
		for (List sentence : preProcessor) {
			sentenceList.add(StringUtils.joinWithOriginalWhiteSpace(sentence));
		}

		return sentenceList.toArray(new String[0]);
	}
	
	/**
	 * Prints results from the 3 sentence splitters for simple input
	 */
	public static void testSentenceSplitters() {
		String source = "This is a test. This is a T.L.A. test? Hello, Dr. Jones, wow!";

		String[] result1 = getSentencesRegex(source);
		System.out.println("Result 1:");
		for (String s : result1) {
			System.out.println(s);
		}
		System.out.println();

		String[] result2 = getSentencesBI(source);

		System.out.println("Result 2:");
		for (String s : result2) {
			System.out.println(s);
		}
		System.out.println();

		String[] result3 = getSentencesStanford(source);

		System.out.println("Result 3:");
		for (String s : result3) {
			System.out.println(s);
		}

	}

	/**
	 * @see net.sf.classifier4J.summariser.ISummariser#getKeywords(java.lang.String,
	 *      int)
	 */
	@SuppressWarnings("rawtypes")
	public String[] getKeywords(String input, int numKeywords) {
		// get the frequency of each word in the input

		Map wordFrequencies = Utilities.getWordFrequency(input);

		// System.out.println(wordFrequencies);

		Set mostFrequentWords = getMostFrequentWords(numKeywords,
				wordFrequencies);
		// System.out.println(mostFrequentWords);
		@SuppressWarnings("unchecked")
		String[] results = (String[]) mostFrequentWords
				.toArray(new String[mostFrequentWords.size()]);
		if (results.length <= numKeywords) {
			return results;
		} else {
			String[] newResults = new String[numKeywords];
			System.arraycopy(results, 0, newResults, 0, numKeywords);
			return newResults;
		}
	}

}