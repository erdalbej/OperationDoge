<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}
include_once 'admin-header.php';

$litterTitle = NULL;

if(isset($_GET['LitterTitle'])){
	$litterTitle = $_GET['LitterTitle'];
}

//Create puppy
if(isset($_POST['submit_puppy'])){
	if(strlen($_POST['name']) > 0 && strlen($_POST['gender']) > 0 && strlen($_POST['price']) > 0 && strlen($_POST['available']) > 0 && strlen($_POST['birthDate']) > 0){

		$name = $_POST['name'];
		$gender = $_POST['gender'];
		$price = $_POST['price'];
		$available = $_POST['available'];
		$birthDate = $_POST['birthDate'];

		$result = nonQuery("INSERT INTO Puppy (DogName, Gender, Price, Available, BirthDate, PuppyLitter_LitterTitle) VALUES (:name, :gender, :price, :available, :birthdate, :littertitle)", 
			array(
				':name' => $name, 
				':gender' => $gender,
				':price' => $price,
				':available' => $available,
				':birthdate' => $birthDate,
				'littertitle' => $litterTitle  
				)
			);

		if($result["err"] != null){ $createError  = 'Det gick inte att skapa hunden, prova igen!'; } 
		else { $createSuccess = "Hunden skapades"; }
	} else { $createError = 'Saknar värden'; }
} 
//Update puppy
if(isset($_POST['update_puppy'])){
	
	if(strlen($_POST['alter_name']) > 0 && strlen($_POST['alter_gender']) > 0 && strlen($_POST['alter_price']) > 0 && strlen($_POST['alter_available']) > 0 && strlen($_POST['alter_birthDate']) > 0){

		$name = $_POST['alter_name'];
		$gender = $_POST['alter_gender'];
		$price = $_POST['alter_price'];
		$available = $_POST['alter_available'];
		$birthDate = $_POST['alter_birthDate'];

		$result = nonQuery("UPDATE Puppy SET `Gender` = :gender, `Price` = :price, `Available` = :available, `BirthDate` = :birthDate WHERE `DogName` = :name", array(":gender" => $gender, ":price" => $price, ":available" => $available, ":birthDate" => $birthDate, ":name" => $name));

		if($result["err"] == null){
			$updateSuccess = "Hund uppdaterad!"; 
		}else{
			$updateError = "Kunde inte uppdatera hund, prova igen.";
		}
	}else{
		$updateError = "Saknar värden för att uppdatera hund.";
	}
}
//Delete puppy
if(isset($_POST['delete_puppy'])){

	if(strlen($_POST['alter_name']) > 0){

		$name = $_POST['alter_name'];

		$result = nonQuery("DELETE FROM Puppy WHERE `DogName` = :name", array(":name" => $name));

		if($result["err"] === NULL){
			$deleteSuccess = "Hund raderad!"; 
		}else{
			$deleteError = "Kunde inte radera hund, prova igen.";
		}
	}else{
		$deleteError = "Saknar värden för att radera hund.";
	}
}
?>

