<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}

include_once 'admin-header.php';

if(isset($_POST['submit_puppylitter'])){
	if(strlen($_POST['litterTitle']) > 0 && strlen($_POST['litterInfo']) > 0 && is_numeric($_POST['upcoming']) && $_POST['upcoming'] >= 0){
		if (strlen($_POST['litterTitle']) <= 255 && strlen($_POST['litterInfo']) <= 255 && $_POST['upcoming'] <= 1){
			$title = $_POST['litterTitle'];
			$upcoming = $_POST['upcoming'];
			$info = $_POST['litterInfo'];

			$result = nonQuery("INSERT INTO PuppyLitter (LitterTitle, Upcomming, LiterInfo) VALUES (:title, :upcomming, :info)", 
				array(
					':title' => $title, 
					':upcomming' => $upcoming,
					':info' => $info 
					)
				);

			if($result["err"] != null){ $createError  = 'Det gick inte att skapa kullen, prova igen!'; } 
			else { $createSuccess = "Kullen skapades"; }
		} else {$createError = "För stora värden, minska antal tecken till exempel"; }
	} else { $createError = 'Saknar värden'; }
} else if(isset($_POST['update_puppylitter'])){
	
	if(strlen($_POST['alter_litterTitle']) > 0 && strlen($_POST['alter_litterInfo']) > 0 && is_numeric($_POST['alter_upcoming']) && $_POST['alter_upcoming'] >= 0){
		if (strlen($_POST['alter_litterTitle']) <= 255 && strlen($_POST['alter_litterInfo']) <= 255 && $_POST['alter_upcoming'] <= 1){
			$title = $_POST['alter_litterTitle'];
			$upcoming = $_POST['alter_upcoming'];
			$info = $_POST['alter_litterInfo'];

			$result = nonQuery("UPDATE PuppyLitter SET Upcomming = :upcoming, LiterInfo = :info WHERE LitterTitle = :title", 
				array(
					":title" => $title, 
					":upcoming" => $upcoming, 
					":info" => $info
				)
			);

			if($result["err"] == null){ $updateSuccess = "Kull uppdaterad!";  } 
			else { $updateError = "Kunde inte uppdatera kull, prova igen."; }

		} else { $updateError = "För stora värden, prova minsta antal tecken."; }
	} else{ $updateError = "Saknar värden för att uppdatera kull."; }
} else if(isset($_POST['delete_puppylitter'])){

	if(strlen($_POST['alter_litterTitle']) > 0){
		if (strlen($_POST['alter_litterTitle']) <= 255){
			$title = $_POST['alter_litterTitle'];
			$result = nonQuery("DELETE FROM PuppyLitter WHERE LitterTitle = :title", array(":title" => $title));

			if ($result["err"] === NULL){
				$deleteSuccess = "Kull raderad!"; 
			} else{ $deleteError = "Kunde inte radera kull, prova igen."; }
		} else { $deleteError = "För stora värden, minka antal tecken till exempel."; }		
	} else{ $deleteError = "Saknar värden för att radera kull."; }
}
?>

