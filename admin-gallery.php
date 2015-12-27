<?php
include_once 'admin-header.php';

if(isset($_POST['submit_image'])){
	if(strlen($_POST['image_title']) > 0){
		$image_title = $_POST['image_title'];

		if(isset($_FILES['gallery_image'])) {
			$file = $_FILES['gallery_image'];

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];

			$file_ext = explode('.', $file_name);
			$file_ext = strtolower(end($file_ext));

			$allowed_ext = array('jpg');

			if(in_array($file_ext, $allowed_ext)){

				if($file_error === 0){
					//2mb
					if($file_size < 2097152){	
						$file_name_new = uniqid('', true) . '.' . $file_ext;
						$file_destination = '../uploads/' . $file_name_new;

						if(move_uploaded_file($file_tmp, $file_destination)){
							$result = nonQuery("INSERT INTO ImageGallery (`ImagePath`,`ImageTitle`) VALUES (:image_path,:image_title)", array(":image_path" => $file_name_new, ":image_title" => $image_title));
							
							if($result["err"] === null){

							}
						}
					}
				}
			}
		}

	}

}

?>
<main>
	<form enctype="multipart/form-data" method="POST" action="">
		<div class="row">
			<div class="four columns">
				<label for="image_title">Bildtitel:</label>
				<input type="text" name="image_title">
			</div>
			<div class="four columns">
				<label for="gallery_image">Infoga bild:</label>
				<input type="file" name="gallery_image" accept=".jpg">
			</div>
			<div class="four columns">
				<label for="submit_image">&nbsp;</label>
				<input type="submit" name="submit_image" class="button-primary" value="Lägg till bild i galleri">
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
						</tr>
					</thead>
					<tbody id="galleryTable">
						<?php
						$divOne = query("SELECT ImagePath, ImageTitle FROM ImageGallery");
						$divData = $divOne['data'];
						foreach($divData as $key => $row){
							echo '<tr id="gallery' . $key . '">';
							echo '<td class="galleryImageTd">';
							echo '<img src="../uploads/'.$row['ImagePath'].'" width="100" height="100" alt="">';
							echo '</td>';
							echo '<td class="galleryTitleTd">';
							echo $row['ImageTitle'];
							echo '</td>';
							echo '</tr>';
						}			
						?>				
					</tbody>
				</table>
			</div>
</main>
<?php
include_once '../footer.php';
?>