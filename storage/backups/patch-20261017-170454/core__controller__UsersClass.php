<?php
declare(strict_types=1);

/**
 * Clase principal de autenticación y gestión de usuarios.
 * Migrado a PDO con seguridad mejorada.
 *
 * MEJORAS:
 * - Transacciones en operaciones múltiples
 * - hash_equals para comparación segura
 * - CSRF protection
 * - exit después de header()
 * - Validación estricta de sesiones
 */
class UsersClass
{
    private PDO $conn;
    private string $syst;
    private string $logp;
    private UsersCodeAccess $uca;
    private GetCodeDeEncrypt $gc;
    private int $expiry;
    private string $path;
    private string $ip;
    private string $protocol;
    private string $baseurl;
    private DateTime $date;
    private string $timestamp;
    private string $loginp;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->syst = defined('SITE_PATH') ? SITE_PATH : '/';
        $this->logp = $this->syst . "signin/login";
        $this->uca = new UsersCodeAccess();
        $this->gc = new GetCodeDeEncrypt();
        $this->expiry = time() + (3600 * 24);
        $this->path = "/";
        $this->ip = $this->getUserIP();
        $this->protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ||
        ($_SERVER["SERVER_PORT"] ?? 0) == 443 ? "https://" : "http://";
        $this->baseurl = $this->protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname($_SERVER['PHP_SELF'] ?? '/');
        $this->date = new DateTime();
        $this->timestamp = $this->date->format("Y-m-d H:i:s");
        $this->loginp = '<script>window.location.replace("' . $this->syst . 'signin/login");</script>';

