<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}

include_once 'admin-header.php';

$allowed_ext = array('jpg');

if(isset($_POST['submit_newsfeed'])){
	if(strlen($_POST['news_title']) > 0){

		$title = $_POST['news_title'];
		$news_text = $_POST['news_text'];
		$news_link = $_POST['news_link'];

		$file = $_FILES['news_image'];
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

				if(move_uploaded_file($file['tmp_name'], $file_destination)){
					$result = nonQuery("INSERT INTO NewsFeed (`NewsTitle`,`CreatedAt`,`Description`,`NewsImagePath`, `NewsLink`) VALUES (:title,now(),:news_text,:image,:news_link)", 
						array(
							":title" => $title, 
							":news_text" => $news_text, 
							":news_link" => $news_link, 
							":image" => $file_name_new
						)

					);
					
					if($result["err"] != null){
						$news_error = 'Gick inte att ladda upp din annons, prova igen.';
						unlink($file_destination);
					}else{
						$news_success = "Annons upplagd och publicerad!";
					}
				} else { $news_error = 'Gick inte att flytta filen till servern, prova igen'; }
			}else{ $news_error = $image_error; }
		}else{
			$result = nonQuery("INSERT INTO NewsFeed (`NewsTitle`,`CreatedAt`,`Description`, `NewsLink`) VALUES (:title,now(),:news_text,:news_link)", array(":title" => $title, ":news_text" => $news_text, ":news_link" => $news_link));
				
			if($result["err"] === null){
				$news_success = "Nyhet upplagd och publicerad!";
			}else{
				$news_error = "Gick inte att flytta filen till servern, prova igen";
			}
		}
	}
	else {
		$news_error = "Värden saknas.";
	}
}

if(isset($_POST['update_newsfeed'])){
	
	if(strlen($_POST['alter_news_title']) > 0 && strlen($_POST['alter_news_createdAt']) > 0){

		$news_title = $_POST['alter_news_title'];
		$news_text = $_POST['alter_news_text'];
		$news_link = $_POST['alter_news_link'];
		$news_createdAt = $_POST['alter_news_createdAt'];

		$file = $_FILES['alter_news_image'];
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
				
				$result = query("SELECT `NewsImagePath` FROM NewsFeed WHERE `NewsTitle` = :title AND `CreatedAt` = :createdAt", 
					array(
						":title" => $news_title, 
						":createdAt" => $news_createdAt
					)
				);

				if($result["err"] == null){
					$resData = $result["data"];
					if($resData[0]["NewsImagePath"] !== NULL){
						$old_image = "uploads/" . $resData[0]["NewsImagePath"];
					}
				}

				if(move_uploaded_file($file['tmp_name'], $file_destination)){
					$result = nonQuery("UPDATE NewsFeed SET `Description` = :description, `NewsImagePath` = :image_path, `NewsLink` = :newslink WHERE `NewsTitle` = :title AND `CreatedAt` = :createdAt", 
						array(
							":title" => $news_title, 
							":createdAt" => $news_createdAt, 
							":description" => $news_text, 
							":image_path" => $file_name_new, 
							":newslink" => $news_link
						)
					);
							
					
					if($result["err"] != null){
						$news_error = 'Gick inte att uppdatera annons, prova igen.';
						unlink($file_destination);
					}else{
						$news_success = "Annons uppdaterad och publicerad!";
						if(isset($old_image)){
							if(file_exists($old_image)){
						    	unlink($old_image);
							}
						}
					}
				} else { $news_error = 'Gick inte att flytta filen till servern, prova igen'; }
			}else{ $news_error = $image_error; }
		}else{
	
			$result = nonQuery("UPDATE NewsFeed SET `Description` = :description, `NewsLink` = :newslink WHERE `NewsTitle` = :title AND `CreatedAt` = :createdAt", 
				array(
					":title" => $news_title, 
					":createdAt" => $news_createdAt, 
					":description" => $news_text, 
					":newslink" => $news_link
				)
			);
				
			if($result["err"] === null){
				$news_success = "Annons uppdaterad"; 
			}else{
				$news_error = "Kunde inte uppdatera annons, prova igen.";
			}
		}
	}
}

