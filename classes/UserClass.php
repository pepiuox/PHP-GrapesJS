<?php

/*
 * This class will include everything associated with users:
 * Login - Login()
 * Logout - logOut()
 * Password recovery - forgotPassword(), newPassword(), updatePassword()
 * User creation - Registration()
 */

class UserClass {

    public $system;
    public $baseurl;
    private $connection;
    private $ip;
    public $timestamp;

    /*
     * __constructor()
     * Constructor will be called every time Login class is called ($login = new Login())
     */

    public function __construct() {
        global $conn, $base;
        $this->system = $base;
        $this->connection = $conn;
        $this->ip = $this->getUserIP();
        $this->baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        $date = new DateTime();
        $this->timestamp = $date->getTimestamp();

        /* If login data is posted call validation function. */
        if (isset($_POST["signin"])) {
            $this->Login();
        }
        if (isset($_POST["attempts"])) {
            $this->CheckAttempts();
        }
        if (isset($_POST['profile'])) {
            $this->Profile();
        }
        if (isset($_POST["logout"])) {
            $this->Logout();
        }
        if (isset($_POST["updatePassword"])) {
            $this->updatePassword();
        }
    }

    /* End __constructor() */

// generateRandStr(64);
    private function generateRandStr($length) {
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

// Create Hash - Function randHash
    private function randHash($len = 64) {
        return substr(sha1(openssl_random_pseudo_bytes(17)), - $len);
    }

    /*
     * Function Login()
     * Function that validates user login data, cross-checks with database.
     * If data is valid user is logged in, session variables are set.
     */

    private function Login() {
        if (isset($_POST['signin'])) {

            //set login attempt if not set
            if (!isset($_SESSION['attempt'])) {
                $_SESSION['attempt'] = 0;
                $_SESSION['attempt_again'] = 0;
            }
            // Check that data has been submited.
            // Check that both username and password fields are filled with values.
            if (empty($_POST['email'])) {
                $_SESSION['ErrorMessage'] = 'Please fill in the email field.';
            } elseif (empty($_POST['password'])) {
                $_SESSION['ErrorMessage'] = 'Please fill in the Password field.';
            } elseif (empty($_POST['PIN'])) {
                $_SESSION['ErrorMessage'] = 'Please fill in the PIN field.';
            } else {
                //check if there are 3 attempts already
                if ($_SESSION['attempt_again'] >= 3) {
                    $_SESSION['error'] = 'Your are allowed 3 attempts in 10 minutes';
                } else {
                    // User input from Login Form(loginForm.php).
                    $useremail = trim($_POST['email']);

                    $this->verifyAttempts($useremail);

                    // verify if PIN is numeric
                    if (is_numeric($_POST['PIN']) && strlen($_POST['PIN']) === 6) {
                        $userpsw = trim($_POST['password']);
                        $userpin = trim($_POST['PIN']);
                        $remember = trim($_POST['remember']);
                        if ($remember === 'Yes') {
                            define("COOKIE_EXPIRE", 60 * 60 * 24 * 7);  //7 days by default
                            define("COOKIE_PATH", "/");  //Avaible in whole domain
                        }

                        $stmt = $this->connection->prepare("SELECT * FROM uverify WHERE email = ? AND mkpin = ?");
                        $stmt->bind_param("ss", $useremail, $userpin);
                        $stmt->execute();
//fetching result would go here, but will be covered later
                        $result = $stmt->get_result();
                        if ($result->num_rows === 0) {
                            $this->nAttempt($useremail);
                        } else {

                            $urw = $result->fetch_assoc();

                            if (!empty($urw['password_key'])) {
                                $_SESSION['ErrorMessage'] = 'Your account is not active by request for password recovery, check your email or please contact support';
                                header("Location: login.php");
                                exit();
                            }
                            if (!empty($urw['pin_key'])) {
                                $_SESSION['ErrorMessage'] = 'Your account is not active by request for PIN recovery, check your email or please contact support.';
                                header("Location: login.php");
                                exit();
                            }
                            if ($urw['banned'] === 1) {
                                $_SESSION['ErrorMessage'] = 'Access could not be completed, account may be blocked, please contact support.';
                                header("Location: login.php");
                                exit();
                            }

                            if ($urw['is_activated'] === 1 && $urw['banned'] === 0) {
                                $user = $urw['username'];
                                $cml = $urw['email'];
                                $passw = $urw['password'];
                                $level = $urw['level'];
                                $rpa = $urw['rp_active'];
                                $secret_key = $urw['mktoken'];
                                $secret_iv = $urw['mkkey'];
                                $secret_hs = $urw['mkhash'];

                                $cus = $this->ende_crypter('encrypt', $user, $secret_key, $secret_iv);
                                $pass = $this->ende_crypter('decrypt', $passw, $secret_key, $secret_iv);
                                $mail = $this->ende_crypter('encrypt', $cml, $secret_key, $secret_iv);

                                if ($userpsw === $pass) {

                                    if ($rpa === 0) {
                                        $_SESSION['AlertMessage'] = 'Recovery phrase needs to be created for your safety.';
                                        $_SESSION['RecoveryMessage'] = 1;
                                    }

                                    $stmt1 = $this->connection->prepare("SELECT * FROM users WHERE username = ? AND email = ? AND password = ? AND mkpin = ?");
                                    $stmt1->bind_param("ssss", $cus, $mail, $passw, $userpin);
                                    $stmt1->execute();
//fetching result would go here, but will be covered later
                                    $sqr = $stmt1->get_result();
                                    if ($sqr->num_rows === 0) {

                                        $_SESSION['ErrorMessage'] = 'The data is wrong.';
//header('Location: login.php');
                                    }
                                    $row = $sqr->fetch_assoc();
                                    $stmt1->close();
                                    $iduv = $row['idUser'];

                                    $enck = $this->randHash();

                                    $up1 = $this->connection->prepare("UPDATE uverify SET mkhash = ? WHERE iduv = ? AND password = ? AND mkhash = ?");
                                    $up1->bind_param("ssss", $enck, $iduv, $passw, $secret_hs);
                                    $up1->execute();
                                    $inst1 = $up1->affected_rows;
                                    $up1->close();

                                    if ($inst1 === 1) {
                                        $_SESSION['username'] = $user;
                                        $_SESSION['user_id'] = $iduv;
                                        $_SESSION['language'] = $row['language'];
                                        $_SESSION['levels'] = $level;
                                        $_SESSION['hash'] = $enck;

                                        $pro = $this->connection->prepare("UPDATE profiles SET mkhash = ? WHERE idp = ? AND mkhash = ?");
                                        $pro->bind_param("sss", $enck, $iduv, $secret_hs);
                                        $pro->execute();
                                        $inst2 = $pro->affected_rows;
                                        $pro->close();
                                        if ($inst2 === 1) {
                                            $_SESSION['SuccessMessage'] = 'Congratulations you now have access!';
                                            unset($_SESSION['attempt']);
                                            unset($_SESSION['attempt_again']);
                                            unset($_SESSION['id_session_attempt']);
                                        }
                                    } else {
                                        session_destroy();
                                        $_SESSION['ErrorMessage'] = 'Access error!';
                                    }
                                } else {
                                    $this->nAttempt($useremail);
                                    header("Location: login.php");
                                    exit();
                                }
                            } else {

                                $_SESSION['ErrorMessage'] = 'Your account is not active, some process is incomplete, please contact support.';
                                header("Location: login.php");
                                exit();
                            }
                        }
                    } else {
                        $_SESSION['ErrorMessage'] = 'The PIN is not numeric or is not complete.';
                        header("Location: login.php");
                        exit();
                    }
                }
            }

            $this->connection->close();
        }
    }

    /* End Login() */
    /*
     * Function VieLogAttempts()
     * Verifies if the existence of records of user in the table login_attempts 
     */

    private function viewLogAttempts($id, $udt) {
        $result = $this->connection->prepare("SELECT id_session, user_data FROM login_attempts WHERE id_session= ? AND user_data = ?");
        $result->bind_param("ss", $id, $udt);
        $result->execute();
        $num = $result->get_result();
        if ($num->num_rows > 0) {
            return true;
        } else {
            $attempts = 3;
            $att = $this->connection->prepare("INSERT INTO `login_attempts` (`id_session`,`user_data`,`ip_address`,`attempts`)VALUES (?,?,?,?)");
            $att->bind_param("sssi", $id, $udt, $this->ip, $attempts);
            $att->execute();
            return false;
        }
    }

    /*
     * Function CheckAttemps()
     * Create a failed login session , destroys all attempts session data.
     */

    private function CheckAttempts() {
        if (isset($_POST["attempts"])) {
            if (empty($_POST['username'])) {
                $_SESSION['ErrorMessage'] = 'Please fill in the username field.';
            } elseif (empty($_POST['password'])) {
                $_SESSION['ErrorMessage'] = 'Please fill in the Password field.';
            } elseif (empty($_POST['PIN'])) {
                $_SESSION['ErrorMessage'] = 'Please fill in the PIN field.';
            } else {
                $username = trim($_POST['username']);
                $userpsw = trim($_POST['password']);
                $userpin = trim($_POST['PIN']);

                $stmt = $this->connection->prepare("SELECT * FROM uverify WHERE username = ? AND mkpin = ?");
                $stmt->bind_param("ss", $username, $userpin);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows === 0) {
                    $_SESSION['ErrorMessage'] = 'The data is wrong.';
                    header('Location: login.php');
                    exit();
                } else {
                    $urw = $result->fetch_assoc();
                    if ($urw['is_activated'] === 1 && $urw['banned'] === 0) {
                        $email = $urw['email'];
                        $user = $urw['username'];
                        $passw = $urw['password'];
                        $secret_key = $urw['mktoken'];
                        $secret_iv = $urw['mkkey'];

                        $cus = $this->ende_crypter('encrypt', $user, $secret_key, $secret_iv);
                        $pass = $this->ende_crypter('decrypt', $passw, $secret_key, $secret_iv);

                        if ($userpsw === $pass) {
                            $stmt1 = $this->connection->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND mkpin = ?");
                            $stmt1->bind_param("sss", $cus, $passw, $userpin);
                            $stmt1->execute();
//fetching result would go here, but will be covered later
                            $sqr = $stmt1->get_result();
                            if ($sqr->num_rows > 0) {
                                $att = $this->connection->prepare("DELETE FROM `ip` WHERE id_session = ? AND user_data = ?");
                                $att->bind_param("ss", $_SESSION['id_session_attempt'], $email);
                                $att->execute();
                                unset($_SESSION['attempt']);
                                unset($_SESSION['attempt_again']);
                                unset($_SESSION['id_session_attempt']);
                                $_SESSION['SuccessMessage'] = 'Congratulations you now have access!';
                                header('Location: login.php');
                                exit();
                            }
                        } else {
                            $_SESSION['ErrorMessage'] = 'Password incorrect.';
                            header("Location: login.php");
                            exit();
                        }
                    } else {
                        $_SESSION['ErrorMessage'] = 'Invalid username or password incorrect.';
                        header("Location: login.php");
                        exit();
                    }
                }
            }
        }
    }

