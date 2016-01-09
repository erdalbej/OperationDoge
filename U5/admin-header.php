<?php
    include_once 'data/dbHelper.php'; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="description" content="We are doing operation doge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operation Doge - admin</title>	
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.css" />
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){
       $("#updatedTime").html("Sidan uppdaterades senast: " + document.lastModified);
   });
    </script>
	<!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    	<![endif]-->
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1>Admin</h1>
            </header>
            <nav>
                <ul>
                    <li>Hantera sidorna nedan:</li>
                    <li><a href="admin-index.php">Hem</a></li>
                    <li><a href="admin-news.php">Nyheter</a></li>
                    <li><a href="admin-sidebar.php">Annonser</a></li>
                    <li><a href="admin-my-dogs.php">Mina hundar</a></li>
                    <li><a href="admin-school.php">Hundkurser</a></li>
                    <li><a href="admin-kennel.php">Kennel</a></li>
                    <li><a href="admin-gallery.php">Bildgalleri</a></li>
                    <li><a href="admin-thread.php">Gästbok</a></li>
                    <li><a href="admin-user.php">Ändra användaruppgifter</a></li>
                    <li><a href="logout.php">Logga ut</a></li>
                </ul>
            </nav>