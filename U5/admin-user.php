<?php
session_start();
$authenticated = $_SESSION['isAuth'];
if (!$authenticated){
	header('location: /U5/admin.php');
	die();
}

if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
include_once 'admin-header.php';

if(isset($_POST['submit_update'])){
	if(strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0){
		if (strlen($_POST['username']) <= 255 && strlen($_POST['password']) <= 255){
			$new_username = $_POST['username'];
			$password = $_POST['password'];

			$check = query("SELECT Username, Password FROM Admin WHERE Username= :username", array(
				":username" => $username
				));

			if($check["err"] === null){
				if(COUNT($check) > 0){
					if($username === $new_username){
						$result = nonQuery("UPDATE Admin SET Password = :password WHERE Username = :username", 
							array(
								":username" => $username,
								":password" => $password
							)
						);

						if($result["err"] === NULL){
							$userAddSuccess = "Ditt konto är uppdaterat!"; 					
						}else{
							$userAddError = "Kunde inte uppdatera kontot.";
						}
					}
					else {
						$deleteResult = nonQuery("DELETE FROM Admin WHERE Username = :username", 
							array(
								":username" => $username
							)
						);

						$result = nonQuery("INSERT INTO Admin (Username, Password) VALUES (:username,:password)", 
							array(
								":username" => $new_username, ":password" => $password
							)
						);

						if($result["err"] === NULL){
							$userAddSuccess = "Ditt konto är uppdaterat!"; 
						} else{
							$userAddError = "Kunde inte uppdatera kontot.";
						}
					}
				}
				else { $userAddError = "Användaren finns ej"; }
			} else { $userAddError = "Något gick snett prova igen."; }
		} else { $userAddError = "För stor data, prova minska antal tecken."; }	
	} else { $userAddError = "Var vänlig och mata in information i inmatningsfälten"; }
}
?>
<main>
	<?php
		echo 'Hello, ' . $username;
	?>
	<div class="container">
		<form action="" method="POST">
			<div class="row">
				<div class="two columns">
					
				</div>
				<div class="eight columns">
					<div class="row">
						<div class="six columns">
							<label for="username">Nytt användarnamn:</label>
							<input type="text" name="username" maxlength="255" class="u-full-width" required>
						</div>
						<div class="six columns">
							<label for="password">Nytt lösenord:</label>
							<input type="password" name="password" maxlength="255" class="u-full-width" required>
						</div>
					</div>
					<div class="row">
						<div class="twelve columns">
							<input type="submit" name="submit_update" value="Uppdatera" class="u-full-width">
						</div>
					</div>
				</div>
				<div class="two columns">
					
				</div>
			</div>
		</form>
		<div class="row">
			<div class="two columns"></div>
			<div class="eight columns">
				<?php
				if(isset($userAddError)){
					echo '<span class="error-message">'.$userAddError.'</span>';
				}
				if(isset($userAddSuccess)){
					echo '<span class="success-message">'.$userAddSuccess.'</span>';
				}
				?>
			</div>
			<div class="two columns"></div>
		</div>
	</div>
</main>
<?php
include_once 'footer.php';
?>