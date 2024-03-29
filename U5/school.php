<?php
include_once 'header.php';
include_once 'aside.php';
?>

<?php


$comingCourses = 
"SELECT DC.CourseName, DC.CourseTeacher, DC.CourseDate, DC.AgeOfDog, DC.Gender, DC.PriorKnowledge, DC.CourseText, IF((ParticipantCount.NumOfPar > 0), ParticipantCount.NumOfPar, 0) as Participants
FROM DogCourse DC
LEFT JOIN (
	SELECT DogCourse_CourseName AS CourseName, DogCourse_CourseTeacher AS CourseTeacher, DogCourse_CourseDate AS CourseDate, COUNT(*) AS NumOfPar
	FROM Participant
	GROUP BY DogCourse_CourseName, DogCourse_CourseTeacher, DogCourse_CourseDate
) AS ParticipantCount
ON ParticipantCount.CourseName = DC.CourseName AND ParticipantCount.CourseTeacher = DC.CourseTeacher AND ParticipantCount.CourseDate = DC.CourseDate
WHERE DC.CourseDate > NOW()
ORDER BY DC.CourseDate ASC, Participants ASC";

$submitComingCourses = 
"SELECT DC.CourseName, DC.CourseTeacher, DC.CourseDate, DC.AgeOfDog, DC.Gender, DC.PriorKnowledge, DC.CourseText, IF((ParticipantCount.NumOfPar > 0), ParticipantCount.NumOfPar, 0) as Participants
FROM DogCourse DC
LEFT JOIN (
	SELECT DogCourse_CourseName AS CourseName, DogCourse_CourseTeacher AS CourseTeacher, DogCourse_CourseDate AS CourseDate, COUNT(*) AS NumOfPar
	FROM Participant
	GROUP BY DogCourse_CourseName, DogCourse_CourseTeacher, DogCourse_CourseDate
) AS ParticipantCount
ON ParticipantCount.CourseName = DC.CourseName AND ParticipantCount.CourseTeacher = DC.CourseTeacher AND ParticipantCount.CourseDate = DC.CourseDate
WHERE DC.CourseDate > NOW() AND DC.CourseName = :courseName AND DC.CourseTeacher = :courseTeacher AND DC.CourseDate = :courseDate
ORDER BY DC.CourseDate ASC, Participants ASC";

	date_default_timezone_set('Europe/Stockholm');

	if (isset($_POST['submit-regToCourse'])){
		if (strlen($_POST['dogName']) > 0 &&
		 	strlen($_POST['ownerName']) > 0 && 
		 	strlen($_POST['dogAge']) > 0 && 
		 	strlen($_POST['dogGender']) > 0 &&
		 	strlen($_POST['courseName']) > 0 &&
		 	strlen($_POST['courseTeacher']) > 0 &&
		 	strlen($_POST['courseDate']) > 9 && 
			strlen($_POST['email']) > 0){

			$result = query($submitComingCourses,
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
						$insertResult = nonquery('INSERT INTO Participant(DogName, OwnerName, AgeOfDog, Gender, ExtraInfo, DogCourse_CourseName, DogCourse_CourseTeacher, DogCourse_CourseDate, RegisterDate, Email) values(:dogName, :ownerName, :ageOfDog, :gender, :extraInfo, :courseName, :courseTeacher, :courseDate, NOW(), :email)',
							array(
								':dogName' => $_POST['dogName'],
								':ownerName' => $_POST['ownerName'],
								':ageOfDog' => $_POST['dogAge'],
								':gender' => $_POST['dogGender'],
								':extraInfo' => $_POST['extraInfo'],
								':courseName' => $_POST['courseName'],
								':courseTeacher' => $_POST['courseTeacher'],
								':courseDate' => $_POST['courseDate'],
								':email' => $_POST['email']
							)
						);

						if ($insertResult['err'] == null){
							$submitSuccess = true;

							$courseLink = 'course.php?courseName=' . $_POST['courseName'] . '&courseTeacher=' . $_POST['courseTeacher'] . '&courseDate=' . $_POST['courseDate'];
							$headers = 'From: ' . 'no-reply@perry.dog';

							$sent = mail(
								$_POST['email'], 
								'Bekräftelse av bokning',
								'Grattis ' . $_POST['ownerName']. '! Du är nu registrerad på kursen ' . $_POST['courseName'] . ' med läraren ' . $_POST['courseTeacher'] . ' den ' . $_POST['courseDate'] . ' med din hund ' . $_POST['dogName'],
								$headers
							);

						} else { $submitError = 'Gick inte att registrera er på kursen, ni är redan registrerade!'; }
					} 
				} else { $submitError = 'Den kurs du försöker registerar dig till finns inte!'; }
			} else { $submitError = 'Problem med att hitta kursen, prova igen!'; }
		} else { $submitError = 'Saknar värden, fyll i igen!'; }
	} else {
		$result = query($comingCourses);
	
		if ($result['err'] == null){ $courses = $result['data']; } 
		else { $coursesError = "Gick inte att läsa kommande kurser"; }
	}
