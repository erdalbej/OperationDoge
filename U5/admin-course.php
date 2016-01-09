<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}

include_once 'admin-header.php';

$courseName = NULL;
$courseTeacher = NULL;
$courseDate = NULL;

$genders = ['M', 'B', 'F'];

if(isset($_GET['coursename']) && isset($_GET['courseteacher']) && isset($_GET['coursedate'])){
	$courseName = $_GET['coursename'];
	$courseTeacher = $_GET['courseteacher'];
	$courseDate = $_GET['coursedate'];
}

if(isset($_POST['submit-update'])){
	if(strlen($_POST['courseName']) > 0 && 
		strlen($_POST['courseTeacher']) > 0 && 
		strlen($_POST['courseDate']) > 0 &&
		strlen($_POST['dogName']) > 0 &&
		strlen($_POST['email']) > 0 &&
		in_array($_POST['gender'], $genders) &&
		is_numeric($_POST['ageOfDog']) &&
		$_POST['ageOfDog'] >= 0 &&
		strlen($_POST['ownerName']) > 0){

		$result = nonQuery('UPDATE Participant SET OwnerName = :ownerName, Gender = :gender, AgeOfDog = :ageOfDog, ExtraInfo = :extraInfo WHERE Email = :email AND DogName = :dogName AND DogCourse_CourseName = :courseName AND DogCourse_CourseTeacher = :courseTeacher AND DogCourse_CourseDate = :courseDate',
			array(
				':ownerName' => $_POST['ownerName'],
				':gender' => $_POST['gender'],
				':ageOfDog' => $_POST['ageOfDog'],
				':extraInfo' => $_POST['extraInfo'],
				':email' => $_POST['email'],
				':dogName' => $_POST['dogName'],
				':courseName' => $_POST['courseName'],
				':courseTeacher' => $_POST['courseTeacher'],
				':courseDate' => $_POST['courseDate']
			)
		);

		if ($result['err'] == null){
			$updateSuccess = 'Anmälan uppdaterad';
		} else { $updateError = 'Det gick inte att uppdatera anmälan, prova igen!'; }
	} else { $updateError = 'Det gick inte att uppdatera anmälan, saknar värden'; }
}

if(isset($_POST['submit-delete'])){
	if(strlen($_POST['courseName']) > 0 && 
		strlen($_POST['courseTeacher']) > 0 && 
		strlen($_POST['courseDate']) > 0 &&
		strlen($_POST['dogName']) > 0 &&
		strlen($_POST['email']) > 0){

		$result = nonQuery("DELETE FROM Participant WHERE DogName = :dogName AND Email = :email AND DogCourse_CourseName = :courseName AND DogCourse_CourseTeacher = :courseTeacher AND DogCourse_CourseDate = :courseDate", 
			array(
				':dogName' => $_POST['dogName'],
				':email' => $_POST['email'],
				':courseName' => $_POST['courseName'],
				':courseTeacher' => $_POST['courseTeacher'],
				':courseDate' => $_POST['courseDate']
			)
		);

		if($result["err"] === null){
			$deleteSuccess = "Anmälan raderad."; 
		}else{ $deleteError = "Kunde inte ta bort anmälan, prova igen."; }
	}else{ $deleteError = "Kunde inte ta bort anmälan, saknar värden."; }
}
?>

