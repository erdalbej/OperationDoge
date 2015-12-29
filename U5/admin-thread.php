<?php
include_once 'admin-header.php';

//Update thread
if(isset($_POST['thread-update'])){
	
	if(strlen($_POST['title']) > 0 && strlen($_POST['created_at']) > 0){

		$title = $_POST['title'];
		$created_at = $_POST['created_at'];

		if(isset($_POST['username']) && isset($_POST['description'])){
			
			$username = $_POST['username'];
			$description = $_POST['description'];

			$result = nonQuery("UPDATE GuestbookThread SET `Username` = :username, `Description` = :description WHERE `Title` = :title AND `CreatedAt` = :created_at", array(":title" => $title, ":created_at" => $created_at, ":username" => $username, ":description" => $description));

			if($result["err"] == null){
				$thread_success = "Tråd uppdaterad!"; 
			}else{
				$thread_error = "Kunde inte uppdatera tråd, prova igen.";
			}
		}else{
			$thread_error = "Saknar värden för att uppdatera tråd.";
		}
	}else{
		$thread_error = "Saknar värden för att uppdatera tråd.";
	}
}

//Delete thread
if(isset($_POST['thread-delete'])){

	if(strlen($_POST['title']) > 0 && strlen($_POST['created_at']) > 0){

		$title = $_POST['title'];
		$created_at = $_POST['created_at'];

		$result = nonQuery("DELETE FROM GuestbookThread WHERE `Title` = :title AND `CreatedAt` = :created_at", array(":title" => $title, ":created_at" => $created_at));

		if($result["err"] === NULL){
			$thread_success = "Tråd raderad!"; 
		}else{
			$thread_error = "Kunde inte radera tråd, prova igen.";
		}
	}else{
		$thread_error = "Saknar värden för att radera tråd.";
	}
}
?>

<main>
	<div class="container">
	<div class="row">
			<div class="twelve columns">
				<?php
					if(isset($thread_success)){
						echo '<span id="returnMsg" class="success-message">' . $thread_success . '</span>';
					}
					if(isset($thread_error)){
						echo '<span id="returnMsg" class="error-message">' . $thread_error . '</span>';
					}
				?>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<h5>Gästbok - Redigera trådar</h5>
				<p>För att redigera en tråd, klicka på den i listan för att få upp trådinformaion. För att redigera inlägg, klicka på hyperlänken "Redigera inlägg" längst ut till höger i tabellen.</p>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<h3>Välj tråd att redigera</h3>
			</div>
		</div>
			<div class="row">
				<div class="twelve columns">
					<table class="u-full-width">
						<thead>
							<tr>
								<th>Tråd</th>
								<th>Datum</th>
								<th><center>Redigera Tråd</center></th>
								<th><center>Redigera Inlägg</center></th>
							</tr>
						</thead>
						<tbody>
							<?php

							$result = query("SELECT Title, CreatedAt, Username, Description FROM GuestbookThread ORDER BY CreatedAt DESC");

							if($result["err"] != null){
								$load_error = "Kunde inte ladda trådar, prova att ladda om sidan.";
							}else{
								$guestBookData = $result['data'];

								if(count($guestBookData) > 0){
									foreach($guestBookData as $key => $g){
										echo '<tr>';
										echo '<td >';
										echo $g['Title'];
										echo '</td>';
										echo '<td>';
										echo $g["CreatedAt"];
										echo '</td>';
										echo '<td style = "display:none">';
										echo $g["Username"];
										echo '</td>';
										echo '<td style = "display:none">';
										echo $g["Description"];
										echo '</td>';
										echo '<td class="edit-thread">';
										echo '<center><i class="cursor-pointer fa fa-pencil-square-o fa-lg"></i></center>';
										echo '</td>';
										echo '<td>';
										echo '<center><a href="/U5/admin-post.php?title='.$g['Title'].'&createdAt='.$g['CreatedAt'].'">';
										echo '<i class="fa fa-comments fa-lg"></i>';
										echo '</a></center>';
										echo '</td>';
										echo '</tr>';
									}	
								}else{
									$load_error = "Inga trådar tillagda i gästboken.";
								}
							}
							?>				
						</tbody>
					</table>
					<?php

					if(isset($load_error)){
						echo $load_error;
					}

					?>
					<br/>
				</div>
			</div>
		<form hidden enctype="multipart/form-data" action="" method="post">
		<div class="row">
			<div class="twelve columns">
				<h3>Redigera tråd</h3>
			</div>
		</div>
		<div class="row">
			<div class="four columns">
				<label for="title">Tråd:</label>
				<input class="u-full-width" type="text" name="title" id="threadTitle" required readonly>
			</div>
			<div class="four columns">
				<label for="CreatedAt">Datum:</label>
				<input class="u-full-width" type="text" name="created_at" id="threadCreatedAt" required readonly>
			</div>
			<div class="four columns">
				<label for="username">Användare:</label>
				<input class="u-full-width" type="text" name="username" id="threadUsername">
			</div>
		</div>
		<label for="threadDescription">Förklaring:</label>
		<textarea name="description" class="u-full-width" id="threadDescription"></textarea>	
		<input value="Uppdatera" name="thread-update" type="submit">
		<input value="Radera" name="thread-delete" type="submit">
		</form>
	</div>

<script src="js/admin-thread.js"></script>
</main>

<?php
include_once 'footer.php';
?>