<!-- contact block -->

<div class="contact">
	<div class="col-md-5">
		<div class="infocontact">
			<?php
			$resultc = $conn->query("SELECT value FROM config");
			$array = array();
			while ($rowt = $resultc->fetch_row()) {
				$array[] = $rowt;
			}
			echo '<p>';
			echo SITE_NAME . '<br>';
			echo '<a href="mailto:' . SITE_EMAIL . '">' . SITE_EMAIL . '</a><br />';
			echo 'Telf: ' . PHONE_CONTACT;
			echo '</p>';
			?>
		</div>
	</div>
	<div class="col-md-7">
		<?php

		function email_validation($str) {
			return (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str)) ? FALSE : TRUE;
		}

		function checkemail($str) {
			return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
		}

		$nameErr = $emailErr = $answerboxErr = '';
		$name = $email = $message = $answerbox = '';
		if (isset($_POST['submitted'])) {

			$name = protect($_POST["name"]);
			$email = protect($_POST["email"]);
			$message = protect($_POST["message"]);
			$answerbox = $_POST["answerbox"];

			if (empty($_POST["name"]) && strlen($name) < 2) {
				$nameErr = "Nombre es requirido";
			} else {
				// check if name only contains letters and whitespace
				if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
					$nameErr = "Solo letras y espacios en blanco";
				}
			}
			if (empty($_POST["email"])) {
				$emailErr = "Email es requirido";
			} else {
				// check if e-mail address is well-formed

				if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) && !email_validation($email) && !checkemail($email)) {
					$emailErr = "Invalido el formato de email";
				}
			}
			if (empty($_POST["message"]) && strlen($message) < 10) {
				$emailErr = "Escriba una nota en el mensaje";
			}
			if ($answerbox != 38) {
				$answerboxErr = "Calcule la suma correctamente.";
			}
			if (isset($_POST['name']) && checkemail($email) && $answerbox === 38) {
				// put your email address here

				$subject = 'EMAIL DE LA WEB';
				// prepare a "pretty" version of the message
				$body = "Este manesaje a sido enviado desde la página web:" . "\n";
				$body .= "Nombre:  " . $name . "\n";
				$body .= "E-Mail: " . $email . "\n";
				$body .= "Mensaje: " . $message . "\n";

				// Use the submitters email if they supplied one
				// (and it isn't trying to hack your form).
				// Otherwise send from your email address.
				// finally, send the message
				// Always set content-type when sending HTML email

				$header = "MIME-Version: 1.0\r\n";
				$header .= "Content-type: text/html;charset=UTF-8\r\n";
				// More headers
				$headers .= 'From: ' . $email . "\r\n";
				$headers .= 'Reply-To: ' . $email . "\r\n";

				if (mail(SITE_EMAIL, $subject, $body, $headers)) {
					echo "<h4>Correo electrónico enviado con éxito - Mail succesuffully sent!</h4>";
				} else {
					echo "Mailer Error.";
				}
			}
		}
		?>
		<?php
		if ($_SESSION["language"] === '1') {
			echo '<h3>Contact</h3>
		<form class="br" method="POST">
			<div class="col-md-12">
				<label>Name:</label>
				<input id="name" name="name" type="text" value=""/>
				<span class="error">* ' . $nameErr . '</span>
			</div>
			<div class="col-md-12">
				<label>Email:</label>
				<input id="email" name="email" type="text" value=""/>
				<span class="error">* ' . $emailErr . '</span>
			</div>
			<div class="col-md-12">
				<label>Message:</label>
				<textarea id="message" name="message"></textarea>
				<p style="width: 70px;">47 - 9 = ?<input type="text" name="answerbox" id="answerbox" maxlength="2"  size="2"/></p>
				<span class="error">* ' . $answerboxErr . '</span>
			</div>
			<div class="col-md-12">
				<input type="submit" value="Send"/>
				<input type="hidden" value="1" name="submitted" />
			</div>
		</form> ';
		} else {
			echo '<h3>Contacto</h3>
		<form class="br" method="POST">
			<div class="col-md-12">
				<label>Nombre:</label>
				<input id="name" name="name" type="text" value=""/>
				<span class="error">* ' . $nameErr . '</span>
			</div>
			<div class="col-md-12">
				<label>Email:</label>
				<input id="email" name="email" type="text" value=""/>
				<span class="error">* ' . $emailErr . '</span>
			</div>
			<div class="col-md-12">
				<label>Mensaje:</label>
				<textarea id="message" name="message"></textarea>
				<p style="width: 70px;">47 - 9 = ?<input type="text" name="answerbox" id="answerbox" maxlength="2"  size="2"/></p>
				<span class="error">* ' . $answerboxErr . '</span>
			</div>
			<div class="col-md-12">
				<input type="submit" value="Enviar"/>
				<input type="hidden" value="1" name="submitted" />
			</div>
		</form> ';
		}
		?>

	</div>
</div>
