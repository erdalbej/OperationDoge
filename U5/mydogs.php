<?php
include_once 'header.php';
include_once 'aside.php';
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
						//$puppylitter = $dog["Name"];
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
		<div class="row">
			<div class="twelve columns">
				<span>Diva</span>
				<span class="dog-information">
					<h3>Officielt namn</h3>
					<span>Födelsedatum</span>
				</span>
			</div>
		</div>
		<hr>

		<div class="row">
			<div class="twelve columns">
				<img class="dog-pic" src="#" alt="">
			</div>
		</div>

		<div class="row">
			<div class="twelve columns">
				<p>Beskrivning</p>
			</div>
		</div>

		<div class="row">
			<div class="four columns">
				<span><b>Färg:</b></span>
			</div>
			<div class="four columns">
				<span><b>Vikt:</b></span>
			</div>
			<div class="four columns">
				<span><b>Mankhöjd:</b></span>
			</div>
		</div>

		<div class="row">
			<div class="four columns">
				<span><b>Tänder:</b></span>
			</div>
			<div class="four columns">
				<span><b>Mental status:</b></span>
			</div>
			<div class="four columns">
				<span><b>Uppfödare:</b></span>
			</div>
		</div>

		<div class="row">
			<div class="twelve columns">
				<img class="gen-pic" src="#" alt="">
			</div>
		</div>

	</div>
</main>
<script src="js/mydogs.js"></script>
<?php 
	include_once 'footer.php';
?>