$(document).ready(function(){
	$("#mydogslink").click(function() {
		var bothValues = $("#dogs").val().split('|');
		var pOne = encodeURIComponent(bothValues[0]);
		var pTwo = encodeURIComponent(bothValues[1]);
		console.log(pTwo);
		if (pOne != "" && pTwo != "") {
			$("#mydogslink").attr("href", "mydogs.php?dog=" + pOne + "&officialName=" + pTwo);
		}
	});
});