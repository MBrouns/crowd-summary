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
	$("#summary > span").mouseup(function() {
		if(mode == "Highlight" && window.getSelection() == 0) {
			$(this).toggleClass(function(index, cl, mode) {
				$(this).children().each(function() {
					$(this).contents().unwrap();
				});
				return "highlighted";
			});
		}
	});

	// Remove all highlights
	$("#removeAll-button").click(removeAll = function() {
		$("#summary > span").removeClass("highlighted");
	})

	// Initialize user highlighter
	$('#summary').textHighlighter( {
		onBeforeHighlight: function(range) {
        	return true;
        }
	});

	// Gather user input highlights
	
	$("#generate-button").click( generate = function() {
		/* Sentence Summary */
		//sentences = $("#summary span").hasClass("highlighted");
		ids = [];
		$("#generated-summary").html("");
		html = $("#summary").html();
		$("#generated-summary").html(html);
		/*for (var i = 0; i < sentences.length; i++) {
			s = sentences[i];
			html += s + "<br/>";
		};*/
		

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
		$("#SummaryUserSentences").val(ids.toString());

	});

});

