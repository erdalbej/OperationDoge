<?php
include_once 'admin-header.php';

//News create
if(isset($_POST['submit-news'])){

	$submit_news_msg = "Kunde inte lägga till nyhet.";

	if(strlen($_POST['title']) > 0){

		$title = $_POST['title'];
		$news_text = $_POST['news-text'];

		if(isset($_FILES['news-image'])) {
			$file = $_FILES['news-image'];

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
							$result = nonQuery("INSERT INTO News (`Title`,`CreatedAt`,`NewsText`,`NewsImagePath`) VALUES (:title,now(),:news_text,:image)", array(":title" => $title, ":news_text" => $news_text, ":image" => $file_name_new));
							
							if($result["err"] === null){
								$submit_news_msg = "Nyhet tillagd.";
							}else{
								$submit_news_msg = "Kunde inte lägga till nyhet.";
							}
						}
					}
				}
			}else{

				$result = nonQuery("INSERT INTO News (`Title`,`CreatedAt`,`NewsText`) VALUES (:title,now(),:news_text)", array(":title" => $title, ":news_text" => $news_text));
				
				if($result["err"] === null){
					$submit_news_msg = "Nyhet tillagd.";
				}else{
					$submit_news_msg = "Kunde inte lägga till nyhet.";
				}
			}
		}

	}

}

//News update
if(isset($_POST['news-update'])){

	$update_news_msg = "Kunde inte uppdatera nyhet.";

	
	if(strlen($_POST['edit-title']) > 0 && strlen($_POST['edit-date']) > 0){

		$news_title = $_POST['edit-title'];
		$news_createdAt = $_POST['edit-date'];
		$news_text = $_POST['edit-text'];


		//if image is set
		if(isset($_FILES['edit-new-image'])) {

			$file = $_FILES['edit-new-image'];

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
							$result = query("SELECT `NewsImagePath` FROM News WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt));
							$resData = $result["data"];

							if($resData[0]["NewsImagePath"] !== NULL){
								$image_path = "uploads/" . $resData[0]["NewsImagePath"];
								if(file_exists($image_path)){
						        	unlink($image_path);
						    	}
							}

							$result = nonQuery("UPDATE News SET `NewsText` = :news_text, `NewsImagePath` = :image_path WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt, ":news_text" => $news_text, ":image_path" => $file_name_new));
							
							if($result["err"] === null){
								$update_news_msg = "Nyhet uppdaterad"; 
							}else{
								$update_news_msg = "Kunde inte uppdatera nyhet.";
							}
						}
					}
				}
			}else{
	
				$result = nonQuery("UPDATE News SET `NewsText` = :news_text WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt, ":news_text" => $news_text));
				
				if($result["err"] === null){
					$update_news_msg = "Nyhet uppdaterad"; 
				}else{
					$update_news_msg = "Kunde inte uppdatera nyhet.";
				}
			}
		}
	}
	
}

//News delete
if(isset($_POST['news-delete'])){

	$delete_news_msg = "Kunde inte tabort nyhet.";

	if(strlen($_POST['edit-title']) > 0 && strlen($_POST['edit-date']) > 0){

		$news_title = $_POST['edit-title'];
		$news_createdAt = $_POST['edit-date'];

		//Deletes old picture
		$result = query("SELECT `NewsImagePath` FROM News WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt));
		$resData = $result["data"];

		if($resData[0]["NewsImagePath"] !== NULL){
			$image_path = "uploads/" . $resData[0]["NewsImagePath"];
			if(file_exists($image_path)){
	        	unlink($image_path);
	    	}
		}

		$result = nonQuery("DELETE FROM News WHERE `Title` = :news_title AND `CreatedAt` = :news_createdAt", array(":news_title" => $news_title, ":news_createdAt" => $news_createdAt));
		$result["data"];

		if($result["err"] === NULL){

			$delete_news_msg = "Nyhet borttagen."; 

		}else{
			$delete_news_msg = "Kunde inte tabort nyhet.";
		}
	}
}


?>
<main>
	<div class="container">

		<div class="row">
			<div class="twelve columns">
				<h5>Lägg till nyheter till startsidan</h5>
				<p>Lägg till nyheter i det översta formuläret. För att redigera/radera befintliga nyheter, välj nyhet i tabellen nedan genom att klicka på den och redigera/radera den via det nya formuläret som poppar upp.</p>
			</div>
		</div>
		<hr>
		<div id="returnMsg" style="margin:20px 0 50px 0;">
			<?php
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
		<form enctype="multipart/form-data" method="POST" action="" class="news-form">
			<div class="row">
				<div class="twelve columns">
					<h3>Lägg till nyhet</h3>
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Nyhetstitel:</label>
					<input type="text" name="title">
				</div>
				<div class="six columns">
					<label for="image-path">Bild:</label>
					<input type="file" name="news-image" accept=".jpg">	
				</div>
			</div>
			<label for="news-text">Text:</label>
			<textarea name="news-text" id="" class="u-full-width"></textarea>
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
							</tr>
						</thead>
						<tbody id="newsTable">
							<?php
							$result = query("SELECT Title, CreatedAt, NewsText, NewsImagePath FROM News");
							$newsData = $result['data'];
							foreach($newsData as $key => $n){
								echo '<tr id="news' . $key . '">';
								echo '<td class="titleTd">';
								echo $n['Title'];
								echo '</td>';
								echo '<td class="CreatedAtTd">';
								echo $n["CreatedAt"];
								echo '</td>';
								echo '<td class="newsTextTd" style = "display:none">';
								echo $n["NewsText"];
								echo '</td>';
								echo '<td class="newsImagePathTd" style = "display:none">';
								echo $n["NewsImagePath"];
								echo '</td>';
								echo '</tr>';
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
				<h3>Redigera Nyhet</h3>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="edit-title">Nyhetstitel:</label>
				<input class="u-full-width" type="text" name="edit-title" id="edit-title-id" readonly>
			</div>
			<div class="six columns">
				<label for="edit-date">Datum:</label>
				<input class="u-full-width" type="text" name="edit-date" id="edit-date-id" readonly>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="edit-old-image">Bild:</label>
				<img id="edit-old-image-id" src="" alt="" width="100" height="100">
			</div>
			<div class="six columns">
				<label for="edit-new-image">Välj ny bild:</label>
				<input class="u-full-width" name="edit-new-image" id="pictureFile" accept=".jpg" id="edit-new-image-id" type="file">
			</div>
		</div>
		<label for="edit-text">Text:</label>
		<textarea name="edit-text" class="u-full-width" id="edit-text-id"></textarea>	
		<input value="Uppdatera" name="news-update" type="submit">
		<input value="Radera" name="news-delete" type="submit">
		</form>

	</div>
<script src="js/admin-news.js"></script>
</main>

<?php
include_once 'footer.php';
?>