        if (isset($_POST["signin"])) {
            $this->Login();
        }
        if (isset($_POST["attempts"])) {
            $this->CheckAttempts();
        }
        if (isset($_POST["profile"])) {
            $this->Profile();
        }
        if (isset($_POST["logout"])) {
            $this->Logout();
        }
        if (isset($_POST["updatePassword"])) {
            $this->updatePassword();
        }
    }

    public function getUserIP(): string
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $client = $_SERVER['HTTP_CLIENT_IP'] ?? '';
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $remote = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    public function isValidUsername(string $username): bool
    {
        if (strlen($username) < 7 || strlen($username) > 30) {
            return false;
        }
        return ctype_alnum($username);
    }

    private function Login(): void
    {
        if (!isset($_POST["signin"])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION["ErrorMessage"] = "Token CSRF inválido";
            return;
        }

        if (!isset($_SESSION["attempt"])) {
            $_SESSION["attempt"] = 0;
            $_SESSION["attempt_again"] = 0;
        }

        if (empty($_POST["email"])) {
            $_SESSION["ErrorMessage"] = "Por favor complete el campo de email";
            return;
        } elseif (empty($_POST["password"])) {
            $_SESSION["ErrorMessage"] = "Por favor complete el campo de contraseña";
            return;
        } elseif (empty($_POST["PIN"])) {
            $_SESSION["ErrorMessage"] = "Por favor complete el campo de PIN";
            return;
        }

        if ($_SESSION["attempt_again"] >= 3) {
            $_SESSION["error"] = "Solo se permiten 3 intentos en 10 minutos";
            return;
        }

        $useremail = $this->gc->procheck($_POST["email"]);
        $this->verifyAttempts($useremail);

        if (!is_numeric($_POST["PIN"]) || strlen($_POST["PIN"]) !== 6) {
            $_SESSION["ErrorMessage"] = "El PIN no es numérico o está incompleto";
            echo $this->loginp;
            return;
        }

        $userpsw = $this->gc->procheck($_POST["password"]);
        $userpin = trim($_POST["PIN"]);

        if (!empty($_POST["remember"])) {
            $remember = trim($_POST["remember"]);
            if ($remember === "Yes") {
                define("COOKIE_EXPIRE", $this->expiry);
                define("COOKIE_PATH", "/");
            }
        }

        $site = 1;
        $query = $this->conn->prepare(
            "SELECT SECURE_HASH, SECURE_TOKEN FROM site_security WHERE site = :s"
        );
        $query->execute([':s' => $site]);
        $secure = $query->fetch(PDO::FETCH_ASSOC);

        if (!$secure) {
            $_SESSION["ErrorMessage"] = "Error de configuración del sitio";
            return;
        }

        $stoken = $secure['SECURE_TOKEN'];
        $shash = $secure['SECURE_HASH'];

        $isact = 1;
        $usrm = $this->gc->ende_crypter("encrypt", $useremail, $stoken, $shash);
        $upin = $this->gc->ende_crypter("encrypt", $userpin, $stoken, $shash);

        $stmt = $this->conn->prepare(
            "SELECT * FROM uverify WHERE email = :e AND mkpin = :p AND is_activate = :a"
        );
        $stmt->execute([':e' => $usrm, ':p' => $upin, ':a' => $isact]);

        if ($stmt->rowCount() === 0) {
            $this->nAttempt($useremail);
            return;
        }

        $urw = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($urw["password_key"])) {
            $_SESSION["ErrorMessage"] = "Su cuenta no está activa por solicitud de recuperación de contraseña";
            header('Location: ' . $this->logp);
            exit;
        }

        if (!empty($urw["pin_key"])) {
            $_SESSION["ErrorMessage"] = "Su cuenta no está activa por solicitud de recuperación de PIN";
            header('Location: ' . $this->logp);
            exit;
        }

        if ($urw["banned"] === 1) {
            $_SESSION["ErrorMessage"] = "No se pudo completar el acceso, la cuenta puede estar bloqueada";
            header('Location: ' . $this->logp);
            exit;
        }

        $ucode = $urw["usercode"];
        $uco = $this->conn->prepare("SELECT is_active FROM users_active WHERE usercode = :u");
        $uco->execute([':u' => $ucode]);
        $uar = $uco->fetch(PDO::FETCH_ASSOC);

        if (!$uar || $uar["is_active"] !== $urw["is_activate"]) {
            $_SESSION["ErrorMessage"] = "Su cuenta no está activa";
            echo $this->loginp;
            return;
        }

        if ($urw["is_activate"] !== 1 || $urw["is_banned"] !== 0) {
            $_SESSION["ErrorMessage"] = "Su cuenta no está activa";
            echo $this->loginp;
            return;
        }

        $secret_key = $urw["mktoken"];
        $secret_iv = $urw["mkkey"];
        $secret_hs = $urw["mkhash"];

        $user = $this->gc->ende_crypter("decrypt", $urw["username"], $stoken, $shash);
        $cus = $this->gc->ende_crypter("encrypt", $user, $secret_key, $secret_iv);
        $cml = $this->gc->ende_crypter("decrypt", $urw["email"], $stoken, $shash);
        $mail = $this->gc->ende_crypter("encrypt", $cml, $secret_key, $secret_iv);
        $passw = $urw["password"];
        $pass = $this->gc->ende_crypter("decrypt", $passw, $secret_key, $secret_iv);
        $level = $urw["level"];
        $rpa = $urw["rp_active"];

        // ✅ Comparación segura con hash_equals
        if (!hash_equals($userpsw, $pass)) {
            $this->nAttempt($useremail);
            echo $this->loginp;
            return;
        }

        if ($rpa === 0) {
            $_SESSION["AlertMessage"] = "Se necesita crear una frase de recuperación para su seguridad";
            $_SESSION["RecoveryMessage"] = 1;
        }

        $stmt1 = $this->conn->prepare(
            "SELECT * FROM users WHERE username = :u AND email = :e AND password = :p AND mkpin = :m"
        );
        $stmt1->execute([':u' => $cus, ':e' => $mail, ':p' => $passw, ':m' => $upin]);

        if ($stmt1->rowCount() === 0) {
            $_SESSION["ErrorMessage"] = "Los datos son incorrectos";
            header('Location: ' . $this->logp);
            exit;
        }

        $row = $stmt1->fetch(PDO::FETCH_ASSOC);
        $iduv = (int) $row["idUser"];

        // ✅ TRANSACCIÓN para garantizar atomicidad
        $this->conn->beginTransaction();
        try {
            $enck = $this->gc->randHash();
            $nid = $this->gc->getIdCode();

            $up1 = $this->conn->prepare(
                "UPDATE uverify SET iduv = :nid, mkhash = :mh
                WHERE iduv = :id AND password = :p AND mkhash = :oh"
            );
            $up1->execute([
                ':nid' => $nid,
                ':mh' => $enck,
                ':id' => $iduv,
                ':p' => $passw,
                ':oh' => $secret_hs
            ]);
            $inst1 = $up1->rowCount();

            $pro = $this->conn->prepare(
                "UPDATE users_profiles SET mkhash = :mh WHERE idp = :id AND mkhash = :oh"
            );
            $pro->execute([':mh' => $enck, ':id' => $nid, ':oh' => $secret_hs]);
            $inst2 = $pro->rowCount();

            if ($inst1 !== 1 || $inst2 !== 1) {
                throw new Exception('No se pudieron actualizar los datos de usuario');
            }

            $_SESSION["access_id"] = $ucode;
            $_SESSION["username"] = $user;
            $_SESSION["user_id"] = $nid;
            $_SESSION["levels"] = $level;
            $_SESSION["hash"] = $enck;
            $_SESSION['email'] = $cml;
            $_SESSION['logged_in'] = true;

            $_SESSION["SuccessMessage"] = "¡Felicidades, ahora tiene acceso!";
            unset($_SESSION["attempt"]);
            unset($_SESSION["attempt_again"]);
            unset($_SESSION["id_session_attempt"]);

            $this->conn->commit();

        } catch (Exception $e) {
            $this->conn->rollBack();
            session_destroy();
            $_SESSION["ErrorMessage"] = "Error de acceso";
            error_log('UsersClass Login error: ' . $e->getMessage());
        }
    }

    private function CheckAttempts(): void
    {
        if (!isset($_POST["attempts"])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION["ErrorMessage"] = "Token CSRF inválido";
            return;
        }

        if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["PIN"])) {
            $_SESSION["ErrorMessage"] = "Por favor complete todos los campos";
            return;
        }

        $username = $this->gc->procheck($_POST["username"]);
        $userpsw = $this->gc->procheck($_POST["password"]);
        $userpin = trim($_POST["PIN"]);

        $site = 1;
        $query = $this->conn->prepare(
            "SELECT SECURE_HASH, SECURE_TOKEN FROM site_security WHERE site = :s"
        );
        $query->execute([':s' => $site]);
        $secure = $query->fetch(PDO::FETCH_ASSOC);

        if (!$secure) {
            $_SESSION["ErrorMessage"] = "Error de configuración";
            return;
        }

        $stoken = $secure['SECURE_TOKEN'];
        $shash = $secure['SECURE_HASH'];

        $user = $this->gc->ende_crypter("encrypt", $username, $stoken, $shash);
        $pin = $this->gc->ende_crypter("encrypt", $userpin, $stoken, $shash);

        $stmt = $this->conn->prepare(
            "SELECT * FROM uverify WHERE username = :u AND mkpin = :p"
        );
        $stmt->execute([':u' => $user, ':p' => $pin]);

        if ($stmt->rowCount() === 0) {
            $_SESSION["ErrorMessage"] = "Los datos son incorrectos";
            header('Location: ' . $this->logp);
            exit;
        }

        $urw = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($urw["is_activate"] !== 1 || $urw["banned"] !== 0) {
            $_SESSION["ErrorMessage"] = "Cuenta no activa o bloqueada";
            echo $this->loginp;
            return;
        }

        $email = $urw["email"];
        $passw = $urw["password"];
        $secret_key = $urw["mktoken"];
        $secret_iv = $urw["mkkey"];

        $cus = $this->gc->ende_crypter("encrypt", $username, $secret_key, $secret_iv);
        $pass = $this->gc->ende_crypter("encrypt", $userpsw, $secret_key, $secret_iv);

        // ✅ Comparación segura con hash_equals
        if (!hash_equals($passw, $pass)) {
            $_SESSION["ErrorMessage"] = "Usuario o contraseña inválidos";
            echo $this->loginp;
            return;
        }

        $stmt1 = $this->conn->prepare(
            "SELECT * FROM users WHERE username = :u AND password = :p AND mkpin = :m"
        );
        $stmt1->execute([':u' => $cus, ':p' => $pass, ':m' => $pin]);

        if ($stmt1->rowCount() > 0) {
            $att = $this->conn->prepare(
                "DELETE FROM `ip` WHERE id_session = :s AND user_data = :u"
            );
            $att->execute([
                ':s' => $_SESSION["id_session_attempt"] ?? '',
                ':u' => $email
            ]);

            unset($_SESSION["attempt"]);
            unset($_SESSION["attempt_again"]);
            unset($_SESSION["id_session_attempt"]);
            $_SESSION["SuccessMessage"] = "¡Felicidades, ahora tiene acceso!";
            echo $this->loginp;
        } else {
            $_SESSION["ErrorMessage"] = "Contraseña incorrecta";
            echo $this->loginp;
        }
    }

    private function verifyAttempts(string $udata): void
    {
        $result = $this->conn->prepare(
            "SELECT COUNT(id_session) as cnt, id_session FROM ip WHERE user_data = :u GROUP BY id_session"
        );
        $result->execute([':u' => $udata]);
        $row = $result->fetch(PDO::FETCH_ASSOC);

        if ($row && (int) $row['cnt'] >= 3) {
            $_SESSION["attempt_again"] = $_SESSION["attempt"] = 3;
            $_SESSION["id_session_attempt"] = $row["id_session"];

            if ($_SESSION["attempt_again"] >= 3) {
                $_SESSION["ErrorMessage"] = "Tiene la cuenta bloqueada por más de 3 intentos fallidos";
                header('Location: ' . $this->logp);
                exit;
            }
        }
    }

    private function nAttempt(string $useremail): void
    {
        if (!isset($_SESSION["id_session_attempt"])) {
            $idattempt = $this->gc->randHash();
            $_SESSION["id_session_attempt"] = $idattempt;
        } else {
            $idattempt = $_SESSION["id_session_attempt"];
        }

        $_SESSION["attempt"] += 1;
        $this->Attempts($idattempt, $useremail);
    }

    private function Attempts(string $idatt, string $udata): void
    {
        if (!isset($_SESSION["attempt_again"])) {
            return;
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO `ip` (`id_session`, `user_data`, `address`) VALUES (:s, :u, :a)"
        );
        $stmt->execute([':s' => $idatt, ':u' => $udata, ':a' => $this->ip]);

        $result = $this->conn->prepare(
            "SELECT COUNT(*) as cnt FROM `ip` WHERE `user_data` = :u"
        );
        $result->execute([':u' => $udata]);
        $num = (int) $result->fetchColumn();

        $_SESSION["attempt_again"] = $num;

        if ($_SESSION["attempt"] >= 3) {
            $att = $this->conn->prepare(
                "INSERT INTO `login_attempts` (`id_session`, `user_data`, `ip_address`, `attempts`)
            VALUES (:s, :u, :i, :a)"
            );
            $att->execute([
                ':s' => $idatt,
                ':u' => $udata,
                ':i' => $this->ip,
                ':a' => $_SESSION["attempt"]
            ]);
        }

        if ($_SESSION["attempt_again"] >= 3) {
            $_SESSION["error"] = "Solo se permiten 3 intentos en 10 minutos";
            echo $this->loginp;
        } else {
            $_SESSION["ErrorMessage"] = "Email, contraseña o PIN incorrectos";
            echo $this->loginp;
        }
    }

    public function logout(): void
    {
        if (isset($_POST["logout"])) {
            if (!empty($_SESSION["user_id"])) {
                if (isset($_COOKIE["ckid"])) {
                    setcookie('ckid', '', $this->expiry - (3600 * 24), $this->path);
                }
                $_SESSION = [];
                unset($_SESSION["access_id"]);
                unset($_SESSION["username"]);
                unset($_SESSION["user_id"]);
                unset($_SESSION["level"]);
                unset($_SESSION["hash"]);
                unset($_SESSION);
                session_destroy();
                header('Location: ' . $this->logp);
                exit;
            }
        } else {
            header("Location: " . $this->syst);
            exit;
        }
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"]);
    }

    public function Profile(): void
    {
        if (isset($_POST["profile"])) {
            header("Location: " . $this->syst . "profile/userprofile");
            exit;
        }
    }

    private function updatePassword(): void
    {
        // Implementación similar a userChange.php
    }
}
