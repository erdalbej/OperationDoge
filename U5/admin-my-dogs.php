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
		<form enctype="multipart/form-data" method="POST" action="" >
			<div class="row">
				<div class="twelve columns">
					<h3>Lägg till hund</h3>
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Hundnamn</label>
					<input required maxlength="255" type="text" name="title" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Officiellt namn</label>
					<input required maxlength="255" type="text" name="title" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Färg</label>
					<input maxlength="255" type="text" name="title" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Uppfödare</label>
					<input maxlength="255" type="text" name="title" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Vikt i kg</label>
					<input min="0" step="0.1" value="0" type="number" name="title" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Mankhöjd i meter</label>
					<input min="0" step="0.1" type="number" name="title" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">MentalStatus</label>
					<input maxlength="255" type="text" name="title" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Födelsedag</label>
					<input required type="date" name="title" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="image-path">Bild på hund:</label>
					<input type="file" name="news-image" accept=".jpg">	
				</div>
				<div class="six columns">
					<label for="image-path">Stamtavla:</label>
					<input type="file" name="news-image" accept=".jpg">	
				</div>
			</div>
			<label for="news-text">Beskrivning:</label>
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
			<div class="twelve columns">
				<h3>Redigera befintliga hundar</h3>
				<p>Välj en hund i listan genom att klicka på den. Redigera den sedan i det nya formuläret som poppar upp.</p>

				<div class="row">
				<div class="twelve columns">
					<table class="u-full-width">
						<thead>
							<tr>
								<th>Namn</th>
								<th>Officielt Namn</th>
							</tr>
						</thead>
						<tbody id="newsTable">
							<?php
								if (!isset($myDogsError)){
									foreach($myDogs as $key => $d){
									echo '<tr id="dog' . $key . '">';
									echo '<td class="nameTd">';
									echo $d['Name'];
									echo '</td>';
									echo '<td class="officialNameTd">';
									echo $d["OfficialName"];
									echo '</td>';
									echo '</tr>';
								}
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
				<h3>Redigera Hund</h3>
			</div>
		</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Färg</label>
					<input maxlength="255" type="text" name="title" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Uppfödare</label>
					<input maxlength="255" type="text" name="title" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">Vikt i kg</label>
					<input min="0" step="0.1" value="0" type="number" name="title" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Mankhöjd i meter</label>
					<input min="0" step="0.1" type="number" name="title" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="title">MentalStatus</label>
					<input maxlength="255" type="text" name="title" class="u-full-width">
				</div>
				<div class="six columns">
					<label for="title">Födelsedag</label>
					<input required type="date" name="title" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="six columns">
					<label for="image-path">Bild på hund:</label>
					<input type="file" name="news-image" accept=".jpg">	
				</div>
				<div class="six columns">
					<label for="image-path">Stamtavla:</label>
					<input type="file" name="news-image" accept=".jpg">	
				</div>
			</div>
			<label for="news-text">Beskrivning:</label>
			<textarea name="news-text" id="" class="u-full-width"></textarea>
			<div class="row">
				<div class="six columns">
					<input value="Uppdatera" name="news-update" type="submit">		
				</div>
				<div class="six columns">
					<input value="Radera" name="news-delete" type="submit">	
				</div>
			</div>
		</form>

	</div>
<script src="js/admin-my-dogs.js"></script>
	</div>
</main>

<?php
include_once 'footer.php';
?>