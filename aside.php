<aside>
	<span class="aside-title">Nyheter</span>
	<?php
	$divOne = query("SELECT NewsTitle, Description, NewsImagePath, NewsLink FROM NewsFeed");
	$divData = $divOne['data'];
	foreach($divData as $key => $row){
		echo '<span id="newsfeed' . $key . '"></span>';
		echo '<div class="sidebar-item">';
		echo '<div class="news-title">';
		echo $row['NewsTitle'];
		echo '</div>';
		if($row['NewsImagePath'] !== null){
			echo '<img src="uploads/'.$row['NewsImagePath'].'" class="newsfeed-img" alt="">';
		}
		echo '<div class="news-desc">';
		echo $row['Description'];
		echo '</div>';
		if($row['NewsLink'] !== null){
			echo '<br><a href="'.$row['NewsLink'].'" class="news-more">Läs mer</a>';
		}
		echo '<hr class="newsfeed-sep">';
		echo '</div>';
	}			
	?>	
</aside>