$(document).ready(function(){

	$(".edit-news").click(function (event) {    
		var parent = $(this).parent();
		console.log(parent);

		var data = {};
		data.newsTitle = $(parent).children().eq(0).text();
		data.newsCreatedAt = $(parent).children().eq(1).text();
		data.newsText = $(parent).children().eq(2).text();
		data.newsImage = $(parent).children().eq(3).text();


		$("#edit-title-id").val(data.newsTitle);
		$("#edit-createdAt").val(data.newsCreatedAt);
		$("#edit-text-id").val(data.newsText);
		if(data.newsImage !== ""){
			$("#edit-old-image-id").attr('src', 'uploads/' + data.newsImage);
		}else{
			$("#edit-old-image-id").attr('src', 'uploads/noimage.jpg');
		}
		
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);
		
		
	})

})