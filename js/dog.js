$(document).ready(function(){
	$("#puppylink").click(function() {
		var url = $("#puppylitters").val();
		if (url != "") {
			$("#puppylink").attr("href", "dogs.php?puppylitter=" + url);
		}
	});
});