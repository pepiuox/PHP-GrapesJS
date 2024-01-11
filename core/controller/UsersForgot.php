<?php

/**
 * Description of userForgot
 *
 * @author PePiuoX
 */
class UsersForgot {

    public $baseurl;
    protected $conn;
    public $date;
	public $timestamp;
    public $gc;
	

    public function __construct() {
       global $conn;
        $this->conn = $conn;
        $this->gc = new GetCodeDeEncrypt();
        $this->date = new DateTime();
        $this->timestamp = $this->date->format('Y-m-d H:i:s');
        $this->baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        /* If forgot password form data is posted call forgotPassword() function. */
        if (isset($_POST["forgotPassword"])) {
            $this->forgotPassword();
        }
        /* If forgot PIN form data is posted call forgotPIN() function. */
        if (isset($_POST["forgotPIN"])) {
            $this->forgotPIN();
        }
        if (isset($_POST["updatePassword"])) {
            $this->updatePassword();
        }
        if (isset($_POST["updatePIN"])) {
            $this->updatePIN();
        }
    }

    /*
     * Function forgotPassword()
     * If the email exists $forgot_password_key is created to database, after this user will be sent an reset key via e-mail.
     * This is the first step of password reset.
     */


    private function forgotPassword() {
// Require credentials for DB connection.
        if (isset($_POST['forgotPassword'])) {
			$recoveryphrase = $this->procheck($_POST["recoveryphrase"]);
            $email = trim($_POST['email']);
$usrm = $this->gc->ende_crypter(
                            "encrypt",
                            $email,
                            SECURE_TOKEN,
                            SECURE_HASH
                        );
// Require credentials for DB connection.
// Check if username or email is already taken.
            $stmt = $this->conn->prepare("SELECT username, email, mkhash FROM uverify WHERE email = ? AND recovery_phrase = ?");
            $stmt->bind_param("ss", $usrm, $recoveryphrase);
            $stmt->execute();
            $result = $stmt->get_result();           
            $stmt->close();
			
// If e-mail exists a key for password reset is created into database, after this an e-mail will be sent to user with link and the token key.
            if ($result->num_rows === 1) {
				$urw = $result->fetch_assoc();
				$hash = $urw['mkhash'];
                $uname = $urw['username'];
                $forgot_password_key = $this->enfKey();
                $inactive = 0;
                $stmt = $this->conn->prepare("UPDATE uverify SET password_key = ?, is_activated = ? WHERE email = ?");
                $stmt->bind_param("sis", $forgot_password_key, $inactive, $usrm);
                $stmt->execute();
                $stmt->close();

                $startT = date("Y-m-d H:i:s");
                $expireT = date('Y-m-d H:i:s', strtotime('+2 hour', strtotime($startT)));
                $stmt1 = $this->conn->prepare("INSERT INTO forgot_pass (username, email, password_key, expire) VALUES (?,?,?,?)");
                $stmt1->bind_param("ssss", $uname, $usrm, $forgot_password_key, $expireT);
                $stmt1->execute();
                $stmt1->close();

                $to = $email;
                $subject = "Reset your password";
                $from = 'contact@labemotion.net'; // Insert the e-mail from where you want to send the emails.
                $body = "Your reset key is: " . $forgot_password_key . "\n";
                $body .= '<a href="' . $this->baseurl . '/signin/password_reset.php?email=' . $email . '&key=' . $forgot_password_key . '&hash=' . $hash . '">Click to reset your password.</a>'; // Replace YOURWEBSITEURL with your own URL for the link to work.
                $headers = "From: " . $from . "\r\n";
                $headers .= "Reply-To: " . $from . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $success = mail($to, $subject, $body, $headers);
                if ($success === true) {
                    $_SESSION['SuccessMessage'] = 'Email has been sent.' . '\n' .
                            'The email contains the steps to follow to reset your password!';
                } else {
                    $_SESSION['ErrorMessage'] = 'Error sending a message to your email!';
                }
// Always give this message to prevent data colleting even if the e-mail doesn't exist(The password reset e-mail is only sent if the e-mail exists in database).
            } else {
                $_SESSION['ErrorMessage'] = 'Email does not exist or is incorrect!';
            }
        }
        $this->conn->close();
    }

    /* End forgotPassword() */

