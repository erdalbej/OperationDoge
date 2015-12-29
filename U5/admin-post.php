<?php
include_once 'admin-header.php';

$thread_title = NULL;
$thread_createdAt = NULL;

if(isset($_GET['title']) && isset($_GET['createdAt'])){
	$thread_title = $_GET['title'];
	$thread_createdAt = $_GET['createdAt'];
}

//Post update
if(isset($_POST['post-update'])){

	$returnMsgPostUpdate = "Kunde inte uppdatera post.";

	
	if(strlen($_POST['username']) > 0 && strlen($_POST['datetime']) > 0){

		$username = $_POST['username'];
		$datetime = $_POST['datetime'];
		$post_text = $_POST['postText'];


		//if image is set
		if(isset($_FILES['newImage'])) {

			$file = $_FILES['newImage'];

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
						$file_destination = 'uploads/' . $file_name_new;

						if(move_uploaded_file($file_tmp, $file_destination)){

							//Deletes old picture
							$result = query("SELECT `PostImagePath` FROM Post WHERE `Username` = :username AND `DateTime` = :datetime", array(":username" => $username, ":datetime" => $datetime));
							$resData = $result["data"];

							if($resData[0]["PostImagePath"] !== NULL){
								$image_path = "uploads/" . $resData[0]["PostImagePath"];
								if(file_exists($image_path)){
						        	unlink($image_path);
						    	}
							}

							$result = nonQuery("UPDATE Post SET `PostText` = :post_text, `PostImagePath` = :image_path WHERE `Username` = :username AND `DateTime` = :datetime", array(":username" => $username, ":datetime" => $datetime, ":post_text" => $post_text, ":image_path" => $file_name_new));
							
							if($result["err"] === null){
								$returnMsgPostUpdate = "Post uppdaterad"; 
							}else{
								$returnMsgPostUpdate = "Kunde inte uppdatera post.";
							}
						}
					}
				}
			}else{
	
				$result = nonQuery("UPDATE Post SET `PostText` = :post_text WHERE `Username` = :username AND `DateTime` = :datetime", array(":username" => $username, ":datetime" => $datetime, ":post_text" => $post_text));
				
				if($result["err"] === null){
					$returnMsgPostUpdate = "Post uppdaterad"; 
				}else{
					$returnMsgPostUpdate = "Kunde inte uppdatera post.";
				}
			}
		}
	}
	
}

//Post delete
if(isset($_POST['post-delete'])){

	$returnMsgPostDelete = "Kunde inte tabort post.";

	if(strlen($_POST['username']) > 0 && strlen($_POST['datetime']) > 0){

		$username = $_POST['username'];
		$datetime = $_POST['datetime'];

		//Deletes old picture
		$result = query("SELECT `PostImagePath` FROM Post WHERE `Username` = :username AND `DateTime` = :datetime", array(":username" => $username, ":datetime" => $datetime));
		$resData = $result["data"];

		if($resData[0]["PostImagePath"] !== NULL){
			$image_path = "uploads/" . $resData[0]["PostImagePath"];
			if(file_exists($image_path)){
	        	unlink($image_path);
	    	}
		}

		$result = nonQuery("DELETE FROM Post WHERE `Username` = :username AND `DateTime` = :datetime", array(":username" => $username, ":datetime" => $datetime));
		$result["data"];

		if($result["err"] === NULL){

			$returnMsgPostDelete = "Tråd raderad."; 

		}else{
			$returnMsgPostDelete = "Kunde inte tabort tråd.";
		}
	}
}
?>

<main>
	<div class="container">
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

							$divOne = query("SELECT Username, CreatedAt, PostText, PostImagePath FROM Post WHERE Thread_Title = :thread_title AND Thread_CreatedAt = :thread_datetime", array(":thread_title" => $thread_title, ":thread_datetime" => $thread_createdAt));

							if($divOne["err"] != null){
								$load_error = "Kunde inte ladda trådar, prova att ladda om sidan.";
							}else{
								$divData = $divOne['data'];

								if(count($divData) > 0){

									foreach($divData as $key => $row){
										echo '<tr id="news' . $key . '">';
										echo '<td class="usernameTd">';
										echo $row['Username'];
										echo '</td>';
										echo '<td class="dateTimeTd">';
										echo $row["CreatedAt"];
										echo '</td>';
										echo '<td class="postTextTd" style = "display:none">';
										echo $row["PostText"];
										echo '</td>';
										echo '<td class="postImagePath" style = "display:none">';
										echo $row['PostImagePath'];
										echo '</td>';
										echo '<td class="edit-post">';
										echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
										echo '</td>';
										echo '</tr>';
										
									}
								}
							}
							?>				
						</tbody>
					</table>
					<br/>
				</div>
			</div>
			<div id="returnMsg" style="margin-bottom:20px;">
			<?php
				if(isset($returnMsgPostUpdate)){
					echo $returnMsgPostUpdate;
				}
				if(isset($returnMsgPostDelete)){
					echo $returnMsgPostDelete;
				}
			?>
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
					<label for="postDateTime">Datum:</label>
					<input class="u-full-width" type="text" name="datetime" id="postDateTime" readonly>
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="postImage">Bild:</label>
					<img id="postImage" src="" alt="" width="100" height="100">
				</div>
				<div class="six columns">
					<label for="newImage">Välj ny bild:</label>
					<input class="u-full-width" name="newImage" id="pictureFile" accept=".jpg" id="newImage" type="file">
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<label for="postText">Post text:</label>
						<textarea name="postText" class="u-full-width" id="postText"></textarea>
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