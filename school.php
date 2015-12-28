<?php
include_once 'header.php';
include_once 'aside.php';
?>

<?php
	date_default_timezone_set('Europe/Stockholm');

	if (isset($_POST['submit-regToCourse'])){
		if (strlen($_POST['dogName']) > 0 &&
		 	strlen($_POST['ownerName']) > 0 && 
		 	strlen($_POST['dogAge']) > 0 && 
		 	strlen($_POST['dogGender']) > 0 &&
		 	strlen($_POST['courseName']) > 0 &&
		 	strlen($_POST['courseTeacher']) > 0 &&
		 	strlen($_POST['courseDate']) > 9){

			$result = query('SELECT * FROM ComingCourses WHERE CourseName = :courseName AND CourseTeacher = :courseTeacher AND CourseDate = :courseDate',
				array(
					':courseName' => $_POST['courseName'],
					':courseTeacher' => $_POST['courseTeacher'],
					':courseDate' => $_POST['courseDate']
				)
			);

			if ($result['err'] == null){
				if (count($result['data']) > 0){
					$data = $result['data'];
					$foundCourse = $data[0];

					if ($foundCourse['AgeOfDog'] > $_POST['dogAge']){ $submitError = 'Kursen kräver att din hund är äldre'; }
					if ($foundCourse['Gender'] != 'B' && $foundCourse['Gender'] != $_POST['dogGender']){ $submitError = 'Kursen tar ej emot hundar av angivet kön'; }
					if (!isset($_POST['reserveSpot']) && $foundCourse['Participants'] >= 10){ $submitError = 'Kursplatserna hann ta slut, gör anmälan igen om du vill registera dig för en reservplats. Annars kan du gå tillbaka och se övriga kurser.'; }

					if (!isset($submitError)){
						$insertResult = nonquery('INSERT INTO Participant(DogName, OwnerName, AgeOfDog, Gender, ExtraInfo, DogCourse_CourseName, DogCourse_CourseTeacher, DogCourse_CourseDate) values(:dogName, :ownerName, :ageOfDog, :gender, :extraInfo, :courseName, :courseTeacher, :courseDate)',
							array(
								':dogName' => $_POST['dogName'],
								':ownerName' => $_POST['ownerName'],
								':ageOfDog' => $_POST['dogAge'],
								':gender' => $_POST['dogGender'],
								':extraInfo' => $_POST['extraInfo'],
								':courseName' => $_POST['courseName'],
								':courseTeacher' => $_POST['courseTeacher'],
								':courseDate' => $_POST['courseDate'] 
							)
						);

						if ($insertResult['err'] == null){
							$submitSuccess = true;
							#$submitSuccess = 'Grattis ' . $_POST['ownerName']. '! Du är nu registrerad på kursen ' . $_POST['courseName'] . ' med läraren ' . $_POST['courseTeacher'] . ' den ' . $_POST['courseDate'] . ' med din hund ' . $_POST['dogName'];
						} else { $submitError = 'Gick inte att registrera er på kursen, ni är redan registrerade!'; }
					} 
				} else { $submitError = 'Den kurs du försöker registerar dig till finns inte!'; }
			} else { $submitError = 'Problem med att hitta kursen, prova igen!'; }
		} else { $submitError = 'Saknar värden, fyll i igen!'; }
	} else {
		$result = query('SELECT * FROM ComingCourses');
	
		if ($result['err'] == null){ $courses = $result['data']; } 
		else { $coursesError = "Gick inte att läsa kommande kurser"; }
	}
?>

