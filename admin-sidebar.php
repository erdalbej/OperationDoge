<?php
include_once 'admin-header.php';

// Create
if(isset($_POST['submit_newsfeed'])){
	if(strlen($_POST['news_title']) > 0){
		$returnMsgNewsFeedAdd = "Kunde inte lägga till ditt objekt!";

		$title = $_POST['news_title'];
		$news_text = $_POST['news_text'];
		$news_link = $_POST['news_link'];

		if(isset($_FILES['news_image'])) {
			$file = $_FILES['news_image'];

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

						//Ger filnamnet ett unikt namn så det inte krockar om man lägger upp samma bild flera gånger.
						$file_name_new = uniqid('', true) . '.' . $file_ext;
						$file_destination = 'uploads/' . $file_name_new;

						if(move_uploaded_file($file_tmp, $file_destination)){
							$result = nonQuery("INSERT INTO NewsFeed (`NewsTitle`,`DateTime`,`Description`,`NewsImagePath`, `NewsLink`) VALUES (:title,now(),:news_text,:image, :news_link)", array(":title" => $title, ":news_text" => $news_text, ":news_link" => $news_link, ":image" => $file_name_new));
							
							if($result["err"] === null){
								$returnMsgNewsFeedAdd = "Ditt objekt är tillagt!"; 
							}
						}
					}
				}
			}else{
				//Ingen bild selectead
				$result = nonQuery("INSERT INTO NewsFeed (`NewsTitle`,`DateTime`,`Description`, `NewsLink`) VALUES (:title,now(),:news_text,:news_link)", array(":title" => $title, ":news_text" => $news_text, ":news_link" => $news_link));
				
				if($result["err"] === null){
					$returnMsgNewsFeedAdd = "Ditt objekt är tillagt!"; 
				}
			}
		}

	}

}

if(isset($_POST['update_newsfeed'])){

	$returnMsgNewsFeedUpdate = "Kunde inte uppdatera objekt.";

	
	if(strlen($_POST['alter_news_title']) > 0 && strlen($_POST['alter_news_datetime']) > 0){

		$title = $_POST['alter_news_title'];
		$news_text = $_POST['alter_news_text'];
		$news_link = $_POST['alter_news_link'];
		$news_datetime = $_POST['alter_news_datetime'];


		//if image is set
		if(isset($_FILES['alter_news_image'])) {

			$file = $_FILES['alter_news_image'];

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
							$result = query("SELECT `NewsImagePath` FROM NewsFeed WHERE `NewsTitle` = :title AND `DateTime` = :datetime", array(":title" => $title, ":datetime" => $datetime));
							$resData = $result["data"];

							if($resData[0]["NewsImagePath"] !== NULL){
								$image_path = "uploads/" . $resData[0]["NewsImagePath"];
								if(file_exists($image_path)){
						        	unlink($image_path);
						    	}
							}

							$result = nonQuery("UPDATE NewsFeed SET `Description` = :description, `NewsImagePath` = :image_path, `NewsLink` = :newslink WHERE `NewsTitle` = :title AND `DateTime` = :datetime", array(":title" => $title, ":datetime" => $datetime, ":description" => $news_text, ":image_path" => $file_name_new, ":newslink" => $news_link));
							
							if($result["err"] === null){
								$returnMsgNewsFeedUpdate = "Objekt uppdaterat"; 
							}else{
								$returnMsgNewsFeedUpdate = "Kunde inte uppdatera objekt.";
							}
						}
					}
				}
			}else{
	
				$result = nonQuery("UPDATE NewsFeed SET `Description` = :description, `NewsLink` = :newslink WHERE `NewsTitle` = :title AND `DateTime` = :datetime", array(":title" => $title, ":datetime" => $datetime, ":description" => $news_text, ":newslink" => $news_link));
				
				if($result["err"] === null){
					$returnMsgNewsFeedUpdate = "Objekt uppdaterat"; 
				}else{
					$returnMsgNewsFeedUpdate = "Kunde inte uppdatera objekt.";
				}
			}
		}
	}
	
}

//Delete 
if(isset($_POST['delete_newsfeed'])){
	$returnMsgNewsFeedDelete = "Kunde inte ta bort objekt.";

	if(strlen($_POST['alter_news_title']) > 0 && strlen($_POST['alter_news_datetime']) > 0){

		$alter_title = $_POST['alter_news_title'];
		$alter_datetime = $_POST['alter_news_datetime'];

		$result = nonQuery("DELETE FROM NewsFeed WHERE `NewsTitle` = :title AND `DateTime` = :datetime", array(":title" => $alter_title, ":datetime" => $alter_datetime));
		$result["data"];

		if($result["err"] === NULL){
			$returnMsgNewsFeedDelete = "Objekt raderat."; 
		}else{
			$returnMsgNewsFeedDelete = "Kunde inte ta bort objekt.";
		}
	}
}
?>

