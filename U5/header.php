<?php
    include_once 'data/dbHelper.php'; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="description" content="Kennel Batuulis homepage">	
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kennel Perry - Rhodesian Ridgeback</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/jquery.fancybox.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){
       $("#updatedTime").html("Sidan uppdaterades senast: " + document.lastModified);
       $("#mobile-menu").click(function() {
          $( "nav" ).toggle();
        });
       $(window).on('resize', function(){
            var win = $(this);
            if (win.width() >= 768) { $("nav").show(); }
        });
    });
    
    </script>
	<!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    	<![endif]-->
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1><center class="logo-display"><i class="fa fa-lemon-o icon-lemon fa-2x"></i></center><br class="logo-display"><span class="logo">Kennel Perry - <span class="slogan">Rhodesian Ridgeback</span></span></h1>
                <br>
                <div id="mobile-menu"><i class="fa fa-bars fa-2x"></i><span class="menu-text">Visa meny</span></div>
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
