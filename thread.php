<?php
include_once 'header.php';
include_once 'aside.php';

$thread_title = $_GET['title'];
$thread_createdAt = $_GET['createdAt'];

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
							$result = nonQuery("INSERT INTO Post (`Username`,`CreatedAt`,`PostText`, `PostImagePath`, `Thread_Title`, `Thread_CreatedAt`) VALUES (:username,now(),:post_text,:image,:thread_title,:thread_createdAt)", array(":username" => $username, ":post_text" => $post_text, ":image" => $file_name_new, ":thread_title" => $thread_title, ":thread_createdAt" => $thread_createdAt));
							
							if($result["err"] === null){
							
							}
						}
					}
				}
			}else{
	
				$result = nonQuery("INSERT INTO Post (`Username`,`CreatedAt`,`PostText`, `Thread_Title`, `Thread_CreatedAt`) VALUES (:username,now(),:post_text,:thread_title,:thread_createdAt)", array(":username" => $username, ":post_text" => $post_text, ":thread_title" => $thread_title, ":thread_createdAt" => $thread_createdAt));
				
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
				echo '<h3 class="post-threaddatetime">'.$thread_createdAt.'</h3>';
			?>
			
			<?php
				$divOne = query("SELECT Username, CreatedAt, PostText, PostImagePath, Thread_Title, Thread_CreatedAt FROM Post WHERE Thread_Title='$thread_title' AND Thread_CreatedAt='$thread_createdAt'");
				$divData = $divOne['data'];
		
				echo '<ul class="posts">';
				foreach($divData as $key => $row){
					echo '<li>';
					echo '<span id="news' . $key . '"></span>';
					echo '<span class="post-username"> <b>Användarnamn: &nbsp;</b>';
					echo $row['Username'];
					echo '</span>';
					echo '<span class="post-datetime"> <b>Datum: &nbsp;</b>';
					echo $row['CreatedAt'];
					echo '<br><br>';
					echo '</span>';
					echo '<br><br>';
					echo '<span class="post-posttext">';
					echo $row['PostText'];
					echo '</span>';
					echo '<br><br>';
					if($row['PostImagePath'] !== null){
						echo '<img src="uploads/'.$row['PostImagePath'].'" alt="" width="150" height="150">';
					}
					echo '</li>';
				}			
				echo '</ul>';
				?>

			<form enctype="multipart/form-data" method="POST" action="">
				<div class="row">
					<div class="twelve columns">
						<h3>Kommentera</h3>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<label for="username">Användarnamn:</label>
						<input class="u-full-width" type="text" name="username">
					</div>
					<div class="six columns">
						<label for="post_image">Infoga bild:</label>
						<input class="u-full-width" type="file" name="post_image" accept=".jpg">
					</div>
				</div>
				<div class="row">
					<div class="twleve columns">
						<label for="post_text">Kommentar:</label>
						<textarea class="u-full-width" name="post_text"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="four columns">
						<input type="submit" name="submit_post" class="button-primary u-full-width" value="Kommentera">
					</div>
					<div class="eight columns">
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