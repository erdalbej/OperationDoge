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
						$file_destination = 'uploads/' . $file_name_new;

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
	<div class="container">

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
				<p>redigera information genom tabell och modal likt tidigre?</p>

				<div class="row">
				<div class="twelve columns">
					<table class="u-full-width">
						<thead>
							<tr>
								<th>Nyhetstitel</th>
								<th>Datum</th>
								<th>Text</th>
								<th>Bild</th>
							</tr>
						</thead>
						<tbody id="newsTable">
							<?php
							$divOne = query("SELECT Title, DateTime, NewsText, NewsImagePath FROM News");
							$divData = $divOne['data'];
							foreach($divData as $key => $row){
								echo '<tr id="news' . $key . '">';
								echo '<td class="titleTd">';
								echo $row['Title'];
								echo '</td>';
								echo '<td class="dateTimeTd">';
								echo $row["DateTime"];
								echo '</td>';
								echo '<td class="newsTextTd">';
								echo $row["NewsText"];
								echo '</td>';
								echo '<td class="newsImagePathTd">';
								echo $row["NewsImagePath"];
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
	</div>
</main>

<?php
include_once '../footer.php';
?>