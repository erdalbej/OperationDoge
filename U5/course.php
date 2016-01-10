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
WHERE DC.CourseDate > NOW() AND DC.CourseName = :courseName AND DC.CourseTeacher = :courseTeacher AND DC.CourseDate = :courseDate
ORDER BY DC.CourseDate ASC, Participants ASC";

$courseName = null;
$courseTeacher = null;
$courseDate = null;

if(isset($_GET['courseName']) && isset($_GET['courseTeacher']) && isset($_GET['courseTeacher'])){
	$courseName = $_GET['courseName'];
	$courseTeacher = $_GET['courseTeacher'];
	$courseDate = $_GET['courseDate'];
}

if (strlen($courseName) > 0 &&
	strlen($courseTeacher) > 0 &&
	strlen($courseDate) > 0){

	$courseResult = query($comingCourses,
		array (
			':courseName' => $courseName,
			':courseTeacher' => $courseTeacher,
			':courseDate' => $courseDate
		)
	);

	if ($courseResult['err'] == null){
		$courses = $courseResult['data'];
		if (count($courses) > 0){
			$foundCourse = $courses[0];

			$participantResult = query('SELECT DogName, OwnerName, Email, AgeOfDog, Gender, ExtraInfo, RegisterDate FROM Participant WHERE DogCourse_CourseName = :courseName AND DogCourse_CourseTeacher = :courseTeacher AND DogCourse_CourseDate = :courseDate ORDER BY RegisterDate ASC', 
				array(
					':courseName' => $courseName,
					':courseTeacher' => $courseTeacher,
					':courseDate' => $courseDate
				)
			);

			if ($participantResult['err'] == null){
				$participants = $participantResult['data'];
			} else { $participantError = 'Gick inte att läsa deltagare för kursen, prova igen!'; }
		} else { $courseError = 'Den kursen du letar efter finns inte.'; }
	} else { $courseError = 'Något gick fel, prova söka efter kursen igen!'; }
} else { $courseError =  'Saknar värden för att hitta kursinformation.'; }
?>
<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h1 style="text-align: center;">Kursinformation</h1>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<?php
					if (!isset($courseError)){
						echo 
							'<h5>' . $foundCourse['CourseName'] .' - ' . $foundCourse['CourseTeacher'] . '</h5>' .
							'<p><i>' . $foundCourse['CourseDate'] . '</i><p>';

						if (!isset($participantError)){
							if (count($participants) > 0){
								foreach($participants as $key => $p){
									if ($key == 10){
										echo '<p><b style="color: red;">Reservdeltagare</b></p>';
									}

									$tempGender = $p['Gender'];

									if ($tempGender == 'F'){
										$tempGender = 'Tik';
									} else if ($tempGender == 'M'){
										$tempGender = 'Hane';
									} else {
										$tempGender = 'Båda';
									}

									echo '<p>' . ($key+1) . ' - ' . $p['OwnerName'] . ', ' . $p['DogName'] . ', ' . $tempGender . ', ' . $p['AgeOfDog'] . ' år' . '<p>';
								}
							} else { echo '<p>Inga deltagare har anmält sig till kursen.</p>'; }
						} else { echo $participantError; }
					} else { echo $courseError; }
				?>
				<hr>
				<span><a href="school.php">Tillbaka</a></span>
			</div>
		</div>
	</div>		
</main>

<?php
include_once 'footer.php';
?>