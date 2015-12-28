<?php
include_once 'header.php';
include_once 'aside.php';
?>
<main class="height-uv">

<?php
$divOne = query("SELECT Title, DateTime, NewsText, NewsImagePath FROM News");
$divData = $divOne['data'];
foreach($divData as $key => $row){

	if($row['NewsImagePath'] === NULL){
		$image = "noimage.jpg";
	}else{
		$image = $row['NewsImagePath'];
	}

	echo '<span id="news' . $key . '"></span>';
	echo '<h2>';
	echo $row['Title'];
	echo '</h2>';
	echo '<img class="news-image floatleft" src="uploads/'.$image.'" width="100" height="100" alt="">';
	echo '<p class="details">';
	echo $row['NewsText'];
	echo '</p>';
	echo '<span class="news-datetime">';
	echo $row['DateTime'];
	echo '</span>';
	echo '<br/>';
	echo '<br class="clearleft">';
}			
?>	
</main>

<?php
include_once 'footer.php';
?>