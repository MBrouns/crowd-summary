import net.sf.classifier4J.summariser.SimpleSummariser;

public class C4JTester {

	public static void main(String[] args) {
		SimpleSummariser summariser = new SimpleSummariser();
		String input = "Classifier4J is a java package for working with text. Classifier4J includes a summariser. A Summariser allows the summary of text. A Summariser is really cool. I don't think there are any other java summarisers.";
		String result = summariser.summarise(input, 2);

		System.out.println(result);
	}

}
