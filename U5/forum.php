<?php
include_once 'header.php';
include_once 'aside.php';

if(isset($_POST['submit-thread'])){
	if(strlen($_POST['title']) > 0){
		if (strlen($_POST['title']) <= 255 && strlen($_POST['description']) <= 255 && strlen($_POST['username']) <= 255){
			$title = $_POST['title'];
			$description = $_POST['description'];
			$username = $_POST['username'];

			$nowRes = query("SELECT NOW() as now");

			if ($nowRes['err'] == null){
				$nowData = $nowRes['data'];
				$now = $nowData[0];

				$result = nonQuery("INSERT INTO GuestbookThread (Title, CreatedAt, Description, Username) VALUES (:title, :now, :description, :username)", 
					array(
						':title' => $title, 
						':description' => $description, 
						':now' => $now['now'],
						':username' => $username
					)
				);
			} else { $createError = 'Gick inte att skapa tråden, prova igen.'; }
			if($result["err"] != null){ $createError = 'Det gick inte att skapa tråden, prova igen!'; } 
		} else { $createError = 'För stora datamänged, prova minska antalet tecken.'; }
	} else { $createError = 'Saknar värden'; }
} else {
	$threadResult = query("SELECT Title, CreatedAt, Description, Username FROM GuestbookThread ORDER BY CreatedAt DESC");

	if ($threadResult['err'] == null){
		$threads = $threadResult['data'];
	} else { $threadError = 'Det gick inte att läsa in forumstrådarna, prova igen!'; }
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
		if (isset($_POST['submit-thread'])){
			if (!isset($createError)){
				echo 
				'<div class="row">
					<div class="twelve columns">
						<p>Tråden är skapad! <a href="thread.php?title=' . $title . '&createdAt=' . $now['now'] . '">Tryck här</a> för att komma till den nyskapade tråden</p>
					</div>
				</div>';
			} else { echo $createError; }

			echo '<p><a href="forum.php">Tillbaka</a></p>';		
		} else {
			if (count($threads) > 0){
				foreach($threads as $key => $t){
				echo 
					'<div class="row">
						<div class="twelve columns">
							<ul class="threads">' . 
								'<span id="news' . $key . '"></span>' .
								'<li>' .
									'<span class="thread-title">' .
										'<a href="thread.php?title='.$t['Title'].'&createdAt='.$t['CreatedAt'].'">' .
											$t['Title'] .
										'</a>' .
									'</span>' .
									'<span class="thread-date">' .
										$t['CreatedAt'] .
									'</span>' .
									'<br>' .
									'<span class="thread-username"><b>Startad av: </b>' . $t['Username'] . '</span>' .
									'<br>' .
									'<span class="thread-description">' .
										$t['Description'] .
									'</span>
								</li> 
							</ul>
						</div>
					</div>';
				}	
			} else {
				echo
					'<div class="row">
						<div class="twelve columns">
							<p>Inga trådar är ännu skapade</p>
						</div>
					</div>';
			}

			echo 
				'<hr>
				<div class="row">
					<div class="twelve columns">
						<form method="POST" action="" class="thread-form">
							<div class="row">
								<div class="six columns">
									<label required for="title">Trådtitel:</label>
									<input required maxlength="255" type="text" name="title">
								</div>
								<div class="six columns">
									<label for="username">Användarnamn:</label>
									<input type="text" maxlength="255" name="username">
								</div>
							</div>
							<div class="row">
								<div class="twelve columns">
									<label for="description">Beskrivning:</label>
									<textarea name="description" maxlength="255" class="u-full-width" id="description"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="twelve columns">
									<label for="submit-thread">&nbsp;</label>
									<input type="submit" name="submit-thread" class="btn-primary" value="Skapa en ny tråd">
								</div>
							</div>
						</form>
					</div>
				</div>';
		}
		?>
	</div>
</main>
<?php
include_once 'footer.php';
?>