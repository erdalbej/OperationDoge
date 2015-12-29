$(document).ready(function(){
	$("#mydogslink").click(function() {
		var url = $("#dogs").val();
		if (url != "") {
			$("#mydogslink").attr("href", "mydogs.php?dog=" + url);
		}
	});
});