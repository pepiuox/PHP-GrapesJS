<!-- contact block -->

<div class="row">    
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
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        $nameErr = $emailErr = $answerboxErr = '';
        $name = $email = $message = $answerbox = '';

        if (isset($_POST['submitted'])) {

            function email_validation($str) {
                return (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str)) ? FALSE : TRUE;
            }

            function checkemail($str) {
                return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
            }

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

                $mail = new PHPMailer(true);

                try {

                    //Set PHPMailer to use SMTP.
                    $mail->isSMTP();
                    //If SMTP requires TLS encryption then set it
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output                  
                    $mail->Host = "mail.paoparedes.com"; //Set SMTP host name
                    //Set this to true if SMTP host requires authentication to send email
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                    //Set TCP port to connect to
                    $mail->Port = 465;
                    //Provide username and password
                    $mail->Username = "contact@paoparedes.com";
                    $mail->Password = "35U+Q*~T0V5G";
                    $mail->SMTPKeepAlive = true;
                    //From email address and name 
                    $mail->setFrom("info@paoparedes.com", "Paola Paredes");
                    $mail->addAddress($email, $name); //Recipient name is optional
                    $mail->addCC('paredes.paola@gmail.com');
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $body;
                    $mail->AltBody = "This is the plain text version of the email content";

                    $mail->send();

                    $errTyp = "danger";
                    $errMSG = "No se envio su email - Cannot send to your email: " . $mail->ErrorInfo;
                } catch (Exception $e) {
                    $errTyp = "success";
                    $errMSG = "<h3>Su email fue enviado - Your email has been send</h3>";
                }
            }
        }
        ?>
        <?php
        if (isset($errMSG)) {
            ?>
            <div class="col_full">
                <div class="alert alert-<?php echo ($errTyp == "success") ? "success" : $errTyp; ?>">
                    <?php echo $errMSG; ?>
                </div>
            </div>
            <?php
        }
        ?>
        <h3>Contact</h3>         
        <form class="br" method="POST">
            <div class="col-md-12">
                <label>Nombre:</label>
                <input id='name' name='name' type="text" value=""/>
                <span class="error">* <?php echo $nameErr; ?></span>
            </div>
            <div class="col-md-12">
                <label>Email:</label>
                <input id='email' name='email' type="text" value=""/>
                <span class="error">* <?php echo $emailErr; ?></span>
            </div>                        
            <div class="col-md-12">
                <label>Mensaje:</label>
                <textarea id='message' name="message"></textarea>
                <p style='width: 70px;'>47 - 9 = ?<input type="text" name="answerbox" id="answerbox" maxlength="2"  size="2"/></p>
                <span class="error">* <?php echo $answerboxErr; ?></span>
            </div>
            <div class="col-md-12">
                <input type="submit" value="Enviar"/>  
                <input type='hidden' value='1' name='submitted' />
            </div>
        </form>       
    </div>
</div>

