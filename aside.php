<aside>
	<span>NYHETER</span>
	<?php
	$divOne = query("SELECT NewsTitle, Description, NewsImagePath, NewsLink FROM NewsFeed");
	$divData = $divOne['data'];
	foreach($divData as $key => $row){
		echo '<span id="newsfeed' . $key . '"></span>';
		echo '<div class="sidebar-item">';
		echo '<div>';
		echo $row['NewsTitle'];
		echo '</div>';
		echo '<img src="uploads/'.$row['NewsImagePath'].'" alt="">';
		echo '<div>';
		echo $row['Description'];
		echo '</div>';
		echo '<br><a href="'.$row['NewsLink'].'" class="course-more">Läs mer</a>';
		echo '</div>';
		echo '<br><br>';
	}			
	?>	
</aside>