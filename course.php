<?php
include_once 'header.php';
include_once 'aside.php';
?>

<?php
$courseName = $_GET['courseName'];
$courseTeacher = $_GET['courseTeacher'];
$courseDate = $_GET['courseDate'];

if (strlen($courseName) > 0 &&
	strlen($courseTeacher) > 0 &&
	strlen($courseDate) > 0){

	$courseResult = query('SELECT * FROM ComingCourses WHERE CourseName = :courseName AND CourseTeacher = :courseTeacher AND CourseDate = :courseDate',
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
		} else {
			$courseError = 'Den kursen du letar efter finns inte.';
		}

		$foundCourse = $courses[0];


	} else {
		$courseError = 'Något gick fel, prova söka efter kursen igen!';
	}

} else {
	$courseError =  'Saknar värden för att hitta kursinformation.';
}
?>
<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h1>Kursinformation</h1>
				<hr>
			</div>
		</div>
		<?php
			echo $courseError;
			print_r($_GET);
		?>
	</div>		
</main>

<?php
include_once 'footer.php';
?>