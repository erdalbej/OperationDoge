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
	<!--<ul>
	<?php
	$query = query("SELECT Username, Password FROM Admin");
	$data = $query['data'];
	foreach($data as $key => $row){
		echo '<li>Username: ';
		echo $row['Username'];
		echo '</li>';

		echo '<li>Password: ';
		echo $row['Password'];
		echo '</li>';
	}			
	?>
	</ul>-->

	<h2>Image title 1</h2>
	<img src="#" class="floatleft" alt="">
	<p class="details">
		Image text goes here
	</p>

	<br class="clearleft">

	<h2>Image title 2</h2>
	<img src="#" class="floatleft" alt="">
	<p class="details">
		Image text goes here
	</p>

	<br class="clearleft">

</main>

<?php
include_once 'footer.php';
?>