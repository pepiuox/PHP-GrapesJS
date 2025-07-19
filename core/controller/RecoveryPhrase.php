<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
/**
 * Description of RecoveryPhrase
 *
 * @author PePiuoX
 */
class RecoveryPhrase
{
    protected $conn;
    public $baseurl;
    private $iduv;
    private $usercode;
    private $mkhash;
    public $gc;
    public $pt;

    //put your code here
    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->baseurl = SITE_PATH;
        $this->gc = new GetCodeDeEncrypt();
        $this->pt = new Protect();
        $this->iduv = $_SESSION["user_id"];
        $this->usercode = $_SESSION["access_id"];
        $this->mkhash = $_SESSION["hash"];

        if (isset($_POST["makerecoveryphrase"])) {
            $this->MakeRecoveryPhrase();
        }
        if (isset($_POST["updaterecoveryphrase"])) {
            $this->UpdateRecoveryPhrase();
        }
    }

    private function MakeRecoveryPhrase()
    {
        // Require credentials for DB connection.
        if (isset($_POST["makerecoveryphrase"])) {
            if (!empty($_POST["pin"]) && !empty($_POST["rvphrase"])) {
                $rcvphrase = $this->pt->secureStr($_POST["rvphrase"]);
                $userpin = $this->pt->secureStr($_POST["pin"]);
                $cnull = 0;

                $upin = $this->gc->ende_crypter(
                    "encrypt",
                    $userpin,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $result = $this->conn->prepare(
                    "SELECT mktoken, mkkey, recovery_phrase  FROM uverify WHERE iduv = ? AND usercode = ? AND mkhash = ? AND mkpin = ? AND rp_active = ?"
                );
                $result->bind_param(
                    "ssssi",
                    $this->iduv,
                    $this->usercode,
                    $this->mkhash,
                    $upin,
                    $cnull
                );
                $result->execute();
                $resu = $result->get_result();
                $nuv = $resu->num_rows;
                $result->close();

                if ($nuv === 1) {
                    $urw = $resu->fetch_assoc();
                    $secret_key = $urw["mktoken"];
                    $secret_iv = $urw["mkkey"];
                    $prhase = $urw["recovery_phrase"];
                    $dcrvp = $this->gc->ende_crypter(
                        "decrypt",
                        $prhase,
                        $secret_key,
                        $secret_iv
                    );
                    if ($dcrvp === $rcvphrase) {
                        $crvp = $this->gc->ende_crypter(
                            "encrypt",
                            $rcvphrase,
                            $secret_key,
                            $secret_iv
                        );
                        $rpac = 1;

                        $update = $this->conn->prepare(
                            "UPDATE uverify SET recovery_phrase = ?, rp_active = ? WHERE iduv = ? AND usercode = ? AND mkhash = ? AND mkpin = ?"
                        );
                        $update->bind_param(
                            "sissss",
                            $crvp,
                            $rpac,
                            $this->iduv,
                            $this->usercode,
                            $this->mkhash,
                            $upin
                        );
                        $update->execute();
                        $nupd = $update->affected_rows;
                        $update->close();
                        if ($nupd === 1) {
                            unset($_SESSION["AlertMessage"]);
                            unset($_SESSION["RecoveryMessage"]);
                            $_SESSION["SuccessMessage"] =
                                "Thanks your account is now more secure.";
                            echo '<script>window.location.href = "profile.php";
</script>';
                        } else {
                            $_SESSION["ErrorMessage"] =
                                "Error problems with updating data.";
                        }
                    }
                } else {
                    $_SESSION["ErrorMessage"] =
                        "Error problems with data verification.";
                }
                $result->close();
            } else {
                $_SESSION["ErrorMessage"] = "Fill in the boxes correctly .";
            }
        }
    }

    private function UpdateRecoveryPhrase()
    {
        // Require credentials for DB connection. urvphrase
        if (isset($_POST["updaterecoveryphrase"])) {
            if (!empty($_POST["pin"]) && !empty($_POST["rvphrase"])) {
                $rcvphrase = $this->pt->secureStr($_POST["rvphrase"]);
                $urvphrase = $this->pt->secureStr($_POST["urvphrase"]);
                $userpin = $this->pt->secureStr($_POST["pin"]);
                $cnull = 1;

                $upin = $this->gc->ende_crypter(
                    "encrypt",
                    $userpin,
                    SECURE_TOKEN,
                    SECURE_HASH
                );

                $result = $this->conn->prepare(
                    "SELECT mktoken, mkkey, recovery_phrase  FROM uverify WHERE iduv = ? AND usercode = ? AND mkhash = ? AND mkpin = ? AND rp_active = ?"
                );
                $result->bind_param(
                    "ssssi",
                    $this->iduv,
                    $this->usercode,
                    $this->mkhash,
                    $upin,
                    $cnull
                );
                $result->execute();
                $resu = $result->get_result();
                $nuv = $resu->num_rows;
                $result->close();

                if ($nuv === 1) {
                    $urw = $resu->fetch_assoc();
                    $secret_key = $urw["mktoken"];
                    $secret_iv = $urw["mkkey"];
                    $prhase = $urw["recovery_phrase"];
                    $dcrvp = $this->gc->ende_crypter(
                        "decrypt",
                        $prhase,
                        $secret_key,
                        $secret_iv
                    );
                    if ($dcrvp === $rcvphrase) {
                        $crvp = $this->gc->ende_crypter(
                            "encrypt",
                            $urvphrase,
                            $secret_key,
                            $secret_iv
                        );

                        $update = $this->conn->prepare(
                            "UPDATE uverify SET recovery_phrase = ? WHERE iduv = ? AND usercode = ? AND mkhash = ? AND mkpin = ?"
                        );
                        $update->bind_param(
                            "sssss",
                            $crvp,
                            $this->iduv,
                            $this->usercode,
                            $this->mkhash,
                            $upin
                        );
                        $update->execute();
                        $nupd = $update->affected_rows;
                        $update->close();
                        if ($nupd === 1) {
                            unset($_SESSION["AlertMessage"]);
                            unset($_SESSION["RecoveryMessage"]);
                            $_SESSION["SuccessMessage"] =
                                "Thanks your account is now more secure.";
                            echo '<script>window.location.href = "profile.php";
</script>';
                        } else {
                            $_SESSION["ErrorMessage"] =
                                "Error problems with updating data.";
                        }
                    }
                } else {
                    $_SESSION["ErrorMessage"] =
                        "Error problems with data verification.";
                }
                $result->close();
            } else {
                $_SESSION["ErrorMessage"] = "Fill in the boxes correctly .";
            }
        }
    }
}
