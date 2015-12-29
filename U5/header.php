<?php
    include_once 'data/dbHelper.php'; 
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
    <meta name="description" content="We are doing operation doge">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <title>Operation Doge</title>	
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/jquery.fancybox.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
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
                <h1>Operation Doge</h1>
            </header>
            <nav>
                <ul>
                    <li><a href="index.php">Hem</a></li>
                    <li><a href="mydogs.php">Mina hundar</a></li>
                    <li><a href="dogs.php">Kennel</a></li>
                    <li><a href="school.php">Hundskola</a></li>
                    <li><a href="gallery.php">Bildgalleri</a></li>
                    <li><a href="forum.php">Gästbok</a></li>
                    <li><a href="contact.php">Kontakt</a></li>
                </ul>
            </nav>