    /*
     * Function verifyAttempts()
     * Show a failed login session  for more that 3 attempts and block the access.
     */

    private function verifyAttempts($udata) {
        $result = $this->connection->prepare("SELECT id_session, user_data FROM `ip` WHERE `user_data` = ? GROUP BY id_session");
        $result->bind_param("s", $udata);
        $result->execute();
        $num = $result->get_result();

        if ($num->num_rows >= 3) {
            $_SESSION['attempt_again'] = $_SESSION['attempt'] = 3;
            // Get data from IP
            $idss = $num->fetch_assoc();

            $_SESSION['id_session_attempt'] = $idss['id_session'];
            // Call function nAttempt();

            if ($_SESSION['attempt_again'] >= 3) {
                $_SESSION['ErrorMessage'] = 'You have the account blocked for more than 3 failed access attempts.';
                header("Location: login.php");
                exit();
            }
        }
    }

    /*
     * Function nAttempt()
     * Create a failed login session , destroys all attempts session data.
     */

    private function nAttempt($useremail) {
        //Create id session attempts
        if (!isset($_SESSION['id_session_attempt'])) {
            $idattempt = $this->randHash();
            $_SESSION['id_session_attempt'] = $idattempt;
        } else {
            $idattempt = $_SESSION['id_session_attempt'];
        }
        //this is where we put our 3 attempt limit
        $_SESSION['attempt'] += 1;
        //set the time to allow login if third attempt is reach
        // Call class attempts for record logs 
        $this->Attempts($idattempt, $useremail);
    }