<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h4 style="text-align: center;">Kennel</h4>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<?php
				if(isset($createSuccess)){
					echo '<span id="returnMsg" class="success-message">' . $createSuccess . '</span>';
				}
				if(isset($createError)){
					echo '<span id="returnMsg" class="error-message">' . $createError . '</span>';
				}
				if(isset($updateSuccess)){
					echo '<span id="returnMsg" class="success-message">' . $updateSuccess . '</span>';
				}
				if(isset($updateError)){
					echo '<span id="returnMsg" class="error-message">' . $updateError . '</span>';
				}
				if(isset($deleteSuccess)){
					echo '<span id="returnMsg" class="success-message">' . $deleteSuccess . '</span>';
				}
				if(isset($deleteError)){
					echo '<span id="returnMsg" class="error-message">' . $deleteError . '</span>';
				}	
				?>
			</div>
		</div>
		<div class="twelve columns">
				<h5>Lägg till kull</h5>
				<p>För att lägga till en kull i informationen nedan och tryck på knappen. </p>
				<hr>
			</div>
		<div class="row">
			<div class="twelve columns">
				<form method="POST" action="">
					<div class="row">
						<div class="six columns">
							<label required for="litterTitle">Kull:</label>
							<input required maxlength="255" type="text" name="litterTitle">
						</div>
						<div class="six columns">
							<label for="upcoming">Kommande:</label>
							<select required class="u-full-width" name="upcoming">
								<option value="1">Ja</option>
								<option value="0">Nej</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="twelve columns">
							<label for="litterInfo">Kullinformation:</label>
							<textarea name="litterInfo" maxlength="255" class="u-full-width"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="twelve columns">
							<label for="submit_puppylitter">&nbsp;</label>
							<input type="submit" name="submit_puppylitter" value="Skapa kull">
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="row">
			<div class="twelve columns">
				<h5>Redigera kull</h5>
				<p>För att redigera en kull, klicka på den i listan för att få upp kullinformaion. För att redigera hundar inom kull, klicka på hyperlänken "Redigera kull" längst ut till höger i tabellen.</p>
				<hr>
			</div>
		</div>
	<div class="row">
		<div class="twelve columns">
			<h3>Välj kull att redigera</h3>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
			<table class="u-full-width">
				<thead>
					<tr>
						<th>Titel</th>
						<th>Kommande</th>
						<th>Kullinformation</th>
						<th><center>Redigera Kull</center></th>
						<th><center>Redigera Hundar i kull</center></th>
					</tr>
				</thead>
				<tbody>
					<?php

					$result = query("SELECT LitterTitle, Upcomming, LiterInfo FROM PuppyLitter ORDER BY LitterTitle DESC");

					if($result["err"] != null){
						$load_error = "Kunde inte ladda kullar, prova att ladda om sidan.";
					}else{
						$puppyLitterData = $result['data'];

						if(count($puppyLitterData) > 0){
							foreach($puppyLitterData as $key => $p){
								echo '<tr>';
								echo '<td >';
								echo $p['LitterTitle'];
								echo '</td>';
								echo '<td>';
								echo $p["Upcomming"];
								echo '</td>';
								echo '<td>';
								echo $p["LiterInfo"];
								echo '</td>';
								echo '<td class="edit-litter">';
								echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
								echo '</td>';
								echo '<td>';
								echo '<center><a href="/U5/admin-puppy-from-litter.php?LitterTitle='.$p['LitterTitle'].'">';
								echo '<i class="fa fa-pencil-square-o fa-lg"></i>';
								echo '</a></center>';
								echo '</td>';
								echo '</tr>';
							}	
						} else{ $load_error = "Finns inga kullar tillgängliga."; }
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
	<div class="row">
		<div class="twelve columns">
			<form hidden method="POST" action="">
				<div class="row">
					<div class="six columns">
						<label for="alter_litterTitle">Kull:</label>
						<input maxlength="255" type="text" id="LitterTitle" name="alter_litterTitle" readonly>
					</div>
					<div class="six columns">
						<label for="alter_upcoming">Kommande:</label>
						<select required class="u-full-width" id="LitterUpcoming" name="alter_upcoming">
							<option value="1">Ja</option>
							<option value="0">Nej</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="twelve columns">
						<label for="alter_litterInfo">Kullinformation:</label>
						<textarea name="alter_litterInfo" maxlength="255" id="LitterInfo" class="u-full-width"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="twelve columns">
						<label for="update_puppylitter">&nbsp;</label>
						<input type="submit" name="update_puppylitter" value="Uppdatera kull">
						<input type="submit" name="delete_puppylitter" value="Radera kull">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="js/admin-kennel.js"></script>
</main>

<?php
include_once 'footer.php';
?>