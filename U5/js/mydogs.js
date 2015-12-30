$(document).ready(function(){
	$("#mydogslink").click(function() {
		var pOne = $("#dogs").val();
		var pTwo = "";
		console.log(pTwo);
		if (pOne != "" && pTwo != "") {
			$("#mydogslink").attr("href", "mydogs.php?dog=" + pOne + "&officialName=" + pTwo);
		}
	});
});