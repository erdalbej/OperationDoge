<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}
include_once 'admin-header.php';

$thread_title = NULL;
$thread_createdAt = NULL;

$allowed_ext = array('jpg');

if(isset($_GET['title']) && isset($_GET['createdAt'])){
	$thread_title = $_GET['title'];
	$thread_createdAt = $_GET['createdAt'];
}

//Post update
if(isset($_POST['post-update'])){
	
	if(strlen($_POST['username'])){

		$username = $_POST['username'];
		$createdAt = $_POST['createdAt'];
		$post_text = $_POST['postText'];

		$file = $_FILES['newImage'];
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

				$result = query("SELECT `PostImagePath` FROM Post WHERE `Username` = :username AND `CreatedAt` = :createdAt", array(":username" => $username, ":createdAt" => $createdAt));
				if($result["err"] == null){
					$resData = $result["data"];
					if($resData[0]["PostImagePath"] !== NULL){
						$old_image = "uploads/" . $resData[0]["PostImagePath"];
					}
				}

				if(move_uploaded_file($file_tmp, $file_destination)){
					$result = nonQuery("UPDATE Post SET `PostText` = :post_text, `PostImagePath` = :image_path WHERE `Username` = :username AND `CreatedAt` = :createdAt", array(":username" => $username, ":createdAt" => $createdAt, ":post_text" => $post_text, ":image_path" => $file_name_new));
					
					if($result["err"] != null){
						$post_error = 'Gick inte att spara ditt inlägg, prova igen!';
						unlink($file_destination);
					}else{
						$post_success = 'Post uppdaterad!';
						if(isset($old_image)){
							if(file_exists($old_image)){
						    	unlink($old_image);
							}
						}
					}	
				}else{ $post_error = 'Gick inte att flytta filen till servern, prova igen'; }			
			}else{ $post_error = $image_error; }
		}else{
			$result = nonQuery("UPDATE Post SET `PostText` = :post_text WHERE `Username` = :username AND `CreatedAt` = :createdAt", array(":username" => $username, ":createdAt" => $createdAt, ":post_text" => $post_text));
			
			if($result["err"] != null){
				$post_error = "Kunde inte uppdatera post, prova igen.";
			}else{
				$post_success = "Post uppdaterad"; 
			}
		}
	}
}

//Post delete
if(isset($_POST['post-delete'])){

	if(strlen($_POST['username']) > 0 && strlen($_POST['createdAt']) > 0){

		$username = $_POST['username'];
		$createdAt = $_POST['createdAt'];

		$result = query("SELECT `PostImagePath` FROM Post WHERE `Username` = :username AND `CreatedAt` = :createdAt", array(":username" => $username, ":createdAt" => $createdAt));
		if($result["err"] == null){
			$resData = $result["data"];
			if($resData[0]["PostImagePath"] !== NULL){
				$old_image = "uploads/" . $resData[0]["PostImagePath"];
			}
		}

		$result = nonQuery("DELETE FROM Post WHERE `Username` = :username AND `CreatedAt` = :createdAt", array(":username" => $username, ":createdAt" => $createdAt));

		if($result["err"] === NULL){
			$post_success = "Post raderad."; 
			if(isset($old_image)){
				if(file_exists($old_image)){
			    	unlink($old_image);
				}
			}
		}else{
			$post_error = "Kunde inte tabort post, prova igen.";
		}
	}else{ $post_error = "Kunde inte tabort post, saknar värden."; }
}
?>

<main>
	<div class="container">
	<div class="row">
			<div class="twelve columns">
				<?php
					if(isset($post_success)){
						echo '<span id="returnMsg" class="success-message">' . $post_success . '</span>';
					}
					if(isset($post_error)){
						echo '<span id="returnMsg" class="error-message">' . $post_error . '</span>';
					}
				?>
			</div>
		</div>
	<div class="row">
		<div class="twelve columns">
			<h5>Gästbok - Redigera poster</h5>
			<p>För att redigera ett inlägg, klicka på kommentaren i listan för att få upp kommentarinformation. </p>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
			<h3>Aktuell tråd: <strong> <?php echo $thread_title ?> </strong></h3>
		</div>
	</div>
	<hr>
		<div class="row">
			<div class="eight columns">
				<h3>Välj post att redigera</h3>
			</div>
			<div class="four columns">
			</div>
				<div class="row">
				<div class="twelve columns">
					<table class="u-full-width">
						<thead>
							<tr>
								<th>Användare</th>
								<th>Datum</th>
								<th><center>Redigera inlägg</center></th>
							</tr>
						</thead>
						<tbody>
							<?php

							$result = query("SELECT Username, CreatedAt, PostText, PostImagePath FROM Post WHERE Thread_Title = :thread_title AND Thread_CreatedAt = :thread_createdAt ORDER BY CreatedAt DESC", array(":thread_title" => $thread_title, ":thread_createdAt" => $thread_createdAt));

							if($result["err"] != null){
								$load_error = "Kunde inte ladda inlägg, prova att ladda om sidan.";
							}else{
								$postData = $result['data'];

								if(count($postData) > 0){

									foreach($postData as $key => $p){
										echo '<tr id="news' . $key . '">';
										echo '<td class="usernameTd">';
										echo $p['Username'];
										echo '</td>';
										echo '<td>';
										echo $p["CreatedAt"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p["PostText"];
										echo '</td>';
										echo '<td class="hide-td">';
										echo $p['PostImagePath'];
										echo '</td>';
										echo '<td class="edit-post">';
										echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
										echo '</td>';
										echo '</tr>';
										
									}
								}else{
									$load_error = "Det finns inga inlägg i denna tråd.";
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
			<form hidden enctype="multipart/form-data" action="" method="post">
			<div class="row">
				<div class="twelve columns">
					<h3>Redigera post</h3>
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="postUsername">Användare:</label>
					<input class="u-full-width" type="text" name="username" id="postUsername" readonly>
				</div>
				<div class="six columns">
					<label for="createdAt">Datum:</label>
					<input class="u-full-width" type="text" name="createdAt" id="postcreatedAt" readonly>
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="postImage">Bild:</label>
					<img id="postImage" src="" alt="" width="100" height="100" maxlength="255">
				</div>
				<div class="six columns">
					<label for="newImage">Välj ny bild:</label>
					<input class="u-full-width" name="newImage" id="pictureFile" accept=".jpg" id="newImage" type="file" maxlength="255">
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<label for="postText">Post text:</label>
						<textarea name="postText" class="u-full-width" id="postText" maxlength="255"></textarea>
				</div>
			</div>
			<input value="Uppdatera" name="post-update" type="submit">
			<input value="Radera" name="post-delete" type="submit">
		</form>
	</div>
<script src="js/admin-post.js"></script>
</main>

<?php
include_once 'footer.php';
?>