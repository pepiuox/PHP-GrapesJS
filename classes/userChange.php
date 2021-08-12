<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of userForgot
 *
 * @author PePiuoX
 */
class userChange {

    public $baseurl;
   private $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $this->baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

        if (isset($_POST["changePassword"])) {
            $this->updatePassword();
        }
        if (isset($_POST["changePIN"])) {
            $this->updatePIN();
        }
    }

    /*
     * function updatePassword()
     * Get information from Password Reset Form, if the email & token key are correct, update the passwordin database.
     * This is the third and final step of password reset.
     */

    private function updatePassword() {
        if (isset($_POST['updatePassword'])) {
            if (isset($_GET['email']) && isset($_GET['key']) && isset($_GET['hash'])) {
// Require credentials for DB connection.

                $email = htmlentities($_GET['email']);
                $changekey = htmlentities($_GET['key']);
                $hash = htmlentities($_GET['hash']);

                $chck = $this->connection->prepare("SELECT * FROM change_pass WHERE email=? AND mkhash=? AND password_key=?");
                $chck->bind_param("sss", $email, $hash, $changekey);
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

                        $stmt = $this->connection->prepare("SELECT * FROM uverify WHERE email=? AND mkhash=? AND password_key=? AND recovery_phrase=?");
                        $stmt->bind_param("sss", $email, $hash, $changekey, $recoveryphrase);
                        $stmt->execute();
                        $very = $stmt->get_result();

                        if ($very->num_rows === 1) {
                            $dt = $very->fetch_assoc();
                            $duv = $dt['iduv'];
                            $pin = $dt['mkpin'];

                            function randHash($len = 32) {
                                return substr(sha1(openssl_random_pseudo_bytes(21)), - $len);
                            }

                            function randKey($len = 32) {
                                return substr(sha1(openssl_random_pseudo_bytes(13)), - $len);
                            }

                            function encKey($len = 32) {
                                return substr(sha1(openssl_random_pseudo_bytes(17)), - $len);
                            }

                            $ekey = randHash();
                            $eiv = randkey();
                            $enck = enckey();

                            define("ENCRYPT_METHOD", "AES-256-CBC");
                            define("SECRET_KEY", $ekey);
                            define("SECRET_IV", $eiv);

                            function ende_crypter($action, $string) {
                                $output = false;
                                $encrypt_method = ENCRYPT_METHOD;
                                $secret_key = SECRET_KEY;
                                $secret_iv = SECRET_IV;
// hash
                                $key = hash('sha256', $secret_key);

// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                                $iv = substr(hash('sha256', $secret_iv), 0, 16);
                                if ($action == 'encrypt') {
                                    $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
                                } else if ($action == 'decrypt') {
                                    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                                }
                                return $output;
                            }

                            $securing = ende_crypter('encrypt', $password2);
                            $cml = ende_crypter('encrypt', $email);
                            $clenkey = '';
                            $upd = $this->connection->query("UPDATE uverify password=?, mktoken=?, mkkey=?, mkhash=, password_key=? WHERE email=? AND recovery_phrase=? AND password_key=?");
                            $upd->bind_param("sssss", $securing, $ekey, $eiv, $$enck, $clenkey, $email, $recoveryphrase, $changekey);
                            $upd->execute();
                            $upd->close();
                            if ($upd === TRUE) {
                                $stmt = $this->connection->prepare("UPDATE users SET email = ?, password = ?  WHERE idUser=? AND mkpin=?");
                                $stmt->bind_param("sssi", $cml, $securing, $duv, $pin);
                                $stmt->execute();
                                $stmt->close();
                                header('Location: index.php');
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
                $this->connection->close();
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
            if (isset($_GET['email']) && isset($_GET['key']) && isset($_GET['hash'])) {
                // Require credentials for DB connection.


                $email = htmlentities($_GET['email']);
                $changekey = htmlentities($_GET['key']);
                $hash = htmlentities($_GET['hash']);

                $chck = $this->connection->prepare("SELECT * FROM change_pin WHERE email=? AND mkhash=? AND password_key=?");
                $chck->bind_param("sss", $email, $hash, $changekey);
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
                        $very = $this->connection->query("SELECT * FROM uverify WHERE email='$email' AND pin_key='$changekey' AND recovery_phrase='$recoveryphrase'");

                        if ($very->num_rows === 1) {
                            $dt = $very->fetch_assoc();
                            $duv = $dt['iduv'];

                            define("ENCRYPT_METHOD", "AES-256-CBC");
                            define("SECRET_KEY", $dt['mktoken']);
                            define("SECRET_IV", $dt['mkkey']);

                            function ende_crypter($action, $string) {
                                $output = false;
                                $encrypt_method = ENCRYPT_METHOD;
                                $secret_key = SECRET_KEY;
                                $secret_iv = SECRET_IV;
// hash
                                $key = hash('sha256', $secret_key);
// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                                $iv = substr(hash('sha256', $secret_iv), 0, 16);
                                if ($action == 'encrypt') {
                                    $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
                                } else if ($action == 'decrypt') {
                                    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                                }
                                return $output;
                            }

                            $checkm = ende_crypter('encrypt', $email);

                            $fnal = $this->connection->query("SELECT idUser, FROM users WHERE email='$checkm'");
                            $rt = $fnal->fetch_assoc();
                            if ($fnal->num_rows === 1) {
                                if ($duv === $rt['idUser']) {
                                    $npin = rand(000000, 999999);
                                    $clenkey = '';
                                    $upd = $this->connection->query("UPDATE uverify mkpin='$npin', pin_key='$clenkey' WHERE email='$email' AND recovery_phrase='$recoveryphrase'");
                                    if ($upd === TRUE) {
                                        $stmt = $this->connection->prepare("UPDATE users SET mkpin=?  WHERE idUser=? AND mkpin=?");
                                        $stmt->bind_param("ssi", $npin, $duv, $npin);
                                        $stmt->execute();
                                        $stmt->close();
                                        header('Location: index.php');
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
            $this->connection->close();
        }
    }

    /* End updatePin() */
}
