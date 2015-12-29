<aside>
	<span class="aside-title">Nyheter</span>
	<?php
	$result = query("SELECT NewsTitle, CreatedAt, Description, NewsImagePath, NewsLink FROM NewsFeed ORDER BY CreatedAt LIMIT 3");
	$newsFeedData = $result['data'];
	foreach($newsFeedData as $key => $n){
		echo '<span id="newsfeed' . $key . '"></span>';
		echo '<div class="sidebar-item">';
		echo '<div class="news-title">';
		echo $n['NewsTitle'];
		echo '</div>';
		if($n['NewsImagePath'] !== null){
			echo '<img src="uploads/'.$n['NewsImagePath'].'" class="newsfeed-img" alt="">';
		}
		echo '<div class="news-desc">';
		echo $n['Description'];
		echo '</div>';
		if(strlen($n['NewsLink']) > 0){
			echo '<br><a href="'.$n['NewsLink'].'" class="news-more">Läs mer</a>';
		}
		echo '<hr class="newsfeed-sep">';
		echo '</div>';
	}			
	?>	
</aside>