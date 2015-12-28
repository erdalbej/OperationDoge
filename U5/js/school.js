$(document).ready(function(){
	$('.reg-course').click(function(elem){
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(700);

		var numberOfParticipants = $(this).parent().find('span .numOfParticipants').html();
		var numberOfParticipants = parseInt(numberOfParticipants);

		var name = $(this).parent().find('.courseInfoHeader').html();
		name = name.split(' - ');
		courseName = name[0];
		courseTeacher = name[1];
		courseDate = $(this).parent().find('.course-date').html();

		$('#formCourseName').prop('value', courseName);
		$('#formCourseTeacher').prop('value',courseTeacher);
		$('#formCourseDate').prop('value',courseDate);

		if (numberOfParticipants >= 10){
			$('#reserveSpot').prop('checked', true);
			$('#reserve-spot-label').show();
		} else {
			$('#reserve-spot-label').hide();
			$('#reserveSpot').prop('checked', false);
		}

		$('#formHeader').html(name[0] + ' - ' + name[1] + ' - ' + courseDate);
	})
})