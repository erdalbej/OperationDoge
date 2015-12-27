<?php
include_once 'admin-header.php';

//Update thread
if(isset($_POST['thread-update'])){
	$returnMsgThreadUpdate = "Kunde inte uppdatera tråd.";

	if(strlen($_POST['title']) > 0 && strlen($_POST['datetime']) > 0){

		$title = $_POST['title'];
		$datetime = $_POST['datetime'];

		if(isset($_POST['username']) && isset($_POST['description'])){
			
			$username = $_POST['username'];
			$description = $_POST['description'];

			$result = nonQuery("UPDATE GuestbookThread SET `Username` = :username, `Description` = :description WHERE `Title` = :title AND `DateTime` = :datetime", array(":title" => $title, ":datetime" => $datetime, ":username" => $username, ":description" => $description));
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

	if(strlen($_POST['title']) > 0 && strlen($_POST['datetime']) > 0){

		$title = $_POST['title'];
		$datetime = $_POST['datetime'];

		$result = nonQuery("DELETE FROM GuestbookThread WHERE `Title` = :title AND `DateTime` = :datetime", array(":title" => $title, ":datetime" => $datetime));
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
	<form enctype="multipart/form-data" action="" method="post">
		<h3>Redigera Tråd</h3>
		<div class="row">
			<div class="three columns">
				<label for="title">Tråd:</label>
				<input type="text" name="title" id="threadTitle" readonly>
			</div>
			<div class="three columns">
				<label for="datetime">Datum:</label>
				<input type="text" name="datetime" id="threadDateTime" readonly>
			</div>
			<div class="three columns">
				<label for="username">Användare:</label>
				<input type="text" name="username" id="threadUsername">
			</div>
			<div class="three columns">
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label for="threadDescription">Förklaring:</label>
					<textarea name="description" class="u-full-width" id="threadDescription"></textarea>
			</div>
			<div class="six columns">
			</div>
		</div>
		<input value="Uppdatera" name="thread-update" type="submit">
		<input value="Radera" name="thread-delete" type="submit">
	</form>
	<div style="margin-bottom:30px;">
	<?php
		if(isset($returnMsgThreadUpdate)){
			echo $returnMsgThreadUpdate;
		}
		if(isset($returnMsgThreadDelete)){
			echo $returnMsgThreadDelete;
		}
	?>
	</div>
	
	<div class="row">
		<div class="eight columns">
			<h3>Välj tråd att redigera</h3>
			<div class="row">
			<div class="twelve columns">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Tråd</th>
							<th>Datum</th>
							<th>Användare</th>
							<th>Förklaring</th>
						</tr>
					</thead>
					<tbody id="Threadtable">
						<?php
						$divOne = query("SELECT Title, DateTime, Username, Description FROM GuestbookThread");
						$divData = $divOne['data'];
						foreach($divData as $key => $row){
							echo '<tr id="news' . $key . '">';
							echo '<td class="titleTd">';
							echo $row['Title'];
							echo '</td>';
							echo '<td class="dateTimeTd">';
							echo $row["DateTime"];
							echo '</td>';
							echo '<td class="Username">';
							echo $row["Username"];
							echo '</td>';
							echo '<td class="Description">';
							echo $row["Description"];
							echo '</td>';
							echo '</tr>';
						}			
						?>				
					</tbody>
				</table>
			</div>
		</div>

		</div>

		<div class="four columns">
			
		</div>
	</div>

<script src="js/thread.js"></script>
</main>

<?php
include_once 'footer.php';
?>