/* Set active class to menu */
page = window.location.pathname.split("/")[1];
if (page != undefined) {
	$("#menu-" + page).addClass("active");
}