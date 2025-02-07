<?php

/**
 * Description of Register 
 * InstallUser
 *
 * @author PePiuoX
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class installUser {

    public $baseurl;
    protected $connection;
    private $ip;
    private $gc;
    public $dt;
    public $time;
    private $uca;
    public $protocol;
    public $pathapp;
    public $source;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $this->uca = new UsersCodeAccess();
        $this->gc = new GetCodeDeEncrypt();
        $this->ip = $this->getUserIP();
        $this->dt = new DateTime();
        $this->time = $this->dt->format("Y-m-d H:i:s");

        $this->pathapp = dirname(__DIR__, 3);
        $this->source = str_replace('\\', '/', $this->pathapp);
        $this->protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
        $this->baseurl = $this->protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        /* If registration data is posted call createUser function. */
        if (isset($_POST["register"])) {
            $this->RegisterAdmin();
        }
        if (isset($_POST["verifyuser"])) {
            $this->VerifyUser();
        }
        if (isset($_POST["cleanuser"])) {
            $this->CleanUser();
        }
        $this->includes();
    }

    private function includes() {
        require_once $this->source . "/core/PHPMailer/src/Exception.php";
        require_once $this->source . "/core/PHPMailer/src/PHPMailer.php";
        require_once $this->source . "/core/PHPMailer/src/SMTP.php";
    }

// This function check that they do not have html symbols 
    public function procheck($string) {
        $str = htmlentities(stripslashes($string), ENT_QUOTES);
        return htmlspecialchars(trim($str), ENT_QUOTES);
    }

// This function validate string of first name or lastname
    public function ValidNames($str) {
        return preg_match('/^[_a-zA-Z ]+$/', $str);
    }

// This function validate string of username
    public function risValidUsername($str) {
        return preg_match('/^[_a-zA-Z0-9-_\.]+$/', $str);
    }

