notes = [];


$(document).ready(function() {

	// Action Buttons
	mode = "Highlight";
	$("#summary").css("cursor","text");
	$(".btn-group button").click(function() {  
    	$(".btn-group button").not(this).removeClass('active');
    	$(this).toggleClass('active');
    	mode = $(this).text();
    	if (mode == "Highlight") {
    		$("#summary").css("cursor","text");
    	} else {
    		$("#summary").css("cursor","crosshair")
    	}
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
		$("#SummaryUserNotes").val(JSON.stringify(notes));
		


	});




	$("#summary").popover({ container: '#summary' });
	$("#summary span").click(function() {
		if(mode == "Notes") {
			id = $(this).attr("id").substr(8);
			offset = $(this).offset().top;
			
			$("#summary").popover("show");
			if( $("#note" + id).html() != undefined ) {
				$(".popover textarea").val($("#note" + id).html());
			}

			$(".popover").css("top", offset-80 + "px");
			$(".popover textarea").after("<input type='hidden' name='sentence-note-id' value='"+ id +"' />");
			$("#notes-save").click(function() {
				obj = new Object();
				obj.sentence = $(this).prev().prev().val();
				obj.note = $(this).prev().prev().prev().val();
				
				$("#summary").popover("hide");

				// Display note
				if( $("#note" + id).val() == undefined ) {
					note = "<div class='alert alert-warning note' id='note"+ obj.sentence +"'>"+ obj.note +"</div>";
					$("body").append(note);
					$("#note" + obj.sentence).css("left", $("#summary").position().left + 960 + "px");
					$("#note" + obj.sentence).css("top", offset-15);

				} else {
					$("#note" + id).html(obj.note.replace(/\n/g, '<br />'));
					for (var i = notes.length - 1; i >= 0; i--) {
						o = notes[i];
						if (o.sentence == id) {
							notes.splice(i, 1);
						}
					};
				}
				notes.push(obj);

			});

			
		}
	})

});