<?php
session_start();
include_once 'data/dbHelper.php';	
if (isset($_POST['login-submit']) && isset($_POST['password']) && isset($_POST['username'])){
	if(strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0){
		if (strlen($_POST['username']) <= 255 && strlen($_POST['password']) <= 255){
			$username = $_POST['username'];
			$password = $_POST['password'];
			$result = query("SELECT Username, Password FROM Admin WHERE Username = :username", array(":username" => $username), true);

			if ($result['err'] == null){
				$data = $result['data'];
				if ($data['Username'] == $username && $data['Password'] == $password){		
					$_SESSION['isAuth'] = true;
					$_SESSION['username'] = $data['Username'];
					header('location: /U5/admin-index.php');
				} 
				else { $error = 'Fel användarnamn och/eller lösenord'; }
			} else { $error = 'Något gick fel, prova igen'; }
		} else { $error = "För stora textvärden, prova igen."; }
	} else { $error = 'Saknar värden, prova igen!'; }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.css" />
</head>
<body>
	<section id="login">
		<form action="" method="POST">		
			<label for="username">Användarnamn:</label>
			<input type="text" name="username" maxlength="255" required>
			<br>
			<label for="password">Lösenord:</label>
			<input type="password" name="password" maxlength="255" required>
			<br>
			<input type="submit" class="login-button" name="login-submit" value="Logga in">
		</form>
		<?php
		if(isset($error)){
			echo '<span class="error-message">' . $error . '</span>';
		}
		?>
	</section>
</body>
</html>