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
 * Description of change PIN
 */
class ChangePIN
{
    public $baseurl;
    protected $conn;
    private $iduv;
    private $ucode;
    private $usname;
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
        $this->usname = $_SESSION["username"];
        $this->level = $_SESSION["levels"];
        $this->mkhash = $_SESSION["hash"];
        if (isset($_POST["changePIN"])) {
            $this->UpdatePIN();
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

    private function UpdatePIN()
    {
        if (isset($_POST["changePIN"])) {
            $cemail = $this->procheck($_POST["email"]);
            $phrase = $this->procheck($_POST["recoveryphrase"]);
            $pin = $this->procheck($_POST["pin"]);

            $umail = $this->gc->ende_crypter(
                "encrypt",
                $cemail,
                SECURE_TOKEN,
                SECURE_HASH
            );
            $upin = $this->gc->ende_crypter(
                "encrypt",
                $pin,
                SECURE_TOKEN,
                SECURE_HASH
            );
//Select table uverify
            $stmt = $this->conn->prepare(
                "SELECT username, usercode, email, password, mktoken, mkkey, recovery_phrase FROM uverify WHERE iduv = ? AND usercode = ? AND email = ? AND mkhash = ? AND mkpin=?"
            );
            $stmt->bind_param(
                "sssss",
                $this->iduv,
                $this->ucode,
                $umail,
                $this->mkhash,
                $upin
            );
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
//If result is same to 1
            if ($result->num_rows === 1) {
                $urw = $result->fetch_assoc();
                $usrcode = $urw["usercode"];
                $username = $urw["username"];
                $email = $urw["email"];
                $password = $urw["password"];
                $secret_key = $urw["mktoken"];
                $secret_iv = $urw["mkkey"];
                $rvphrase = $urw["recovery_phrase"];
//Decrypt username
                $nusr = $this->gc->ende_crypter(
                    "decrypt",
                    $username,
                    SECURE_TOKEN,
                    SECURE_HASH
                );
//Decrypt recovery phrase
                $dcrp = $this->gc->ende_crypter(
                    "encrypt",
                    $phrase,
                    $secret_key,
                    $secret_iv
                );
//Compare data
                if ($dcrp === $rvphrase && $this->ucode === $usrcode) {
                    $ekey = $this->gc->randToken(); //Generate new token
                    $eiv = $this->gc->randkey(); //Generate new key
                    $enck = $this->gc->randHash(); //Generate new hash
//Encrypt username for update table users
                    $nwusr = $this->gc->ende_crypter(
                        "encrypt",
                        $nusr,
                        $ekey,
                        $eiv
                    );
//Encrypt email for update table users
                    $cml = $this->gc->ende_crypter(
                        "encrypt",
                        $cemail,
                        $ekey,
                        $eiv
                    );
//Encrypt password for update table uverify and users
                    $npass = $this->gc->ende_crypter(
                        "encrypt",
                        $password,
                        $ekey,
                        $eiv
                    );
//Generate new PIN code
                    $newpin = rand(100000, 999999);
//Encrypt PIN code for update table uverify and users
                    $nupin = $this->gc->ende_crypter(
                        "encrypt",
                        $newpin,
                        SECURE_TOKEN,
                        SECURE_HASH
                    );
//Encrypt recovery phrase for update table uverify
                    $nphr = $this->gc->ende_crypter(
                        "encrypt",
                        $phrase,
                        $ekey,
                        $eiv
                    );
//Update table uverify
                    $stmt1 = $this->conn->prepare(
                        "UPDATE uverify SET password=?, mktoken=?, mkkey=?, mkhash=?, mkpin = ?, recovery_phrase = ? WHERE iduv=? AND usercode = ? AND email=? AND mkhash = ? AND mkpin=?"
                    );
                    $stmt1->bind_param(
                        "sssssssssss",
                        $npass,
                        $ekey,
                        $eiv,
                        $enck,
                        $nupin,
                        $nphr,
                        $this->iduv,
                        $this->ucode,
                        $umail,
                        $this->mkhash,
                        $upin
                    );
                    $stmt1->execute();
                    $inst1 = $stmt1->affected_rows;
                    $stmt1->close();
//Update table users
                    $stmt2 = $this->conn->prepare(
                        "UPDATE users SET username = ?, email = ?, password = ?, mkpin = ? WHERE idUser = ? AND usercode = ? AND mkpin = ?"
                    );
                    $stmt2->bind_param(
                        "sssssss",
                        $nwusr,
                        $cml,
                        $npass,
                        $nupin,
                        $this->iduv,
                        $this->ucode,
                        $upin
                    );
                    $stmt2->execute();
                    $inst2 = $stmt2->affected_rows;
                    $stmt2->close();
//Update table users_profiles
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
                        $this->sPMailer($username, $email, $newpin);
                    } else {
                        $_SESSION["ErrorMessage"] =
                            "Error in updated the new PIN code.";
                    }
                } else {
                    $_SESSION["ErrorMessage"] =
                        "There was an error creating the new PIN code.";
                }
            } else {
                $_SESSION["ErrorMessage"] =
                    "The passphrase or PIN is incorrect.";
            }
        }
    }

    private function sPMailer($username, $email, $pin)
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
            "Your new PIN code is: <b>" .
            $pin .
            "</b>" .
            "\r\n" .
            "We recommend saving it, you do not need to access it with your password." .
            "\r\n";
        $body .= "Remember save your PIN code and recovery phrase always.</p>";
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

        if (!$mail->send()) {
            echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
        } else {
            echo "<h4>The email message was sent. Remember! Save this, your new PIN code is: " .
                $pin .
                " Thank you for updated your PIN</h4>";
        }
    }
    /* End forgotPIN() */
}