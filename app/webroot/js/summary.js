$(document).ready(function() {

	$("#highlight-button").click();


	$('#summary').on('click', '.highlighted', function() {
		$('#summary').getHighlighter().removeHighlights(this);
	});

	$('#summary').textHighlighter( {
		onBeforeHighlight: function(range) {
        	console.log(range);
        }
	});

	$('#color-picker div.color').click(function() {
	  var color = $(this).css('background-color');
	  $('#summary').getHighlighter().setColor(color);
	});
          
});