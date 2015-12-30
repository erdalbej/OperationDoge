$(document).ready(function(){

	$(".edit-puppy").click(function (event) {    
		var parent = $(this).parent();

		var data = {};
		data.puppyName = $(parent).children().eq(0).text();
		data.puppyGender = $(parent).children().eq(1).text();
		data.puppyPrice = $(parent).children().eq(2).text();
		data.puppyAvailable = $(parent).children().eq(3).text();
		data.puppyBirthDate = $(parent).children().eq(4).text();

		$("#PuppyName").val(data.puppyName);
		$("#PuppyGender").val(data.puppyGender);
		$("#PuppyPrice").val(data.puppyPrice);
		$("#PuppyAvailable").val(data.puppyAvailable);
		$("#PuppyBirthDate").val(data.puppyBirthDate);;

		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);

	})
})