<main>
	<div class="container">
		<h1 style="text-align: center;">Aktuella kurser</h1>
		<hr>
		<?php 
			if (isset($courses)){
				foreach($courses as $key => $c){
					$tempGender = $c['Gender'];

					if ($tempGender == 'F'){
						$tempGender = 'Tik';
					} else if ($tempGender == 'M'){
						$tempGender = 'Hane';
					} else {
						$tempGender = 'Båda';
					}

					echo 
						'<div class="row">' .
						'<div class="twelve columns">' .
						'<h5 class="courseInfoHeader">'. $c['CourseName'] .' - '. $c['CourseTeacher'] . '</h5>' .
						'<label>Kursdatum: <span class="label-value course-date">' . $c['CourseDate'] . '</span></label>' .
						'<label>Platser: <span class="label-value"><span class="numOfParticipants">' . $c['Participants'] . '</span>/10</span></label>' .
						'<label>Åldersgräns: <span class="label-value">' . $c['AgeOfDog'] . ' år</span></label>' .
						'<label>Kön: <span class="label-value">' . $tempGender . '</span></label>' .
						'<label>Om kursen: </label>' .
						'<p>' . $c['CourseText'] . '<br><i>Förkunskaper: </i>' . $c['PriorKnowledge'] .'</p>' .
						'<span class="reg-course" id="course'. $key .'"><i class="fa fa-pencil-square-o fa-lg"></i><b> Registrera dig på kursen </b></span>' . 
						'</div>' .
						'</div>' .
						'<hr>';
				}
			} else {
				if (isset($coursesError)){
					print_r($coursesError);
				}

				if (isset($submitError)){
					print_r($submitError);
				}

				if (isset($submitSuccess)){
					$tempGender = $_POST['dogGender'];

					if ($tempGender == 'F'){
						$tempGender = 'Tik';
					} else if ($tempGender == 'M'){
						$tempGender = 'Hane';
					} else {
						$tempGender = 'Båda';
					}

					$confirm = '<h3>Detta är en bekräftelse på din bokning.</h3>';
					if ($_POST['reserveSpot']){
						$confirm = '<h3>Detta är en bekräftelse på din reservplats.</h3>';
					}

					echo 
						'<hr>' .
						'<div class="row">' .
						'<div class="twelve columns">' .
						'<h5>Grattis '. $_POST['ownerName'] .'!</h5>' .
						$confirm .
						'<label>Kursdatum: <span class="label-value course-date">' . $_POST['courseDate'] . '</span></label>' .
						'<label>Kursnamn: <span class="label-value course-date">' . $_POST['courseName'] . '</span></label>' .
						'<label>Kurslärare: <span class="label-value course-date">' . $_POST['courseTeacher'] . '</span></label>' .
						'<label>Din hund: <span class="label-value course-date">' . $_POST['dogName'] . ', ' . $tempGender . ', ' . $_POST['dogAge'] . ' år</span></label>' .
						'</div>' .
						'</div>' .
						'<hr>';
				}
				echo ' <a href="school.php">Gå tillbaks här</a>';
			}
		?>
		<form hidden method="POST">
			<div class="row">
				<div class="twelve columns">
					<h3 id="formHeader"><h3>
				</div>
			</div>
		    <div class="row">
		      <div class="six columns">
		        <label for="dogName">Hundnamn</label>
		        <input class="u-full-width" type="text" placeholder="Buster" id="dogName" name="dogName">
		      </div>
		      <div class="six columns">
		        <label for="ownerName">Ägarenamn</label>
		        <input class="u-full-width" type="text" placeholder="Anna Svensson" id="ownerName" name="ownerName">
		      </div>
		    </div>
		    <div class="row">
		      <div class="six columns">
		        <label for="dogAge">Hundålder</label>
		        <input class="u-full-width" type="number" min="0" step="1" value="0" id="dogAge" name="dogAge">
		      </div>
		      <div class="six columns">
		        <label for="dogGender">Hundkön</label>
		        <select class="u-full-width" id="dogGender" name="dogGender">
		          <option value="M">Hane</option>
		          <option value="F">Tik</option>
		        </select>
		      </div>
		    </div>
		    <label for="exampleMessage">Extra information</label>
		    <textarea class="u-full-width" placeholder="Hej! Min hund har problem med..." id="extraInfo" name="extraInfo"></textarea>
		    <label>
		      <input style="display:none;" type="checkbox" id="reserveSpot" name="reserveSpot" checked="reserve">
		      <span hidden class="label-body" id="reserve-spot-label" style="color: red;"><b>Reservplats</b></span>
		    </label>
		    <input class="button-primary" type="submit" value="Skicka anmälan" name="submit-regToCourse">
		    <input style="display:none" type="text" name="courseName" id="formCourseName">
		    <input style="display:none" type="text" name="courseTeacher" id="formCourseTeacher">
		    <input style="display:none" type="text" name="courseDate" id="formCourseDate">
		    <form>
	</div>		
</main>
<script>
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
</script>

<?php
include_once 'footer.php';
?>