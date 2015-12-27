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
						'<h5 class="courseInfoHeader">'. $c['CourseName'] .' - '. $c['CourseTeacher'] . '</h5>' .
						'<label>Kursdatum: <span class="label-value course-date">' . $c['CourseDate'] . '</span></label>' .
						'<label>Platser: <span class="label-value"><span class="numOfParticipants">' . $c['Participants'] . '</span>/10</span></label>' .
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
					<h3 id="formHeader"><h3>
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
		    <label>
		      <input disabled type="checkbox" id="reserveSpot">
		      <span class="label-body">Reservplats</span>
		    </label>
		    <input class="button-primary" type="submit" value="Skicka anmälan" name="submit-regToCourse">
		    <form>
	</div>		
</main>
<script>
$(document).ready(function(){
	$('.reg-course').click(function(elem){
		$('html, body').animate({ scrollTop: $(document).height() }, 500);
		$('form').show(700);

		var numberOfParticipants = $(this).parent().find('span .numOfParticipants').html();
		var numberOfParticipants = parseInt(numberOfParticipants);

		var name = $(this).parent().find('.courseInfoHeader').html();
		var time = $(this).parent().find('.course-date').html();

		if (numberOfParticipants >= 10){
			$('#reserveSpot').prop('checked', true);
		}

		console.log(name);
		console.log(time);

		$('#formHeader').html(name + ' - ' + time);

		
	})
})
</script>

<?php
include_once 'footer.php';
?>