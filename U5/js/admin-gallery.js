$(document).ready(function(){

	$(".delete-picture").click(function (event) {    
		var parent = $(this).parent();

		var data = {};
		data.imageTitle = $(parent).children().eq(1).text();
		data.imagePath = $(parent).children().eq(2).text();

		var form = 
			'<form method="POST" action="" >' +
 				'<input type="hidden" name="image-path" value="' + data.imagePath + '">' +
 				'<input type="hidden" name="image-title" value="' + data.imageTitle + '">' +
 				'<input name="remove-image-submit" value="submit">' +
			'</form>';

		$(form).appendTo("body").submit()

	})
})