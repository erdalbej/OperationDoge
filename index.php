<?php
include_once 'header.php';
?>

<aside>
	<div class="sidebar-item">
		<img src="#" alt="">
		<a href="#">text that will be linked or?</a>
	</div>

	<div class="sidebar-item">
		<img src="#" alt="">
		<a href="#">text that will be linked or? #2</a>
	</div>

</aside>
<main>
<?php
$divOne = query("SELECT Title, DateTime, NewsText, NewsImagePath FROM News");
$divData = $divOne['data'];
foreach($divData as $key => $row){
	echo '<span id="news' . $key . '"></span>';
	echo '<h2>';
	echo $row['Title'];
	echo '</h2>';
	echo '<img src="uploads/'.$row['NewsImagePath'].'" width="45" height="45" class="floatleft" alt="">';
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