<?php
include_once 'header.php';
include_once 'aside.php';
?>

<?php
$result = query("SELECT Title, CreatedAt, NewsText, NewsImagePath FROM News ORDER BY CreatedAt DESC LIMIT 10");

if ($result['err'] == null){
	$newsData = $result['data'];
} else {
	$newsError = 'Gick inte att läsa nyheter, prova ladda om sidan.';
}
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
if (!isset($newsError)){

	if (count($newsData) > 0){
		foreach($newsData as $key => $n){

			if($n['NewsImagePath'] === NULL){
				$image = "noimage.jpg";
			}else{
				$image = $n['NewsImagePath'];
			}

			echo '<div class="row"><div class="twelve columns">';
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
			echo '</div></div>';
		}	
	} else {
		echo '<div class="row"><div class="twelve columns">';
		echo 'Finns inga nyheter att visa';
		echo '</div></div>';
	}

} else {
	echo '<div class="row"><div class="twelve columns">';
	echo $newsError;
	echo '</div></div>';
}	
?>	

</main>

<?php
include_once 'footer.php';
?>