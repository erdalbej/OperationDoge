<?php
include_once 'admin-header.php';
?>
<main>

	<form method="POST" action="">
		<label for="title">Titel:</label>
		<input type="text" name="title">
		<br>

		<label for="news-text">Text:</label>
		<textarea name="news-text" id="" cols="30" rows="10"></textarea>
		
		<br>

		<label for="image-path">Bild:</label>
		<input type="text" name="image-path">
		<br>
		
		<input type="submit" name="submit-news">
	</form>

</main>

<?php
include_once '../footer.php';
?>