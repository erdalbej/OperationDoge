<?php
include_once 'header.php';
include_once 'aside.php';
?>

<?php
if (isset($_POST['message-submit'])){
	if (strlen($_POST['email']) > 0 && 
		strlen($_POST['description']) > 0 &&
		strlen($_POST['message']) > 0){

		$headers = 'From: ' . $_POST['email'];

		if ($_POST['sendToSender']){
			$headers = $headers . "\r\n" . "CC: " . $_POST['email'];
		}

		$sent = mail(
			'robert.barlin@gmail.com', 
			$_POST['description'],
			$_POST['message'],
			$headers
		);
			
		if ($sent){
			$sendMessageSuccess = 'Meddelande skickat!';
		} else { $sendMessageError = 'Gick inte att skicka meddelandet, prova igen.'; }
	} else { $sendMessageError = 'Saknar värden för att skicka mail.'; }
}
?>

<main>
	<div class="container">

		<div class="row contactRow">
			<div class="twelve columns">
				<h5>Kontaktinfo</h5>
				<hr>
				<label>Namn </label>
				<span>Perry</span>
			</div>
		</div>

		<div class="row contactRow">
			<div class="twelve columns">
				<label>Adress </label>
				<span><a href="https://goo.gl/maps/Lhj8XogtHwq">Ole Römers väg 6, Lund</a></span>
			</div>
		</div>

		<div class="row contactRow">
			<div class="twelve columns">
				<label>Telefon </label>
				<span><a href="tel:+46555555555">+46 555 555 555</a></span>
			</div>
		</div>

		<div class="row contactRow">
			<div class="twelve columns">
				<label>Mail </label>
				<span><a href="mailto:john.doe@mail.com">batuulis@mail.com</a></span>
				<hr>
				<h5>Kontakta oss</h5>
			</div>
		</div>
			<form method="POST">		
			  <div class="row">
			    <div class="six columns">
			      <label for="email">E-post *</label>
			      <input required class="u-full-width" type="email" placeholder="din-epost@mailbox.com" id="email" name="email">
			    </div>
			    <div class="six columns">
			      <label for="description">Ämne *</label>
			      <input required class="u-full-width" type="text" placeholder="Ämne" id="description" maxlength="255" name="description">
			    </div>
			  </div>
			  <label for="message">Meddelande *</label>
			  <textarea required class="u-full-width" placeholder="Skriv ditt meddelande här... " id="message" maxlength="255" name="message"></textarea>
				<label>
				    <input type="checkbox" name="sendToSender" value="Yes" checked>
				    <span class="label-body">Skicka kopia till mig</span>
			 	</label>
			  <input type="submit" value="Skicka" name="message-submit">
				<?php
				  	if (isset($sendMessageError)){
				  		print_r($sendMessageError);
				  	}

				  	if (isset($sendMessageSuccess)){
				  		print_r($sendMessageSuccess);
				  	}
				 ?>
			</form>

		<div class="row mapRow">
			<div class="twelve columns">
				<iframe 
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2247.7612934265917!2d13.211435015825314!3d55.71052090271986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x465397b5eef19a89%3A0x50d854d198f468dc!2sOle+R%C3%B6mers+v%C3%A4g+6%2C+223+63+Lund!5e0!3m2!1ssv!2sse!4v1451120672607" 
					width="600" 
					height="400" 
					frameborder="0" 
					style="border:0" allowfullscreen>
				</iframe>
			</div>
		</div>
	</div>
</main>

<?php
include_once 'footer.php';
?>
