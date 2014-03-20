$(document).ready(function() {

	// Action Buttons
	mode = "Highlight";
	$(".btn-group button").click(function() {  
    	$(".btn-group button").not(this).removeClass('active');
    	$(this).toggleClass('active');
    	mode = $(this).text();
	});

	// Initialize highlights
	for (var i = 0; i < highlights.length; i++) {
		$("#sentence" + highlights[i]).addClass("highlighted");
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
				
		//flavour 1: just use html of the highlighted document: BOOK STYLE
		ids = [];
		html1 = $("#summary").html();
		$("#generated-summary").html(html1);		
		
		// flavour 2: only use the highlighted parts of the text
		html2 = '';
		$.each($("#summary .highlighted"), function(i,val) {
			html2 += $(val).text() + "<br/>";
			id = $(val).attr("id");
			if(id != undefined) {
				id = id.substr(8);
			} else {
				id = $(val).parent().attr("id").substr(8);
			}
			ids.push(id);

		});
		$("#user-summary").html(html2);
		
		$("#SummaryUserSentences").val(ids.toString());

	});

});