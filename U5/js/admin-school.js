$(document).ready(function(){

	$(".edit-course").click(function (event) {    
		var parent = $(this).parent();

		var data = {};
		data.courseName = $(parent).children().eq(0).text();
		data.courseTeacher = $(parent).children().eq(1).text();
		data.courseDate = $(parent).children().eq(2).text();
		data.courseText = $(parent).children().eq(3).text();



		$("#courseName").val(data.courseName);
		$("#courseTeacher").val(data.courseTeacher);
		$("#courseDate").val(data.courseDate);
		$("#courseText").val(data.courseText);

		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(300);

	})
})