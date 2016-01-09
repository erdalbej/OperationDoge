<?php
include_once 'header.php';
include_once 'aside.php';
$dogName = NULL;
$officialName = NULL;

if(isset($_GET['dog']) && isset($_GET['officialName'])){
	$dogName = $_GET['dog'];
	$officialName = $_GET['officialName'];

}

?>
<main class="height-uv">
	<div class="container">
		<div class="row">
			<div class="four columns">
				<label for="dogs">Välj hund</label>
				<select id="dogs" class="u-full-width" name="dogs">
					<?php
					$dogData = query("SELECT OfficialName, Name FROM MyDog");
					$dogRes = $dogData['data'];
					foreach($dogRes as $key => $dog){
						echo '<option value="' .  $dog['Name'] .'|' . $dog['OfficialName'] . '">';
						echo $dog['Name'];
						echo '</span>';
						echo '</option>';
					}
					?>
				</select>
			</div>
			<div class="four columns">
				<label for="">&nbsp;</label>
				<a id="mydogslink" href="">Visa hund</a>
			</div>
			<div class="four columns">

			</div>
		</div>
		
		<?php
		if(strlen($dogName) > 0 && strlen($officialName) > 0){
			$result = query("SELECT OfficialName, Name, BirthDate, Description, Color, Height, Weight, MentalStatus, Breader, GenImagePath, DogImagePath FROM MyDog WHERE Name = :dogName AND OfficialName = :officialName", 
				array(
					':dogName' => $dogName,
					':officialName' => $officialName
					)
				);
			$dog = $result['data'][0];
			if(count($result) > 0){
				if($dog['DogImagePath'] === NULL){
					$image = "noimage.jpg";
				}else{
					$image = $dog['DogImagePath'];
				}
				echo '<div class="row">';
				echo '<div class="twelve columns">';
				echo '<h2>';
				echo $dog['Name'];
				echo '</h2>';
				echo '<p><b>Officiellt namn: </b>' . $dog['OfficialName'] . '</p>';
				echo '<img class="dog-image floatleft" src="uploads/'.$image.'" width="200" height="200" alt="">';
				echo '<p class="details dog-details">';
				echo $dog['Description'];
				echo '</p>';
				echo '</div>';
				echo '</div>';
				echo '<div class="row">';
				echo '<div class="four columns">';
				echo '<span><b>Färg:</b>&nbsp;'.$dog['Color'].'</span>';
				echo '</div>';
				echo '<div class="four columns">';
				echo '<span><b>Vikt:&nbsp;</b>'.$dog['Weight'].' kg</span>';
				echo '</div>';
				echo '<div class="four columns">';
				echo '<span><b>Mankhöjd:&nbsp;</b>'.$dog['Height'].' cm</span>';
				echo '</div>';
				echo '</div>';

				echo '<div class="row">';
				echo '<div class="six columns">';
				echo '<span><b>Mental status:&nbsp;</b>'.$dog['MentalStatus'].'</span>';
				echo '</div>';
				echo '<div class="six columns">';
				echo '<span><b>Uppfödare:&nbsp;</b>'.$dog['Breader'].'</span>';
				echo '</div>';
				echo '</div>';

				echo '<div class="row">';
				echo '<div class="twelve columns">';
				echo '<br><h1 class="gen-heading">Stamtavla</h1>';
				if($dog['GenImagePath'] !== null){
					echo '<img class="gen-pic" src="uploads/'.$dog['GenImagePath'].'" alt="">';
				}
				else {
					echo '<span>Finns ingen stamtavla tillgänglig</span>';
				}
				echo '</div>';
				echo '</div>';
			}	
			else {
				echo '<span>Hund finns ej</span>';
			}		
		}
		else {
			echo '<span>Välj en hund i menyn och tryck på visa hund</span>'; 
		}
		?>




	</div>
</main>
<script src="js/mydogs.js"></script>
<?php 
include_once 'footer.php';
?>