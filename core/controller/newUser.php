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
            $conn = \PepiuoX\Database\Connection::shared();
        }

        $this->uca = new UsersCodeAccess($conn);
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

    /**
     * Carga el autoloader de Composer (que ya provee PHPMailer).
     *
     * Las rutas relativas dependian del directorio de trabajo y fallaban cuando el archivo
     * se incluia desde otro contexto.
     */
    private function includes(): void {
        $autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';

        if (is_file($autoload)) {
            require_once $autoload;
        }
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

    /**
     * Claves del sitio (`SECURE_TOKEN`, `SECURE_HASH`) leidas de `site_security`.
     *
     * @return array{0:string,1:string}|null
     */
    private function siteKeys(): ?array {
        try {
            $stmt = $this->connection->prepare(
                'SELECT SECURE_TOKEN, SECURE_HASH FROM site_security WHERE site = :site LIMIT 1'
            );
            $stmt->execute([':site' => 1]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row === false || (string) $row['SECURE_TOKEN'] === '' || (string) $row['SECURE_HASH'] === '') {
                return null;
            }

            return [(string) $row['SECURE_TOKEN'], (string) $row['SECURE_HASH']];
        } catch (PDOException $e) {
            error_log('NewUser::siteKeys error: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * ¿Existe ya ese nombre de usuario?
     *
     * El valor se cifra con las claves del sitio antes de comparar: en `uverify` el usuario
     * se guarda cifrado, asi que comparar en claro no detectaba duplicados.
     */
    public function checkUsername(string $username): int {
        $keys = $this->siteKeys();

        if ($keys === null) {
            return 0;
        }

        $encrypted = $this->ende_crypter('encrypt', $username, $keys[0], $keys[1]);

        if (!is_string($encrypted)) {
            return 0;
        }

        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM uverify WHERE username = :u');
        $stmt->execute([':u' => $encrypted]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * ¿Existe ya ese correo? Igual que `checkUsername()`, compara el valor cifrado.
     */
    public function checkEmail(string $email): int {
        $keys = $this->siteKeys();

        if ($keys === null) {
            return 0;
        }

        $encrypted = $this->ende_crypter('encrypt', $email, $keys[0], $keys[1]);

        if (!is_string($encrypted)) {
            return 0;
        }

        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM uverify WHERE email = :e');
        $stmt->execute([':e' => $encrypted]);

        return (int) $stmt->fetchColumn();
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

    /** Rol asignado a los registros publicos; debe existir en `users_roles.name`. */
    private const DEFAULT_ROLE = 'Stantard User';

    /**
     * Si es `true`, la cuenta queda pendiente de verificacion por correo
     * (`is_activate = 0`) y no podra iniciar sesion hasta activarla.
     */
    private const REQUIRE_EMAIL_VERIFICATION = false;

    /**
     * Da de alta un usuario nuevo con los datos en el mismo formato que exige el acceso.
     *
     * Formato de almacenamiento (lo que `UsersClass::Login()` espera encontrar):
     *  - `uverify.username`, `uverify.email` y `uverify.mkpin`: cifrados con las claves del
     *    sitio (necesario para poder buscar por igualdad).
     *  - `users.username` y `users.email`: cifrados con las claves del usuario.
     *  - `users.mkpin`: cifrado con las claves del sitio (el acceso lo descifra asi).
     *  - `users.password` y `uverify.password`: cifrados con las claves del usuario.
     *
     * Todo se escribe en una transaccion: o queda el usuario completo con sus filas de
     * activacion y rol, o no queda nada.
     *
     * @return void
     */
    private function Register(): void {
        if (!isset($_POST['register'])) {
            return;
        }

        $username   = trim((string) ($_POST['username'] ?? ''));
        $email      = trim((string) ($_POST['email'] ?? ''));
        $password   = (string) ($_POST['password'] ?? '');
        $repassword = (string) ($_POST['password2'] ?? '');
        $agree      = (string) ($_POST['agreeTerms'] ?? '');

        if ($agree !== 'agree') {
            $_SESSION['ErrorMessage'] = 'You need to accept the terms and conditions!';

            return;
        }

        if ($username === '' || $email === '' || $password === '' || $repassword === '') {
            $_SESSION['ErrorMessage'] = 'Fill in the fields!';

            return;
        }

        if (!$this->risValidUsername($username)) {
            $_SESSION['ErrorMessage'] = 'Please enter a valid user!';

            return;
        }

        if (!$this->risValidEmail($email)) {
            return;
        }

        if (strlen($password) < 8) {
            $_SESSION['ErrorMessage'] = 'La contrasena debe tener al menos 8 caracteres.';

            return;
        }

        if ($password !== $repassword) {
            $_SESSION['ErrorMessage'] = 'The password does not match!';

            return;
        }

        if ($this->checkUsername($username) > 0) {
            $_SESSION['ErrorMessage'] = 'User already exists!';

            return;
        }

        if ($this->checkEmail($email) > 0) {
            $_SESSION['ErrorMessage'] = 'Email already exists!';

            return;
        }

        $keys = $this->siteKeys();

        if ($keys === null) {
            error_log('NewUser::Register sin claves de sitio (site_security).');
            $_SESSION['ErrorMessage'] = 'No se pudo completar el registro. Intente mas tarde.';

            return;
        }

        [$siteToken, $siteHash] = $keys;

        $userKey  = $this->randToken();
        $userIv   = $this->randKey();
        $userHash = $this->randHash();
        $newId    = bin2hex(random_bytes(32));
        $usercode = $this->gc->getRandomCode();
        $pin      = (string) random_int(100000, 999999);
        $code     = $this->gc->getIdCode();
        $levelKey = $this->gc->iRandKey(64);
        $role     = self::DEFAULT_ROLE;

        $withSite = $this->ende_crypter('encrypt', $username, $siteToken, $siteHash);
        $mailSite = $this->ende_crypter('encrypt', $email, $siteToken, $siteHash);
        $pinSite  = $this->ende_crypter('encrypt', $pin, $siteToken, $siteHash);
        $pass     = $this->ende_crypter('encrypt', $password, $userKey, $userIv);
        $userUsr  = $this->ende_crypter('encrypt', $username, $userKey, $userIv);
        $userMail = $this->ende_crypter('encrypt', $email, $userKey, $userIv);

        foreach ([$withSite, $mailSite, $pinSite, $pass, $userUsr, $userMail] as $cipher) {
            if (!is_string($cipher) || $cipher === '') {
                error_log('NewUser::Register fallo al cifrar los datos del alta.');
                $_SESSION['ErrorMessage'] = 'No se pudo completar el registro. Intente mas tarde.';

                return;
            }
        }

        $isActive = self::REQUIRE_EMAIL_VERIFICATION ? 0 : 1;

        $this->connection->beginTransaction();

        try {
            $verify = $this->connection->prepare(
                'INSERT INTO uverify
                    (iduv, usercode, username, email, password, usr_type, mktoken, mkkey, mkhash, mkpin,
                     level, level_key, activation_code, rp_active, is_activate, is_verified, is_banned)
                 VALUES
                    (:iduv, :usercode, :username, :email, :password, :usr_type, :mktoken, :mkkey, :mkhash, :mkpin,
                     :level, :level_key, :activation_code, :rp_active, :is_activate, :is_verified, :is_banned)'
            );
            $verify->execute([
                ':iduv'            => $newId,
                ':usercode'        => $usercode,
                ':username'        => $withSite,
                ':email'           => $mailSite,
                ':password'        => $pass,
                ':usr_type'        => 'guest',
                ':mktoken'         => $userKey,
                ':mkkey'           => $userIv,
                ':mkhash'          => $userHash,
                ':mkpin'           => $pinSite,
                ':level'           => $role,
                ':level_key'       => $levelKey,
                ':activation_code' => $code,
                ':rp_active'       => 0,
                ':is_activate'     => $isActive,
                ':is_verified'     => 0,
                ':is_banned'       => 0,
            ]);

            if ($verify->rowCount() !== 1) {
                throw new RuntimeException('No se pudo crear la fila de verificacion.');
            }

            $user = $this->connection->prepare(
                'INSERT INTO users
                    (idUser, usercode, username, email, password, usr_type, verified, status, ip, signup_time, email_verified, mkpin)
                 VALUES
                    (:idUser, :usercode, :username, :email, :password, :usr_type, :verified, :status, :ip, :signup_time, :email_verified, :mkpin)'
            );
            $user->execute([
                ':idUser'        => $newId,
                ':usercode'      => $usercode,
                ':username'      => $userUsr,
                ':email'         => $userMail,
                ':password'      => $pass,
                ':usr_type'      => 'guest',
                ':verified'      => 0,
                ':status'        => 0,
                ':ip'            => $this->ip,
                ':signup_time'   => $this->time,
                ':email_verified' => $code,
                ':mkpin'         => $pinSite,
            ]);

            if ($user->rowCount() !== 1) {
                throw new RuntimeException('No se pudo crear la ficha del usuario.');
            }

            $profile = $this->connection->prepare(
                'INSERT INTO users_profiles (idp, usercode, mkhash) VALUES (:idp, :usercode, :mkhash)'
            );
            $profile->execute([':idp' => $newId, ':usercode' => $usercode, ':mkhash' => $userHash]);

            $action = $this->connection->prepare(
                'INSERT INTO users_actions (usercode, action, validation) VALUES (:usercode, :action, :validation)'
            );
            $action->execute([':usercode' => $usercode, ':action' => 'validation', ':validation' => $code]);

            $active = $this->connection->prepare(
                'INSERT INTO users_active (usercode, is_active) VALUES (:usercode, :is_active)'
            );
            $active->execute([':usercode' => $usercode, ':is_active' => $isActive]);

            $assign = $this->connection->prepare(
                'INSERT INTO users_assigned_role (usercode, role_id)
                 SELECT :usercode, idRol FROM users_roles WHERE name = :role LIMIT 1'
            );
            $assign->execute([':usercode' => $usercode, ':role' => $role]);

            $this->connection->commit();
        } catch (Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            error_log('NewUser::Register error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Database error. Please try again later.';

            return;
        }

        $_SESSION['uid'] = $newId;
        $_SESSION['SuccessMessage'] = 'Registro correcto. Guarde su PIN de acceso: ' . $pin;

        $this->sendPMailer($email, $username, $pin, $code, $userHash);
    }

    public function rVerify(): bool {
        return true;
    }

    /**
     * Envia el PIN y el codigo de activacion. Best effort: un fallo de correo no deshace
     * el alta (ya confirmada en base de datos) y nunca se muestra informacion tecnica.
     */
    private function sendPMailer(string $email, string $username, string $pin, string $code, string $hash): void {
        try {
            $mailSettings = $this->connection->prepare(
                'SELECT MAILSERVER, PORTSERVER, USEREMAIL, PASSMAIL FROM site_security WHERE site = :site LIMIT 1'
            );
            $mailSettings->execute([':site' => 1]);
            $config = $mailSettings->fetch(PDO::FETCH_ASSOC);

            if ($config === false || (string) $config['MAILSERVER'] === '') {
                error_log('NewUser::sendPMailer sin configuracion SMTP en site_security.');

                return;
            }

            $keys = $this->siteKeys();

            if ($keys === null) {
                return;
            }

            $fromMail = $this->ende_crypter('decrypt', (string) $config['USEREMAIL'], $keys[0], $keys[1]);
            $fromPass = $this->ende_crypter('decrypt', (string) $config['PASSMAIL'], $keys[0], $keys[1]);

            // Las credenciales de correo pueden estar cifradas con las claves del sitio o
            // guardadas en claro en instalaciones antiguas: se aceptan ambos casos.
            if (!is_string($fromMail)) {
                $fromMail = (string) $config['USEREMAIL'];
            }

            if (!is_string($fromPass)) {
                $fromPass = (string) $config['PASSMAIL'];
            }

            if ($fromMail === '' || $fromPass === '') {
                error_log('NewUser::sendPMailer sin credenciales de correo utilizables.');

                return;
            }

            $safeUsername = htmlspecialchars($username, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $safePin = htmlspecialchars($pin, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $siteUrl = defined('SITE_PATH') ? SITE_PATH : 'http://localhost:150/';

            $body  = '<html><body><h4>Hola ' . $safeUsername . '.</h4>';
            $body .= '<p>Su PIN de acceso es: <b>' . $safePin . '</b><br>';
            $body .= 'Guardelo: lo necesita junto con su correo y contrasena.</p>';
            $body .= '<p>Codigo de activacion: ' . htmlspecialchars($code, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
            $body .= '</body></html>';

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = (string) $config['MAILSERVER'];
            $mail->SMTPAuth = true;
            $mail->Username = $fromMail;
            $mail->Password = $fromPass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = (int) $config['PORTSERVER'];
            $mail->setFrom($fromMail, defined('SITE_NAME') ? SITE_NAME : 'PHP GrapesJS');
            $mail->addAddress($email, $username);
            $mail->isHTML(true);
            $mail->Subject = 'Su acceso a ' . (defined('SITE_NAME') ? SITE_NAME : 'PHP GrapesJS');
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            $mail->send();
        } catch (Throwable $e) {
            error_log('NewUser::sendPMailer error: ' . $e->getMessage());
        }
    }

    /** @deprecated Usa $this->gc->generateSecureRandomString() */
    public function generateRandStr(int $length): string {
        return $this->gc->generateSecureRandomString($length, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }
}
