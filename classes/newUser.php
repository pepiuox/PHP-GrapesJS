<?php
/**
 * Description of Register
 *
 * @author PePiuoX
 */

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class newUser {

    public $baseurl;
    private $connection;
    private $ip;
    public $mail;

    public function __construct() {
        global $conn;
        $this->mail = new PHPMailer();

        $this->ip = $this->getUserIP();
        $this->connection = $conn;
        $this->baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        /* If registration data is posted call createUser function. */
        if (isset($_POST["register"])) {
            $this->Register();
        }
    }

    public function procheck($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES);
    }

    public function risValidUsername($str) {
        return preg_match('/^[a-zA-Z0-9-_]+$/', $str);
    }

    public function risValidEmail($str) {
        if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str)) {
            $_SESSION['ErrorMessage'] = 'Please insert the correct email.';
            exit();
        }
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    private function VerifyUser() {
        if (isset($_POST["verifyuser"])) {
            if ($this->CountSUser() === true) {
                $_SESSION['StepInstall'] = 5;
                $_SESSION['AlertMessage'] = "There is a user with the highest level of administration, delete that user, to continue with the installation.";
            } else if ($this->CountAUser() === true) {
                $_SESSION['StepInstall'] = 5;
                $_SESSION['AlertMessage'] = "There is a user with the medium level of administration, delete that user, to continue with the installation.";
            } else {
                $_SESSION['StepInstall'] = 5;
                $_SESSION['SuccessMessage'] = "There are no users with administrator level.";
            }
        }
    }

    public function checkUsername($username) {

        $query = $this->connection->prepare("SELECT username FROM uverify WHERE username=?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        
        return $result->num_rows;
    }

    public function checkEmail($email) {
        $query = $this->connection->prepare("SELECT email FROM uverify WHERE email=?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();
        
        return $result->num_rows;
    }

    public function getUserIP() {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

// randtoken maker
    private function randToken($len = 64) {
        return substr(sha1(openssl_random_pseudo_bytes(21)), - $len);
    }

// randkey maker
    private function randKey($len = 64) {
        return substr(sha1(openssl_random_pseudo_bytes(13)), - $len);
    }

// randhash maker
    private function randHash($len = 64) {
        return substr(sha1(openssl_random_pseudo_bytes(17)), - $len);
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

    /* start Register() 
     * Function Register(){
     * Function that includes everything for new user creation.
     * Data is taken from registration form, converted to prevent SQL injection and
     * checked that values are filled, if all is correct data is entered to user database.
     */

    private function Register() {

        if (isset($_POST['register'])) {

            $username = $this->procheck($_POST['username']);
            $email = $this->procheck($_POST['email']);
            $password = $this->procheck($_POST['password']);
            $repassword = $this->procheck($_POST['password2']);
            $agree = $this->procheck($_POST['agreeTerms']);
            if ($agree != 'agree') {
                $_SESSION['ErrorMessage'] = "You need to accept the terms and conditions, to register your account!";
                header('Location: register.php');
                exit();
            }

            $dt = new DateTime();
            $time = $dt->format('Y-m-d H:i:s');

// message for incomplete field or actions
            if (empty($username) || empty($email) || empty($password) || empty($repassword)) {
                $_SESSION['ErrorMessage'] = "Fill in the fields or boxes!";
            } elseif (!$this->risValidUsername($username)) {
                $_SESSION['ErrorMessage'] = "Please enter a valid user!";
            } elseif ($this->checkUsername($username) > 0) {
                $_SESSION['ErrorMessage'] = "User already exists!";
            } elseif (!$this->risValidEmail($email)) {
                $_SESSION['ErrorMessage'] = "Enter a valid email address!";
            } elseif ($this->checkEmail($email) > 0) {
                $_SESSION['ErrorMessage'] = "Email already exists!";
            } elseif ($password != $repassword) {
                $_SESSION['ErrorMessage'] = "The password does not match!";
            } else {
// check first if the password are identical
                if ($password === $repassword) {

                    $ekey = $this->randToken();
                    $eiv = $this->randkey();
                    $enck = $this->randHash();

                    $newid = uniqid(rand(), false);
                    $pass = $this->ende_crypter('encrypt', $password, $ekey, $eiv);
                    $cml = $this->ende_crypter('encrypt', $email, $ekey, $eiv);
                    $eusr = $this->ende_crypter('encrypt', $username, $ekey, $eiv);
                    $pin = rand(000000, 999999);
                    $code = $this->randkey();
                    $status = 0;
                    $dvd = 0;
                    $mvd = 0;
                    $ban = 1;
                    $is_actd = 0;

                    // adding data in table uverify
                    $stmt1 = $this->connection->prepare("INSERT INTO uverify (iduv,username,email,password,mktoken,mkkey,mkhash,mkpin,activation_code,is_activated,banned) "
                            . "VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt1->bind_param("sssssssssii", $newid, $username, $email, $pass, $ekey, $eiv, $enck, $pin, $code, $is_actd, $ban);
                    $stmt1->execute();
                    $inst2 = $stmt1->affected_rows;
                    $stmt1->close();

                    // adding data in table users and info
                    $stmt = $this->connection->prepare("INSERT INTO users (idUser,username,email,password,status,ip,signup_time,email_verified,document_verified,mobile_verified,mkpin) "
                            . "VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt->bind_param("ssssisssiis", $newid, $eusr, $cml, $pass, $status, $this->ip, $time, $code, $dvd, $mvd, $pin);
                    $stmt->execute();
                    $inst1 = $stmt->affected_rows;
                    $stmt->close();

                    // adding data in table info
                    $info = $this->connection->prepare("INSERT INTO profiles(idp,mkhash) VALUES (?,?)");
                    $info->bind_param("ss", $newid, $enck);
                    $info->execute();
                    $inst3 = $info->affected_rows;
                    $info->close();

                    if ($inst1 === 1 && $inst2 === 1 && $inst3 === 1) {
                        // message for PIN save                       
                        $query = $this->connection->prepare("SELECT * FROM uverify WHERE username=? AND email=? AND password=?");
                        $query->bind_param("sss", $username, $email, $pass);
                        $query->execute();
                        $result = $query->get_result();
                        if ($result->num_rows === 1) {
                            $row = $result->fetch_assoc();
                            $upid = $row['iduv'];
                            $upin = $row['mkpin'];

                            $_SESSION['uid'] = $row['iduv'];

                            $this->updatePIN($upid, $upin);
                            $this->sMailer($email, $pin, $code, $enck);
                            //$this->sendEmail($email, $pin, $code, $enck);
                            $_SESSION['SuccessMessage'] = 'Remember! Save this, your PIN code is: ' . $pin . ' Thank you for registering';
                            $this->rverify();
                        } else {
                            $_SESSION['ErrorMessage'] = 'Security log could not be completed, see technical support .';
                        }
                        $query->close();
                    } else {
                        $_SESSION['ErrorMessage'] = 'User creation failed, check with support to continue with your registration.';
                    }
                }
            }
        }
        $this->connection->close();
    }

    public function rVerify() {
        return TRUE;
    }

    private function sMailer($email, $pin, $code, $enck) {

        $body = 'Your access PIN code is: <b>' . $pin . '</b>' . "\r\n" . 'We recommend saving it, you do not need to access it with your password.' . "\r\n";
        $body .= 'To activate your account, click on the following link' . "\r\n" . ' <a href="' . $this->baseurl . '/verify.php?id=' . $email . '&code=' . $code . '&hash=' . $enck . '">Verify your email</a>' . "\r\n"; // Input the URL of your website.
        $body .= 'Login to your account and create your recovery phrase.';

        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = 'smtp.hostinger.com';
        $this->mail->Port = 465;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'info@cerroblanco.club';
        $this->mail->Password = '0h5Hiv4@108';
        $this->mail->setFrom('info@cerroblanco.club', 'Cerro Blanco');
        $this->mail->addReplyTo('info@cerroblanco.club', 'Cerro Blanco');
        $this->mail->addAddress('recipient@domain.tld', 'Receiver Name');
        $this->mail->Subject = 'Checking your email for verification';
        //$this->mail->msgHTML(file_get_contents('message.html'), __DIR__);
        $this->mail->Body = $body;
        //$this->mail->addAttachment('attachment.txt');
        if (!$this->mail->send()) {
            echo 'Mailer Error: ' . $this->mail->ErrorInfo;
        } else {
            echo 'The email message was sent.';
        }
    }

    private function sendEmail($email, $pin, $code, $enck) {
        $to = $email;
        $subject = "Your code to activate your account.";
        $from = 'admin@fornicard.com'; // This should be changed to an email that you would like to send activation e-mail from.
        $body = 'Your access PIN code is: <b>' . $pin . '</b>' . "\r\n" . 'We recommend saving it, you do not need to access it with your password.' . "\r\n";
        $body .= 'To activate your account, click on the following link' . "\r\n" . ' <a href="' . $this->baseurl . '/verify.php?id=' . $email . '&code=' . $code . '&hash=' . $enck . '">Verify your email</a>' . "\r\n"; // Input the URL of your website.
        $body .= 'Login to your account and create your recovery phrase.';
        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $success = mail($to, $subject, $body, $headers);
        if ($success === true) {
            $_SESSION['SuccessMessage'] = 'A message was sent to your mailbox to verify your new account! ';
        } else {
            $_SESSION['ErrorMessage'] = 'Error sending a message to your mailbox to verify your new account! ';
        }
    }

    private function updatePIN($upid, $upin) {

        $update = $this->connection->prepare("UPDATE users SET mkpin=? WHERE idUser=?");
        $update->bind_param("ss", $upin, $upid);
        $update->execute();
        if ($update->affected_rows === 1) {
            $_SESSION['SuccessMessage'] = 'The user has been created! '
                    . 'The user has successfully registered!'
                    . '<meta http-equiv="refresh" content="3;URL=index.php" />';
        }
        $update->close();
    }

    public function generateRandStr($length) {
        $randstr = "";
        for ($i = 0; $i < $length; $i++) {
            $randnum = mt_rand(0, 61);
            if ($randnum < 10) {
                $randstr .= chr($randnum + 53);
            } else if ($randnum < 36) {
                $randstr .= chr($randnum + 49);
            } else {
                $randstr .= chr($randnum + 61);
            }
        }
        return $randstr;
    }
}
