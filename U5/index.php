<?php
include_once 'header.php';
include_once 'aside.php';
?>

<main class="height-uv">
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h1 class="page-header">Välkommen till Batuulis hemsida!</h1>
			</div>
		</div>
	</div>

<?php
$result = query("SELECT Title, CreatedAt, NewsText, NewsImagePath FROM News ORDER BY CreatedAt DESC LIMIT 10");
$newsData = $result['data'];
foreach($newsData as $key => $n){

	if($n['NewsImagePath'] === NULL){
		$image = "noimage.jpg";
	}else{
		$image = $n['NewsImagePath'];
	}

	echo '<span id="news' . $key . '"></span>';
	echo '<h2>';
	echo $n['Title'];
	echo '</h2>';
	echo '<img class="news-image floatleft" src="uploads/'.$image.'" width="100" height="100" alt="">';
	echo '<p class="details">';
	echo $n['NewsText'];
	echo '</p>';
	echo '<span class="news-datetime">';
	echo $n['CreatedAt'];
	echo '</span>';
	echo '<br/>';
	echo '<br class="clearleft">';
}			
?>	
</main>

<?php
include_once 'footer.php';
?>