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
		//$('#summary').highlight(generated[i]);
		$("#sentence" + generated[i]).addClass("highlighted");
	};
	
	// Handle sentences highlights
	$("#summary > span").click(function() {
		$(this).toggleClass("highlighted");
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
	
	$("#generate-button").click( function() {
		/* Sentence Summary */
		sentences = $("#summary span").hasClass("highlighted");
		ids = [];
		$("#generated-summary").html("");
		html = $("#summary").html();
		/*for (var i = 0; i < sentences.length; i++) {
			s = sentences[i];
			html += s + "<br/>";
		};*/
		$("#generated-summary").html(html);

		/* User Summary */
		$("#user-summary").html("");
		//other type html
		html = '';
		$.each($("#summary .highlighted"), function(i,val) {
			html += $(val).text() + "<br/>";
			id = $(val).attr("id");
			if(id != undefined) {
				id = id.substr(8);
			} else {
				id = $(val).parent().attr("id").substr(8);
			}
			ids.push(id);

		});
		$("#user-summary").html(html);
		$("#ids-dump").html(ids.toString());
	});

});

