<?php
//
//  This application develop by PePiuoX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * Description of Register
 */

class NewUsers
{
    public $baseurl;
    protected $conn;
    private $ip;
    private $gc;
    public $dt;
    public $time;
    private $uca;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->uca = new UsersCodeAccess();
        $this->gc = new GetCodeDeEncrypt();
        $this->ip = $this->getUserIP();
        $this->dt = new DateTime();
        $this->time = $this->dt->format("Y-m-d H:i:s");
        $this->baseurl =
            "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);

        /* If registration data is posted call createUser function. */
        if (isset($_POST["register"])) {
            $this->Register();
        }
        $this->includes();
    }
    private function includes()
    {
        require_once "../PHPMailer/src/Exception.php";
        require_once "../PHPMailer/src/PHPMailer.php";
        require_once "../PHPMailer/src/SMTP.php";
    }

    public function procheck($string)
    {
		$str = htmlentities(stripslashes($string), ENT_QUOTES);
        return htmlspecialchars(trim($str), ENT_QUOTES);
    }

    public function risValidUsername($str)
    {
        return preg_match('/^[_a-zA-Z0-9-_\.]+$/', $str);
    }

    public function risValidEmail($str)
    {
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

    public function risValidPassword($str)
    {
        $pattern = '/^(?=.*[!@#$%^&*()\-_=+`~\[\]{}?])(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,30}$/';
        return preg_match($pattern, $str);
    }

    private function checkUsername($username)
    {
        $user = $this->gc->ende_crypter(
            "encrypt",
            $username,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $query = $this->conn->prepare(
            "SELECT username FROM uverify WHERE username=?"
        );
        $query->bind_param("s", $user);
        $query->execute();
        $result = $query->get_result();

        return $result->num_rows;
    }

    private function checkEmail($email)
    {
        $mail = $this->gc->ende_crypter(
            "encrypt",
            $email,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $query = $this->conn->prepare(
            "SELECT email FROM uverify WHERE email=?"
        );
        $query->bind_param("s", $mail);
        $query->execute();
        $result = $query->get_result();

        return $result->num_rows;
    }

    public function getUserIP()
    {
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

    /* start Register()
     * Function Register(){
     * Function that includes everything for new user creation.
     * Data is taken from registration form, converted to prevent SQL injection and
     * checked that values are filled, if all is correct data is entered to user database.
     */

    private function Register()
    {
        if (isset($_POST["register"])) {
            $username = $this->procheck($_POST["username"]);
            $email = $this->procheck($_POST["email"]);
            $password = $this->procheck($_POST["password"]);
            $repassword = $this->procheck($_POST["password2"]);
            $agree = $this->procheck($_POST["agreeTerms"]);
            if ($agree != "agree") {
                $_SESSION["ErrorMessage"] =
                    "You need to accept the terms and conditions, to register your account!";
                header("Location: register.php");
                exit;
            }

            // message for incomplete field or actions
            if (
                empty($username) ||
                empty($email) ||
                empty($password) ||
                empty($repassword)
            ) {
                $_SESSION["ErrorMessage"] = "Fill in the fields or boxes!";
            } elseif (!$this->risValidUsername($username) === 0) {
                $_SESSION["ErrorMessage"] = "Please enter a valid user!";
            } elseif ($this->checkUsername($username) > 0) {
                $_SESSION["ErrorMessage"] = "User already exists!";
            } elseif (!$this->risValidEmail($email)) {
                $_SESSION["ErrorMessage"] = "Enter a valid email address!";
            } elseif ($this->checkEmail($email) > 0) {
                $_SESSION["ErrorMessage"] = "Email already exists!";
            } elseif ($this->risValidPassword($password) === 0) {
                $_SESSION["ErrorMessage"] =
                    "The password need capital letters, numbers and symbols and be more than 8 to 30 digits!";
            } elseif ($password != $repassword) {
                $_SESSION["ErrorMessage"] = "The password does not match!";
            } else {
                // check first if the password are identical
                if ($password === $repassword) {
                    // Generate codes for new users
                    $ekey = $this->gc->randToken();
                    $eiv = $this->gc->randkey();
                    $enck = $this->gc->randHash();

                    $user = $this->gc->ende_crypter(
                        "encrypt",
                        $username,
                        SECURE_TOKEN,
                        SECURE_HASH
                    );
                    $uml = $this->gc->ende_crypter(
                        "encrypt",
                        $email,
                        SECURE_TOKEN,
                        SECURE_HASH
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
                        SECURE_TOKEN,
                        SECURE_HASH
                    );

                    $usrcod = $this->gc->getRandomCode();
                    $code = $this->gc->getIdCode();
                    $status = 0;
                    $dvd = 0;
                    $mvd = 0;
                    $ban = 1;
                    $is_actd = 0;
                    $action = "validation";

                    // adding data in table uverify
                    $stmt1 = $this->conn->prepare(
                        "INSERT INTO uverify (iduv,usercode,username,email,password,mktoken,mkkey,mkhash,mkpin,activation_code,is_activated,banned) " .
                            "VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"
                    );
                    $stmt1->bind_param(
                        "sssssssssii",
                        $newid,
                        $usrcod,
                        $user,
                        $uml,
                        $pass,
                        $ekey,
                        $eiv,
                        $enck,
                        $mp,
                        $code,
                        $is_actd,
                        $ban
                    );
                    $stmt1->execute();
                    $inst2 = $stmt1->affected_rows;
                    $stmt1->close();

                    // adding data in table users
                    $stmt = $this->conn->prepare(
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
                    $prof = $this->conn->prepare(
                        "INSERT INTO users_profiles(idp,usercode,mkhash) VALUES (?,?,?)"
                    );
                    $prof->bind_param("sss", $newid, $usrcod, $enck);
                    $prof->execute();
                    $inst3 = $prof->affected_rows;
                    $prof->close();

                    // adding data in table info
                    $info = $this->conn->prepare(
                        "INSERT INTO users_info(userid,usercode) VALUES (?,?)"
                    );
                    $info->bind_param("ss", $newid, $usrcod);
                    $info->execute();
                    $inst4 = $info->affected_rows;
                    $info->close();

                    // adding data in table info
                    $uact = $this->conn->prepare(
                        "INSERT INTO users_actions(usercode, action, validation) VALUES (?,?,?)"
                    );
                    $uact->bind_param("sss", $usrcod, $action, $code);
                    $uact->execute();
                    $inst5 = $uact->affected_rows;
                    $uact->close();

                    $this->uca->AddUserCode($usrcod);

                    if (
                        $inst1 === 1 &&
                        $inst2 === 1 &&
                        $inst3 === 1 &&
                        $inst4 === 1 &&
                        $inst5 === 1
                    ) {
                        // message for PIN save
                        $query = $this->conn->prepare(
                            "SELECT iduv, mkpin FROM uverify WHERE username=? AND email=? AND password=?"
                        );
                        $query->bind_param("sss", $user, $uml, $pass);
                        $query->execute();
                        $result = $query->get_result();
                        $query->close();
						
                        if ($result->num_rows === 1) {
                                $this->sendPMailer(
                                $email,
                                $username,
                                $pin,
                                $code,
                                $enck
                            );
                        } else {
                            $_SESSION["ErrorMessage"] =
                                "Security log could not be completed, see technical support .";
                        }
                    } else {
                        $_SESSION["ErrorMessage"] =
                            "User creation failed, check with support to continue with your registration.";
                    }
                }
            }
        }
        $this->conn->close();
    }

    private function sendPMailer($email, $username, $pin, $code, $enck)
    {

        $usr = $this->gc->ende_crypter(
            "decrypt",
            USEREMAIL,
            SECURE_TOKEN,
            SECURE_HASH
        );
        $pas = $this->gc->ende_crypter(
            "decrypt",
            PASSMAIL,
            SECURE_TOKEN,
            SECURE_HASH
        );

        $subject =
            "Revise su correo electrónico para verificación, Su código para activar su cuenta.";
        // This should be changed to an email that you would like to send activation e-mail from.
        $body = "<html>
<body><h4>Hola " . $username . ".</h4>";
        $body .=
            "<p>Su código PIN de acceso es: <b>" .
            $pin .
            "</b>" .
            "<br>" .
            "Te recomendamos guardarlo, lo necesitas para acceder con tu contraseña." .
            "<br>";
        $body .=
            "Para activar su cuenta, haga clic en el siguiente enlace" .
            "<br>" .
            ' <a href="' .
            DOMAIN_SITE .
            "/checkactions/verify.php?vcode=" .
            $code .
            "&usr=" .
            $enck .
            '">Verifica tu cuenta</a>' .
            "<br>"; // Input the URL of your website.
        $body .= "Su cuenta necesita verificarse.</p></body>
</html>";
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = MAILSERVER; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = $usr; //SMTP username
        $mail->Password = $pas; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = PORTSERVER; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom($usr, SITE_NAME);
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
        } else {
            echo "<h4>The email message was sent. Remember! Save this, your PIN code is: " .
                $pin .
                " Thank you for registering</h4>";
        }
    }
}
