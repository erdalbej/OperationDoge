$(document).ready(function(){

	$(".edit-post").click(function (event) {    
		var parent = $(this).parent();
		var data = {};
		data.postUsername = $(parent).children().eq(0).text();
		data.postcreatedAt = $(parent).children().eq(1).text();
		data.postText = $(parent).children().eq(2).text();
		data.postImagePath = $(parent).children().eq(3).text();


		$("#postUsername").val(data.postUsername);
		$("#postcreatedAt").val(data.postcreatedAt);
		$("#postText").val(data.postText);
		if(data.postImagePath !== ""){
			$("#postImage").attr('src', 'uploads/' + data.postImagePath);
		}else{
			$("#postImage").attr('src', 'uploads/noimage.jpg');
		}

		$("#returnMsg").html("");

		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);
		
		

	})


})