    /*
     * Function Attemps()
     * Create a failed login session , destroys all session data.
     */

    private function Attempts($idatt, $udata) {
        if (isset($_SESSION['attempt_again'])) {

            $stmt = $this->connection->prepare("INSERT INTO `ip` (`id_session`, `user_data`, `address`)VALUES (?,?,?)");
            $stmt->bind_param("sss", $idatt, $udata, $this->ip);
            $stmt->execute();

            $result = $this->connection->prepare("SELECT id_session, user_data FROM `ip` WHERE `user_data` = ?");
            $result->bind_param("s", $udata);
            $result->execute();
            $num = $result->get_result();

            $_SESSION['attempt_again'] = $num->num_rows;

            if ($_SESSION['attempt'] >= 3) {
                $att = $this->connection->prepare("INSERT INTO `login_attempts` (`id_session`,`user_data`,`ip_address`,attempts)VALUES (?,?,?,?)");
                $att->bind_param("sssi", $idatt, $udata, $this->ip, $_SESSION['attempt']);
                $att->execute();
                //note 10*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
            }
            if ($_SESSION['attempt_again'] >= 3) {
                $_SESSION['error'] = 'Your are allowed 3 attempts in 10 minutes';
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['ErrorMessage'] = 'Invalid email, password or PIN incorrect.';
                header("Location: login.php");
                exit();
            }
        }
    }

