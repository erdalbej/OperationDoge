<?php
include_once 'header.php';
include_once 'aside.php';

if(isset($_POST['submit-thread'])){
	if(strlen($_POST['title']) > 0){

		$title = $_POST['title'];
		$description = $_POST['description'];
		$username = $_POST['username'];
		$result = nonQuery("INSERT INTO GuestbookThread (`Title`,`DateTime`,`Description`,`Username`) VALUES (:title,now(),:description,:username)", array(":title" => $title, ":description" => $description, ":username" => $username ));

		if($result["err"] === null){

		}
	}
}

?>
<main>
	<div class="row">
		<div class="twelve columns">
			<form method="POST" action="" class="thread-form">
				<div class="row">
					<div class="six columns">
						<label for="title">Trådtitel:</label>
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
	</div>

	<div class="row">
		<div class="twelve columns">
			<ul class="threads">
				<?php
				$divOne = query("SELECT Title, DateTime, Description, Username FROM GuestbookThread");
				$divData = $divOne['data'];
				foreach($divData as $key => $row){
					echo '<span id="news' . $key . '"></span>';
					echo '<li>';
					echo '<span class="thread-title">';
					echo '<a href="/operationdoge/thread.php?title='.$row['Title'].'&datetime='.$row['DateTime'].'">';
					echo $row['Title'];
					echo '</a>';
					echo '</span>';
					echo '<span class="thread-date">';
					echo $row['DateTime'];
					echo '</span>';
					echo '<br>';
					echo '<span class="thread-username">';
					echo '<b>Startad av:</b> ' . $row['Username'];
					echo '</span>';
					echo '<br>';
					echo '<span class="thread-description">';
					echo $row['Description'];
					echo '</span>';
					echo '</li>';
				}			
				?>
			</ul>
		</div>
	</div>
</main>
<?php
include_once 'footer.php';
?>