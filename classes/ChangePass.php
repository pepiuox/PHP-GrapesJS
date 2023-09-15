<?php

class ChangePass {

    public $baseurl;
    protected $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $this->baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

        if (isset($_POST["changePassword"])) {
            $this->ChangePassword();
        }
    }

    private function ChangePassword() {
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
                    $upd = $this->connection->prepare("UPDATE uverify password=?, mktoken=?, mkkey=?, mkhash=, password_key=? WHERE email=? AND recovery_phrase=? AND password_key=?");
                    $upd->bind_param("sssss", $securing, $ekey, $eiv, $$enck, $clenkey, $email, $recoveryphrase, $changekey);
                    $upd->execute();
                    $upd->close();
                    if ($upd === TRUE) {
                        $stmt = $this->connection->prepare("UPDATE users SET email = ?, password = ?  WHERE idUser=? AND mkpin=?");
                        $stmt->bind_param("sssi", $cml, $securing, $duv, $pin);
                        $stmt->execute();
                        $stmt->close();
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    $_SESSION['ErrorMessage'] = 'The data does not match to update your password.';
                }
            } else {
                $_SESSION['ErrorMessage'] = 'Passwords do not match!';
            }
        }
    }
}
