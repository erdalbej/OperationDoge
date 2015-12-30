$(document).ready(function(){

	$(".edit-post").click(function (event) {    
		var parent = $(this).parent();
		var data = {};
		data.owner = $(parent).children().eq(0).text();
		data.email = $(parent).children().eq(1).text();
		data.dogName = $(parent).children().eq(2).text();
		data.age = $(parent).children().eq(3).text();
		data.gender = $(parent).children().eq(4).text();
		data.extraInfo = $(parent).children().eq(5).text();
		data.courseName = $(parent).children().eq(6).text();
		data.courseTeacher = $(parent).children().eq(7).text();
		data.courseDate = $(parent).children().eq(8).text();


		$("#owner").val(data.owner);
		$("#email").val(data.email);
		$("#dogName").val(data.dogName);
		$("#age").val(data.age);
		$("#gender").val(data.gender);
		$("#extraInfo").val(data.extraInfo);
		$("#courseName").val(data.courseName);
		$("#courseTeacher").val(data.courseTeacher);
		$("#courseDate").val(data.courseDate);
		

		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);
		
		

	})


})