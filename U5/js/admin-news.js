$(document).ready(function(){

	$("tbody tr").click(function (event) {    
		var parent = $(event.target).parent();

		var data = {};
		data.news_title = $(parent).children().eq(0).text();
		data.news_datetime = $(parent).children().eq(1).text();
		data.news_text = $(parent).children().eq(2).text();
		data.news_image = $(parent).children().eq(3).text();


		$("#edit-title-id").val(data.news_title);
		$("#edit-date-id").val(data.news_datetime);
		$("#edit-text-id").val(data.news_text);
		if(data.news_image !== ""){
			$("#edit-old-image-id").attr('src', 'uploads/' + data.news_image);
		}else{
			$("#edit-old-image-id").attr('src', 'uploads/noimage.jpg');
		}

		$("#returnMsg").html("");
		
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);
		
	})


})