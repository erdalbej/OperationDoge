<?php
include_once 'header.php';
include_once 'aside.php';

$thread_title = $_GET['title'];
$thread_datetime = $_GET['datetime'];

if(isset($_POST['submit_post'])){
	if(strlen($_POST['post_text']) > 0){

		$username = $_POST['username'];
		$post_text = $_POST['post_text'];
		
		if(isset($_FILES['post_image'])) {
			$file = $_FILES['post_image'];

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];

			$file_ext = explode('.', $file_name);
			$file_ext = strtolower(end($file_ext));

			$allowed_ext = array('jpg');

			if(in_array($file_ext, $allowed_ext)){

				if($file_error === 0){
					//2mb
					if($file_size < 2097152){

						$file_name_new = uniqid('', true) . '.' . $file_ext;
						$file_destination = 'uploads/' . $file_name_new;

						if(move_uploaded_file($file_tmp, $file_destination)){
							$result = nonQuery("INSERT INTO Post (`Username`,`DateTime`,`PostText`, `PostImagePath`, `Thread_Title`, `Thread_DateTime`) VALUES (:username,now(),:post_text,:image,:thread_title,:thread_datetime)", array(":username" => $username, ":post_text" => $post_text, ":image" => $file_name_new, ":thread_title" => $thread_title, ":thread_datetime" => $thread_datetime));
							
							if($result["err"] === null){
							
							}
						}
					}
				}
			}else{
	
				$result = nonQuery("INSERT INTO Post (`Username`,`DateTime`,`PostText`, `Thread_Title`, `Thread_DateTime`) VALUES (:username,now(),:post_text,:thread_title,:thread_datetime)", array(":username" => $username, ":post_text" => $post_text, ":thread_title" => $thread_title, ":thread_datetime" => $thread_datetime));
				
				if($result["err"] === null){
				
				}
			}
		}

	}

}

?>
<main>
	<div class="row">
		<div class="twelve columns">
			<?php 
				echo '<h1 class="post-threadtitle">'.$thread_title.'</h1>';
				echo '<h3 class="post-threaddatetime">'.$thread_datetime.'</h3>';
			?>
			
			<?php
				$divOne = query("SELECT Username, DateTime, PostText, PostImagePath, Thread_Title, Thread_DateTime FROM Post WHERE Thread_Title='$thread_title' AND Thread_DateTime='$thread_datetime'");
				$divData = $divOne['data'];
		
				echo '<ul class="posts">';
				foreach($divData as $key => $row){
					echo '<li>';
					echo '<span id="news' . $key . '"></span>';
					echo '<span class="post-username"> <b>Användare: &nbsp;</b>';
					echo $row['Username'];
					echo '</span>';
					echo '<span class="post-datetime"> <b>Datum: &nbsp;</b>';
					echo $row['DateTime'];
					echo '<br><br>';
					echo '</span>';
					echo '<br><br>';
					echo '<span class="post-posttext">';
					echo $row['PostText'];
					echo '</span>';
					echo '<a href="/edit-post.php?username='.$row['Username'].'&datetime='.$row['DateTime'].'">';
					echo 'edit';
					echo '</a>';
					echo '<br><br>';
					if($row['PostImagePath'] !== null){
						echo '<img src="uploads/'.$row['PostImagePath'].'" alt="" width="200" height="200">';
					}
					echo '</li>';
				}			
				echo '</ul>';
				?>
		<form enctype="multipart/form-data" method="POST" action="">
				<div class="row">
					<div class="three columns">
						<label for="username">Namn:</label>
						<input type="text" name="username">
					</div>
					<div class="three columns">
						<label for="post_text">Kommentar:</label>
						<textarea name="post_text" id="" cols="30" rows="10"></textarea>
					</div>
					<div class="three columns">
						<label for="post_image">Infoga bild:</label>
						<input type="file" name="post_image" accept=".jpg">
					</div>
					<div class="three columns">
						<label for="submit_post">&nbsp;</label>
						<input type="submit" name="submit_post" class="button-primary" value="Kommentera">
					</div>
				</div>
				<hr>
			</form>
		</div>
	</div>
</main>
<?php
include_once 'footer.php';
?>