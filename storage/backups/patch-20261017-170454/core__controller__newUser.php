<?php
//
//  This application develop by PEPIUOX.
//  Migrated to PDO & Secured by AI Assistant
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class NewUser {
    public $baseurl;
    protected $connection;
    private $ip;
    private $gc;
    public $dt;
    public $time;
    private $uca;

    public function __construct() {
        global $conn;
        if (!($conn instanceof PDO)) {
            throw new RuntimeException('NewUser requires $conn to be a PDO instance.');
        }
        $this->uca = new UsersCodeAccess();
        $this->gc = new GetCodeDeEncrypt();
        $this->ip = $this->getUserIP();
        $this->dt = new DateTime();
        $this->time = $this->dt->format("Y-m-d H:i:s");
        $this->connection = $conn;
        $this->baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

        if (isset($_POST["register"])) {
            $this->Register();
        }
        $this->includes();
    }

    private function includes() {
        require_once "../core/PHPMailer/src/Exception.php";
        require_once "../core/PHPMailer/src/PHPMailer.php";
        require_once "../core/PHPMailer/src/SMTP.php";
    }

    /** Escapa HTML de forma segura (UTF-8 + ENT_HTML5) */
    public function procheck(?string $string): string {
        return htmlspecialchars((string)($string ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function risValidUsername(string $str): bool {
        return (bool)preg_match('/^[a-zA-Z0-9-_]+$/', $str);
    }

    public function risValidEmail(string $str): bool {
        if (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['ErrorMessage'] = 'Please insert the correct email.';
            return false;
        }
        return true;
    }

    private function VerifyUser() {
        if (isset($_POST["verifyuser"])) {
            if ($this->CountSUser() === true) {
                $_SESSION['StepInstall'] = 5;
                $_SESSION['AlertMessage'] = "There is a user with the highest level of administration...";
            } else if ($this->CountAUser() === true) {
                $_SESSION['StepInstall'] = 5;
                $_SESSION['AlertMessage'] = "There is a user with the medium level of administration...";
            } else {
                $_SESSION['StepInstall'] = 5;
                $_SESSION['SuccessMessage'] = "There are no users with administrator level.";
            }
        }
    }

    public function checkUsername(string $username): int {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM uverify WHERE username = :u");
        $stmt->execute([':u' => $username]);
        return (int)$stmt->fetchColumn();
    }

    public function checkEmail(string $email): int {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM uverify WHERE email = :e");
        $stmt->execute([':e' => $email]);
        return (int)$stmt->fetchColumn();
    }

    public function getUserIP(): string {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = $_SERVER['HTTP_CLIENT_IP'] ?? '';
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $remote  = $_SERVER['REMOTE_ADDR'] ?? '';

        if (filter_var($client, FILTER_VALIDATE_IP)) return $client;
        if (filter_var($forward, FILTER_VALIDATE_IP)) return $forward;
        return $remote;
    }

    /** Generadores seguros delegados a GetCodeDeEncrypt */
    private function randToken(int $len = 64): string { return $this->gc->generateSecureToken($len, 'base64'); }
    private function randKey(int $len = 64): string  { return $this->gc->generateSecureToken($len, 'base64'); }
    private function randHash(int $len = 64): string { return $this->gc->generateSecureToken($len, 'hash'); }

    /** Cifrado seguro con AES-256-GCM (delegado a GetCodeDeEncrypt) */
    private function ende_crypter(string $action, string $string, string $secret_key, string $secret_iv): string|false {
        return $this->gc->ende_crypter($action, $string, $secret_key, $secret_iv);
    }

    private function Register(): void {
        if (!isset($_POST['register'])) return;

        $username   = $this->procheck($_POST['username'] ?? '');
        $email      = $this->procheck($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';
        $repassword = $_POST['password2'] ?? '';
        $agree      = $_POST['agreeTerms'] ?? '';

        if ($agree !== 'agree') {
            $_SESSION['ErrorMessage'] = "You need to accept the terms and conditions!";
            header('Location: register.php');
            exit;
        }

        if (empty($username) || empty($email) || empty($password) || empty($repassword)) {
            $_SESSION['ErrorMessage'] = "Fill in the fields!";
            return;
        }
        if (!$this->risValidUsername($username)) {
            $_SESSION['ErrorMessage'] = "Please enter a valid user!";
            return;
        }
        if ($this->checkUsername($username) > 0) {
            $_SESSION['ErrorMessage'] = "User already exists!";
            return;
        }
        if (!$this->risValidEmail($email)) {
            return; // mensaje ya seteado
        }
        if ($this->checkEmail($email) > 0) {
            $_SESSION['ErrorMessage'] = "Email already exists!";
            return;
        }
        if ($password !== $repassword) {
            $_SESSION['ErrorMessage'] = "The password does not match!";
            return;
        }

        // Generar tokens seguros
        $ekey   = $this->randToken();
        $eiv    = $this->randKey();
        $enck   = $this->randHash();
        $newid  = bin2hex(random_bytes(16)); // uniqid(rand()) → random_bytes
        $pass   = $this->ende_crypter('encrypt', $password, $ekey, $eiv);
        $cml    = $this->ende_crypter('encrypt', $email, $ekey, $eiv);
        $eusr   = $this->ende_crypter('encrypt', $username, $ekey, $eiv);
        $pin    = random_int(100000, 999999); // rand() → random_int()
        $code   = $this->randKey();
        $status = 0; $dvd = 0; $mvd = 0; $ban = 1; $is_actd = 0;

        try {
            // INSERT uverify
            $stmt1 = $this->connection->prepare(
                "INSERT INTO uverify (iduv,username,email,password,mktoken,mkkey,mkhash,mkpin,activation_code,is_activate,banned)
            VALUES (:iduv,:username,:email,:password,:mktoken,:mkkey,:mkhash,:mkpin,:activation_code,:is_activate,:banned)"
            );
            $stmt1->execute([
                ':iduv' => $newid, ':username' => $username, ':email' => $email, ':password' => $pass,
                ':mktoken' => $ekey, ':mkkey' => $eiv, ':mkhash' => $enck, ':mkpin' => $pin,
                ':activation_code' => $code, ':is_activate' => $is_actd, ':banned' => $ban
            ]);

            // INSERT users
            $stmt = $this->connection->prepare(
                "INSERT INTO users (idUser,username,email,password,status,ip,signup_time,email_verified,document_verified,mobile_verified,mkpin)
            VALUES (:idUser,:username,:email,:password,:status,:ip,:signup_time,:email_verified,:document_verified,:mobile_verified,:mkpin)"
            );
            $stmt->execute([
                ':idUser' => $newid, ':username' => $eusr, ':email' => $cml, ':password' => $pass,
                ':status' => $status, ':ip' => $this->ip, ':signup_time' => $this->time,
                ':email_verified' => $code, ':document_verified' => $dvd, ':mobile_verified' => $mvd, ':mkpin' => $pin
            ]);

            // INSERT profiles
            $info = $this->connection->prepare("INSERT INTO profiles(idp,mkhash) VALUES (:idp,:mkhash)");
            $info->execute([':idp' => $newid, ':mkhash' => $enck]);

            if ($stmt1->rowCount() === 1 && $stmt->rowCount() === 1 && $info->rowCount() === 1) {
                $query = $this->connection->prepare("SELECT iduv, mkpin FROM uverify WHERE username=:u AND email=:e AND password=:p");
                $query->execute([':u' => $username, ':e' => $email, ':p' => $pass]);
                $row = $query->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $_SESSION['uid'] = $row['iduv'];
                    $this->updatePIN($row['iduv'], $row['mkpin']);
                    $this->sendPMailer($email, $username, $pin, $code, $enck);
                    $_SESSION['SuccessMessage'] = 'Remember! Save this, your PIN code is: ' . $pin;
                    $this->rVerify();
                } else {
                    $_SESSION['ErrorMessage'] = 'Security log could not be completed.';
                }
            } else {
                $_SESSION['ErrorMessage'] = 'User creation failed.';
            }
        } catch (PDOException $e) {
            error_log('NewUser Register error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Database error. Please try again later.';
        }
    }

    public function rVerify(): bool { return true; }

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
            error_log('NewUser Mailer Error: ' . $e->getMessage());
            echo "<h4>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</h4>";
        }
    }

    private function updatePIN(string $upid, int $upin): void {
        $update = $this->connection->prepare("UPDATE users SET mkpin = :pin WHERE idUser = :id");
        $update->execute([':pin' => $upin, ':id' => $upid]);
        if ($update->rowCount() === 1) {
            $_SESSION['SuccessMessage'] = 'The user has successfully registered! <meta http-equiv="refresh" content="3;URL=index.php" />';
        }
    }

    /** @deprecated Usa $this->gc->generateSecureRandomString() */
    public function generateRandStr(int $length): string {
        return $this->gc->generateSecureRandomString($length, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }
}
