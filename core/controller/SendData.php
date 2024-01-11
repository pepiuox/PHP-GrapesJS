<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class SendData
{
    public $baseurl;
    protected $conn;
    public $date;
	public $timestamp;
    public $gc;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->gc = new GetCodeDeEncrypt();
        $this->date = new DateTime();
        $this->timestamp = $this->date->format("Y-m-d H:i:s");
        $this->baseurl =
            "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);
        /* If forgot password form data is posted call forgotPassword() function. */
        if (isset($_POST["forgotUsername"])) {
            $this->forgotUsername();
        }
        /* If forgot PIN form data is posted call forgotPIN() function. */
        if (isset($_POST["forgotEmail"])) {
            $this->forgotEmail();
        }

        $this->includes();
    }
    private function includes()
    {
        require_once "../core/PHPMailer/src/Exception.php";
        require_once "../core/PHPMailer/src/PHPMailer.php";
        require_once "../core/PHPMailer/src/SMTP.php";
    }

    private function procheck($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES);
    }

    private function checkUsername($username)
    {
        $user = $this->gc->ende_crypter(
            "encrypt",
            $username,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $query = $this->conn->prepare(
            "SELECT username FROM uverify WHERE username=?"
        );
        $query->bind_param("s", $user);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        return $result->num_rows;
    }

    private function checkEmail($email)
    {
        $mail = $this->gc->ende_crypter(
            "encrypt",
            $email,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $query = $this->conn->prepare(
            "SELECT email FROM uverify WHERE email=?"
        );
        $query->bind_param("s", $mail);
        $query->execute();
        $result = $query->get_result();
        $query->close();
        return $result->num_rows;
    }

    private function forgotUsername()
    {
        if (isset($_POST["forgotUsername"])) {
			
			$recoveryphrase = $this->procheck($_POST["recoveryphrase"]);
            $email = $this->procheck($_POST["email"]);
            if ($this->checkEmail($email) === 1) {
                $usrnm = $this->gc->ende_crypter(
                    "encrypt",
                    $email,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $stmt = $this->conn->prepare(
                    "SELECT username FROM uverify WHERE email=? AND recovery_phrase=?"
                );
                $stmt->bind_param("ss", $usrnm, $recoveryphrase);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                $row = $result->fetch_assoc();
                $username = $row["username"];
                $myusr = $this->gc->ende_crypter(
                    "decrypt",
                    $username,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $usr = $this->gc->ende_crypter(
                    "decrypt",
                    USEREMAIL,
                    SECURE_TOKEN,
                    SECURE_HASH
                );
                $pas = $this->gc->ende_crypter(
                    "decrypt",
                    PASSMAIL,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $subject =
                    "We send you the username account with which you registered.";
                // This should be changed to an email that you would like to send activation e-mail from.
                $body = "<p>Hello " . $myusr . ".</b>";
                $body .=
                    "Your email is : <b>" .
                    $myusr .
                    "</b>" .
                    "\r\n" .
                    "We recommend you update your data for your security." .
                    "\r\n";

                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
                    $mail->isSMTP(); //Send using SMTP
                    $mail->Host = MAILSERVER; //Set the SMTP server to send through
                    $mail->SMTPAuth = true; //Enable SMTP authentication
                    $mail->Username = $usr; //SMTP username
                    $mail->Password = $pas; //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                    $mail->Port = PORTSERVER; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    $mail->setFrom($usr, SITE_NAME);
                    $mail->addAddress($email, $myusr);
                    $mail->isHTML(true);
                    $mail->Subject = $subject;

                    $mail->Body = $body;
                    $mail->send();

                    echo "<h4>The email message was sent.</h4><br><h5>Remember!</h4><br><h4> Your Username: " .
                        $myusr .
                        ".</h4>";
                } catch (Exception $e) {
                    echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
                }
            } else {
                echo "<h4>There is no user registered with the email.</h4>";
            }
        }
    }

    private function forgotEmail()
    {
        if (isset($_POST["forgotEmail"])) {
			$recoveryphrase = $this->procheck($_POST["recoveryphrase"]);
            $username = $this->procheck($_POST["username"]);
            if ($this->checkUsername($username) === 1) {
                $usrnm = $this->gc->ende_crypter(
                    "encrypt",
                    $username,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $stmt = $this->conn->prepare(
                    "SELECT email FROM uverify WHERE username=? AND recovery_phrase=?"
                );
                $stmt->bind_param("ss", $usrnm, $recoveryphrase);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                $row = $result->fetch_assoc();
                $myemail = $row["email"];

                $email = $this->gc->ende_crypter(
                    "decrypt",
                    $myemail,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $usr = $this->gc->ende_crypter(
                    "decrypt",
                    USEREMAIL,
                    SECURE_TOKEN,
                    SECURE_HASH
                );
                $pas = $this->gc->ende_crypter(
                    "decrypt",
                    PASSMAIL,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $subject =
                    "We send you the email account with which you registered.";
                // This should be changed to an email that you would like to send activation e-mail from.
                $body = "<p>Hello " . $username . ".</b>";
                $body .=
                    "Your email is : <b>" .
                    $email .
                    "</b>" .
                    "\r\n" .
                    "We recommend you update your data for your security." .
                    "\r\n";

                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
                    $mail->isSMTP(); //Send using SMTP
                    $mail->Host = MAILSERVER; //Set the SMTP server to send through
                    $mail->SMTPAuth = true; //Enable SMTP authentication
                    $mail->Username = $usr; //SMTP username
                    $mail->Password = $pas; //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                    $mail->Port = PORTSERVER; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    $mail->setFrom($usr, SITE_NAME);
                    $mail->addAddress($email, $username);
                    $mail->isHTML(true);
                    $mail->Subject = $subject;

                    $mail->Body = $body;
                    $mail->send();

                    echo "<h4>The email message was sent.</h4><br><h5>Remember!</h4><br><h4> Your Email: " .
                        $email .
                        ".</h4>";
                } catch (Exception $e) {
                    echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
                }
            } else {
                echo "<h4>There is no email registered with the username.</h4>";
            }
        }
    }
}
