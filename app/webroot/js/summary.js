jQuery.fn.highlight=function(c){function e(b,c){var d=0;if(3==b.nodeType){var a=b.data.toUpperCase().indexOf(c);if(0<=a){d=document.createElement("span");d.className="highlighted";a=b.splitText(a);a.splitText(c.length);var f=a.cloneNode(!0);d.appendChild(f);a.parentNode.replaceChild(d,a);d=1}}else if(1==b.nodeType&&b.childNodes&&!/(script|style)/i.test(b.tagName))for(a=0;a<b.childNodes.length;++a)a+=e(b.childNodes[a],c);return d}return this.length&&c&&c.length?this.each(function(){e(this,c.toUpperCase())}): this};jQuery.fn.removeHighlight=function(){return this.find("span.highlighted").each(function(){this.parentNode.firstChild.nodeName;with(this.parentNode)replaceChild(this.firstChild,this),normalize()}).end()};

$(document).ready(function() {

	// Action Buttons
	mode = "Highlight";
	$(".btn-group button").click(function() {  
    	$(".btn-group button").not(this).removeClass('active');
    	$(this).toggleClass('active');
    	mode = $(this).text();
	});

	// Initialize highlights
	for (var i = 0; i < generated.length; i++) {
		$('#summary').highlight(generated[i]);
	};
	
	// Remove highlights
	$('#summary').on('click', '.highlighted', function() {
		$('#summary').getHighlighter().removeHighlights(this);
	});

	// Initialize user highlighter
	$('#summary').textHighlighter( {
		onBeforeHighlight: function(range) {
        	return true;
        }
	});
	$('#color-picker div.color').click(function() {
	  var color = $(this).css('background-color');
	  $('#summary').getHighlighter().setColor(color);
	});

	// Gather user input highlights
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

