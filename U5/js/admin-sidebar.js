$(document).ready(function(){

	$(".edit-sidebar").click(function (event) {    
		var parent = $(this).parent();

		var data = {};
		data.newsFeedTitle = $(parent).children().eq(0).text();
		data.newsFeedCreatedAt = $(parent).children().eq(1).text();
		data.newsFeedDescription = $(parent).children().eq(2).text();
		data.newsFeedLink = $(parent).children().eq(3).text();


		$("#newsFeedTitle").val(data.newsFeedTitle);
		$("#newsFeedCreatedAt").val(data.newsFeedCreatedAt);
		$("#newsFeedDescription").val(data.newsFeedDescription);
		$("#newsFeedLink").val(data.newsFeedLink);

		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);

	})


})