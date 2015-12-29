$(document).ready(function(){

	$(".edit-thread").click(function (event) {    
		var parent = $(this).parent();

		var data = {};
		data.threadTitle = $(parent).children().eq(0).text();
		data.threadCreatedAt = $(parent).children().eq(1).text();
		data.threadUsername = $(parent).children().eq(2).text();
		data.threadDescription = $(parent).children().eq(3).text();



		$("#threadTitle").val(data.threadTitle);
		$("#threadCreatedAt").val(data.threadCreatedAt);
		$("#threadUsername").val(data.threadUsername);
		$("#threadDescription").val(data.threadDescription);

		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);

	})
})