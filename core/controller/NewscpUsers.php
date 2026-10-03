<?php
//
//  This application develop by PePiuoX.
//  Migrated to PDO & Secured by AI Assistant
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class NewscpUsers {
    public $baseurl;
    protected $conn;
    private $ip;
    public $gc;
    public $dt;
    public $time;
    private $uca;

    public function __construct() {
        global $conn;
        if (!($conn instanceof PDO)) {
            throw new RuntimeException('NewscpUsers requires $conn to be a PDO instance.');
        }
        $this->conn = $conn;
        $this->uca  = new UsersCodeAccess();
        $this->gc   = new GetCodeDeEncrypt();
        $this->ip   = $this->getUserIP();
        $this->dt   = new DateTime();
        $this->time = $this->dt->format("Y-m-d H:i:s");
        $this->baseurl = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);

        if (isset($_POST["cspregister"])) {
            $this->RegisterCSP();
        }
        $this->includes();
    }

    private function includes() {
        require_once URL."/core/PHPMailer/src/Exception.php";
        require_once URL."/core/PHPMailer/src/PHPMailer.php";
        require_once URL."/core/PHPMailer/src/SMTP.php";
    }

    public function procheck(?string $string): string {
        return htmlspecialchars((string)($string ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function risValidUsername(string $str): bool {
        return (bool)preg_match('/^[_a-zA-Z0-9\-\.]+$/', $str);
    }

    public function risValidEmail(string $str): bool {
        if (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["ErrorMessage"] = "Please insert the correct email.";
            return false;
        }
        return true;
    }

    public function risValidPassword(string $str): bool {
        $pattern = '/^(?=.*[!@#$%^&*()\-_=+`~\[\]{}?])(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,30}$/';
        return (bool)preg_match($pattern, $str);
    }

    private function checkUsername(string $username): int {
        $user = $this->gc->ende_crypter("encrypt", $username, SECURE_TOKEN, SECURE_HASH);
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM uverify WHERE username = :u");
        $stmt->execute([':u' => $user]);
        return (int)$stmt->fetchColumn();
    }

    private function checkEmail(string $email): int {
        $mail = $this->gc->ende_crypter("encrypt", $email, SECURE_TOKEN, SECURE_HASH);
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM uverify WHERE email = :e");
        $stmt->execute([':e' => $mail]);
        return (int)$stmt->fetchColumn();
    }

    public function getUserIP(): string {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR']      = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP']   = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = $_SERVER['HTTP_CLIENT_IP'] ?? '';
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $remote  = $_SERVER['REMOTE_ADDR'] ?? '';

        if (filter_var($client, FILTER_VALIDATE_IP))  return $client;
        if (filter_var($forward, FILTER_VALIDATE_IP)) return $forward;
        return $remote;
    }

    private function RegisterCSP(): void {
        if (!isset($_POST["cspregister"])) return;

        if (empty($_POST["cspuser"])) {
            $_SESSION["ErrorMessage"] = "Select a registration option!";
            return;
        }

        $cspuser    = $this->procheck($_POST["cspuser"]);
        $username   = $this->procheck($_POST["username"] ?? '');
        $email      = $this->procheck($_POST["email"] ?? '');
        $password   = $_POST["password"] ?? '';
        $repassword = $_POST["password2"] ?? '';
        $agree      = $_POST["agreeTerms"] ?? '';

        if ($agree !== "agree") {
            $_SESSION["ErrorMessage"] = "You need to accept the terms and conditions!";
            header("Location: register.php");
            exit;
        }

        $typusr = match ($cspuser) {
            'clients'  => 'cliente',
            'services' => 'servicios',
            'products' => 'productor',
            default    => null,
        };
        if ($typusr === null) {
            $_SESSION["ErrorMessage"] = "Select a valid registration option!";
            return;
        }

        if (empty($username) || empty($email) || empty($password) || empty($repassword)) {
            $_SESSION["ErrorMessage"] = "Fill in the fields!";
            return;
        }
        if (!$this->risValidUsername($username)) {
            $_SESSION["ErrorMessage"] = "Please enter a valid user!";
            return;
        }
        if ($this->checkUsername($username) > 0) {
            $_SESSION["ErrorMessage"] = "User already exists!";
            return;
        }
        if (!$this->risValidEmail($email)) return;
        if ($this->checkEmail($email) > 0) {
            $_SESSION["ErrorMessage"] = "Email already exists!";
            return;
        }
        if (!$this->risValidPassword($password)) {
            $_SESSION["ErrorMessage"] = "The password needs capital letters, numbers and symbols (8-30 digits)!";
            return;
        }
        if ($password !== $repassword) {
            $_SESSION["ErrorMessage"] = "The password does not match!";
            return;
        }

        try {
            $user   = $this->gc->ende_crypter("encrypt", $username, SECURE_TOKEN, SECURE_HASH);
            $uml    = $this->gc->ende_crypter("encrypt", $email, SECURE_TOKEN, SECURE_HASH);
            $ekey   = $this->gc->randToken();
            $eiv    = $this->gc->randKey();
            $enck   = $this->gc->randHash();
            $newid  = $this->gc->getRandCode();
            $pass   = $this->gc->ende_crypter("encrypt", $password, $ekey, $eiv);
            $cml    = $this->gc->ende_crypter("encrypt", $email, $ekey, $eiv);
            $eusr   = $this->gc->ende_crypter("encrypt", $username, $ekey, $eiv);
            $pin    = random_int(100000, 999999);
            $mp     = $this->gc->ende_crypter("encrypt", (string)$pin, SECURE_TOKEN, SECURE_HASH);
            $usrcod = $this->gc->getRandomCode();
            $code   = $this->gc->getIdCode();
            $status = 0; $dvd = 0; $mvd = 0; $ban = 1; $is_actd = 0;
            $action = "validation";

            // uverify
            $stmt1 = $this->conn->prepare(
                "INSERT INTO uverify (iduv,usercode,username,email,password,usr_type,mktoken,mkkey,mkhash,mkpin,activation_code,is_activate,banned)
            VALUES (:iduv,:usercode,:username,:email,:password,:usr_type,:mktoken,:mkkey,:mkhash,:mkpin,:activation_code,:is_activate,:banned)"
            );
            $stmt1->execute([
                ':iduv' => $newid, ':usercode' => $usrcod, ':username' => $user, ':email' => $uml,
                ':password' => $pass, ':usr_type' => $typusr, ':mktoken' => $ekey, ':mkkey' => $eiv,
                ':mkhash' => $enck, ':mkpin' => $mp, ':activation_code' => $code,
                ':is_activate' => $is_actd, ':banned' => $ban
            ]);

            // users_csp
            $stmt = $this->conn->prepare(
                "INSERT INTO users_csp (idUser,usercode,username,email,password,usr_type,status,ip,signup_time,email_verified,document_verified,mobile_verified,mkpin)
            VALUES (:idUser,:usercode,:username,:email,:password,:usr_type,:status,:ip,:signup_time,:email_verified,:document_verified,:mobile_verified,:mkpin)"
            );
            $stmt->execute([
                ':idUser' => $newid, ':usercode' => $usrcod, ':username' => $eusr, ':email' => $cml,
                ':password' => $pass, ':usr_type' => $typusr, ':status' => $status, ':ip' => $this->ip,
                ':signup_time' => $this->time, ':email_verified' => $code, ':document_verified' => $dvd,
                ':mobile_verified' => $mvd, ':mkpin' => $mp
            ]);

            // users_profiles
            $prof = $this->conn->prepare("INSERT INTO users_profiles(idp,usercode,usrtypes,mkhash) VALUES (:idp,:usercode,:usrtypes,:mkhash)");
            $prof->execute([':idp' => $newid, ':usercode' => $usrcod, ':usrtypes' => $typusr, ':mkhash' => $enck]);

            // users_info
            $info = $this->conn->prepare("INSERT INTO users_info(userid,usercode) VALUES (:userid,:usercode)");
            $info->execute([':userid' => $newid, ':usercode' => $usrcod]);

            // users_actions
            $uact = $this->conn->prepare("INSERT INTO users_actions(usercode, action, validation) VALUES (:usercode,:action,:validation)");
            $uact->execute([':usercode' => $usrcod, ':action' => $action, ':validation' => $code]);

            $this->uca->AddUserCode($usrcod);

            if ($stmt1->rowCount() === 1 && $stmt->rowCount() === 1 && $prof->rowCount() === 1
                && $info->rowCount() === 1 && $uact->rowCount() === 1) {
                $query = $this->conn->prepare("SELECT iduv, mkpin FROM uverify WHERE username=:u AND email=:e AND password=:p");
            $query->execute([':u' => $user, ':e' => $uml, ':p' => $pass]);
            if ($query->rowCount() === 1) {
                $this->sendPMailer($email, $username, $pin, $code, $enck);
            } else {
                $_SESSION["ErrorMessage"] = "Security log could not be completed.";
            }
                } else {
                    $_SESSION["ErrorMessage"] = "User creation failed.";
                }
        } catch (PDOException $e) {
            error_log('NewscpUsers error: ' . $e->getMessage());
            $_SESSION["ErrorMessage"] = 'Database error. Please try again later.';
        }
    }

    private function sendPMailer(string $email, string $username, int $pin, string $code, string $enck): void {
        try {
            $usr = $this->gc->ende_crypter("decrypt", USEREMAIL, SECURE_TOKEN, SECURE_HASH);
            $pas = $this->gc->ende_crypter("decrypt", PASSMAIL, SECURE_TOKEN, SECURE_HASH);

            $subject = "Revise su correo electrónico para verificación.";
            $safeUsername = $this->procheck($username);
            $safePin      = $this->procheck((string)$pin);
            $safeCode     = urlencode($code);
            $safeUsr      = urlencode($enck);

            $body  = "<html><body><h4>Hola {$safeUsername}.</h4>";
            $body .= "<p>Su código PIN de acceso es: <b>{$safePin}</b><br>";
            $body .= "Te recomendamos guardarlo, lo necesitas para acceder con tu contraseña.<br>";
            $body .= "Para activar su cuenta, haga clic en el siguiente enlace<br>";
            $body .= ' <a href="' . DOMAIN_SITE . "/checkactions/verify.php?vcode={$safeCode}&usr={$safeUsr}\">Verifica tu cuenta</a><br>";
            $body .= "Su cuenta necesita verificarse.</p></body></html>";

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = MAILSERVER;
            $mail->SMTPAuth   = true;
            $mail->Username   = $usr;
            $mail->Password   = $pas;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = PORTSERVER;
            $mail->setFrom($usr, SITE_NAME);
            $mail->addAddress($email, $username);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);
            $mail->send();
        } catch (Exception $e) {
            error_log('NewscpUsers Mailer Error: ' . $e->getMessage());
        }
    }
}
