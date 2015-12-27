<?php
include_once 'header.php';
include_once 'aside.php';
?>

<?php
	$result = query("SELECT * FROM ComingCourses");
	
	if ($result['err'] == null){
		$courses = $result['data'];
	} else {
		$coursesError = "Gick inte att läsa kommande kurses";
	}
?>

<main>
	<div class="container">
		<?php 
			if (!isset($coursesError)){
				foreach($courses as $key => $c){
					echo 
						'<div class="row">' .
						'<div class="twelve columns">' .
						'<h5>'. $c['CourseName'] .' - '. $c['CourseTeacher'] . '</h5>' .
						'<label>Kursdatum: <span class="label-value">' . $c['CourseDate'] . '</span></label>' .
						'<label>Platser: <span class="label-value">' . $c['Participants'] . '/10</span></label>' .
						'<label>Åldersgräns: <span class="label-value">' . $c['AgeOfDog'] . ' år</span></label>' .
						'<label>Kön: <span class="label-value">' . $c['Gender'] . '</span></label>' .
						'<label>Om kursen: </label>' .
						'<p>' . $c['CourseText'] . '<br><i>Förkunskaper: </i>' . $c['PriorKnowledge'] .'</p>' .
						'<span class="reg-course" id="course'. $key .'"><i class="fa fa-pencil-square-o fa-lg"></i><b> Registrera dig på kursen </b></span>' . 
						'</div>' .
						'</div>' .
						'<hr>';
				}
			} else {
				print_r($coursesError);
			}
		?>
		<form hidden>
			<div class="row">
				<div class="twelve columns">
					<span>Agility - Robert - 2015-10-12<span>
				</div>
			</div>
		    <div class="row">
		      <div class="six columns">
		        <label for="dogName">Hundnamn</label>
		        <input class="u-full-width" type="text" placeholder="Buster" id="dogName" name="dogName">
		      </div>
		      <div class="six columns">
		        <label for="ownerName">Ägarenamn</label>
		        <input class="u-full-width" type="text" placeholder="Anna Svensson" id="ownerName" name="ownerName">
		      </div>
		    </div>
		    <div class="row">
		      <div class="six columns">
		        <label for="dogAge">Hundålder</label>
		        <input class="u-full-width" type="number" min="0" step="1" value="0" id="dogAge" name="dogAge">
		      </div>
		      <div class="six columns">
		        <label for="dogGender">Hundkön</label>
		        <select class="u-full-width" id="dogGender" name="dogGender">
		          <option value="M">Hane</option>
		          <option value="F">Tik</option>
		        </select>
		      </div>
		    </div>
		    <label for="exampleMessage">Extra information</label>
		    <textarea class="u-full-width" placeholder="Hej! Min hund har problem med..." id="extraInfo" name="extraInfo"></textarea>
		    <label class="example-send-yourself-copy">
		      <input disabled type="checkbox" checked>
		      <span class="label-body">Reservplats</span>
		    </label>
		    <input class="button-primary" type="submit" value="Skicka anmälan" name="submit-regToCourse">
		    <form>
	</div>		
</main>
<script>
$(document).ready(function(){
	$('.reg-course').click(function(elem){
		console.log(elem.target);
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(700);
	})
})
</script>

<?php
include_once 'footer.php';
?>