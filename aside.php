<aside>
	<span>NYHETER</span>

	<h3>Hundkurser</h3>

	<?php
	$divOne = query("SELECT CourseName, CourseText FROM DogCourse LIMIT 3");
	$divData = $divOne['data'];
	foreach($divData as $key => $row){
		echo '<span id="dogcourses' . $key . '"></span>';
		echo '<div class="sidebar-item">';
		echo '<span class="course-name">';
		echo $row['CourseName'];
		echo '</span>';
		echo '<p class="course-desc">';
		echo $row['CourseText'];
		echo '</p>';
		echo '<span class="course-more">Läs mer</span>';
		echo '<br><br>';
	}			
	?>	

	<div class="sidebar-item">
		<img src="#" alt="">
		<a href="#">text that will be linked or?</a>
	</div>

	<div class="sidebar-item">
		<img src="#" alt="">
		<a href="#">text that will be linked or? #2</a>
	</div>

</aside>