    private function forgotPIN() {
// Require credentials for DB connection.

        if (isset($_POST['forgotPIN'])) {
			$recoveryphrase = $this->procheck($_POST["recoveryphrase"]);
            $email = trim($_POST['email']);

// Check if username or email is already taken.
            $stmt = $this->conn->prepare("SELECT username, email, mkhash FROM uverify WHERE email = ?  AND recovery_phrase = ?");
            $stmt->bind_param("s", $email, $recoveryphrase);
            $stmt->execute();
            $result = $stmt->get_result();
            $urw = $result->fetch_assoc();
            $stmt->close();
            $hash = $urw['mkhash'];
            $uname = $urw['username'];
// If e-mail exists a key for password reset is created into database, after this an e-mail will be sent to user with link and the token key.
            if ($result->num_rows === 1) {

                $forgot_pin_key = $this->gc->getKeyCode();
                $inactive = 0;
                $stmt = $this->conn->prepare("UPDATE uverify SET pin_key = ?, is_activated = ? WHERE email = ?");
                $stmt->bind_param("sis", $forgot_pin_key, $inactive, $email);
                $stmt->execute();
                $stmt->close();

                $startT = date("Y-m-d H:i:s");
                $expireT = date('Y-m-d H:i:s', strtotime('+2 hour', strtotime($startT)));
                $stmt1 = $this->conn->prepare("INSERT INTO forgot_pin (username,email,pin_key,expire) VALUES (?,?,?,?)");
                $stmt1->bind_param("ssss", $uname, $email, $forgot_pin_key, $expireT);
                $stmt1->execute();
                $stmt1->close();

                $to = $email;
                $subject = "Reset PIN";
                $from = 'contact@labemotion.net'; // Insert the e-mail from where you want to send the emails.
                $body = "Your reset key is: " . $forgot_pin_key . "\n";
                $body .= '<a href="' . $this->baseurl . '/signin/pin_reset.php?email=' . $email . '&key=' . $forgot_pin_key . '&hash=' . $hash . '">Click here to reset your PIN</a>'; // Replace YOURWEBSITEURL with your own URL for the link to work.
                $headers = "From: " . $from . "\r\n";
                $headers .= "Reply-To: " . $from . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $success = mail($to, $subject, $body, $headers);
// Always give this message to prevent data colleting even if the e-mail doesn't exist(The password reset e-mail is only sent if the e-mail exists in database).
                if ($success === true) {
                    $_SESSION['SuccessMessage'] = 'Email has been sent.' . '\n' .
                            'The email contains the steps to follow to reset your PIN!';
                } else {
                    $_SESSION['ErrorMessage'] = 'Error sending a message to your email!';
                }
            } else {
                $_SESSION['ErrorMessage'] = 'Email does not exist or is incorrect.';
            }
        }
        $this->conn->close();
    }

    /* End forgotPIN() */

    /*
     * function updatePassword()
     * Get information from Password Reset Form, if the email & token key are correct, update the passwordin database.
     * This is the third and final step of password reset.
     */

    private function updatePassword() {
        if (isset($_POST['updatePassword'])) {
            if (isset($_GET['key']) && isset($_GET['hash'])) {
// Require credentials for DB connection.

                $forgotkey = htmlentities($_GET['key']);
                $hash = htmlentities($_GET['hash']);

                $chck = $this->conn->prepare("SELECT * FROM forgot_pass WHERE mkhash=? AND password_key=?");
                $chck->bind_param("ss", $hash, $forgotkey);
                $chck->execute();
                $okey = $chck->get_result();
                $chck->close();

                $ct = $okey->fetch_assoc();
                $nowTime = date("Y-m-d H:i:s");
                $et = $ct['expire'];

                if ($nowTime >= $et) {
                    $_SESSION['ErrorMessage'] = 'Time expired to reset your password.';
                    header('Location: index.php');
                }
                if (!empty($password3) && !empty($email)) {
// User input from Forgot password form(passwordResetForm.php).
                    $vemail = trim($_POST['vemail']);
                    $recoveryphrase = trim($_POST['recoveryphrase']);
                    $password2 = trim($_POST['password2']);
                    $password3 = trim($_POST['password3']);

// Check that both entered passwords match.
                    if ($password3 === $password2 && $vemail === $email) {

                        $stmt = $this->conn->prepare("SELECT * FROM uverify WHERE email=? AND mkhash=? AND password_key=? AND recovery_phrase=?");
                        $stmt->bind_param("sss", $email, $hash, $forgotkey, $recoveryphrase);
                        $stmt->execute();
                        $very = $stmt->get_result();

                        if ($very->num_rows === 1) {
                            $dt = $very->fetch_assoc();
                            $duv = $dt['iduv'];
                            $pin = $dt['mkpin'];

                            $ekey = $this->gc->randToken();
                            $eiv = $this->gc->randkey();
                            $enck = $this->gc->randHash();

                            $securing = $this->gc->ende_crypter('encrypt', $password2, $ekey, $eiv);
                            $cml = $this->gc->ende_crypter('encrypt', $email, $ekey, $eiv);

                            $clenkey = '';
                            $upd = $this->conn->query("UPDATE uverify SET password=?, mktoken=?, mkkey=?, mkhash=, password_key=? WHERE email=? AND recovery_phrase=? AND password_key=?");
                            $upd->bind_param("sssss", $securing, $ekey, $eiv, $$enck, $clenkey, $email, $recoveryphrase, $forgotkey);
                            $upd->execute();
                            $upd->close();
                            if ($upd === TRUE) {
                                $stmt = $this->conn->prepare("UPDATE users SET email = ?, password = ?  WHERE idUser=? AND mkpin=?");
                                $stmt->bind_param("sssi", $cml, $securing, $duv, $pin);
                                $stmt->execute();
                                $stmt->close();
                                header('Location: index.php');
                                exit;
                            }
                        } else {
                            $_SESSION['ErrorMessage'] = 'The data does not match to update your password.';
                        }
                    } else {
                        $_SESSION['ErrorMessage'] = 'Passwords do not match!';
                    }
                } else {
                    $_SESSION['ErrorMessage'] = 'Please fill in all the required fields.';
                }
                $this->conn->close();
            }
        }
    }

