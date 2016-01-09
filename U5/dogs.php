<?php
include_once 'header.php';
include_once 'aside.php';

$puppyLitter = NULL;

if(isset($_GET['puppylitter'])){
	$puppyLitter = $_GET['puppylitter'];
}

?>
<main class="height-uv">
	<div class="container">
		<div class="row">
			<div class="four columns">
				<label for="puppylitter">Välj kull</label>
				<select id="puppylitters" class="u-full-width" name="puppylitter">
					<?php
					$puppys = query("SELECT LitterTitle FROM PuppyLitter");

					if ($puppys['err'] == null){
						$puppyRes = $puppys['data'];
						foreach($puppyRes as $key => $puppy){
							echo "<option>";
							echo $puppy["LitterTitle"];
							echo '</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="four columns">
				<label for="">&nbsp;</label>
				<a id="puppylink" href="">Visa kull</a>
			</div>
			<div class="four columns">

			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<?php
				if(strlen($puppyLitter) > 0){
					$result = query("SELECT DogName, Gender, Price, Available, BirthDate, PuppyLitter_LitterTitle FROM Puppy WHERE PuppyLitter_LitterTitle = :puppylitter ORDER BY PuppyLitter_LitterTitle",
						array(
							':puppylitter' => $puppyLitter
							)
						);
					$upcomingresult = query("SELECT Upcomming FROM PuppyLitter WHERE LitterTitle = :puppyLitter", 
						array(
							':puppyLitter' => $puppyLitter
						)
					);
					if($upcomingresult['err'] == null){
						if(count($upcomingresult['data']) > 0){
							$pl = $upcomingresult['data'][0];
							echo '<span class="upcomingpl"> Kommande kull: ';
							echo '<b>';
							if($pl["Upcomming"] === "1"){
								echo "Ja";
							}
							else {
								echo "Nej";
							}
							echo '</b>';
							echo '</span>';
						}
					}
					
					if ($result['err'] == null){
						$puppysData = $result['data'];
						if(count($puppysData) > 0){
							foreach($puppysData as $key => $p){
								echo '<span id="dogs' . $key . '"></span>';
								echo '<h2>';
								echo $p['DogName'];
								echo '</h2>';
								echo '<p class="details">';
								echo '<b>Kull: </b>' . $p['PuppyLitter_LitterTitle'] . '<br>';
								echo '<b>Födelsedatum: </b>' . $p['BirthDate'] . '<br>';
								echo '<b>Kön: </b>' . $p['Gender'] . '<br>';
								echo '<b>Pris: </b>' . $p['Price'] . ' kr<br>';
								echo '<b>Tillgänglig: </b>';
								if($p['Available'] === "1"){
									echo '<span class="dog-available">Ja</span><br>'; 
								}
								else {
									echo '<span class="dog-not-available">Nej</span><br>'; 
								}
								echo '</p>';
								echo '<br/>';
								echo '<br class="clearleft">';
							}	
						}
						else {
							echo '<span>Kull finns ej</span>';
						}
					} else {
						echo '<span>Gick inte att hämta data, prova igen</span>';
					}		
				} else {
					echo '<span>Välj en kull att visa i menyn</span>';
				}	
				?>
			</div>
		</div>
	</div>
</main>
<script src="js/dog.js"></script>
<?php
include_once 'footer.php';
?>