<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}
include_once 'admin-header.php';
?>

<?php

$allowed_ext = array('jpg');

if (isset($_POST['submit-new-dog'])){
	if (strlen($_POST['dogName']) > 0 &&
		strlen($_POST['officialName']) > 0) {

		$dogImg = $_FILES['dogImg'];
		$genImg = $_FILES['genImg'];

		if (isset($dogImg) && $dogImg['error'] != UPLOAD_ERR_NO_FILE){
			if (strlen($dogImg['name']) == 0){
				$submitNewDogError = 'Hundbilden saknar filnamn'; 
			}

			$dogImg_ext = explode('.', $dogImg['name']);
			$dogImg_ext = strtolower(end($dogImg_ext));

			if (!isset($submitNewDogError) && !in_array($dogImg_ext, $allowed_ext)){
				$submitNewDogError = 'Endast .jpg filer är tillåtna, prova ladda up en annan bild.';
			}

			if(!isset($submitNewDogError) && $dogImg['error'] != UPLOAD_ERR_OK){
				if ($dogImg['error'] == UPLOAD_ERR_INI_SIZE){ 
					$submitNewDogError = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				} else {
					$submitNewDogError = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			$dog_name_new = uniqid('', true) . '.' . $dogImg_ext;
			$dog_destination = 'uploads/' . $dog_name_new;

			if (!isset($submitNewDogError) && !move_uploaded_file($dogImg['tmp_name'], $dog_destination)){
				$submitNewDogError = 'Det gick inte att ladda upp hundbilden, prova igen!';
			}
		}

		if(isset($genImg) && $genImg['error'] != UPLOAD_ERR_NO_FILE){
			
			if (strlen($genImg['name']) == 0){ 
				$submitNewDogError = 'Stamtavla saknar filnamn'; 
			}

			$genImg_ext = explode('.', $genImg['name']);
			$genImg_ext = strtolower(end($genImg_ext));

			if (!isset($submitNewDogError) && !in_array($genImg_ext, $allowed_ext)){
				$submitNewDogError = 'Endast .jpg filer är tillåtna, prova ladda up en annan bild.';
			}

			if(!isset($submitNewDogError) && $genImg['error'] != UPLOAD_ERR_OK){
				if ($genImg['error'] == UPLOAD_ERR_INI_SIZE){ 
					$submitNewDogError = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				} else {
					$submitNewDogError = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			$gen_name_new = uniqid('', true) . '.' . $genImg_ext;
			$gen_destination = 'uploads/' . $gen_name_new;

			if (!isset($submitNewDogError) && !move_uploaded_file($genImg['tmp_name'], $gen_destination)){
				$submitNewDogError = 'Det gick inte att ladda upp stamtavla, prova igen!';
			}
		}

		$result = query('SELECT * FROM MyDog WHERE Name = :dogName AND OfficialName = :officialName',
			array(
				':dogName' => $_POST['dogName'],
				':officialName' => $_POST['officialName']
			)
		);

		if ($result['err'] == null){
			if (count($result['data']) > 0){
				$submitNewDogError = 'Hunden du försöker lägga till finns redan!';
			} else {
				$submitNewDogSuccess = 'Hund tillagd';
			}
		} else {
			$submitNewDogError = 'Det gick inte att posta den nya hundern, prova igen!';
		}

		$genImgPath = null;
		$dogImgPath = null;

		if (isset($gen_name_new)){
			$genImgPath = $gen_name_new;
		} 

		if (isset($dog_name_new)){
			$dogImgPath = $dog_name_new;
		}

		if (!isset($submitNewDogError)){	
			$insertResult = nonquery('INSERT INTO MyDog(Name, OfficialName, Birthdate, Description, Color, Height, Weight, MentalStatus, Breader, GenImagePath, DogImagePath) VALUES(:dogName, :officialName, :birthdate, :description, :color, :height, :weight, :mental, :breader, :genImgPath, :dogImgPath)',
				array(
					':dogName' => $_POST['dogName'],
					':officialName' => $_POST['officialName'],
					':color' => $_POST['color'],
					':birthdate' => $_POST['birthdate'],
					':description' => $_POST['description'],
					':height' => $_POST['height'],
					':weight' => $_POST['weight'],
					':mental' => $_POST['mental'],
					':breader' => $_POST['breader'],
					':genImgPath' => $genImgPath,
					':dogImgPath' => $dogImgPath
				)
			);
			
			if($result["err"] != null){
				$submitNewDogError = 'Gick inte att spara ditt inlägg, prova igen!';
			} 
		} 

		if (isset($submitNewDogError)) {
			if (isset($gen_destination) && file_exists($gen_destination)){
				unlink($gen_destination);
			} 

			if (isset($dog_destination) && file_exists($dog_destination)){
				unlink($dog_destination);
			} 
		} 
	} else {
		$submitNewDogError = 'Saknar värden';
	}
} else if (isset($_POST['submit-update-dog'])){
	if (strlen($_POST['dogName']) > 0 &&
		strlen($_POST['officialName']) > 0) {

		$findDogResult = query('SELECT * FROM MyDog WHERE Name = :dogName AND OfficialName = :officialName',
			array(
				':dogName' => $_POST['dogName'],
				':officialName' => $_POST['officialName']
			)
		);

		if ($findDogResult['err'] == null){
			if (count($findDogResult['data']) == 0){
				$submitUpdateDogError = 'Hunden du försöker ta bort finns ej.';
			} else {
				$currentData = $findDogResult['data'];
				$currentDog = $currentData[0];
			}
		}  else { $submitUpdateDogError = 'Gick inte att bekräfta hunden, prova igen.'; }

		if (!isset($submitUpdateDogError)){
			$dogImg = $_FILES['dogImg'];
			$genImg = $_FILES['genImg'];

			if (isset($dogImg) && $dogImg['error'] != UPLOAD_ERR_NO_FILE){
				if (strlen($dogImg['name']) == 0){
					$submitUpdateDogError = 'Hundbilden saknar filnamn'; 
				}

				if (!isset($submitUpdateDogError)){
					$dogImg_ext = explode('.', $dogImg['name']);
					$dogImg_ext = strtolower(end($dogImg_ext));
				}

				if (!isset($submitUpdateDogError) && !in_array($dogImg_ext, $allowed_ext)){
					$submitUpdateDogError = 'Endast .jpg filer är tillåtna, prova ladda up en annan bild.';
				}

				if(!isset($submitUpdateDogError) && $dogImg['error'] != UPLOAD_ERR_OK){
					if ($dogImg['error'] == UPLOAD_ERR_INI_SIZE){ 
						$submitUpdateDogError = 'Filen är för stor, 2mb stora filer är tillåtna'; 
					} else {
						$submitUpdateDogError = 'Filen gick inte att ladda upp, prova igen!';
					}
				}

				if (!isset($submitUpdateDogError)){
					$dog_name_new = uniqid('', true) . '.' . $dogImg_ext;
					$dog_destination = 'uploads/' . $dog_name_new;
				}

				if (!isset($submitUpdateDogError) && !move_uploaded_file($dogImg['tmp_name'], $dog_destination)){
					$submitUpdateDogError = 'Det gick inte att ladda upp hundbilden, prova igen!';
				}

				
			}

			if(isset($genImg) && $genImg['error'] != UPLOAD_ERR_NO_FILE){
				
				if (strlen($genImg['name']) == 0){ 
					$submitUpdateDogError = 'Stamtavla saknar filnamn'; 
				}

				if (!isset($submitUpdateDogError)){
					$genImg_ext = explode('.', $genImg['name']);
					$genImg_ext = strtolower(end($genImg_ext));
				}

				if (!isset($submitUpdateDogError) && !in_array($genImg_ext, $allowed_ext)){
					$submitUpdateDogError = 'Endast .jpg filer är tillåtna, prova ladda up en annan bild.';
				}

				if(!isset($submitUpdateDogError) && $genImg['error'] != UPLOAD_ERR_OK){
					if ($genImg['error'] == UPLOAD_ERR_INI_SIZE){ 
						$submitUpdateDogError = 'Filen är för stor, 2mb stora filer är tillåtna'; 
					} else {
						$submitUpdateDogError = 'Filen gick inte att ladda upp, prova igen!';
					}
				}

				if (!isset($submitUpdateDogError)){
					$gen_name_new = uniqid('', true) . '.' . $genImg_ext;
					$gen_destination = 'uploads/' . $gen_name_new;
				}

				if (!isset($submitUpdateDogError) && !move_uploaded_file($genImg['tmp_name'], $gen_destination)){
					$submitUpdateDogError = 'Det gick inte att ladda upp stamtavla, prova igen!';
				}	
			}

			$genImgPath = $currentDog['GenImagePath'];
			$dogImgPath = $currentDog['DogImagePath'];

			if (isset($gen_name_new)){
				$genImgPath = $gen_name_new;
			} 

			if (isset($dog_name_new)){
				$dogImgPath = $dog_name_new;
			}

			if (!isset($submitUpdateDogError)){
				$updateResult = nonQuery('UPDATE MyDog SET Color = :color, Birthdate = :birthdate, Description = :description, Height = :height, Weight = :weight, MentalStatus = :mental, Breader = :breader, GenImagePath = :genImgPath, DogImagePath = :dogImgPath WHERE Name = :dogName AND OfficialName = :officialName',
					array(
						':dogName' => $_POST['dogName'],
						':officialName' => $_POST['officialName'],
						':color' => $_POST['color'],
						':birthdate' => $_POST['birthdate'],
						':description' => $_POST['description'],
						':height' => $_POST['height'],
						':weight' => $_POST['weight'],
						':mental' => $_POST['mental'],
						':breader' => $_POST['breader'],
						':genImgPath' => $genImgPath,
						':dogImgPath' => $dogImgPath
					)
				);

				if ($updateResult['err'] == null){
					if (isset($genImgPath) && $currentDog['GenImagePath'] != null && $genImgPath != $currentDog['GenImagePath']){
						$oldPath = 'uploads/' . $currentDog['GenImagePath'];
						if (file_exists($oldPath)){
							unlink($oldPath);
						}
					} 

					if (isset($dogImgPath) && $currentDog['DogImagePath'] != null && $dogImgPath != $currentDog['DogImagePath']){
						$oldPath = 'uploads/' . $currentDog['DogImagePath'];
						if (file_exists($oldPath)){
							unlink($oldPath);
						}
					}

					$submitUpdateDogSuccess = 'Hund updaterad';
				} else {
					$submitUpdateDogError = 'Gick inte att uppdatera hund, prova igen!';

					if (isset($dog_destination)){
						if (file_exists($dog_destination)){
							unlink($dog_destination);
						}
					}

					if (isset($gen_destination)){
						if (file_exists($gen_destination)){
							unlink($gen_destination);
						}
					}
				}
			}
		} else { $submitUpdateDogError = 'Gick inte att bekräfta att hund finns, prova igen!'; }
	} else { $submitUpdateDogError = 'Saknar värden för att ta bort hund'; }
} else if (isset($_POST['submit-delete-dog'])) {
	if (strlen($_POST['dogName']) > 0 &&
		strlen($_POST['officialName']) > 0) {

		$findDogResult = query('SELECT * FROM MyDog WHERE Name = :dogName AND OfficialName = :officialName',
			array(
				':dogName' => $_POST['dogName'],
				':officialName' => $_POST['officialName']
			)
		);

		if ($findDogResult['err'] == null){
			if (count($findDogResult['data']) > 0){
				$deleteResult = nonquery('DELETE FROM MyDog WHERE Name = :dogName AND OfficialName = :officialName',
					array(
						':dogName' => $_POST['dogName'],
						':officialName' => $_POST['officialName']
					)
				);

				if ($deleteResult['err'] != null){ 
					$submitDeleteDogError = 'Gick inte att ta bort hund, prova igen.'; 
				} else {
					$submitDeleteDogSuccess = 'Hund bortagen';
				}
			} else { $submitDeleteDogError = 'Hunden du försöker ta bort finns ej.'; }
		} else { $submitDeleteDogError = 'Gick inte att bekräfta att hund finns, prova igen!'; }
	} else { $submitDeleteDogError = 'Saknar värden för att ta bort hund'; }
}


$myDogsResult = query('SELECT * FROM MyDog ORDER BY Name ASC');
if ($myDogsResult['err'] == null){
	$myDogs = $myDogsResult['data'];
} else {
	$myDogsError = 'Det gick inte att läsa hundarna, prova igen!';
}
?>

<main>
	<div class="container">
		<div class="row">
		<div class="twelve columns">
			<?php
			if (isset($submitNewDogError)){
				echo '<span id="returnMsg" class="error-message">' . $submitNewDogError . '</span>';
			}

			if (isset($submitUpdateDogError)){
				echo '<span id="returnMsg" class="error-message">' . $submitUpdateDogError . '</span>';
			}

			if (isset($submitDeleteDogError)){
				echo '<span id="returnMsg" class="error-message">' . $submitDeleteDogError . '</span>';
			}

			if (isset($myDogsError)){
				echo '<span id="returnMsg" class="error-message">' . $myDogsError . '</span>';
			}

			if(isset($submitNewDogSuccess)){
				echo '<span id="returnMsg" class="success-message">' . $submitNewDogSuccess . '</span>';
			}

			if(isset($submitUpdateDogSuccess)){
				echo '<span id="returnMsg" class="success-message">' . $submitUpdateDogSuccess . '</span>';
			}

			if(isset($submitDeleteDogSuccess)){
				echo '<span id="returnMsg" class="success-message">' . $submitDeleteDogSuccess . '</span>';
			}
			?>
		</div>
	</div>
		<div class="row">
			<div class="twelve columns">
				<h4 style="text-align: center;">Mina Hundar</h4>
			</div>
		</div>
		<hr>
		<div id="returnMsg" style="margin:20px 0 50px 0;">

		</div>
		<div class="row">
				<div class="twelve columns">
					<h3 id="add-dog-header">Lägg till hund  <i class="fa fa-plus" id="add-dog-icon"></i></h3>
				</div>
			</div>
		<form hidden enctype="multipart/form-data" method="POST" action="" id='add-dog-form'>
			<div class="row">
				<div class="six columns">
					<label for="title">Hundnamn</label>
					<input required maxlength="255" type="text" name="dogName" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Officiellt namn</label>
					<input required maxlength="255" type="text" name="officialName" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Färg</label>
					<input maxlength="255" type="text" name="color" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Uppfödare</label>
					<input maxlength="255" type="text" name="breader" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Vikt i kg</label>
					<input min="0" step="0.01" value="0" type="number" name="weight" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Mankhöjd i meter</label>
					<input min="0" step="0.01" type="number" name="height" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">MentalStatus</label>
					<input maxlength="255" type="text" name="mental" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Födelsedag</label>
					<input type="date" name="birthdate" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="image-path">Bild på hund:</label>
					<input type="file" name="dogImg" accept=".jpg">	
				</div>
				<div class="six columns">
					<label for="image-path">Stamtavla:</label>
					<input type="file" name="genImg" accept=".jpg">	
				</div>
			</div>
			<label for="news-text">Beskrivning:</label>
			<textarea name="description" class="u-full-width"></textarea>
			<div class="row">
				<div class="four columns">
					<input type="submit" name="submit-new-dog" value="Lägg till">		
				</div>
				<div class="eight columns">
				</div>
			</div>
		</form>
		<hr>
		<br/>
		<div class="row">
			<div class="twelve columns">
				<h3>Redigera befintliga hundar</h3>
				<p>Välj en hund i listan genom att klicka på den. Redigera den sedan i det nya formuläret som poppar upp.</p>

				<div class="row">
					<div class="twelve columns">
						<table class="u-full-width">
							<thead>
								<tr>
									<th>Namn</th>
									<th>Officielt Namn</th>
								</tr>
							</thead>
							<tbody id="newsTable">
								<?php
									if (!isset($myDogsError)){
										if (count($myDogs) > 0){
											foreach($myDogs as $key => $d){
												echo '<tr id="dog' . $key . '">';
												echo '<td class="nameTd">';
												echo $d['Name'];
												echo '</td>';
												echo '<td class="officialNameTd">';
												echo $d["OfficialName"];
												echo '</td>';
												echo '<td hidden class="colorTd">';
												echo $d["Color"];
												echo '</td>';
												echo '<td hidden class="breaderTd">';
												echo $d["Breader"];
												echo '</td>';
												echo '<td hidden class="weightTd">';
												echo $d["Weight"];
												echo '</td>';
												echo '<td hidden class="heightTd">';
												echo $d["Height"];
												echo '</td>';
												echo '<td hidden class="mentalTd">';
												echo $d["MentalStatus"];
												echo '</td>';
												echo '<td hidden class="birthdateTd">';
												echo $d["Birthdate"];
												echo '</td>';
												echo '<td hidden class="imageDogTd">';
												echo $d["GodImagePath"];
												echo '</td>';
												echo '<td hidden class="imageGenTableTd">';
												echo $d["GenImagePath"];
												echo '</td>';
												echo '<td hidden class="descTd">';
												echo $d["Description"];
												echo '</td>';
												echo '</tr>';
											}
										} else {
											echo 'Finns inga egna hundar';
										}
									}			
								?>				
							</tbody>
						</table>
					</div>
				</div>

			</div>
		</div>
		<br/>

		<form hidden enctype="multipart/form-data" action="" method="post" id="update-delete-form">
			<div class="row">
				<div class="twelve columns">
					<h3>Redigera Hund</h3>
				</div>
			</div>
			<div class="row">	
				<div class="six columns">
					<label for="title">Hundnamn</label>
					<span maxlength="255" type="text" class="u-full-width" id="text-dog-name"></span>
					<input hidden maxlength="255" type="text" name="dogName" class="u-full-width" id="edit-dog-name">
				</div>
				<div class="six columns">
					<label for="title">Officiellt namn</label>
					<span maxlength="255" type="text" class="u-full-width" id="text-official-name"></span>
					<input hidden maxlength="255" type="text" name="officialName" class="u-full-width" id="edit-official-name">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Färg</label>
					<input maxlength="255" type="text" name="color" class="u-full-width" id="edit-color">
				</div>
				<div class="six columns">
					<label for="title">Uppfödare</label>
					<input maxlength="255" type="text" name="breader" class="u-full-width" id="edit-breader">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Vikt i kg</label>
					<input min="0" step="0.01" value="0" type="number" name="weight" class="u-full-width" id="edit-weight">
				</div>
				<div class="six columns">
					<label for="title">Mankhöjd i meter</label>
					<input min="0" step="0.01" type="number" name="height" class="u-full-width" id="edit-height">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">MentalStatus</label>
					<input maxlength="255" type="text" name="mental" class="u-full-width" id="edit-mental">
				</div>
				<div class="six columns">
					<label for="title">Födelsedag</label>
					<input type="date" name="birthdate" class="u-full-width" id="edit-birthdate"> 
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="image-path">Bild på hund:</label>
					<input type="file" name="dogImg" accept=".jpg" id="edit-img-dog">	
				</div>
				<div class="six columns">
					<label for="image-path">Stamtavla:</label>
					<input type="file" name="genImg" accept=".jpg" id="edit-img-gen-table">	
				</div>
			</div>
			<label for="news-text">Beskrivning:</label>
			<textarea class="u-full-width" id="edit-description" name="description"></textarea>
			<div class="row">
				<div class="six columns">
					<input value="Uppdatera" name="submit-update-dog" type="submit">		
				</div>
				<div class="six columns">
					<input value="Radera" name="submit-delete-dog" type="submit">	
				</div>
			</div>
		</form>
	</div>
<script src="js/admin-my-dogs.js"></script>
</main>

<?php
include_once 'footer.php';
?>