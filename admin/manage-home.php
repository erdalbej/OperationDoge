<?php
include_once 'admin-header.php';

//Kollar om submit-news knappen är tryckt
if(isset($_POST['submit-news'])){
	//Kollar så det finns några värden i title dvs längd är större än 0
	if(strlen($_POST['title']) > 0){

		//Skapar softa variablar och lägger deras värden i dessa
		$title = $_POST['title'];
		$news_text = $_POST['news-text'];
		//DateTime som är en del av PK fixar vi med MySQL via now() i queryn

		//om image är selected..
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

						//Ger filnamnet ett unikt namn så det inte krockar om man lägger upp samma bild flera gånger.
						$file_name_new = uniqid('', true) . '.' . $file_ext;
						$file_destination = '../uploads/' . $file_name_new;

						if(move_uploaded_file($file_tmp, $file_destination)){
							$result = nonQuery("INSERT INTO News (`Title`,`DateTime`,`NewsText`,`NewsImagePath`) VALUES (:title,now(),:news_text,:image)", array(":title" => $title, ":news_text" => $news_text, ":image" => $file_name_new));
							
							if($result["err"] === null){
								//Inlagd med i news med bild
							}
						}
					}
				}
			}else{
				//Ingen bild selectead
				$result = nonQuery("INSERT INTO News (`Title`,`DateTime`,`NewsText`) VALUES (:title,now(),:news_text)", array(":title" => $title, ":news_text" => $news_text));
				
				if($result["err"] === null){
					//Inlagd utan bild
				}
			}
		}

	}

}



?>
<main>
	<div class="row">
		<div class="six columns">
		</div>
		<div class="six columns">
			<h3>Lägg till nyheter till startsidan</h3>
		</div>
	</div>
	<div class="row">
		<div class="six columns">
			
		</div>
		<div class="six columns">
			<form enctype="multipart/form-data" method="POST" action="" class="news-form">
				<label for="title">Titel:</label>
				<input type="text" name="title">
				<br>

				<label for="news-text">Text:</label>
				<textarea name="news-text" id="" cols="30" rows="10"></textarea>
				
				<br>

				<label for="image-path">Bild:</label>
				<input type="file" name="news-image" accept=".jpg">
				<br>
				
				<input type="submit" name="submit-news" class="button-primary" value="Lägg till nyheten">
			</form>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="six columns">
			
		</div>
		<div class="six columns">
			<h3>Redigera befintliga nyheter</h3>
			<p>redigera information genom tabell och modal likt tidigre?</p>
		</div>
	</div>

</main>

<?php
include_once '../footer.php';
?>