?>

<main class="height-uv">
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
						'<h5 class="courseInfoHeader">'. $c['CourseName'] .' - '. $c['CourseTeacher'] . '</h5> ' .
						'<label><a href="course.php?courseName=' . $c['CourseName'] . '&courseTeacher=' . $c['CourseTeacher'] . '&courseDate=' . $c['CourseDate'] . '"><i class="fa fa-info-circle"></i> Deltagarinfo</a></label>' .
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
					$sendMessage = '<span><i>Ett bekräftelsemail har skickats till dig med samma information.</i><span>';

					if ($tempGender == 'F'){
						$tempGender = 'Tik';
					} else if ($tempGender == 'M'){
						$tempGender = 'Hane';
					} else {
						$tempGender = 'Båda';
					}

					$confirm = '<h3>Detta är en bekräftelse på din bokning.</h3>';
					if (isset($_POST['reserveSpot'])){
						$confirm = '<h3>Detta är en bekräftelse på din reservplats.</h3>';
					}

					if (!$sent){
						$sendMessage = "<span><b>Ditt bekräftelsemail gick inte att leverera. <a href=\"contact.php\">Kontakta oss här.</a></b></span>";
					}

					echo 
						'<div class="row">' .
						'<div class="twelve columns">' .
						'<h5>Grattis '. $_POST['ownerName'] .'!</h5>' .
						$confirm .
						$sendMessage .
						'<label>Kursdatum: <span class="label-value course-date">' . $_POST['courseDate'] . '</span></label>' .
						'<label>Kursnamn: <span class="label-value course-date">' . $_POST['courseName'] . '</span></label>' .
						'<label>Kurslärare: <span class="label-value course-date">' . $_POST['courseTeacher'] . '</span></label>' .
						'<label>Din hund: <span class="label-value course-date">' . $_POST['dogName'] . ', ' . $tempGender . ', ' . $_POST['dogAge'] . ' år </span></label>' .
						'<p><a href="' . $courseLink . '">Till kursen</a><p>' .
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
		        <input required maxlength="255" class="u-full-width" type="text" placeholder="Buster" id="dogName" name="dogName">
		      </div>
		      <div class="six columns">
		        <label for="ownerName">Ägarenamn</label>
		        <input required maxlength="255" class="u-full-width" type="text" placeholder="Anna Svensson" id="ownerName" name="ownerName">
		      </div>
		    </div>
		    <div class="row">
		      <div class="six columns">
		        <label for="dogAge">Hundålder</label>
		        <input required maxlength="255" class="u-full-width" type="number" min="0" step="1" value="0" id="dogAge" name="dogAge">
		      </div>
		      <div class="six columns">
		        <label for="dogGender">Hundkön</label>
		        <select required class="u-full-width" id="dogGender" name="dogGender">
		          <option value="M">Hane</option>
		          <option value="F">Tik</option>
		        </select>
		      </div>
		       <div class="twelve columns">
		        <label for="email">E-post adress</label>
		        <input required maxlength="255" type="email" class="u-full-width" placeholder="din-epost@mail.com" id="email" name="email">
		      </div>
		    </div>
		    <label for="exampleMessage">Extra information</label>
		    <textarea class="u-full-width" maxlength="255" placeholder="Hej! Min hund har problem med..." id="extraInfo" name="extraInfo"></textarea>
		    <label>
		      <input style="display:none;" type="checkbox" id="reserveSpot" name="reserveSpot" checked="reserve">
		      <span hidden class="label-body" id="reserve-spot-label" style="color: red;"><b>Reservplats</b></span>
		    </label>
		    <input type="submit" value="Skicka anmälan" name="submit-regToCourse">
		    <input required style="display:none" type="text" name="courseName" id="formCourseName">
		    <input required style="display:none" type="text" name="courseTeacher" id="formCourseTeacher">
		    <input required style="display:none" type="text" name="courseDate" id="formCourseDate">
		    <form>
	</div>		
</main>
<script type="text/javascript" src="js/school.js"></script>
<?php
include_once 'footer.php';
?>