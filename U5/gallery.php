<?php
include_once 'header.php';
include_once 'aside.php';
?>
<main class="height-uv">
	<section id="gallery">
		<p>
			<?php
			$result = query("SELECT ImagePath, ImageTitle FROM ImageGallery");

			if ($result['err'] == null){
				$galleryData = $result['data'];
				foreach($galleryData as $key => $g){
					echo '<span id="gallery' . $key . '"></span>';
					echo '<a class="fancybox" href="uploads/'.$g['ImagePath'].'" data-fancybox-group="gallery" title="'.$g['ImageTitle'].'"><img class="gallery-img" src="uploads/'.$g['ImagePath'].'" width="200" height="200" alt="" /></a>';
				}
			} else {
				echo '<span>Det gick inte att läsa in bilder, prova igen.</span>';
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

