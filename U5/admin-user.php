<?php
include_once 'admin-header.php';

if(isset($_POST['submit_update'])){
	if(strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0){
		$new_username = $_POST['username'];
		$username = "Robot";
		$password = $_POST['password'];

		$check = query("SELECT Username, Password FROM Admin WHERE Username= :username", array(
			":username" => $username
			));

		if($check["err"] === null){
			if(COUNT($check) > 0){
				if($username === $new_username){
					$result = nonQuery("UPDATE Admin SET Password = :password WHERE Username = :username", array(
						":username" => $username,
						":password" => $password
						));
					if($result["err"] === NULL){
						$userAddSuccess = "Ditt konto är uppdaterat!"; 
					}else{
						$userAddError = "Kunde inte uppdatera kontot.";
					}
				}
				else {
					$deleteResult = nonQuery("DELETE FROM Admin WHERE Username = :username", array(
						":username" => $username
						));
					$result = nonQuery("INSERT INTO Admin (`Username`,`Password`) VALUES (:username,:password)", array(
						":username" => $new_username, ":password" => $password
						));
					if($result["err"] === NULL){
						$userAddSuccess = "Ditt konto är uppdaterat!"; 
					}else{
						$userAddError = "Kunde inte uppdatera kontot.";
					}
				}
			}
			
		}
		
	}
	else {
		$userAddError = "Behövs text i inputs";
	}
}
?>
<main>
	<div class="container">
		<form action="" method="POST">
			<div class="row">
				<div class="two columns">
					
				</div>
				<div class="eight columns">
					<div class="row">
						<div class="six columns">
							<label for="username">Nytt användarnamn:</label>
							<input type="text" name="username" class="u-full-width">
						</div>
						<div class="six columns">
							<label for="password">Nytt lösenord:</label>
							<input type="password" name="password" class="u-full-width">
						</div>
					</div>
					<div class="row">
						<div class="twelve columns">
							<input type="submit" name="submit_update" value="Uppdatera" class="button-primary u-full-width">
						</div>
					</div>
				</div>
				<div class="two columns">
					
				</div>
			</div>
		</form>
		<?php
		if(isset($userAddError)){
			echo $userAddError;
		}
		if(isset($userAddSuccess)){
			echo $userAddSuccess;
		}
		?>
	</div>
</main>
<?php
include_once 'footer.php';
?>