<?php
include_once 'header.php';
include_once 'aside.php';
$puppys = $_GET['puppylitter'];
?>
<main class="height-uv">
	<div class="container">
		<div class="row">
			<div class="four columns">
				<label for="puppylitter">Välj kull</label>
				<select id="puppylitters" class="u-full-width" name="puppylitter">
					<?php
					$puppyData = query("SELECT LitterTitle FROM PuppyLitter");
					$puppyRes = $puppyData['data'];
					foreach($puppyRes as $key => $puppy){
						echo "<option>";
						echo $puppy["LitterTitle"];
						$puppylitter = $puppy["LitterTitle"];
						echo '</option>';
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
				if(strlen($puppys) > 0){
					$divOne = query("SELECT DogName, Gender, Price, PuppyImagePath, Available, BirthDate, PuppyLitter_LitterTitle FROM Puppy WHERE PuppyLitter_LitterTitle = :puppylitter ORDER BY PuppyLitter_LitterTitle",
						array(
							':puppylitter' => $puppys
							)
						);
					$divData = $divOne['data'];
					if(count($divData) > 0){
						foreach($divData as $key => $row){

							if($row['PuppyImagePath'] === NULL){
								$image = "noimage.jpg";
							}else{
								$image = $row['PuppyImagePath'];
							}
							echo '<span id="dogs' . $key . '"></span>';
							echo '<h2>';
							echo $row['DogName'];
							echo '</h2>';
							echo '<img class="dog-image floatleft" src="uploads/'.$image.'" width="100" height="100" alt="">';
							echo '<p class="details">';
							echo '<b>Kull: </b>' . $row['PuppyLitter_LitterTitle'] . '<br>';
							echo '<b>Födelsedatum: </b>' . $row['BirthDate'] . '<br>';
							echo '<b>Kön: </b>' . $row['Gender'] . '<br>';
							echo '<b>Pris: </b>' . $row['Price'] . ' kr<br>';
							echo '<b>Tillgänglig: </b>';
							if($row['Available'] === "1"){
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