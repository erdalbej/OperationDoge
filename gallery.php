<?php
include_once 'header.php';
?>
<main>
	<section id="gallery">
		<p>
			<?php
			$divOne = query("SELECT ImagePath, ImageTitle FROM ImageGallery");
			$divData = $divOne['data'];
			foreach($divData as $key => $row){
				echo '<tr id="gallery' . $key . '">';
				echo '<a class="fancybox" href="uploads/'.$row['ImagePath'].'" data-fancybox-group="gallery" title="'.$row['ImageTitle'].'"><img class="gallery-img" src="uploads/'.$row['ImagePath'].'" width="200" height="200" alt="" /></a>';
			}			
			?>
		</p>
	</section>
</main>

<script type="text/javascript">
$(document).ready(function() {
	$('.fancybox').fancybox();
});
</script>
<script type="text/javascript" src="js/jquery.fancybox.js"></script>
<?php
include_once 'footer.php';
?>

