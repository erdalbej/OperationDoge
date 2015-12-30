$(document).ready(function(){

	$(".edit-litter").click(function (event) {    
		var parent = $(this).parent();

		var data = {};
		data.litterTitle = $(parent).children().eq(0).text();
		data.litterUpcoming = $(parent).children().eq(1).text();
		data.litterInfo = $(parent).children().eq(2).text();

		$("#LitterTitle").val(data.litterTitle);
		$("#LitterUpcoming").val(data.litterUpcoming);
		$("#LitterInfo").val(data.litterInfo);;

		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);

	})
})