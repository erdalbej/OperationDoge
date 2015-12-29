<?php
session_start();
include_once 'data/dbHelper.php';	
if ($_SERVER['REQUEST_METHOD'] == "POST" &&
	isset($_POST['login-submit']) && 
	isset($_POST['password']) && 
	isset($_POST['username'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$result = query("SELECT `Username`,`Password` FROM Admin WHERE Username = :username", array(":username" => $username), true);

if ($result['err'] == null){
	$data = $result['data'];
	if ($data['Username'] && $data['Username'] == $username && $data['Password'] == $password){		
		$_SESSION['isAuth'] = true;
		$_SESSION['username'] = $data['Username'];
		header('location: /U5/admin-index.php');
	} else { $error = 'Fel lösenord eller användarnamn'; }
} else { $error = 'Något gick fel, prova igen'; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.css" />
</head>
<body>
	<?php
		if(isset($error)){
			echo $error;
		}
	?>
	<section id="login">
		<form action="" method="POST">		
			<label for="username">Användarnamn:</label>
			<input type="text" name="username">
			<br>
			<label for="password">Lösenord:</label>
			<input type="password" name="password">
			<br>
			<input type="submit" class="login-button button-primary" name="login-submit" value="Logga in">
		</form>
	</section>
</body>
</html>