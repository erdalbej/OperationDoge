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
			<!--<?php
				$thread_title = $_GET['title'];
				$thread_datetime = $_GET['datetime'];

				$divOne = query("SELECT Username, DateTime, PostText, Thread_Title, Thread_DateTime FROM Post WHERE Thread_Title='$thread_title' AND Thread_DateTime='$thread_datetime'");
				$divData = $divOne['data'];
				foreach($divData as $key => $row){
					echo '<span id="news' . $key . '"></span>';
					echo '<span>';
					echo $row['Username'];
					echo '</span>';
				}			
				?>-->
		</div>
	</div>
</main>
<?php
include_once 'footer.php';
?>