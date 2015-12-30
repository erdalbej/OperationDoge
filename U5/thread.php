<?php
include_once 'header.php';
include_once 'aside.php';

$allowed_ext = array('jpg');
$thread_title = NULL;
$thread_createdAt = NULL;

if(isset($_GET['title']) && isset($_GET['createdAt'])){
	$thread_title = $_GET['title'];
	$thread_createdAt = $_GET['createdAt'];
}

$resultThread = query('SELECT Title, CreatedAt, Description, Username FROM GuestbookThread WHERE Title = :title AND CreatedAt = :createdAt',
	array(
		':title' => $thread_title,
		':createdAt' => $thread_createdAt
	)
);

if ($resultThread['err'] == null){
	if (count($resultThread['data']) == 0){
		$threadError = "Tråden du söker finns inte";
	} 
}

if(isset($_POST['submit_post'])){
	if(strlen($_POST['post_text'] && !isset($threadError)) > 0 &&
	   strlen($_POST['username']) > 0){

		$username = $_POST['username'];
		$post_text = $_POST['post_text'];
		
		$file = $_FILES['post_image'];
		if(isset($file) && ($file['error'] == 1 || $file['size'] > 0)) {
			
			if (strlen($file['name']) == 0){ $postError = 'Saknar filnamn'; }

			$file_ext = explode('.', $file['name']);
			$file_ext = strtolower(end($file_ext));

			if (!in_array($file_ext, $allowed_ext)){ $postError = 'Endast .jpg filer är tillåtna, prova ladda up en anna bild.'; }
			if($file['error'] != UPLOAD_ERR_OK){
				if ($file['error'] == UPLOAD_ERR_INI_SIZE){ 
					$postError = 'Filen är för stor, 2mb stora filer är tillåtna'; 
				}else if($file['error'] > 1){
					$postError = 'Filen gick inte att ladda upp, prova igen!';
				}
			}

			if (!isset($postError)){
				$file_name_new = uniqid('', true) . '.' . $file_ext;
				$file_destination = 'uploads/' . $file_name_new;

				if(move_uploaded_file($file['tmp_name'], $file_destination)){
					$result = nonQuery("INSERT INTO Post (`Username`,`CreatedAt`,`PostText`, `PostImagePath`, `Thread_Title`, `Thread_CreatedAt`) VALUES (:username, now(), :post_text, :image, :thread_title, :thread_createdAt)", 
						array(
							":username" => $username, 
							":post_text" => $post_text, 
							":image" => $file_name_new, 
							":thread_title" => $thread_title, 
							":thread_createdAt" => $thread_createdAt
						)
					);
					
					if($result["err"] != null){
						$postError = 'Gick inte att spara ditt inlägg, prova igen!';
						unlink($file_destination);
					} 
				} else { $postError = 'Gick inte att flytta filen till servern, prova igen'; }
			}
		} else {
			$result = nonQuery("INSERT INTO Post (`Username`,`CreatedAt`,`PostText`, `Thread_Title`, `Thread_CreatedAt`) VALUES (:username,now(),:post_text,:thread_title,:thread_createdAt)", 
				array(
					":username" => $username, 
					":post_text" => $post_text, 
					":thread_title" => $thread_title, 
					":thread_createdAt" => $thread_createdAt
				)
			);
				
			if($result["err"] != null){ $postError = 'Gick ej att posta inlägg, prova igen!'; }
		}
	} else { $postError = 'Saknar värden för att posta inlägg.'; }
}

if (!isset($threadError)){
	$postsResult = query('SELECT Username, CreatedAt, PostText, PostImagePath, Thread_Title, Thread_CreatedAt FROM Post WHERE Thread_Title = :threadTitle AND Thread_CreatedAt = :threadCreatedAt ORDER BY CreatedAt ASC',
		array(
			':threadTitle' => $thread_title,
			':threadCreatedAt' => $thread_createdAt
		)
	);

	if ($postsResult['err'] == null){
		$posts = $postsResult['data'];
	} else {
		$postsError = 'Gick inte att läsa poster för denna tråd';
	}
}
?>
<main class="height-uv">
	<div class="container">
		<div class="row">
			<div class="twelve columns">				
				<?php
				
				if (!isset($threadError)){
					echo '<h1 class="post-threadtitle">'.$thread_title.'</h1>';
					echo '<h3 class="post-threaddatetime">'.$thread_createdAt.'</h3>';
					echo '<hr>';

					echo '<ul class="posts">';
					foreach($posts as $key => $row){
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
							echo '<img class="hide-img" src="uploads/'.$row['PostImagePath'].'" alt="" width="150" height="150">';
						}
						echo '</li>';
					}			
					echo '</ul>';
					?>

				<form enctype="multipart/form-data" method="POST" id="post-form">
					<div class="row">
						<div class="twelve columns">
							<h3>Kommentera</h3>
						</div>
					</div>
					<div class="row">
						<div class="six columns">
							<label for="username">Användarnamn:</label>
							<input required maxlength="255" class="u-full-width" type="text" name="username">
						</div>
						<div class="six columns">
							<label for="post_image">Infoga bild:</label>
							<input maxlength="255" class="u-full-width" type="file" name="post_image" accept=".jpg">
						</div>
					</div>
					<div class="row">
						<div class="twleve columns">
							<label for="post_text">Kommentar:</label>
							<textarea required maxlength="255" class="u-full-width" name="post_text"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="four columns">
							<input form="post-form" type="submit" name="submit_post" class="button-primary u-full-width" value="Kommentera">
						</div>
						<div class="eight columns">
							<?php
								if (isset($postError)){
									echo "<span style=\"color: red;\">$postError</span>";
								}
							?>
						</div>
					</div>
					
				</form>

				<?php
				} else {
					echo $threadError;
				}
				?>	
			</div>
		</div>
	</div>
	
</main>
<?php
include_once 'footer.php';
?>