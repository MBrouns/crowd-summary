$(document).ready(function() {
	/* Set active class to menu */
	page = window.location.pathname.split("/")[1];
	if (page != undefined) {
		$("#menu-" + page).addClass("active");
	}

	//Remove warning
	$(".alert-flash .glyphicon-remove").click(function() {
		$(this).parent().fadeOut("slow");
	});

	setTimeout(function(){$(".alert-flash .glyphicon-remove").parent().fadeOut("slow")},5000);
});