<?php
include_once 'header.php';
include_once 'aside.php';

if(isset($_POST['submit-thread'])){
	if(strlen($_POST['title']) > 0){

		$title = $_POST['title'];
		$description = $_POST['description'];
		$result = nonQuery("INSERT INTO GuestbookThread (`Title`,`DateTime`,`Description`) VALUES (:title,now(),:description)", array(":title" => $title, ":description" => $description));

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
					<div class="four columns">
						<label for="title">Titel:</label>
						<input type="text" name="title">
					</div>
					<div class="four columns">
						<label for="description">Beskrivning:</label>
						<input type="text" name="description">
					</div>
					<div class="four columns">
						<label for="submit-thread">&nbsp;</label>
						<input type="submit" name="submit-thread" value="Skapa en ny tråd">
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="twelve columns">
			<ul class="threads">
				<?php
				$divOne = query("SELECT Title, DateTime, Description FROM GuestbookThread");
				$divData = $divOne['data'];
				foreach($divData as $key => $row){
					echo '<span id="news' . $key . '"></span>';
					echo '<li>';
					echo '<span class="thread-title">';
					echo '<a href="/thread.php?title='.$row['Title'].'&datetime='.$row['DateTime'].'">';
					echo $row['Title'];
					echo '</a>';
					echo '</span>';
					echo '<span class="thread-date">';
					echo $row['DateTime'];
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