    public function activeAttempts(){
        $lastlogs = '2021-08-28 21:46:41';
        $tnow = time();
        $lastlogs = strtotime($lastlogs);
        echo round(abs($tnow - $lastlogs) / 60);
    }
    /* Function DiffTime()
     * Find the difference between two dates. 
     */

    private function DiffTime($start, $end, $returnType = 1) {
        $seconds_diff = $end - $start;
        if ($returnType == 1) {
            return round($seconds_diff / 60); // minutes
        } else if ($returnType == 2) {
            return round($seconds_diff / 3600); // hours
        } else {
            return round($seconds_diff / 3600 / 24); // days
        }
    }

    /*
     * Function logOut()
     * Logs user out, destroys all session data.
     */

    public function logout() {
        if (isset($_POST['logout'])) {
            if (!empty($_SESSION['user_id'])) {
                if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
                    setcookie("cookname", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
                    setcookie("cookid", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
                }
                $_SESSION = array();
                /* Unset PHP session variables */
                unset($_SESSION['username']);
                unset($_SESSION['user_id']);
                unset($_SESSION['level']);
                unset($_SESSION['hash']);
                session_destroy(); // Destroy all session data.
                header('Location: ' . $this->system . 'login.php');
                exit();
            }
        } else {
            header('Location: ' . $this->system . 'index.php');
            exit();
        }
    }

    /* End logOut() */

    /*
     * Function isLoggedIn()
     * Check if user is already logged in, if not then prompt login form.
     */

    public function isLoggedIn() {
        if (!empty($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    /* End isLoggedIn() */
    /*
     * Function Profile()
     * Redirect to profile page
     */

    public function Profile() {
        if (isset($_POST['profile'])) {
            header('Location: ' . $this->system . 'users/profile.php');
            exit();
        }
    }

    /* End Profile() */

    private function LastSession() {
        $time = $_SERVER['REQUEST_TIME'];

        /**
         * for a 30 minute timeout, specified in seconds
         */
        $timeout_duration = 1800;

        /**
         * Here we look for the user's LAST_ACTIVITY timestamp. If
         * it's set and indicates our $timeout_duration has passed,
         * blow away any previous $_SESSION data and start a new one.
         */
        if (isset($_SESSION['LAST_ACTIVITY']) &&
                ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
            session_unset();
            session_destroy();
            session_start();
        }

        /**
         * Finally, update LAST_ACTIVITY so that our timeout
         * is based on it and not the user's login time.
         */
        $_SESSION['LAST_ACTIVITY'] = $time;
    }

    private function SessionActivity() {
        if ($_SESSION['last_activity'] < time() - $_SESSION['expire_time']) { //have we expired?
//redirect to logout.php
            header('Location: ' . $this->system . 'signin/logout.php'); //change yoursite.com to the name of you site!
            exit();
        } else { //if we haven't expired:
            $_SESSION['last_activity'] = time(); //this was the moment of last activity.
        }
//
        $_SESSION['logged_in'] = true; //set you've logged in
        $_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
        $_SESSION['expire_time'] = 30 * 60; //expire time in seconds: three hours (you must change this)
//
        $expire_time = 30 * 60; //expire time
        if ($_SESSION['last_activity'] < time() - $expire_time) {
            echo 'Session expired';
            die();
        } else {
            $_SESSION['last_activity'] = time(); // you have to add this line when logged in also;
            echo 'You are uptodate';
        }
    }

    public function isValidUsername($username) {
        if (strlen($username) < 3) {
            return false;
        }

        if (strlen($username) > 17) {
            return false;
        }

        if (!ctype_alnum($username)) {
            return false;
        }

        return true;
    }

    /*
     * Function newPassword()
     * URL opened from e-mail, get email & token key from URL.
     * If the e-mail and token exist in database prompt new password form.
     * Otherwise prompt an error.
     * This is the second step of password reset.
     */

    private function newPassword() {

// Values from password_reset.php URL.
        $email = htmlspecialchars($_GET['email']);
        $forgot_password_key = htmlspecialchars($_GET['key']);

// Require credentials for DB connection.


        $stmt = $this->connection->prepare("SELECT email,fpassword_key FROM uverify WHERE email = ? AND fpassword_key = ?");
        $stmt->bind_param("ss", $email, $forgot_password_key);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            include ("views/passwordResetForm.php");
        } else {
            $_SESSION['ErrorMessage'] = 'Please contact support at contact@labemotion.net';
        }
        $this->connection->close();
    }

    /* End newPassword() */

    private function memberRegistration() {
// Require credentials for DB connection.
// Variables for createUser()
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $password2 = trim($_POST['password2']);
        $email = $_POST['email'];

        if ($password === $password2) {
// Create hashed user password.
            $securing = password_hash($password, PASSWORD_DEFAULT);

// Check that all fields are filled with values.
            if (!empty($username) && !empty($password) && !empty($email)) {

// Check if username or email is already taken.
                $stmt = $this->connection->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

// Check if email is in the correct format.
                if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
                    $_SESSION['ErrorMessage'] = 'Please insert the correct email.';
                    header('Location: registration.php');
                    exit();
                }

// If username or email is taken.
                if ($result->num_rows != 0) {
// Promt user error about username or email already taken.
                    $_SESSION['ErrorMessage'] = 'Firstname is taken from user or email!';
                    header('Location: registration.php');
                    exit();
                } else {
// Insert data into database
                    $code = substr(md5(mt_rand()), 0, 15);
                    $stmt = $this->connection->prepare("INSERT INTO users (username, email, password, activation_code) VALUES (?,?,?,?)");
                    $stmt->bind_param("ssss", $username, $email, $securing, $code);
                    $stmt->execute();
                    $stmt->close();

// Send user activation e-mail

                    $to = $email;
                    $subject = "Your activation code for registration.";
                    $from = 'contact@labemotion.net'; // This should be changed to an email that you would like to send activation e-mail from.
                    $body = 'Please follow the steps below <br> To activate your account, click on the following link' . ' <a href="' . $this->baseurl . '/verify.php?id=' . $email . '&code=' . $code . '">Click for activete your account</a>.'; // Input the URL of your website.
                    $headers = "From: " . $from . "\r\n";
                    $headers .= "Reply-To: " . $from . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $success = mail($to, $subject, $body, $headers);
                    if ($success === true) {
                        $_SESSION['SuccessMessage'] = 'A message was sent to your mailbox to activate your new account! ';
                    } else {
                        $_SESSION['ErrorMessage'] = 'Error sending a message to your mailbox to activate your new account! ';
                    }
// If registration is successful return user to registration.php and promt user success pop-up.
                    $_SESSION['ErrorMessage'] = 'The user has been created!';
                    header('Location: register.php');
                    exit();
                }
            } else {
// If registration fails return user to registration.php and promt user failure error.
                $_SESSION['ErrorMessage'] = 'Please complete all fields!';
                header('Location: register.php');
                exit();
            }
        } else {
            $_SESSION['ErrorMessage'] = 'Passwords do not match!';
            header('Location: register.php');
            exit();
        }
        $this->connection->close();
    }

    /* End Registration() */

    private function updateUserField($username, $field, $value) {
        $stmt = $this->connection->prepare("UPDATE uverify SET ? = ? WHERE username = ?");
        $stmt->bind_param("sss", $field, $value, $username);
        $stmt->execute();
        return $this->connection->close();
    }

    public function isSuperdmin() {
        return ($this->userlevel == SUPERADMIN_LEVEL);
    }

    public function isAdmin() {
        return ($this->userlevel == ADMIN_LEVEL);
    }

    public function isMaster() {
        return ($this->userlevel == MASTER_LEVEL);
    }

    public function isAgent() {
        return ($this->userlevel == AGENT_LEVEL);
    }

    public function isMember() {
        return ($this->userlevel == AGENT_MEMBER_LEVEL);
    }

}

/* End class UserClass */