<main>
	<div class="container">
	<div class="row">
			<div class="twelve columns">
				<?php
					if(isset($updateSuccess)){
						echo '<span id="returnMsg" class="success-message">' . $updateSuccess . '</span>';
					}
					if(isset($updateError)){
						echo '<span id="returnMsg" class="error-message">' . $updateError . '</span>';
					}

					if(isset($deleteSuccess)){
						echo '<span id="returnMsg" class="success-message">' . $deleteSuccess . '</span>';
					}
					if(isset($deleteError)){
						echo '<span id="returnMsg" class="error-message">' . $deleteError . '</span>';
					}
				?>
			</div>
		</div>
	<div class="row">
		<div class="twelve columns">
			<h5>Kurs - Redigera anmälningar</h5>
			<p>För att redigera en anmälan, klicka på anmälan i listan för att få upp informationen. </p>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
			<h3>Aktuell kurs: <strong> <?php echo $courseName . ', ' . $courseTeacher . ', ' . $courseDate;?> </strong></h3>
		</div>
	</div>
	<hr>
		<div class="row">
			<div class="eight columns">
				<h3>Välj anmälan att redigera</h3>
			</div>
			<div class="four columns">
			</div>
				<div class="row">
				<div class="twelve columns">
					<table class="u-full-width">
						<thead>
							<tr>
								<th>Ägare</th>
								<th>Email</th>
								<th>Hundnamn</th>
								<th><center>Redigera inlägg</center></th>
							</tr>
						</thead>
						<tbody>
							<?php

							$result = query("SELECT OwnerName, Email, DogName, DogCourse_CourseName, DogCourse_CourseTeacher, DogCourse_CourseDate, Gender, AgeOfDog, ExtraInfo FROM Participant WHERE DogCourse_CourseName = :courseName AND DogCourse_CourseTeacher = :courseTeacher AND DogCourse_CourseDate = :courseDate ORDER BY RegisterDate ASC",
								 array(
								 	':courseName' => $courseName,
								 	':courseTeacher' => $courseTeacher,
								 	':courseDate' => $courseDate
								 )
							);

							if($result["err"] != null){
								$load_error = "Kunde inte ladda kurs, den kanske inte finns.";
							}else{
								$courseData = $result['data'];

								if(count($courseData) > 0){
									foreach($courseData as $key => $p){
										echo '<tr id="news' . $key . '">';
										echo '<td>';
										echo $p['OwnerName'];
										echo '</td>';
										echo '<td>';
										echo $p["Email"];
										echo '</td>';
										echo '<td>';
										echo $p["DogName"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p["AgeOfDog"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p["Gender"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p["ExtraInfo"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p["DogCourse_CourseName"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p["DogCourse_CourseTeacher"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p["DogCourse_CourseDate"];
										echo '</td>';
										echo '<td class="edit-post">';
										echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
										echo '</td>';
										echo '</tr>';
										
									}
								}else{
									$load_error = "Det finns inga anmälningar för denna kurs.";
								}
							}
							?>				
						</tbody>
					</table>
					<?php
					if(isset($load_error)){
						echo $load_error;
					}
					?>
					<br/>
				</div>
			</div>
			</div>
			<form hidden method="post">
				<div class="row">
					<div class="twelve columns">
						<h3>Redigera anmälning</h3>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<label for="postUsername">Ägare:</label>
						<input class="u-full-width" type="text" name="ownerName" id="owner" readonly>
					</div>
					<div class="six columns">
						<label for="createdAt">Email:</label>
						<input class="u-full-width" type="text" name="email" id="email" readonly>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<label for="postUsername">Hundnamn:</label>
						<input class="u-full-width" type="text" name="dogName" id="dogName"readonly>
					</div>
					<div class="six columns">
						<label for="createdAt">Ålder:</label>
						<input class="u-full-width" type="number" min="0" step="1" name="ageOfDog" id="age">
					</div>
				</div>
				<div class="row">
					<div class="twelve columns">
						<label for="gender">Kön</label>
				      	<select required class="u-full-width" name="gender" id="gender">
				      	  <option value="M">Hane</option>
				      	  <option value="F">Tik</option>
				      	</select>
				    </div>
				</div>
				<div class="row">
					<div class="twelve columns">
						<label for="gender">Extra info</label>
						<textarea class="u-full-width" type="text" name="extraInfo" id="extraInfo"></textarea>
					</div>
				</div>
				<input hidden required class="u-full-width" type="text" name="courseName" id="courseName">
				<input hidden required class="u-full-width" type="text" name="courseTeacher" id="courseTeacher">
				<input hidden required class="u-full-width" type="date" name="courseDate" id="courseDate">
				<input value="Uppdatera" name="submit-update" type="submit">
				<input value="Radera" name="submit-delete" type="submit">
			</form>
	</div>
<script src="js/admin-course.js"></script>
</main>

<?php
include_once 'footer.php';
?>