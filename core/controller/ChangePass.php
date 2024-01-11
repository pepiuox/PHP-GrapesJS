<?php
//
//  This application develop by PePiuoX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * Description of change Password
 */
class ChangePass
{
    public $baseurl;
    protected $conn;
    private $iduv;
    private $ucode;
    private $level;
    private $mkhash;
    private $gc;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->gc = new GetCodeDeEncrypt();
        $this->baseurl = SITE_PATH;

        $this->iduv = $_SESSION["user_id"];
        $this->ucode = $_SESSION["access_id"];
        $this->level = $_SESSION["levels"];
        $this->mkhash = $_SESSION["hash"];

        if (isset($_POST["changePassword"])) {
            $this->ChangePassword();
        }
        $this->includes();
    }

    private function includes()
    {
        require_once "../PHPMailer/src/Exception.php";
        require_once "../PHPMailer/src/PHPMailer.php";
        require_once "../PHPMailer/src/SMTP.php";
    }

    public function procheck($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES);
    }

    private function ChangePassword()
    {
        if (isset($_POST["changePassword"])) {
            $vemail = $this->procheck($_POST["email"]);
            $cpassword = $this->procheck($_POST["cpassword"]);
            $recoveryphrase = $this->procheck($_POST["recoveryphrase"]);
            $password = $this->procheck($_POST["password"]);
            $password2 = $this->procheck($_POST["password2"]);

            // Check that both entered passwords match.
            if ($password === $password2) {
                //Encrypt email
                $umail = $this->gc->ende_crypter(
                    "encrypt",
                    $vemail,
                    SECURE_TOKEN,
                    SECURE_HASH
                );
                //Select data in table uverify
                $stmt = $this->conn->prepare(
                    "SELECT usercode, username, email, password, mktoken, mkkey, mkpin, recovery_phrase FROM uverify WHERE iduv = ? AND usercode = ? AND email=? AND mkhash=?"
                );
                $stmt->bind_param(
                    "ssss",
                    $this->iduv,
                    $this->ucode,
                    $umail,
                    $this->mkhash
                );
                $stmt->execute();
                $very = $stmt->get_result();
                $stmt->close();
                //If result is same to 1
                if ($very->num_rows === 1) {
                    $dt = $very->fetch_assoc();
                    $duv = $dt["iduv"];
                    $srname = $dt["username"];
                    $email = $dt["email"];
                    $chpss = $dt["password"];
                    $mkt = $dt["mktoken"];
                    $mkk = $dt["mkkey"];
                    $pin = $dt["mkpin"];
                    $rcphr = $dt["ecovery_phrase"];
                    //Decrypt username
                    $uname = $this->gc->ende_crypter(
                        "decrypt",
                        $srname,
                        SECURE_TOKEN,
                        SECURE_HASH
                    );
                    //Encrypt password
                    $cpass = $this->gc->ende_crypter(
                        "encrypt",
                        $cpassword,
                        $mkt,
                        $mkk
                    );
                    //Compare same password
                    if ($cpass === $chpss) {
                        $ekey = $this->gc->randToken();
                        $eiv = $this->gc->randkey();
                        $enck = $this->gc->randHash();
                        //Encrypt username for update table users
                        $nname = $this->gc->ende_crypter(
                            "decrypt",
                            $uname,
                            $ekey,
                            $eiv
                        );
                        //Encrypt password for update table uverify and users
                        $secpass = $this->gc->ende_crypter(
                            "encrypt",
                            $password2,
                            $ekey,
                            $eiv
                        );
                        //Encrypt email for update table users
                        $cml = $this->gc->ende_crypter(
                            "encrypt",
                            $vemail,
                            $ekey,
                            $eiv
                        );
                        //Encrypt recovery phrase for update table uverify
                        $rcp = $this->gc->ende_crypter(
                            "encrypt",
                            $recoveryphrase,
                            $ekey,
                            $eiv
                        );
                        //Update table uverify
                        $stmt1 = $this->conn->prepare(
                            "UPDATE uverify password = ?, mktoken = ?, mkkey = ?, mkhash = ?, recovery_phrase = ? WHERE idUser = ? AND email = ? AND password = ? AND recovery_phrase = ?"
                        );
                        $stmt1->bind_param(
                            "sssssssss",
                            $secpass,
                            $ekey,
                            $eiv,
                            $enck,
                            $rcp,
                            $this->iduv,
                            $email,
                            $cpass,
                            $rcphr
                        );
                        $stmt1->execute();
                        $inst1 = $stmt1->affected_rows;
                        $stmt1->close();
                        //Update table users
                        $stmt2 = $this->conn->prepare(
                            "UPDATE users SET username = ?, email = ?, password = ?  WHERE idUser = ? AND mkpin = ?"
                        );
                        $stmt2->bind_param(
                            "sssss",
                            $nname,
                            $cml,
                            $secpass,
                            $duv,
                            $pin
                        );
                        $stmt2->execute();
                        $inst2 = $stmt2->affected_rows;
                        $stmt2->close();
                        //Update table users profiles
                        $stmt3 = $this->conn->prepare(
                            "UPDATE users_profiles SET mkhash = ? WHERE idp = ? AND usercode = ? AND mkhash = ?"
                        );
                        $stmt3->bind_param(
                            "ssss",
                            $enck,
                            $this->iduv,
                            $this->ucode,
                            $this->mkhash
                        );
                        $stmt3->execute();
                        $inst3 = $stmt3->affected_rows;
                        $stmt3->close();
                        //Compare affected rows
                        if ($inst1 === 1 && $inst2 === 1 && $inst3 === 1) {
                            $this->sPMailer($username, $email);
                        } else {
                            $_SESSION["ErrorMessage"] =
                                "Error in updated the new password.";
                        }
                    } else {
                        $_SESSION["ErrorMessage"] =
                            "The data does not match to update your password.";
                    }
                } else {
                    $_SESSION["ErrorMessage"] =
                        "The data does not match to update your password.";
                }
            } else {
                $_SESSION["ErrorMessage"] = "Passwords do not match!";
            }
        }
    }
    private function sPMailer($username, $email)
    {
        $usrnm = $this->gc->ende_crypter(
            "decrypt",
            $username,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $usrml = $this->gc->ende_crypter(
            "decrypt",
            $email,
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

        $subject = "Your PIN code is changed.";
        // This should be changed to an email that you would like to send activation e-mail from.
        $body = "<p>Hello " . $usrnm . ".</b>";
        $body .=
            "Your new password is updated.</b>" .
            "\r\n" .
            "We recommend saving it, your password." .
            "\r\n";
        $body .= "Remember always to keep your passwords in a safe place.</p>";
        $mail = new PHPMailer(true);
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
        $mail->addAddress($usrml, $usrnm);
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $mail->Body = $body;
        $mail->send();

        if (!$mail->send()) {
            echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
        } else {
            echo "<h4>The email message was sent. Remember! Save this, your new PIN code is: " .
                $pin .
                " Thank you for updated your PIN</h4>";
        }
    }
}