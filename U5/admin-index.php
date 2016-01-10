<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}

include_once 'admin-header.php';
?>
<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h1>Välkommen till administrationen för Kennel Perry</h1>
				<hr>
				<p>
					I navigationsfältet har du möjligt att gå vidare till andra administrationsvyer.
				</p>
			</div>
		</div>
	</div>
</main>

<?php
include_once 'footer.php';
?>