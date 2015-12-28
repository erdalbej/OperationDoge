<?php
include_once 'header.php';
include_once 'aside.php';

$username = $_GET['username'];
$post_datetime = $_GET['datetime'];

$divOne = query("SELECT PostText, PostImagePath, Thread_Title, Thread_DateTime FROM Post WHERE Username='$username' AND DateTime='$post_datetime'");
	$divData = $divOne['data'];

foreach($divData as $key => $row){
	$post_content = $row['PostText'];
	$post_imagepath = $row['PostImagePath'];
	$post_threadtitle = $row['Thread_Title'];
	$post_threaddatetime = $row['Thread_DateTime'];
}
?>

<main>
	<h1>Redigera post</h1>
	<textarea name="post_content"><?php echo $post_content ?></textarea>
</main>

<?php
include_once 'footer.php';
?>