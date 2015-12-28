<?php
include_once 'header.php';
include_once 'aside.php';

if(isset($_POST['submit-thread'])){
	if(strlen($_POST['title']) > 0){

		$title = $_POST['title'];
		$description = $_POST['description'];
		$username = $_POST['username'];
		$result = nonQuery("INSERT INTO GuestbookThread (`Title`,`DateTime`,`Description`,`Username`) VALUES (:title, NOW(), :description, :username)", 
			array(
				':title' => $title, 
				':description' => $description, 
				':username' => $username 
			)
		);

		if($result["err"] != null){
			$createError = 'Det gick inte att skapa tråden, prova igen!';
		} 
	} else {
		$createError = 'Saknar värden';
	}
} else {

	$threadResult = query("SELECT Title, DateTime, Description, Username FROM GuestbookThread");

	if ($threadResult['err'] == null){
		$threads = $threadResult['data'];

		if (count($threads) <= 0){
			$threadError = 'Det finns inga trådar för tillfället';
		}
	} else {
		$threadError = 'Det gick inte att läsa in forumstrådarna, prova igen!';
	}
}

?>
<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h4 style="text-align: center;">Forumtrådar</h4>
				<hr>
			</div>
		</div>
	
		<?php
		foreach($threads as $key => $row){
			echo 
				'<div class="row">
					<div class="twelve columns">
						<ul class="threads">' . 
							'<span id="news' . $key . '"></span>' .
							'<li>' .
								'<span class="thread-title">' .
									'<a href="/thread.php?title='.$row['Title'].'&datetime='.$row['DateTime'].'">' .
										$row['Title'] .
									'</a>' .
								'</span>' .
								'<span class="thread-date">' .
									$row['DateTime'] .
								'</span>' .
								'<br>' .
								'<span class="thread-username">Startad av: ' . $row['Username'] . '</span>' .
								'<br>' .
								'<span class="thread-description">' .
									$row['Description'] .
								'</span>' .
							'</li>' .
						'</ul>
					</div>
				</div>';
		}
	
		echo 
			'<div class="row">
				<div class="twelve columns">
					<form method="POST" action="" class="thread-form">
						<div class="row">
							<div class="six columns">
								<label required for="title">Trådtitel:</label>
								<input type="text" name="title">
							</div>
							<div class="six columns">
								<label for="username">Användarnamn:</label>
								<input type="text" name="username">
							</div>
						</div>
						<div class="row">
							<div class="twelve columns">
								<label for="description">Beskrivning:</label>
								<textarea name="description" class="u-full-width" id="description"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="four columns">
								<label for="submit-thread">&nbsp;</label>
								<input type="submit" name="submit-thread" value="Skapa en ny tråd">
							</div>
							<div class="eight columns">
							</div>
						</div>
					</form>
				</div>
			</div>';
		?>
	</div>
</main>
<?php
include_once 'footer.php';
?>