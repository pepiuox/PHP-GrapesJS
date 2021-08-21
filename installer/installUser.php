
<?php

/**
 * Description of Register
 *
 * @author PePiuoX
 */
class installUser {

    public $baseurl;
    private $connection;
    private $ip;

    public function __construct() {
        global $conn;
        $this->ip = $this->getUserIP();
        $this->connection = $conn;
        $this->baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        /* If registration data is posted call createUser function. */
        if (isset($_POST["register"])) {
            $this->RegisterAdmin();
        }
        if (isset($_POST["verifyuser"])) {
            $this->VerifyUser();
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

    public function checkUsername($username) {

        $num = $this->connection->query("SELECT username FROM uverify WHERE username='$username'")->num_rows;
        return $num;
    }

    public function checkEmail($email) {

        $num = $this->connection->query("SELECT email FROM uverify WHERE email='$email'")->num_rows;
        return $num;
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

    private function CountSUser() {
        $lvushigh = 'Superadmin';

        $qlv = $this->connection->prepare("SELECT level FROM uverify WHERE level=?");
        $qlv->bind_param("s", $lvushigh);
        $qlv->execute();
        $lvresult = $qlv->get_result();
        if ($lvresult->num_rows > 0) {
            return true;
        } else {
            return false;
        }
        $qlv->close();
    }

    private function CountAUser() {

        $lvusmid = 'Admin';
        $qlv1 = $this->connection->prepare("SELECT level FROM uverify WHERE level=?");
        $qlv1->bind_param("s", $lvusmid);
        $qlv1->execute();
        $lvresult1 = $qlv1->get_result();
        if ($lvresult1->num_rows > 0) {
            return true;
        } else {
            return false;
        }
        $qlv1->close();
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

    /* start Register() 
     * Function Register(){
     * Function that includes everything for new user creation.
     * Data is taken from registration form, converted to prevent SQL injection and
     * checked that values are filled, if all is correct data is entered to user database.
     */

    private function RegisterAdmin() {
        if (isset($_POST["register"])) {
            $firstname = $this->procheck($_POST['firstname']);
            $lastname = $this->procheck($_POST['lastname']);
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
            if (empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($password) || empty($repassword)) {
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
                    $lvl = 'Superadmin';

                    $status = 0;
                    $dvd = 0;
                    $mvd = 0;
                    $verif = 1;
                    $is_actd = 1;
                    $ban = 0;
                    $ip = $this->ip;

                    // adding data in table uverify
                    $stmt1 = $this->connection->prepare("INSERT INTO uverify (iduv,username,email,password,mktoken,mkkey,mkhash,mkpin,level,is_activated,verified,banned) "
                            . "VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt1->bind_param("sssssssssiii", $newid, $username, $email, $pass, $ekey, $eiv, $enck, $pin, $lvl, $is_actd, $verif, $ban);
                    $stmt1->execute();
                    $inst2 = $stmt1->affected_rows;
                    $stmt1->close();

                    // adding data in table users and info
                    $stmt = $this->connection->prepare("INSERT INTO users (idUser,username,email,password,verified,status,ip,signup_time,document_verified,mobile_verified,mkpin) "
                            . "VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt->bind_param("ssssiissiis", $newid, $eusr, $cml, $pass, $verif, $status, $ip, $time, $dvd, $mvd, $pin);
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

                        $_SESSION['SuccessMessage'] = 'Remember! Save this, your PIN code is: ' . $pin . ' Thank you for registering' . "\n";
                        $_SESSION['SuccessMessage'] .= "Admin successfully added.";

                        $_SESSION['StepInstall'] = 6;
                        header("Location: install.php?step=6");
                        exit();
                    } else {
                        $_SESSION['ErrorMessage'] = 'User creation failed, check with support to continue with your registration.';
                    }
                }
            }

            $this->connection->close();
        }
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