<main>
	<form enctype="multipart/form-data" action="" method="post">
		<h1>Lägg till i sidmenyn</h1>
		<div class="row">
			<div class="three columns">
				<label for="news_title">Titel:</label>
				<input type="text" class="u-full-width"  name="news_title">
			</div>
			<div class="three columns">
				<label for="news_link">Länk:</label>
				<input type="text" class="u-full-width"  name="news_link">
			</div>
			<div class="three columns">
				
			</div>
			<div class="three columns"></div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="news_image">Infoga bild:</label>
				<input type="file" name="news_image" accept=".jpg">
			</div>
			<div class="six columns">
				
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="news_text">Beskrivning:</label>
				<textarea name="news_text" class="u-full-width"></textarea>
			</div>
			<div class="six columns"></div>
		</div>
		<div class="row">
			<div class="six columns">
				<input value="Lägg till" name="submit_newsfeed" type="submit">
			</div>
			<div class="six columns">

			</div>
		</div>

	</form>
	<div style="margin-bottom:30px;">
		<?php
		if(isset($returnMsgNewsFeedAdd)){
			echo $returnMsgNewsFeedAdd;
		}
		?>
	</div>


	<hr>


	<h1>Uppdatera / ta bort i sidmenyn</h1>
	<form enctype="multipart/form-data" action="" method="post">
		<div class="row">
			<div class="three columns">
				<label for="alter_news_title">Titel:</label>
				<input type="text" class="u-full-width" id="newsFeedTitle"  name="alter_news_title" readonly>
			</div>
			<div class="three columns">
				<label for="alter_news_link">Länk:</label>
				<input type="text" class="u-full-width" id="newsFeedLink" name="alter_news_link">
			</div>
			<div class="three columns">
				<label for="alter_news_datetime">Datum:</label>
				<input type="text" class="u-full-width" id="newsFeedDateTime" name="alter_news_datetime" readonly>
			</div>
			<div class="three columns"></div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="alter_news_image">Infoga bild:</label>
				<input type="file" name="alter_news_image" id="newsFeedImagePath" accept=".jpg">
			</div>
			<div class="six columns"></div>
		</div>
		<div class="row">
			<div class="nine columns">
				<label for="alter_news_text">Beskrivning:</label>
				<textarea name="alter_news_text" id="newsFeedDescription" class="u-full-width"></textarea>
			</div>
			<div class="three columns"></div>
		</div>
		<div class="row">
			<div class="six columns">
				<input value="Uppdatera" name="update_newsfeed" type="submit">
				<input value="Radera" name="delete_newsfeed" type="submit">
			</div>
			<div class="six columns">
				
			</div>
		</div>
	</form>
	<div style="margin-bottom:30px;">
		<?php
		if(isset($returnMsgNewsFeedDelete)){
			echo $returnMsgNewsFeedDelete;
		}
		if(isset($returnMsgNewsFeedUpdate)){
			echo $returnMsgNewsFeedUpdate;
		}
		?>
	</div>
	<div class="row">
		<div class="eight columns">
			<h3>Välj objekt att redigera</h3>
			<div class="row">
				<div class="twelve columns">
					<table class="u-full-width">
						<thead>
							<tr>
								<th>Titel</th>
								<th>Datum</th>
								<th>Beskrivning</th>
								<th>Länk:</th>
								<th>Bild:</th>
							</tr>
						</thead>
						<tbody id="NewsFeedTable">
							<?php
							$divOne = query("SELECT NewsTitle, DateTime, Description, NewsImagePath, NewsLink FROM NewsFeed");
							$divData = $divOne['data'];
							foreach($divData as $key => $row){
								echo '<tr id="newsfeed' . $key . '">';
								echo '<td class="newsTitleTd">';
								echo $row['NewsTitle'];
								echo '</td>';
								echo '<td class="dateTimeTd">';
								echo $row["DateTime"];
								echo '</td>';
								echo '<td class="Description">';
								echo $row["Description"];
								echo '</td>';
								echo '<td class="newsLink">';
								echo $row["NewsLink"];
								echo '</td>';
								if($row['NewsImagePath'] !== null){
									echo '<td class="newsImagePath">';
									echo '<img src="uploads/'.$row['NewsImagePath'].'" width="75" height="75" alt="">';
									echo '</td>';
								}
								else {
									echo '<td class="newsImagePath">';
									echo 'Ingen bild';
									echo '</td>';
								}
								echo '</tr>';
							}			
							?>				
						</tbody>
					</table>
				</div>
			</div>

		</div>

		<div class="four columns">

		</div>
	</div>
<script src="js/newsfeed.js"></script>
</main>
<?php
include_once 'footer.php';
?>