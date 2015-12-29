<?php
include_once 'header.php';
include_once 'aside.php';
$doge = $_GET['dog'];
?>
<main class="height-uv">
	<div class="container">
		<div class="row">
			<div class="four columns">
				<label for="dogs">Välj kull</label>
				<select id="dogs" class="u-full-width" name="dogs">
					<?php
					$dogData = query("SELECT Name FROM MyDog");
					$dogRes = $dogData['data'];
					foreach($dogRes as $key => $dog){
						echo "<option>";
						echo $dog["Name"];
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
		$divOne = query("SELECT OfficialName, Name, BirthDate, Description, Color, Height, Weight, Teeth, MentalStatus, Breader, GenImagePath, DogImagePath FROM MyDog WHERE Name = '$doge'");
		$divData = $divOne['data'];
		foreach($divData as $key => $row){
			if($row['DogImagePath'] === NULL){
				$image = "noimage.jpg";
			}else{
				$image = $row['DogImagePath'];
			}
			echo '<div class="row">';
			echo '<div class="twelve columns">';
			echo '<span id="dogs' . $key . '"></span>';
			echo '<h2>';
			echo $row['Name'];
			echo '</h2>';
			echo '<img class="dog-image floatleft" src="uploads/'.$image.'" width="100" height="100" alt="">';
			echo '<p class="details">';
			echo $row['Description'];
			echo '</p>';
			echo '</div>';
			echo '</div>';
			echo '<div class="row">';
			echo '<div class="four columns">';
			echo '<span><b>Färg:</b>&nbsp;'.$row['Color'].'</span>';
			echo '</div>';
			echo '<div class="four columns">';
			echo '<span><b>Vikt:&nbsp;</b>'.$row['Weight'].' kg</span>';
			echo '</div>';
			echo '<div class="four columns">';
			echo '<span><b>Mankhöjd:&nbsp;</b>'.$row['Height'].' cm</span>';
			echo '</div>';
			echo '</div>';

			echo '<div class="row">';
			echo '<div class="four columns">';
			echo '<span><b>Tänder:&nbsp;</b>'.$row['Teeth'].'</span>';
			echo '</div>';
			echo '<div class="four columns">';
			echo '<span><b>Mental status:&nbsp;</b>'.$row['MentalStatus'].'</span>';
			echo '</div>';
			echo '<div class="four columns">';
			echo '<span><b>Uppfödare:&nbsp;</b>'.$row['Breader'].'</span>';
			echo '</div>';
			echo '</div>';

			echo '<div class="row">';
			echo '<div class="twelve columns">';
			echo '<img class="gen-pic" src="'.$row['GenImagePath'].'" alt="">';
			echo '</div>';
			echo '</div>';
		}			
		?>




	</div>
</main>
<script src="js/mydogs.js"></script>
<?php 
include_once 'footer.php';
?>