<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
/**
 * Description of userForgot
 *
 * @author PePiuoX
 */
class UserChange
{
    public $baseurl;
    protected $conn;
    public $gc;
    public $pt;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->gc = new GetCodeDeEncrypt();
        $this->pt = new Protect();
        $this->baseurl =
            SITE_PATH . dirname($_SERVER["PHP_SELF"]);

        if (isset($_POST["changePassword"])) {
            $this->updatePassword();
        }
        if (isset($_POST["changePIN"])) {
            $this->updatePIN();
        }
    }

    /*
     * function updatePassword()
     * Get information from Password Reset Form, if the email & token key are correct, update the password in database.
     * This is the third and final step of password reset.
     */

    private function updatePassword()
    {
        if (isset($_POST["updatePassword"])) {
            if (
                isset($_GET["email"]) &&
                isset($_GET["key"]) &&
                isset($_GET["hash"])
            ) {
                // Require credentials for DB connection.

                $email = htmlentities($_GET["email"]);
                $hash = htmlentities($_GET["hash"]);
                $changekey = htmlentities($_GET["key"]);

                $userm = $this->gc->ende_crypter(
                    "encrypt",
                    $email,
                    SECURE_TOKEN,
                    SECURE_HASH
                );
//Select form table forgot pass
                $chck = $this->conn->prepare(
                    "SELECT * FROM forgot_pass WHERE email=? AND mkhash=? AND password_key=?"
                );
                $chck->bind_param("sss", $userm, $hash, $changekey);
                $chck->execute();
                $okey = $chck->get_result();
                $chck->close();

                $ct = $okey->fetch_assoc();
                $nowTime = date("Y-m-d H:i:s");
                $et = $ct["expire"];

                if ($nowTime >= $et) {
                    $_SESSION["ErrorMessage"] =
                        "Time expired to reset your password.";
                    header("Location: index.php");
                    exit;
                }
                if (!empty($password3) && !empty($email)) {
                    // User input from Forgot password form(passwordResetForm.php).
                    $vemail = trim($_POST["vemail"]);
                    $recoveryphrase = trim($_POST["recoveryphrase"]);
                    $password2 = trim($_POST["password2"]);
                    $password3 = trim($_POST["password3"]);

                    // Check that both entered passwords match.
                    if ($password3 === $password2 && $vemail === $email) {
                        $stmt = $this->conn->prepare(
                            "SELECT iduv, mkpin FROM uverify WHERE email=? AND mkhash=? AND password_key=? AND recovery_phrase=?"
                        );
                        $stmt->bind_param(
                            "ssss",
                            $email,
                            $hash,
                            $changekey,
                            $recoveryphrase
                        );
                        $stmt->execute();
                        $very = $stmt->get_result();

                        if ($very->num_rows === 1) {
                            $dt = $very->fetch_assoc();
                            $duv = $dt["iduv"];
                            $pin = $dt["mkpin"];

                            $ekey = $this->gc->randToken();
                            $eiv = $this->gc->randkey();
                            $enck = $this->gc->randHash();

                            $securing = $this->gc->ende_crypter(
                                "encrypt",
                                $password2,
                                $ekey,
                                $eiv
                            );
                            $cml = $this->gc->ende_crypter(
                                "encrypt",
                                $email,
                                $ekey,
                                $eiv
                            );
                            $clenkey = "";
                            $upd = $this->conn->query(
                                "UPDATE uverify SET password=?, mktoken=?, mkkey=?, mkhash=, password_key=? WHERE email=? AND recovery_phrase=? AND password_key=?"
                            );
                            $upd->bind_param(
                                "sssss",
                                $securing,
                                $ekey,
                                $eiv,
                                $enck,
                                $clenkey,
                                $email,
                                $recoveryphrase,
                                $changekey
                            );
                            $upd->execute();
                            $upd->close();
                            if ($upd === true) {
                                $stmt = $this->conn->prepare(
                                    "UPDATE users SET email = ?, password = ?  WHERE idUser=? AND mkpin=?"
                                );
                                $stmt->bind_param(
                                    "sssi",
                                    $cml,
                                    $securing,
                                    $duv,
                                    $pin
                                );
                                $stmt->execute();
                                $stmt->close();
                                header("Location: index.php");
                                exit;
                            }
                        } else {
                            $_SESSION["ErrorMessage"] =
                                "The data does not match to update your password.";
                        }
                    } else {
                        $_SESSION["ErrorMessage"] = "Passwords do not match!";
                    }
                } else {
                    $_SESSION["ErrorMessage"] =
                        "Please fill in all the required fields.";
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

    private function updatePIN()
    {
        if (isset($_POST["updatePIN"])) {
            if (
                isset($_GET["email"]) &&
                isset($_GET["key"]) &&
                isset($_GET["hash"])
            ) {
                // Require credentials for DB connection.

                $email = htmlentities($_GET["email"]);
                $changekey = htmlentities($_GET["key"]);
                $hash = htmlentities($_GET["hash"]);

                $chck = $this->conn->prepare(
                    "SELECT * FROM change_pin WHERE email=? AND mkhash=? AND password_key=?"
                );
                $chck->bind_param("sss", $email, $hash, $changekey);
                $chck->execute();
                $okey = $chck->get_result();
                $chck->close();

                $ct = $okey->fetch_assoc();
                $nowTime = date("Y-m-d H:i:s");
                $et = $ct["expire"];

                if ($nowTime >= $et) {
                    $_SESSION["ErrorMessage"] =
                        "Time expired to reset your PIN.";
                    header("Location: index.php");
                    exit;
                }
                // User input from Forgot password form(passwordResetForm.php).
                $vemail = trim($_POST["vemail"]);
                $recoveryphrase = trim($_POST["recoveryphrase"]);

                // Check that both entered passwords match.
                if ($vemail === $email) {
                    if (!empty($recoveryphrase) && !empty($email)) {
                        $very = $this->conn->query(
                            "SELECT * FROM uverify WHERE email='$email' AND pin_key='$changekey' AND recovery_phrase='$recoveryphrase'"
                        );

                        if ($very->num_rows === 1) {
                            $dt = $very->fetch_assoc();
                            $duv = $dt["iduv"];
                            $npin = "";

                            $secret_key = $dt["mktoken"];
                            $secret_iv = $dt["mkkey"];

                            $checkm = $this->gc->ende_crypter(
                                "encrypt",
                                $email,
                                $secret_key,
                                $secret_iv
                            );

                            $fnal = $this->conn->query(
                                "SELECT idUser FROM users WHERE email='$checkm'"
                            );
                            $rt = $fnal->fetch_assoc();
                            if ($fnal->num_rows === 1) {
                                if ($duv === $rt["idUser"]) {
                                    $npin = random_int(100000, 999999);

                                    $clenkey = "";
                                    $upd = $this->conn->query(
                                        "UPDATE uverify mkpin='$npin', pin_key='$clenkey' WHERE email='$email' AND recovery_phrase='$recoveryphrase'"
                                    );
                                    if ($upd === true) {
                                        $stmt = $this->conn->prepare(
                                            "UPDATE users SET mkpin=?  WHERE idUser=? AND mkpin=?"
                                        );
                                        $stmt->bind_param(
                                            "ssi",
                                            $npin,
                                            $duv,
                                            $npin
                                        );
                                        $stmt->execute();
                                        $stmt->close();
                                        header("Location: index.php");
                                        exit;
                                    }
                                }
                            }
                        } else {
                            $_SESSION["ErrorMessage"] =
                                "The data does not match to update your PIN.";
                        }
                    } else {
                        $_SESSION["ErrorMessage"] =
                            "Please fill in all the required fields.";
                    }
                } else {
                    $_SESSION["ErrorMessage"] = "Passwords do not match!";
                }
            }
            $this->conn->close();
        }
    }

    /* End updatePin() */
}
