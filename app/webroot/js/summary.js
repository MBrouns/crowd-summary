$(document).ready(function() {


	$('#summary').on('click', '.highlighted', function() {
		$('#summary').getHighlighter().removeHighlights(this);
	});

	$('#summary').textHighlighter( {
		onBeforeHighlight: function(range) {
        	return true;
        }
	});

	$('#color-picker div.color').click(function() {
	  var color = $(this).css('background-color');
	  $('#summary').getHighlighter().setColor(color);
	});

	sentences = $("#summary").text().split(".");
          



	$("#generate-button").click( function() {
		/* Sentence Summary */
		$("#generated-summary").html("");
		generated = "";
		selected = [];
		html = $("#summary").html();
		for (var i = 0; i < sentences.length; i++) {
			s = sentences[i] + ".";
			if(html.indexOf(jQuery.trim( s )) == -1) {
				// sentence selected.
				selected.push(s);
				generated += s;
			}
		};
		$("#generated-summary").html(generated);

		/* User Summary */
		$("#user-summary").html("");
		$("#user-summary").html($("#summary").getHighlighter().getAllHighlights($("#summary")).text());
	});

});