<?php
include_once 'header.php';
include_once 'aside.php';
?>
<main>
<?php
$divOne = query("SELECT Title, DateTime, NewsText, NewsImagePath FROM News");
$divData = $divOne['data'];
foreach($divData as $key => $row){
	echo '<span id="news' . $key . '"></span>';
	echo '<h2>';
	echo $row['Title'];
	echo '</h2>';
	echo '<img src="uploads/'.$row['NewsImagePath'].'" width="100" height="100" class="floatleft" alt="">';
	echo '<p class="details">';
	echo $row['NewsText'];
	echo '</p>';
	echo '<span>';
	echo $row['DateTime'];
	echo '</span>';
	echo '<br class="clearleft">';
}			
?>	
</main>

<?php
include_once 'footer.php';
?>