<?php
    include_once 'data/dbHelper.php'; 
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
    <meta name="description" content="We are doing operation doge">
    <title>Operation Doge - admin</title>	
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.css" />
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
                    <li><a href="manage-home.php">Hem</a></li>
                    <li><a href="#">Annonser</a></li>
                    <li><a href="#">Mina hundar</a></li>
                    <li><a href="#">Kennel</a></li>
                    <li><a href="#">Hundskola</a></li>
                    <li><a href="manage-gallery.php">Bildgalleri</a></li>
                    <li><a href="#">Gästbok</a></li>
                    <li><a href="#">Kontakt</a></li>
                </ul>
            </nav>