if(isset($_POST['delete_newsfeed'])){

	if(strlen($_POST['alter_news_title']) > 0 && strlen($_POST['alter_news_createdAt']) > 0){

		$alter_title = $_POST['alter_news_title'];
		$alter_createdAt = $_POST['alter_news_createdAt'];

		$result = query("SELECT `NewsImagePath` FROM NewsFeed WHERE `NewsTitle` = :title AND `CreatedAt` = :createdAt", 
			array(
				":title" => $alter_title, 
				":createdAt" => $alter_createdAt
			)
		);

		if($result["err"] == null){
			$resData = $result["data"];
			if($resData[0]["NewsImagePath"] !== NULL){
				$old_image = "uploads/" . $resData[0]["NewsImagePath"];
			}
		}

		$result = nonQuery("DELETE FROM NewsFeed WHERE `NewsTitle` = :title AND `CreatedAt` = :createdAt", array(":title" => $alter_title, ":createdAt" => $alter_createdAt));
		
		if($result["err"] === NULL){
			$news_success = "Annons raderad!"; 
			if(isset($old_image)){
				if(file_exists($old_image)){
			    	unlink($old_image);
				}
			}
		}else{
			$news_error = "Kunde inte tabort annons, prova igen.";
		}
	}else{ $news_error = "Kunde inte tabort annons, saknar värden."; }
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
					<h5>Lägg till annonser i sidan av hemsidan.</h5>
					<p>Lägg till annonser i det översta formuläret. För att redigera/radera befintliga nyheter, välj nyhet i tabellen nedan genom att klicka på den och redigera/radera den via det nya formuläret som poppar upp.</p>
				</div>
			</div>
			<hr>

		<form enctype="multipart/form-data" action="" method="post">
			<h1>Lägg till i sidmenyn</h1>
			<div class="row">
				<div class="six columns">
					<label for="news_title">Titel:</label>
					<input type="text" class="u-full-width" placeholder="Ny hundkurs"  name="news_title" maxlength="255" required>
				</div>
				<div class="six columns">
					<label for="news_link">Länk:</label>
					<input type="text" class="u-full-width" placeholder="http://icsweb.se/u5/school.php"  name="news_link" maxlength="255">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="news_image">Infoga bild:</label>
					<input type="file" name="news_image" maxlength="255" accept=".jpg">
				</div>
				<div class="six columns">
					
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<label for="news_text">Beskrivning:</label>
					<textarea name="news_text" placeholder="Nya hundkurser för äldre hundar..." maxlength="255" class="u-full-width"></textarea>
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<input value="Lägg till" name="submit_newsfeed" type="submit">
				</div>
				<div class="six columns">

				</div>
			</div>
		</form>
		<hr>
		<div class="row">
			<div class="twelve columns">
				<h3>Välj annons att redigera</h3>
				<div class="row">
					<div class="twelve columns">
						<table class="u-full-width">
							<thead>
								<tr>
									<th>Titel</th>
									<th>Datum</th>
									<th>Bild</th>
									<th><center>Redigera annons</center></th>
								</tr>
							</thead>
							<tbody id="NewsFeedTable">
								<?php

								$result = query("SELECT NewsTitle, CreatedAt, Description, NewsImagePath, NewsLink FROM NewsFeed ORDER BY CreatedAt DESC");
								
								if($result["err"] != null){
									$load_error = "Kunde inte ladda annonser, prova att ladda om sidan.";
								}else{
									$newsFeedData = $result['data'];

									if(count($newsFeedData) > 0){

										foreach($newsFeedData as $key => $n){
											echo '<tr id="newsfeed' . $key . '">';
											echo '<td class="newsTitleTd">';
											echo $n['NewsTitle'];
											echo '</td>';
											echo '<td class="dateTimeTd">';
											echo $n["CreatedAt"];
											echo '</td>';
											echo '<td class="hide-td">';
											echo $n["Description"];
											echo '</td>';
											echo '<td class="hide-td">';
											echo $n["NewsLink"];
											echo '</td>';
											if($n['NewsImagePath'] !== null){
												echo '<td class="newsImagePath">';
												echo '<img src="uploads/'.$n['NewsImagePath'].'" width="75" height="75" alt="">';
												echo '</td>';
											}else {
												echo '<td class="newsImagePath">';
												echo 'Ingen bild';
												echo '</td>';
											}
											echo '<td class="edit-sidebar">';
											echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
											echo '</td>';
											echo '</tr>';
										}
									}else{
										$load_error = "Det finns inga annonser.";
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
		<form hidden enctype="multipart/form-data" action="" method="post">
			<div class="row">
				<div class="four columns">
					<label for="alter_news_title">Titel:</label>
					<input type="text" class="u-full-width" id="newsFeedTitle" maxlength="255" name="alter_news_title" readonly>
				</div>
				<div class="four columns">
					<label for="alter_news_createdAt">Datum:</label>
					<input type="text" class="u-full-width" id="newsFeedCreatedAt" maxlength="255" name="alter_news_createdAt" readonly>
				</div>
				<div class="four columns">
					<label for="alter_news_link">Länk:</label>
					<input type="text" class="u-full-width" id="newsFeedLink" maxlength="255" name="alter_news_link">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="alter_news_image">Infoga bild:</label>
					<input type="file" name="alter_news_image" id="newsFeedImagePath" maxlength="255" accept=".jpg">
				</div>
				<div class="six columns"></div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<label for="alter_news_text">Beskrivning:</label>
					<textarea name="alter_news_text" id="newsFeedDescription" maxlength="255" class="u-full-width"></textarea>
				</div>
			</div>
					<input value="Uppdatera" name="update_newsfeed" type="submit">
					<input value="Radera" name="delete_newsfeed" type="submit">
				
		</form>
	</div>
<script src="js/admin-sidebar.js"></script>
</main>
<?php
include_once 'footer.php';
?>