    /* End updatePassword() */

    /*
     * function updatePassword()
     * Get information from Password Reset Form, if the email & token key are correct, update the passwordin database.
     * This is the third and final step of password reset.
     */

    private function updatePIN() {
        if (isset($_POST['updatePIN'])) {
            if (isset($_GET['key']) && isset($_GET['hash'])) {
                // Require credentials for DB connection.

                $forgotkey = htmlentities($_GET['key']);
                $hash = htmlentities($_GET['hash']);

                $chck = $this->conn->prepare("SELECT * FROM forgot_pin WHERE AND mkhash=? AND password_key=?");
                $chck->bind_param("ss",$hash, $forgotkey);
                $chck->execute();
                $okey = $chck->get_result();
                $chck->close();

                $ct = $okey->fetch_assoc();
                $nowTime = date("Y-m-d H:i:s");
                $et = $ct['expire'];

                if ($nowTime >= $et) {
                    $_SESSION['ErrorMessage'] = 'Time expired to reset your PIN.';
                    header('Location: index.php');
                }
// User input from Forgot password form(passwordResetForm.php).
                $vemail = trim($_POST['vemail']);
                $recoveryphrase = trim($_POST['recoveryphrase']);

// Check that both entered passwords match.
                if ($vemail === $email) {
                    if (!empty($recoveryphrase) && !empty($email)) {
                        $very = $this->conn->query("SELECT * FROM uverify WHERE email='$email' AND pin_key='$forgotkey' AND recovery_phrase='$recoveryphrase'");

                        if ($very->num_rows === 1) {
                            $dt = $very->fetch_assoc();
                            $duv = $dt['iduv'];
                            $ekey = $dt['mktoken'];
                            $eiv = $dt['mkkey'];

                            $checkm = $this->ende_crypter('encrypt', $email, $ekey, $eiv);

                            $fnal = $this->conn->query("SELECT idUser, FROM users WHERE email='$checkm'");
                            $rt = $fnal->fetch_assoc();
                            if ($fnal->num_rows === 1) {
                                if ($duv === $rt['idUser']) {
                                    $npin = rand(100000, 999999);
                                    $clenkey = '';
                                    $upd = $this->conn->query("UPDATE uverify mkpin='$npin', pin_key='$clenkey' WHERE email='$email' AND recovery_phrase='$recoveryphrase'");
                                    if ($upd === TRUE) {
                                        $stmt = $this->conn->prepare("UPDATE users SET mkpin=?  WHERE idUser=? AND mkpin=?");
                                        $stmt->bind_param("ssi", $npin, $duv, $npin);
                                        $stmt->execute();
                                        $stmt->close();
                                        header('Location: index.php');
                                        exit;
                                    }
                                }
                            }
                        } else {
                            $_SESSION['ErrorMessage'] = 'The data does not match to update your PIN.';
                        }
                    } else {
                        $_SESSION['ErrorMessage'] = 'Please fill in all the required fields.';
                    }
                } else {
                    $_SESSION['ErrorMessage'] = 'Passwords do not match!';
                }
            }
            $this->conn->close();
        }
    }

    /* End updatePin() */
}
