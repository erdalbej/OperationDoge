<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/');
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

		if (isset($dogImg) && ($dogImg['error'] > 0 || $dogImg['size'] > 0)){
			if (strlen($dogImg['name']) == 0){
				$submitNewDogError = 'Hundbilden saknar filnamn'; 
			}

			$dogImg_ext = explode('.', $dogImg['name']);
			$dogImg_ext = strtolower(end($dogImg_ext));

			if (!in_array($dogImg_ext, $allowed_ext)){
				$submitNewDogError = 'Endast .jpg filer är tillåtna, prova ladda up en annan bild.';
			}

			if($dogImg['error'] != UPLOAD_ERR_OK){
				if ($dogImg['error'] == UPLOAD_ERR_INI_SIZE){ 
					$submitNewDogError = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				} else {
					$submitNewDogError = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			$dog_name_new = uniqid('', true) . '.' . $dogImg_ext;
			$dog_destination = 'uploads/' . $dog_name_new;

			if (!move_uploaded_file($dogImg['tmp_name'], $dog_destination)){
				$submitNewDogError = 'Det gick inte att ladda upp hundbilden, prova igen!';
			}
		}

		if(isset($genImg) && ($genImg['error'] > 0 || $genImg['size'] > 0)){
			
			if (strlen($genImg['name']) == 0){ 
				$submitNewDogError = 'Stamtavla saknar filnamn'; 
			}

			$genImg_ext = explode('.', $genImg['name']);
			$genImg_ext = strtolower(end($genImg_ext));

			if (!in_array($genImg_ext, $allowed_ext)){
				$submitNewDogError = 'Endast .jpg filer är tillåtna, prova ladda up en annan bild.';
			}

			if($genImg['error'] != UPLOAD_ERR_OK){
				if ($genImg['error'] == UPLOAD_ERR_INI_SIZE){ 
					$submitNewDogError = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				} else {
					$submitNewDogError = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			$gen_name_new = uniqid('', true) . '.' . $genImg_ext;
			$gen_destination = 'uploads/' . $gen_name_new;

			if (!move_uploaded_file($genImg['tmp_name'], $gen_destination)){
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
			}
		} else {
			$submitNewDogError = 'Det gick inte att verifiera dubbleter, prova igen!';
		}

		if (!isset($submitNewDogError)){	
				$insertResult = nonquery('INSERT INTO MyDog(Name, OfficialName, Birthdate, Description, Color, Height, Weight, Teeth, MentalStatus, Breader, GenImagePath, DogImagePath) VALUES(:dogName, :officialName, :birthdate, :description, :color, :height, :weight, :teeth, :mental, :breader, :genImgPath, :dogImgPath)',
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
					unlink($file_destination);
				} 
			} 
		} 
	} 
}
		


		$genImgPath = null;
		$dogImgPath = null;

		if ($result['err'] == null){
			if (count($result['data']) == 0){
				

				if ($insertResult['err'] != null){
					$submitNewDogError = 'Det gick inte att lägga in hunden i databasen, prova igen'; 
				} 
			} else { $submitNewDogError = 'Hunden du försöker skapa finns redan'; }
		} else { $submitNewDogError = 'Gick inte att lägga till ny hund, prova igen!'; }
	} else { $submitNewDogError = 'Saknar värden'; }
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
			if(isset($submitNewDogError)){
				echo '<span id="returnMsg" class="error-message">' . $submitNewDogError . '</span>';
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
			<?php
				if (isset($submitNewDogError)){
					echo $submitNewDogError;
				}

				if (isset($myDogsError)){
					echo $myDogsError;
				}

				if(isset($submit_news_msg)){
					echo $submit_news_msg;
				}
				if(isset($delete_news_msg )){
					echo $delete_news_msg ;
				}
				if(isset($update_news_msg)){
					echo $update_news_msg;
				}
			?>
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
					<input min="0" step="0.1" value="0" type="number" name="weight" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Mankhöjd i meter</label>
					<input min="0" step="0.1" type="number" name="height" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">MentalStatus</label>
					<input maxlength="255" type="text" name="mental" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Födelsedag</label>
					<input required type="date" name="birthdate" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="image-path">Bild på hund:</label>
					<input type="file" name="dogImage" accept=".jpg">	
				</div>
				<div class="six columns">
					<label for="image-path">Stamtavla:</label>
					<input type="file" name="genImage" accept=".jpg">	
				</div>
			</div>
			<label for="news-text">Beskrivning:</label>
			<textarea name="description" id="" class="u-full-width"></textarea>
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

								#print_r($_POST);
								print_r($insertResult['err']);
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

		<form hidden enctype="multipart/form-data" action="" method="post">
			<div class="row">
				<div class="twelve columns">
					<h3>Redigera Hund</h3>
				</div>
			</div>
			<div class="row">	
				<div class="six columns">
					<label for="title">Hundnamn</label>
					<input disabled maxlength="255" type="text" name="title" class="u-full-width" id="edit-dog-name">
				</div>
				<div class="six columns">
					<label for="title">Officiellt namn</label>
					<input disabled maxlength="255" type="text" name="title" class="u-full-width" id="edit-official-name">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Färg</label>
					<input maxlength="255" type="text" name="title" class="u-full-width" id="edit-color">
				</div>
				<div class="six columns">
					<label for="title">Uppfödare</label>
					<input maxlength="255" type="text" name="title" class="u-full-width" id="edit-breader">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Vikt i kg</label>
					<input min="0" step="0.1" value="0" type="number" name="title" class="u-full-width" id="edit-weight">
				</div>
				<div class="six columns">
					<label for="title">Mankhöjd i meter</label>
					<input min="0" step="0.1" type="number" name="title" class="u-full-width" id="edit-height">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">MentalStatus</label>
					<input maxlength="255" type="text" name="mental" class="u-full-width" id="edit-mental">
				</div>
				<div class="six columns">
					<label for="title">Födelsedag</label>
					<input required type="date" name="birthday" class="u-full-width" id="edit-birthdate"> 
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="image-path">Bild på hund:</label>
					<input type="file" name="n" accept=".jpg" id="edit-img-dog">	
				</div>
				<div class="six columns">
					<label for="image-path">Stamtavla:</label>
					<input type="file" name="news-image" accept=".jpg" id="edit-img-gen-table">	
				</div>
			</div>
			<label for="news-text">Beskrivning:</label>
			<textarea name="news-text" id="" class="u-full-width" id="edit-description" name="description"></textarea>
			<div class="row">
				<div class="six columns">
					<input value="Uppdatera" name="news-update" type="submit">		
				</div>
				<div class="six columns">
					<input value="Radera" name="news-delete" type="submit">	
				</div>
			</div>
		</form>
	</div>
<script src="js/admin-my-dogs.js"></script>
</main>

<?php
include_once 'footer.php';
?>