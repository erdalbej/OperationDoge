<?php
    include_once 'data/dbHelper.php'; 
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
    <meta name="description" content="We are doing operation doge">
    <title>Operation Doge</title>	
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/jquery.fancybox.css">
    <script>
    $(document).ready(function(){
       $("#message").html("Sidan uppdaterades senast: " + document.lastModified);
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
                    <li><a href="#">Hem</a></li>
                    <li><a href="#">Mina hundar</a></li>
                    <li><a href="#">Kennel</a></li>
                    <li><a href="#">Hundskola</a></li>
                    <li><a href="#">Bildgalleri</a></li>
                    <li><a href="forum.php">Gästbok</a></li>
                    <li><a href="#">Kontakt</a></li>
                </ul>
            </nav>