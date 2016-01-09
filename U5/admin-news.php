<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}

include_once 'admin-header.php';

$allowed_ext = array('jpg');

if (isset($_POST['submit-news'])){

	if (strlen($_POST['title']) > 0){

		$title = $_POST['title'];
		$news_text = $_POST['news-text'];

		$file = $_FILES['news-image'];
		if (isset($file) && (strlen($file['name']) != 0)) {
			
			if (strlen($file['name']) == 0){ $image_error = 'Saknar filnamn'; }

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];
			$file_ext = explode('.', $file_name);
			$file_ext = strtolower(end($file_ext));

			if (!in_array($file_ext, $allowed_ext)){ $image_error = 'Endast .jpg filer är tillåtna, prova ladda up en anna bild.'; }
			if ($file['error'] != UPLOAD_ERR_OK){
				if ($file['error'] == UPLOAD_ERR_INI_SIZE){ 
					$image_error = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				} else if ($file['error'] > 1 && $file['error'] < 9){
					$image_error = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			if (!isset($image_error)){
				$file_name_new = uniqid('', true) . '.' . $file_ext;
				$file_destination = 'uploads/' . $file_name_new;

				if (move_uploaded_file($file['tmp_name'], $file_destination)){
					$result = nonQuery("INSERT INTO News (`Title`,`CreatedAt`,`NewsText`,`NewsImagePath`) VALUES (:title,now(),:news_text,:image)", 
						array(
							":title" => $title, 
							":news_text" => $news_text, 
							":image" => $file_name_new
						)
					);
					
					if ($result["err"] != null){
						$news_error = 'Gick inte att ladda upp din nyhet, prova igen.';
						unlink($file_destination);
					} else { $news_success = "Nyhet upplagd och publicerad!"; }
				} else { $news_error = 'Gick inte att flytta filen till servern, prova igen'; }
			} else { $news_error = $image_error; }
		} else{
			$result = nonQuery("INSERT INTO News (`Title`,`CreatedAt`,`NewsText`) VALUES (:title,now(),:news_text)", array(":title" => $title, ":news_text" => $news_text));

			if ($result["err"] === null){ 
				$news_success = "Nyhet upplagd och publicerad!"; 
			}
			else { 
				$news_error = "Gick inte att flytta filen till servern, prova igen"; 
			}
		}
	}
} else if (isset($_POST['news-update'])){
	
	if (strlen($_POST['edit-title']) > 0 && strlen($_POST['edit-createdAt']) > 0){

		$news_title = $_POST['edit-title'];
		$news_createdAt = $_POST['edit-createdAt'];
		$news_text = $_POST['edit-text'];
		
		$file = $_FILES['edit-new-image'];
		if (isset($file) && (strlen($file['name']) != 0)) {
			
			if (strlen($file['name']) == 0){ $image_error = 'Saknar filnamn'; }

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];
			$file_ext = explode('.', $file_name);
			$file_ext = strtolower(end($file_ext));

			if (!in_array($file_ext, $allowed_ext)){ $image_error = 'Endast .jpg filer är tillåtna, prova ladda up en anna bild.'; }
			if ($file['error'] != UPLOAD_ERR_OK){

				if ($file['error'] == UPLOAD_ERR_INI_SIZE){ 
					$image_error = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				} else if ($file['error'] > 1 && $file['error'] < 9){
					$image_error = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			if (!isset($image_error)){
				$file_name_new = uniqid('', true) . '.' . $file_ext;
				$file_destination = 'uploads/' . $file_name_new;
				
				$result = query("SELECT `NewsImagePath` FROM News WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt));
				if ($result["err"] == null){
					$resData = $result["data"];
					if ($resData[0]["NewsImagePath"] !== NULL){
						$old_image = "uploads/" . $resData[0]["NewsImagePath"];
					}
				}

				if (move_uploaded_file($file['tmp_name'], $file_destination)){
					$result = nonQuery("UPDATE News SET `NewsText` = :news_text, `NewsImagePath` = :image_path WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", 
						array(
							":news_title" => $news_title,
							":news_createdAt" => $news_createdAt, 
							":news_text" => $news_text, 
							":image_path" => $file_name_new
						)
					);
					
					if ($result["err"] != null){
						$news_error = 'Gick inte att uppdatera nyhet, prova igen.';
						unlink($file_destination);
					} else{
						$news_success = "Nyhet uppdaterad och publicerad!";
						if (isset($old_image)){
							if (file_exists($old_image)){
						    	unlink($old_image);
							}
						}
					}
				} else { $news_error = 'Gick inte att flytta filen till servern, prova igen'; }
			} else { $news_error = $image_error; }
		} else {
	
			$result = nonQuery("UPDATE News SET `NewsText` = :news_text WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt, ":news_text" => $news_text));
			
			if( $result["err"] === null){
				$news_success = "Nyhet uppdaterad"; 
			} else {
				$news_error = "Kunde inte uppdatera nyhet, prova igen.";
			}
		}
	}
} else if (isset($_POST['news-delete'])){

	if (strlen($_POST['edit-title']) > 0 && strlen($_POST['edit-createdAt']) > 0){

		$news_title = $_POST['edit-title'];
		$news_createdAt = $_POST['edit-createdAt'];

		$result = query("SELECT `NewsImagePath` FROM News WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt));
		if ($result["err"] == null){
			$resData = $result["data"];
			if ($resData[0]["NewsImagePath"] !== NULL){
				$old_image = "uploads/" . $resData[0]["NewsImagePath"];
			}
		}

		$result = nonQuery("DELETE FROM News WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt));
		
		if ($result["err"] === NULL){
			$news_success = "Nyhet raderad!"; 
			if(isset($old_image)){
				if(file_exists($old_image)){
			    	unlink($old_image);
				}
			}
		} else { $news_error = "Kunde inte tabort nyhet, prova igen."; }
	} else { $news_error = "Kunde inte tabort nyhet, saknar värden."; }
}


?>
<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<?php
					if(isset($news_success)){
						echo '<span id="returnMsg" class="success-message">' . $news_success . '</span>';
					}
					if(isset($news_error)){
						echo '<span id="returnMsg" class="error-message">' . $news_error . '</span>';
					}
				?>
			</div>
		</div>

		<div class="row">
			<div class="twelve columns">
				<h5>Lägg till nyheter till startsidan</h5>
				<p>Lägg till nyheter i det översta formuläret. För att redigera/radera befintliga nyheter, välj nyhet i tabellen nedan genom att klicka på den och redigera/radera den via det nya formuläret som poppar upp.</p>
			</div>
		</div>
		<hr>
		<form enctype="multipart/form-data" method="POST" action="" class="news-form">
			<div class="row">
				<div class="twelve columns">
					<h3>Lägg till nyhet</h3>
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Nyhetstitel:</label>
					<input type="text" maxlength="255" name="title" placeholder="Ny hundkurs" required>
				</div>
				<div class="six columns">
					<label for="image-path">Bild:</label>
					<input type="file" maxlength="255" name="news-image" accept=".jpg">	
				</div>
			</div>
			<label for="news-text">Text:</label>
			<textarea name="news-text" id="" maxlength="255" placeholder="Ny hundkurs med nya möjligheter..." class="u-full-width"></textarea>
			<div class="row">
				<div class="four columns">
					<input type="submit" name="submit-news" value="Lägg till">		
				</div>
				<div class="eight columns">
				</div>
			</div>
		</form>
		<hr>
		<br/>
		<div class="row">
			<div class="twelce columns">
				<h3>Redigera befintliga nyheter</h3>
				<p>Välj en post i listan genom att klicka på den. Redigera den sedan i det nya formuläret som poppade upp.</p>

				<div class="row">
				<div class="twelve columns">
					<table class="u-full-width">
						<thead>
							<tr>
								<th>Nyhetstitel</th>
								<th>Datum</th>
								<th><center>Redigera nyhet</center></th>
							</tr>
						</thead>
						<tbody id="newsTable">
							<?php
							$result = query("SELECT Title, CreatedAt, NewsText, NewsImagePath FROM News ORDER BY CreatedAt DESC");
							if($result["err"] != null){
								$load_error = "Kunde inte ladda inlägg, prova att ladda om sidan.";
							}else{
								$newsData = $result['data'];

								if(count($newsData) > 0){
									foreach($newsData as $key => $n){
										echo '<tr id="news' . $key . '">';
										echo '<td class="titleTd">';
										echo $n['Title'];
										echo '</td>';
										echo '<td class="CreatedAtTd">';
										echo $n["CreatedAt"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $n["NewsText"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $n["NewsImagePath"];
										echo '</td>';
										echo '<td class="edit-news">';
										echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
										echo '</td>';
										echo '</tr>';
									}
								}else{
									$load_error = "Det finns inga nyheter publicerade.";
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
		</div>
		<br/>

		<form hidden enctype="multipart/form-data" action="" method="post">
		<div class="row">
			<div class="twelve columns">
				<h3>Redigera Nyhet</h3>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="edit-title">Nyhetstitel:</label>
				<input class="u-full-width" type="text" name="edit-title" id="edit-title-id" readonly>
			</div>
			<div class="six columns">
				<label for="edit-createdAt">Datum:</label>
				<input class="u-full-width" type="text" name="edit-createdAt" id="edit-createdAt" readonly>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="edit-old-image">Bild:</label>
				<img id="edit-old-image-id" src="" alt="" width="100" height="100">
			</div>
			<div class="six columns">
				<label for="edit-new-image">Välj ny bild:</label>
				<input class="u-full-width" max="255" name="edit-new-image" id="pictureFile" accept=".jpg" id="edit-new-image-id" type="file">
			</div>
		</div>
		<label for="edit-text">Text:</label>
		<textarea name="edit-text" max="255" class="u-full-width" id="edit-text-id"></textarea>	
		<input value="Uppdatera" name="news-update" type="submit">
		<input value="Radera" name="news-delete" type="submit">
		</form>

	</div>
<script src="js/admin-news.js"></script>
</main>

<?php
include_once 'footer.php';
?>