//  This function validate if your email is correct 
    // for change TLD change the values {2,3} to {2,6} o last number dependent the limited of personal TLD
    public function risValidEmail($str) {
        if (
            !preg_match(
                "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$^",
                $str
            )
        ) {
            $_SESSION["ErrorMessage"] = "Please insert the correct email.";
            exit;
        }
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    public function risValidPassword($str) {
        $pattern = '/^(?=.*[!@#$%^&*()\-_=+`~\[\]{}?])(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,30}$/';
        return preg_match($pattern, $str);
    }

// This function check if username exists
    private function checkUsername($username) {
        $user = $this->gc->ende_crypter(
            "encrypt",
            $username,
            $_SESSION['SECURE_TOKEN'],
            $_SESSION['SECURE_HASH']
        );
        $query = $this->connection->prepare(
            "SELECT username FROM uverify WHERE username=?"
        );
        $query->bind_param("s", $user);
        $query->execute();
        $result = $query->get_result();

        return $result->num_rows;
    }

// This function check if email exists
    private function checkEmail($email) {
        $mail = $this->gc->ende_crypter(
            "encrypt",
            $email,
            $_SESSION['SECURE_TOKEN'],
            $_SESSION['SECURE_HASH']
        );
        $query = $this->connection->prepare(
            "SELECT email FROM uverify WHERE email=?"
        );
        $query->bind_param("s", $mail);
        $query->execute();
        $result = $query->get_result();

        return $result->num_rows;
    }

// This function get IP from visitor
    public function getUserIP() {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER["REMOTE_ADDR"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER["HTTP_CLIENT_IP"] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER["HTTP_CLIENT_IP"];
        $forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
        $remote = $_SERVER["REMOTE_ADDR"];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

// function CountSUser() count the hight user level
    private function CountSUser() {
        $lvushigh = 'Super Admin';

        $qlv = $this->connection->prepare("SELECT level FROM uverify WHERE level=?");
        $qlv->bind_param("s", $lvushigh);
        $qlv->execute();
        $lvresult = $qlv->get_result();
        $qlv->close();
        if ($lvresult->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

// function CountAUser() count the medium user level
    private function CountAUser() {

        $lvusmid = 'Admin';
        $qlv1 = $this->connection->prepare("SELECT level FROM uverify WHERE level=?");
        $qlv1->bind_param("s", $lvusmid);
        $qlv1->execute();
        $lvresult1 = $qlv1->get_result();
        $qlv1->close();
        if ($lvresult1->num_rows > 0) {
            return true;
        } else {
            return false;
        }
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

    /* Cleanuser level for first install */

    private function CleanUser() {
        if (isset($_POST["cleanuser"])) {
            $lhigh = 'Super Admin';
            $qlv = $this->connection->prepare("SELECT iduv, level FROM uverify WHERE level=?");
            $qlv->bind_param("s", $lhigh);
            $qlv->execute();
            $lresult = $qlv->get_result();
            $qlv->close();
            if ($lresult->num_rows > 0) {

                $idv = $lresult->fetch_assoc();
                $idclean = $idv['iduv'];
                $stmt = $this->connection->prepare("DELETE FROM uverify WHERE iduv=?");
                $stmt->bind_param("s", $idclean);
                $stmt->execute();
                $stmt->close();
                $_SESSION['StepInstall'] = 5;
                $_SESSION['AlertMessage'] = "A high-level user has been successfully deleted from the installation system, to continue with the installation.";
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
            $username = $this->procheck($_POST["username"]);
            $email = $this->procheck($_POST["email"]);
            $password = $this->procheck($_POST["password"]);
            $repassword = $this->procheck($_POST["password2"]);
            $agree = $this->procheck($_POST["agreeTerms"]);
            if ($agree != "agree") {
                $_SESSION["ErrorMessage"] = "You need to accept the terms and conditions, to register your account!";             
            }

            // message for incomplete field or actions
            if (empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($password) || empty($repassword)) {
                $_SESSION['ErrorMessage'] = "Fill in the fields or boxes!";
            } elseif (!$this->ValidNames($firstname)) {
                $_SESSION['ErrorMessage'] = "Please enter a valid firstname without numbers or simbols!";
            } elseif (!$this->ValidNames($lastname)) {
                $_SESSION['ErrorMessage'] = "Please enter a valid lastname without numbers or simbols!";
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
                    // Generate codes for new users
                    $ekey = $this->gc->randToken();
                    $eiv = $this->gc->randkey();
                    $enck = $this->gc->randHash();
                    $site = 1;
                    
                    $query = $this->connection->prepare(
                    "SELECT SECURE_HASH,SECURE_TOKEN FROM site_security WHERE site=?"
                    );
                    $query->bind_param("i", $site);
                    $query->execute();
                    $result = $query->get_result();
                    $secure = $result->fetch_assoc();
                    $stoken =$secure['SECURE_TOKEN'];
                    $shash = $secure['SECURE_HASH'];

                    $user = $this->gc->ende_crypter(
                        "encrypt",
                        $username,
                        $stoken,
                        $shash
                    );
                    
                    $uml = $this->gc->ende_crypter(
                        "encrypt",
                        $email,
                        $stoken,
                        $shash
                    );

                    $newid = $this->gc->getRandCode();
                    $pass = $this->gc->ende_crypter(
                        "encrypt",
                        $password,
                        $ekey,
                        $eiv
                    );
                    $cml = $this->gc->ende_crypter(
                        "encrypt",
                        $email,
                        $ekey,
                        $eiv
                    );
                    $eusr = $this->gc->ende_crypter(
                        "encrypt",
                        $username,
                        $ekey,
                        $eiv
                    );
                    $pin = rand(100000, 999999);
                    $mp = $this->gc->ende_crypter(
                        "encrypt",
                        $pin,
                        $stoken,
                        $shash
                    );

                    $usrcod = $this->gc->getRandomCode();
                    $code = $this->gc->getIdCode();
                    

                    $lvl = 'Super Admin';
                    $lvlk = $this->gc->iRandKey(64);

                    $status = 0;
                    $dvd = 0;
                    $mvd = 0;
                    $ban = 0;
                    $verif = 1;
                    $is_actd = 1;
                    $action = "validation";

                    // adding data in table uverify
                    $stmt1 = $this->connection->prepare(
                        "INSERT INTO uverify (iduv,usercode,username,email,password,mktoken,mkkey,mkhash,mkpin,level,level_key,is_activated,verified,banned) " .
                        "VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
                    );
                    $stmt1->bind_param(
                        "sssssssssssiii",
                        $newid,
                        $usrcod,
                        $user,
                        $uml,
                        $pass,
                        $ekey,
                        $eiv,
                        $enck,
                        $mp,
                        $lvl,
                        $lvlk,
                        $is_actd,
                        $verif,
                        $ban
                    );
                    $stmt1->execute();
                    $inst2 = $stmt1->affected_rows;
                    $stmt1->close();

                    // adding data in table users
                    $stmt = $this->connection->prepare(
                        "INSERT INTO users (idUser,usercode,username,email,password,status,ip,signup_time,email_verified,document_verified,mobile_verified,mkpin) " .
                        "VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"
                    );
                    $stmt->bind_param(
                        "sssssisssiis",
                        $newid,
                        $usrcod,
                        $eusr,
                        $cml,
                        $pass,
                        $status,
                        $this->ip,
                        $this->time,
                        $code,
                        $dvd,
                        $mvd,
                        $mp
                    );
                    $stmt->execute();
                    $inst1 = $stmt->affected_rows;
                    $stmt->close();
            
                    // adding data in table profile
                    $prof = $this->connection->prepare(
                        "INSERT INTO users_profiles(idp,usercode,mkhash) VALUES (?,?,?)"
                    );
                    $prof->bind_param("sss", $newid, $usrcod, $enck);
                    $prof->execute();
                    $inst3 = $prof->affected_rows;
                    $prof->close();

                    // adding data in table info
                    $info = $this->connection->prepare(
                        "INSERT INTO users_info(userid,usercode,firstname,lastname) VALUES (?,?,?,?)"
                    );
                    $info->bind_param("ssss", $newid, $usrcod, $firstname, $lastname);
                    $info->execute();
                    $inst4 = $info->affected_rows;
                    $info->close();

                    // adding data in table info
                    $uact = $this->connection->prepare(
                        "INSERT INTO users_actions(usercode, action, validation) VALUES (?,?,?)"
                    );
                    $uact->bind_param("sss", $usrcod, $action, $code);
                    $uact->execute();
                    $inst5 = $uact->affected_rows;
                    $uact->close();
                    
                    $this->uca->AddUserCode($usrcod);
                    // $this->uca->AddSecures($newid,$usrcod);

                    if (
                        $inst1 === 1 &&
                        $inst2 === 1 &&
                        $inst3 === 1 &&
                        $inst4 === 1 &&
                        $inst5 === 1
                    ) {
                        // message for PIN save
                        $query = $this->connection->prepare(
                            "SELECT iduv, mkpin FROM uverify WHERE username=? AND email=? AND password=?"
                        );
                        $query->bind_param("sss", $user, $uml, $pass);
                        $query->execute();
                        $result = $query->get_result();
                        $query->close();

                        if ($result->num_rows === 1) {
                            $_SESSION['FullSuccess'] = 'Please remember! Save this, your PIN code is: <h2>' . $pin . '</h2> Thank you for registering. ' . "\n";
                            $_SESSION['SuccessMessage'] = "Admin successfully added.";

                            $_SESSION['StepInstall'] = 6;
                            header("Location: install.php?step=6");
                            exit;
                        } else {
                            $_SESSION["ErrorMessage"] = "Security log could not be completed, see technical support.";
                        }
                    } else {
                        $_SESSION["ErrorMessage"] = "User creation failed, check with support to continue with your registration.";
                    }
                }
            }
        }
        $this->connection->close();
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
