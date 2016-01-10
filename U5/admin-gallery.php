<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}

include_once 'admin-header.php';

$allowed_ext = array('jpg');

if(isset($_POST['submit_image'])){

	if(strlen($_POST['image_title']) > 0){

		$image_title = $_POST['image_title'];

		$file = $_FILES['gallery_image'];
		if(isset($file) && (strlen($file['name']) != 0)) {

			if (strlen($file['name']) == 0){ $image_error = 'Saknar filnamn'; }

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];
			$file_ext = explode('.', $file_name);
			$file_ext = strtolower(end($file_ext));

			if (!in_array($file_ext, $allowed_ext)){ $image_error = 'Endast .jpg filer är tillåtna, prova ladda up en anna bild.'; }
			if($file['error'] != UPLOAD_ERR_OK){
				if ($file['error'] == UPLOAD_ERR_INI_SIZE){ 
					$image_error = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				}else if($file['error'] > 1 && $file['error'] < 9){
					$image_error = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			if(!isset($image_error)){
				$file_name_new = uniqid('', true) . '.' . $file_ext;
				$file_destination = 'uploads/' . $file_name_new;

				if(move_uploaded_file($file_tmp, $file_destination)){
					$result = nonQuery("INSERT INTO ImageGallery (ImagePath, ImageTitle) VALUES (:image_path,:image_title)", 
						array(
							":image_path" => $file_name_new, 
							":image_title" => $image_title
						)
					);
					
					if($result["err"] != null){
						$gallery_error = 'Gick inte att spara din bild, prova igen!';
						unlink($file_destination);
					}else{
						$gallery_success = 'Bild uppladdad!';
					}
				}else{ $gallery_error = 'Gick inte att flytta filen till servern, prova igen'; }			
			}else{ $gallery_error = $image_error; }
		}
	}else{ $gallery_error = "Ingen titel på bild."; }
}

if(isset($_POST['remove-image-submit'])){

	if(isset($_POST['image-path']) && (strlen($_POST['image-path']) > 0) && isset($_POST['image-title']) && (strlen($_POST['image-title']) > 0)){

		$imagePath = $_POST['image-path'];
		$imageTitle = $_POST['image-title'];

		$result = query("SELECT `ImagePath` FROM ImageGallery WHERE `ImagePath` = :imagePath AND `ImageTitle` = :imageTitle", array(":imagePath" => $imagePath, ":imageTitle" => $imageTitle));
		if($result["err"] == null){
			$resData = $result["data"];
			if($resData[0]["ImagePath"] !== NULL){
				$old_image = "uploads/" . $resData[0]["ImagePath"];
			}
		}

		$result = nonQuery("DELETE FROM ImageGallery WHERE `ImagePath` = :imagePath AND `ImageTitle` = :imageTitle", array(":imagePath" => $imagePath, ":imageTitle" => $imageTitle));

		if($result["err"] === NULL){
			$gallery_success = "Bild raderad."; 
			if(isset($old_image)){
				if(file_exists($old_image)){
			    	unlink($old_image);
				}
			}
		}else{
			$gallery_error = "Kunde inte tabort bild, prova igen.";
		}
	}else{ $gallery_error = "Kunde inte tabort bild, saknar värden."; }
}

?>
<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<?php
				if(isset($gallery_success)){
					echo '<span id="returnMsg" class="success-message">' . $gallery_success . '</span>';
				}
				if(isset($gallery_error)){
					echo '<span id="returnMsg" class="error-message">' . $gallery_error . '</span>';
				}
				?>
			</div>
		</div>
		<form enctype="multipart/form-data" method="POST" action="">
			<div class="row">
				<div class="four columns">
					<label for="image_title">Bildtitel:</label>
					<input type="text" name="image_title" placeholder="Batuulius" maxlength="255" required>
				</div>
				<div class="four columns">
					<label for="gallery_image">Infoga bild:</label>
					<input type="file" name="gallery_image" accept=".jpg" maxlength="255" required>
				</div>
				<div class="four columns">
					<label for="submit_image">&nbsp;</label>
					<input type="submit" name="submit_image" value="Lägg till bild i galleri">
				</div>
			</div>
			<hr>
		</form>
		<div class="row">
			<div class="twelve columns">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Bild</th>
							<th>Bildtitel</th>
							<th><center>Ta bort</center></th>
						</tr>
					</thead>
					<tbody id="galleryTable">
						<?php
						
						$result = query("SELECT ImagePath, ImageTitle FROM ImageGallery");
						
						if($result["err"] != null){
								$load_error = "Kunde inte ladda bilder, prova att ladda om sidan.";
							}else{
								$galleryData = $result['data'];

								if(count($galleryData) > 0){

									foreach($galleryData as $key => $img){
										echo '<tr id="gallery' . $key . '">';
										echo '<td class="galleryImageTd">';
										echo '<img src="uploads/'.$img['ImagePath'].'" width="100" height="100" alt="">';
										echo '</td>';
										echo '<td class="galleryTitleTd">';
										echo $img['ImageTitle'];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $img["ImagePath"];
										echo '</td>';
										echo '<td class="delete-picture">';
										echo '<center><i class="cursor-pointer fa fa-trash-o fa-lg"></i></center>';
										echo '</td>';
										echo '</tr>';
									}	
								}else{
									$load_error = "Det finns inga bilder i galleriet!";
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
				</div>
			</div>
		</div>
	<script src="js/admin-gallery.js"></script>
	</main>
	<?php
	include_once 'footer.php';
	?>