<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h4 style="text-align: center;">Hundar i kull: <b><?php echo $litterTitle ?></b></h4>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<?php
				if(isset($createSuccess)){
					echo '<span id="returnMsg" class="success-message">' . $createSuccess . '</span>';
				}
				if(isset($createError)){
					echo '<span id="returnMsg" class="error-message">' . $createError . '</span>';
				}
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
				<h5>Lägg till Hund</h5>
				<p>För att lägga till en hund i kullen fyll i informationen nedan och tryck på knappen. </p>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<form method="POST" action="">
					<div class="row">
						<div class="four columns">
							<label required for="name">Namn:</label>
							<input required maxlength="255" class="u-full-width" type="text" name="name">
						</div>
						<div class="four columns">
							<label for="gender">Kön:</label>
							<select required class="u-full-width" name="gender">
								<option value="Hane">Hane</option>
								<option value="Tik">Tik</option>
							</select>
						</div>
						<div class="four columns">
							<label required for="birthDate">Födelsedatum:</label>
							<input required maxlength="255" class="u-full-width" type="date" name="birthDate">
						</div>
						
					</div>
					<div class="row">
						<div class="six columns">
							<label required for="litterTitle">Pris:</label>
							<input required maxlength="255" class="u-full-width" type="text" name="price">
						</div>
						<div class="six columns">
							<label for="available">Tillgänglig för försäljning:</label>
							<select required class="u-full-width" name="available">
								<option value="1">Ja</option>
								<option value="0">Nej</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="twelve columns">
							<input type="submit" name="submit_puppy" value="Skapa hund">
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="row">
			<div class="twelve columns">
				<h5>Redigera Hund</h5>
				<p>För att redigera en hund, klicka på ikonen till höger i listan för att få upp hunden. </p>
				<hr>
			</div>
		</div>
	<div class="row">
		<div class="twelve columns">
			<h3>Välj hund att redigera</h3>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
			<table class="u-full-width">
				<thead>
					<tr>
						<th>Namn</th>
						<th>Kön</th>
						<th>Pris</th>
						<th>Tillgänglig för försäljning</th>
						<th>Födelsedatum</th>
						<th><center>Redigera Hund</center></th>
					</tr>
				</thead>
				<tbody>
					<?php

					$result = query("SELECT DogName, Gender, Price, Available, BirthDate, PuppyLitter_LitterTitle FROM Puppy WHERE PuppyLitter_LitterTitle = :title ORDER BY DogName DESC", array(":title" => $litterTitle));

					if($result["err"] != null){
						$load_error = "Kunde inte ladda hundar, prova att ladda om sidan.";
					}else{
						$puppyLitterData = $result['data'];

						if(count($puppyLitterData) > 0){
							foreach($puppyLitterData as $key => $p){
								echo '<tr>';
								echo '<td >';
								echo $p['DogName'];
								echo '</td>';
								echo '<td>';
								echo $p["Gender"];
								echo '</td>';
								echo '<td>';
								echo $p["Price"];
								echo '</td>';
								echo '<td>';
								if($p["Available"] === "1"){
									echo 'Ja';
								}else { echo 'Nej'; }
								echo '</td>';
								echo '<td>';
								echo $p["BirthDate"];
								echo '</td>';
								echo '<td class="edit-puppy">';
								echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
								echo '</td>';
								echo '</tr>';
							}	
						}else{
							$load_error = "Finns inga hundar tillgängliga.";
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
	<div class="row">
			<div class="twelve columns">
				<form hidden method="POST" action="">
					<div class="row">
						<div class="four columns">
							<label for="name">Namn:</label>
							<input maxlength="255" class="u-full-width" type="text" id="PuppyName" name="alter_name" readonly>
						</div>
						<div class="four columns">
							<label for="gender">Kön:</label>
							<select required class="u-full-width" name="alter_gender" id="PuppyGender">
								<option value="Hane">Hane</option>
								<option value="Tik">Tik</option>
							</select>
						</div>
						<div class="four columns">
							<label for="birthDate">Födelsedatum:</label>
							<input maxlength="255" class="u-full-width" type="date" id="PuppyBirthDate" name="alter_birthDate">
						</div>
						
					</div>
					<div class="row">
						<div class="six columns">
							<label for="litterTitle">Pris:</label>
							<input maxlength="255" class="u-full-width" type="text" id="PuppyPrice" name="alter_price">
						</div>
						<div class="six columns">
							<label for="available">Tillgänglig för försäljning:</label>
							<select class="u-full-width" id="PuppyAvailable" name="alter_available">
								<option value="1">Ja</option>
								<option value="0">Nej</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="twelve columns">
							<input type="submit" name="update_puppy" value="Uppdatera hund">
							<input type="submit" name="delete_puppy" value="Radera hund">
						</div>
					</div>
				</form>
			</div>
		</div>
</div>

<script src="js/admin-puppy-from-litter.js"></script>
</main>

<?php
include_once 'footer.php';
?>