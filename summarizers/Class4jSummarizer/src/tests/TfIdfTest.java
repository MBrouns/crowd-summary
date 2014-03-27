package tests;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import main.TfIdf;

public class TfIdfTest {

	public static void main(String[] args) {
		
		// TODO Auto-generated method stub
		
		List<String[]> allTerms = new ArrayList<String[]>();
		String[] anArray1 = {"hoi","hoi1","hoi2","hoi3","hoi4"};
		String[] anArray2 = {"hoi","hoi2","hoi5","hoi6"};

		allTerms.add(anArray1);
		allTerms.add(anArray2);

		
		TfIdf tfidf = new TfIdf(allTerms);
		
		for (Map.Entry<String, Double> entry : tfidf.getTfIdfList(1).entrySet()) {
			String key = entry.getKey();
			double value = entry.getValue();
			
			System.out.println(key + " => " + value);
		}
	}
}
