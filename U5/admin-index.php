<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/');
	die();
}
include_once 'admin-header.php';
?>
<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h1>Välkommen till administrationen för Batuulis</h1>
				<hr>
				<p>
					På vänster sida har du möjlighet att gå vidare och administrera hemsidan!
				</p>
			</div>
		</div>
	</div>
</main>

<?php
include_once 'footer.php';
?>