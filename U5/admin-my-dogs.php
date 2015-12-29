<?php
include_once 'admin-header.php';
?>

<?php
$myDogsResult = query('SELECT * FROM MyDog ORDER BY Name ASC');
if ($myDogsResult['err'] == null){
	$myDogs = $myDogsResult['data'];
} else {
	$myDogsError = 'Det gick inte att läsa hundarna, prova igen!';
}
?>

<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h4 style="text-align: center;">Mina Hundar</h4>
			</div>
		</div>
		<hr>
		<div id="returnMsg" style="margin:20px 0 50px 0;">
			<?php
				if (isset($myDogsError)){
					echo $myDogsError;
				}

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
								echo '<td class="newsTextTd" style = "display:none">';
								echo $row["NewsText"];
								echo '</td>';
								echo '<td class="newsImagePathTd" style = "display:none">';
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
	</div>
</main>

<?php
include_once 'footer.php';
?>