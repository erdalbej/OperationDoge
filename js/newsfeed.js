$(document).ready(function(){

	$("tbody tr").click(function (event) {    
		var parent = $(event.target).parent();

		var data = {};
		data.newsFeedTitle = $(parent).children().eq(0).text();
		data.newsFeedDateTime = $(parent).children().eq(1).text();
		data.newsFeedDescription = $(parent).children().eq(2).text();
		data.newsFeedLink = $(parent).children().eq(3).text();
		data.newsFeedImagePath = $(parent).children().eq(4).text();


		$("#newsFeedTitle").val(data.newsFeedTitle);
		$("#newsFeedDateTime").val(data.newsFeedDateTime);
		$("#newsFeedDescription").val(data.newsFeedDescription);
		$("#newsFeedLink").val(data.newsFeedLink);
		$("#newsFeedImagePath").val(data.newsFeedImagePath);

	})


})