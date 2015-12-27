$(document).ready(function(){

	$("tbody tr").click(function (event) {    
		var parent = $(event.target).parent();

		var data = {};
		data.postUsername = $(parent).children().eq(0).text();
		data.postDateTime = $(parent).children().eq(1).text();
		data.postText = $(parent).children().eq(2).text();
		data.postImagePath = $(parent).children().eq(3).text();


		$("#postUsername").val(data.postUsername);
		$("#postDateTime").val(data.postDateTime);
		$("#postText").val(data.postText);
		if(data.postImagePath !== ""){
			$("#postImage").attr('src', 'uploads/' + data.postImagePath);
		}else{
			$("#postImage").attr('src', 'uploads/noimage.jpg');
		}
		
		

	})


})