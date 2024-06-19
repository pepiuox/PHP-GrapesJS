<?php

class ChangePIN {

    public $baseurl;
    protected $connection;
    private $iduv;
    private $level;
    private $mkhash;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $this->baseurl = SITE_PATH;

        $this->iduv = $_SESSION['user_id'];
        $this->level = $_SESSION['levels'];
        $this->mkhash = $_SESSION['hash'];
        if (isset($_POST["changePIN"])) {
            $this->UpdatePIN();
        }
    }

    private function ende_crypter($action, $string, $secret_key, $secret_iv) {
        $output = false;
        $encrypt_method = 'AES-256-CBC';
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

    private function UpdatePIN() {

        if (isset($_POST['changePIN'])) {
            $phrase = trim($_POST['recoveryphrase']);
            $pin = trim($_POST['pin']);

            $stmt = $this->connection->prepare("SELECT usernme, email, mktoken, mkkey, recovery_phrase FROM uverify WHERE iduv = ? AND mkhash = ? AND mkpin=?");
            $stmt->bind_param("sss", $this->iduv, $this->mkhash, $pin);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows === 1) {
                $urw = $result->fetch_assoc();
                $usname = $urw['username'];
                $email = $urw['email'];
                $secret_key = $urw['mktoken'];
                $secret_iv = $urw['mkkey'];
                $rvphrase = $urw['recovery_phrase'];

                $dcrp = $this->ende_crypter('decrypt', $rvphrase, $secret_key, $secret_iv);

                if ($dcrp === $phrase) {

                    $newpin = rand(000000, 999999);
                    $stmt = $this->connection->prepare("UPDATE uverify SET mkpin = ? WHERE iduv = ? AND username = ? AND mkhash = ? AND mkpin = ?");
                    $stmt->bind_param("sssss", $newpin, $this->iduv, $usname, $this->mkhash, $pin);
                    $stmt->execute();
                    $stmt->close();

                    $to = $email;
                    $subject = "PIN change";
                    $from = 'contact@labemotion.net';
                    $body = "Your PIN key is: " . $newpin . "\n";
                    $body .= 'Remember to save your PIN and delete this email for security.'; // Replace YOURWEBSITEURL with your own URL for the link to work.
                    $headers = "From: " . $from . "\r\n";
                    $headers .= "Reply-To: " . $from . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $success = mail($to, $subject, $body, $headers);

                    if ($success === true) {
                        $_SESSION['SuccessMessage'] = 'Email has been sent.' . '\n' .
                                'The email contains the steps to follow after changing your PIN!';
                    } else {
                        $_SESSION['ErrorMessage'] = 'Error sending a message to your email!';
                    }
                }
            } else {
                $_SESSION['ErrorMessage'] = 'The passphrase or PIN is incorrect.';
            }
        }
        $this->connection->close();
    }

    /* End forgotPIN() */
}
