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
			$result["data"];

			if($result["err"] === NULL){
				$returnMsgThreadUpdate = "Tråd uppdaterad"; 
			}else{
				$returnMsgThreadUpdate = "Kunde inte uppdatera tråd.";
			}
		}
	}
}

//Delete thread
if(isset($_POST['thread-delete'])){
	$returnMsgThreadDelete = "Kunde inte tabort tråd.";

	if(strlen($_POST['title']) > 0 && strlen($_POST['created_at']) > 0){

		$title = $_POST['title'];
		$created_at = $_POST['created_at'];

		$result = nonQuery("DELETE FROM GuestbookThread WHERE `Title` = :title AND `CreatedAt` = :created_at", array(":title" => $title, ":created_at" => $created_at));
		$result["data"];

		if($result["err"] === NULL){
			$returnMsgThreadDelete = "Tråd raderad."; 
		}else{
			$returnMsgThreadDelete = "Kunde inte tabort tråd.";
		}
	}
}
?>

<main>
	<div class="container">
		<div class="row">
			<div class="twelve columns">
				<h5>Gästbok - Redigera trådar</h5>
				<p>För att redigera en tråd, klicka på den i listan för att få upp Trådinformaion. För att redigera en posts inlägg, klicka på hyperlänken "Redigera inlägg" längst ut till höger i tabellen.</p>
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
								<th>Användare</th>
								<th>Redigera inlägg</th>
							</tr>
						</thead>
						<tbody id="Threadtable">
							<?php
							$divOne = query("SELECT Title, CreatedAt, Username, Description FROM GuestbookThread");
							$divData = $divOne['data'];
							foreach($divData as $key => $row){
								echo '<tr id="threads' . $key . '">';
								echo '<td>';
								echo $row['Title'];
								echo '</td>';
								echo '<td>';
								echo $row["CreatedAt"];
								echo '</td>';
								echo '<td>';
								echo $row["Username"];
								echo '</td>';
								echo '<td class="Description" style = "display:none">';
								echo $row["Description"];
								echo '</td>';
								echo '<td>';
								echo '<a href="/operationdoge/admin-post.php?title='.$row['Title'].'&createdAt='.$row['CreatedAt'].'">';
								echo 'Redigera inlägg';
								echo '</a>';
								echo '</td>';
								echo '</tr>';
							}			
							?>				
						</tbody>
					</table>
					<br/>
				</div>
			</div>
		<div id="returnMsg" style="margin-bottom:20px;">
		<?php
			if(isset($returnMsgThreadUpdate)){
				echo $returnMsgThreadUpdate;
			}
			if(isset($returnMsgThreadDelete)){
				echo $returnMsgThreadDelete;
			}
		?>
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
				<input class="u-full-width" type="text" name="title" id="threadTitle" readonly>
			</div>
			<div class="four columns">
				<label for="CreatedAt">Datum:</label>
				<input class="u-full-width" type="text" name="created_at" id="threadCreatedAt" readonly>
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