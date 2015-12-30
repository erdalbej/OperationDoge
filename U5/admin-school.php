<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}
include_once 'admin-header.php';

if(isset($_POST['course-delete'])){
	if(strlen($_POST['courseName']) > 0 && 
		strlen($_POST['courseDate']) > 0 &&
		strlen($_POST['courseTeacher']) > 0){

		$result = nonQuery("DELETE FROM DogCourse WHERE CourseName = :courseName AND CourseTeacher = :courseTeacher AND CourseDate = :courseDate", 
			array(
				':courseName' => $_POST['courseName'],
				':courseTeacher' => $_POST['courseTeacher'],
				':courseDate' => $_POST['courseDate']
			)
		);

		if($result["err"] === NULL){
			$courseDeleteSuccess = "Kurs raderad!"; 
		}else{ $courseDeleteError = "Kunde inte radera kursen, prova igen."; }
	}else{ $courseDeleteError = "Saknar värden för att radera kursen."; }
} else if(isset($_POST['course-update'])){
	if(strlen($_POST['courseName']) > 0 && 
		strlen($_POST['courseDate']) > 0 &&
		strlen($_POST['courseTeacher']) > 0){

		$result = nonQuery("UPDATE DogCourse SET CourseText = :courseText WHERE CourseName = :courseName AND CourseTeacher = :courseTeacher AND CourseDate = :courseDate", 
			array(
				':courseName' => $_POST['courseName'],
				':courseTeacher' => $_POST['courseTeacher'],
				':courseDate' => $_POST['courseDate'],
				':courseText' => $_POST['courseText']
			)
		);

		if($result["err"] === NULL){
			$courseUpdateSuccess = "Kurs uppdaterad!"; 
		}else{ $courseUpdateError = "Kunde inte uppdatera kursen, prova igen."; }
	}else{ $courseUpdateError = "Saknar värden för att uppdatera kursen."; }
} else if(isset($_POST['course-create'])){
	if(strlen($_POST['courseName']) > 0 && 
		strlen($_POST['courseDate']) > 0 &&
		strlen($_POST['courseTeacher']) > 0){

		$result = nonQuery("UPDATE DogCourse SET CourseText = :courseText WHERE CourseName = :courseName AND CourseTeacher = :courseTeacher AND CourseDate = :courseDate", 
			array(
				':courseName' => $_POST['courseName'],
				':courseTeacher' => $_POST['courseTeacher'],
				':courseDate' => $_POST['courseDate'],
				':courseText' => $_POST['courseText']
			)
		);

		if($result["err"] === NULL){
			$courseUpdateSuccess = "Kurs uppdaterad!"; 
		}else{ $courseUpdateError = "Kunde inte uppdatera kursen, prova igen."; }
	}else{ $courseUpdateError = "Saknar värden för att uppdatera kursen."; }
}
?>

<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<?php
				if(isset($courseDeleteSuccess)){
					echo '<span id="returnMsg" class="success-message">' . $courseDeleteSuccess . '</span>';
				}
				if(isset($courseDeleteError)){
					echo '<span id="returnMsg" class="error-message">' . $courseDeleteError . '</span>';
				}

				if(isset($courseUpdateSuccess)){
					echo '<span id="returnMsg" class="success-message">' . $courseUpdateSuccess . '</span>';
				}
				if(isset($courseUpdateError)){
					echo '<span id="returnMsg" class="error-message">' . $courseUpdateError . '</span>';
				}
				?>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<h5>Hundkurser - Redigera kurser</h5>
				<p>För att redigera en kurs, klicka på den i listan för att få upp kursinformation. För att redigera anmälningar, klicka på hyperlänken "Redigera anmälningar" längst ut till höger i tabellen.</p>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<h3>Välj kurs att redigera</h3>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Kurs</th>
							<th>Lärare</th>
							<th>Datum</th>
							<th><center>Redigera Kurs</center></th>
							<th><center>Redigera Anmälningar</center></th>
						</tr>
					</thead>
					<tbody>
						<?php

						$result = query("SELECT CourseName, CourseTeacher, CourseDate, CourseText FROM DogCourse ORDER BY CourseDate ASC");

						if($result["err"] != null){
							$load_error = "Kunde inte ladda kurser, prova att ladda om sidan.";
						}else{
							$courses = $result['data'];

							if(count($courses) > 0){
								foreach($courses as $key => $g){
									echo '<tr>';
									echo '<td >';
									echo $g['CourseName'];
									echo '</td>';
									echo '<td>';
									echo $g["CourseTeacher"];
									echo '</td>';
									echo '<td >';
									echo $g["CourseDate"];
									echo '</td>';
									echo '<td class="hide-td">';
									echo $g["CourseText"];
									echo '</td>';
									echo '<td class="edit-course">';
									echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
									echo '</td>';
									echo '<td>';
									echo '<center><a href="/U5/admin-course.php?coursename='.$g['CourseName'].'&courseteacher='.$g['CourseTeacher'].'&coursedate='.$g['CourseDate'].'">';
									echo '<i class="fa fa-comments fa-lg"></i>';
									echo '</a></center>';
									echo '</td>';
									echo '</tr>';
								}	
							}else{
								$load_error = "Inga kurser tillagda i ännu.";
							}
						}
						?>				
					</tbody>
				</table>
				<br/>
			</div>
		</div>
		<form hidden enctype="multipart/form-data" action="" method="post">
			<div class="row">
				<div class="twelve columns">
					<h3>Redigera tråd</h3>
				</div>
			</div>
			<div class="row">
				<div class="four columns">
					<label for="title">Kursnamn:</label>
					<input class="u-full-width" type="text" name="courseName" id="courseName" required readonly>
				</div>
				<div class="four columns">
					<label for="CreatedAt">Lärare:</label>
					<input class="u-full-width" type="text" name="courseTeacher" id="courseTeacher" required readonly>
				</div>
				<div class="four columns">
					<label for="username">Datum:</label>
					<input class="u-full-width" type="date" name="courseDate" maxlength="255" id="courseDate" required readonly>
				</div>
			</div>
			<label for="threadDescription">Förklaring:</label>
			<textarea name="courseText" class="u-full-width" maxlength="500" id="courseText"></textarea>	
			<input value="Uppdatera" name="course-update" type="submit">
			<input value="Radera" name="course-delete" type="submit">
		</form>
	</div>

	<script src="js/admin-school.js"></script>
</main>

<?php
include_once 'footer.php';
?>