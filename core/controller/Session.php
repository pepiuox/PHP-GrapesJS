<?php
declare(strict_types=1);

/**
 * Gestión de sesiones y autenticación de usuarios.
 * Migrado a PDO con seguridad mejorada.
 *
 * CORRECCIONES CRÍTICAS:
 * - md5() reemplazado por password_hash()/password_verify()
 * - SQL Injection en updateUserField() corregido (column name injection)
 * - Cookies seguras (HttpOnly, Secure, SameSite)
 * - mt_rand() reemplazado por random_int()
 * - Regex de email mejorada (usando filter_var)
 * - stripslashes() eliminado (deprecado)
 * - CSRF protection añadida
 * - Rate limiting en login
 */
class Session
{
    public Form $form;
    public Mailer $mailer;
    protected PDO $conn;
    private string $username;
    private string $userid;
    private int $userlevel;
    public int $time;
    public bool $logged_in;
    public array $userinfo = [];
    public string $url;
    public string $referrer;

    public function __construct(PDO $connection, Form $form, Mailer $mailer)
    {
        $this->conn = $connection;
        $this->form = $form;
        $this->mailer = $mailer;
        $this->time = time();
        $this->startSession();
    }

    /**
     * Configuración segura de sesión.
     */
    private function configureSession(): void
    {
        // ✅ Configuración segura de cookies de sesión
        $cookieParams = [
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => true,
            'httponly'  => true,
            'samesite'  => 'Strict',
        ];
        session_set_cookie_params($cookieParams);

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_secure', '1');
        ini_set('session.cookie_samesite', 'Strict');
    }

    public function startSession(): void
    {
        $this->configureSession();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->logged_in = $this->checkLogin();

        if (!$this->logged_in) {
            $this->username = $_SESSION['username'] = GUEST_NAME;
            $this->userlevel = GUEST_LEVEL;
            $this->addActiveGuest($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', $this->time);
        } else {
            $this->addActiveUser($this->username, $this->time);
        }

        $this->removeInactiveUsers();
        $this->removeInactiveGuests();

        $this->referrer = $_SESSION['referrer'] ?? '/';
        $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'] ?? '/';
    }

    public function checkLogin(): bool
    {
        // ✅ Verificar cookies "remember me"
        if (isset($_COOKIE['cookname'], $_COOKIE['cookid'])) {
            $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
            $this->userid = $_SESSION['user_id'] = $_COOKIE['cookid'];
        }

        if (isset($_SESSION['username'], $_SESSION['user_id']) &&
            $_SESSION['username'] !== GUEST_NAME) {

            if ($this->confirmUserID($_SESSION['username'], $_SESSION['user_id']) !== 0) {
                unset($_SESSION['username'], $_SESSION['user_id']);
                return false;
            }

            $this->userinfo = $this->getUserInfo($_SESSION['username']);
        if (empty($this->userinfo)) {
            return false;
        }

        $this->username  = $this->userinfo['username'];
        $this->userid    = $this->userinfo['user_id'];
        $this->userlevel = (int) $this->userinfo['userlevel'];
        return true;
            }

            return false;
    }

    /**
     * Login con password_hash/password_verify.
     *
     * ✅ CORREGIDO: ahora usa password_verify() en lugar de md5()
     */
    public function login(string $subuser, string $subpass, bool $subremember): bool
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $this->form->setError('csrf', 'Token CSRF inválido');
            return false;
        }

        // ✅ Rate limiting
        if (!$this->checkLoginRateLimit()) {
            $this->form->setError('user', 'Demasiados intentos. Intente más tarde.');
            return false;
        }

        $field = 'user';
        $subuser = trim($subuser);
        if (empty($subuser)) {
            $this->form->setError($field, '* Ingrese el nombre de usuario');
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $subuser)) {
            $this->form->setError($field, '* Nombre de usuario no alfanumérico');
        }

        $field = 'pass';
        if (empty($subpass)) {
            $this->form->setError($field, '* Ingrese la contraseña');
        }

        if ($this->form->num_errors > 0) {
            return false;
        }

        // ✅ Obtener usuario de BD
        $stmt = $this->conn->prepare(
            'SELECT username, password, userlevel FROM users WHERE username = :u LIMIT 1'
        );
        $stmt->execute([':u' => $subuser]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->form->setError('user', '* Usuario no encontrado');
            $this->recordLoginAttempt();
            return false;
        }

        // ✅ password_verify() en lugar de md5()
        if (!password_verify($subpass, $user['password'])) {
            $this->form->setError('pass', '* Contraseña inválida');
            $this->recordLoginAttempt();
            return false;
        }

        // ✅ Login exitoso
        $this->userinfo = $this->getUserInfo($subuser);
        $this->username = $_SESSION['username'] = $this->userinfo['username'];
        $this->userid = $_SESSION['user_id'] = $this->generateRandID();
        $this->userlevel = (int) $this->userinfo['userlevel'];

        // ✅ Regenerar ID de sesión tras login
        session_regenerate_id(true);

        $this->updateUserFieldSecure($this->username, 'user_id', $this->userid);
        $this->addActiveUser($this->username, $this->time);
        $this->removeActiveGuest($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');

        // ✅ Cookies seguras
        if ($subremember) {
            $cookieOptions = [
                'expires'  => time() + COOKIE_EXPIRE,
                'path'     => COOKIE_PATH,
                'domain'   => '',
                'secure'   => true,
                'httponly'  => true,
                'samesite'  => 'Strict',
            ];
            setcookie('cookname', $this->username, $cookieOptions);
            setcookie('cookid', $this->userid, $cookieOptions);
        }

        $this->resetLoginRateLimit();
        return true;
    }

    public function logout(): void
    {
        if (isset($_COOKIE['cookname'], $_COOKIE['cookid'])) {
            $cookieOptions = [
                'expires'  => time() - 3600,
                'path'     => COOKIE_PATH,
                'domain'   => '',
                'secure'   => true,
                'httponly'  => true,
                'samesite'  => 'Strict',
            ];
            setcookie('cookname', '', $cookieOptions);
            setcookie('cookid', '', $cookieOptions);
        }

        $_SESSION = [];
        $this->logged_in = false;

        $this->removeActiveUser($this->username);
        $this->addActiveGuest($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', $this->time);

        $this->username = GUEST_NAME;
        $this->userlevel = GUEST_LEVEL;

        session_destroy();
    }

    /**
     * Registro con password_hash.
     *
     * ✅ CORREGIDO: ahora usa password_hash() en lugar de md5()
     */
    public function register(string $subuser, string $subpass, string $subemail): int
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $this->form->setError('csrf', 'Token CSRF inválido');
            return 1;
        }

        $subuser = trim($subuser);
        $subemail = trim($subemail);

        // Validación username
        if (empty($subuser)) {
            $this->form->setError('user', '* Nombre de usuario no introducido');
        } elseif (strlen($subuser) < 5 || strlen($subuser) > 30) {
            $this->form->setError('user', '* Nombre de usuario debe tener entre 5 y 30 caracteres');
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $subuser)) {
            $this->form->setError('user', '* Nombre de usuario no alfanumérico');
        } elseif (strcasecmp($subuser, GUEST_NAME) === 0) {
            $this->form->setError('user', '* Nombre de usuario reservado');
        } elseif ($this->usernameTaken($subuser)) {
            $this->form->setError('user', '* Nombre de usuario ya está en uso');
        } elseif ($this->usernameBanned($subuser)) {
            $this->form->setError('user', '* Nombre de usuario prohibido');
        }

        // Validación password
        if (empty($subpass)) {
            $this->form->setError('pass', '* Contraseña no introducida');
        } elseif (strlen($subpass) < 8) {
            $this->form->setError('pass', '* Contraseña demasiado corta (mínimo 8 caracteres)');
        }

        // ✅ Validación email con filter_var (más robusta que regex)
        if (empty($subemail)) {
            $this->form->setError('email', '* Email no introducido');
        } elseif (!filter_var($subemail, FILTER_VALIDATE_EMAIL)) {
            $this->form->setError('email', '* Email no válido');
        }

        if ($this->form->num_errors > 0) {
            return 1;
        }

        // ✅ password_hash() en lugar de md5()
        $passwordHash = password_hash($subpass, PASSWORD_BCRYPT, ['cost' => 12]);

        if ($this->addNewUser($subuser, $passwordHash, $subemail)) {
            if (defined('EMAIL_WELCOME') && EMAIL_WELCOME) {
                $this->mailer->sendWelcome($subuser, $subemail, $subpass);
            }
            return 0;
        }

        return 2;
    }

    /**
     * ✅ CORREGIDO: SQL Injection eliminado.
     * Antes permitía inyección en el nombre de columna.
     * Ahora usa lista blanca de columnas permitidas.
     */
    public function updateUserFieldSecure(string $username, string $field, mixed $value): bool
    {
        // ✅ Lista blanca de columnas permitidas
        $allowedFields = [
            'user_id', 'email', 'name', 'password',
            'userlevel', 'last_activity', 'timestamp'
        ];

        if (!in_array($field, $allowedFields, true)) {
            error_log("updateUserField: campo no permitido: {$field}");
            return false;
        }

        // ✅ Construir query con nombre de columna validado
        $sql = "UPDATE users SET `{$field}` = :value WHERE username = :username";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':value'    => $value,
            ':username' => $username,
        ]);
    }

    public function editAccount(
        string $subcurpass,
        string $subnewpass,
        string $subemail,
        string $subname
    ): bool {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $this->form->setError('csrf', 'Token CSRF inválido');
            return false;
        }

        if (!empty($subnewpass)) {
            if (empty($subcurpass)) {
                $this->form->setError('curpass', '* Contraseña actual no introducida');
            } else {
                // ✅ Verificar contraseña actual con password_verify
                $stmt = $this->conn->prepare(
                    'SELECT password FROM users WHERE username = :u LIMIT 1'
                );
                $stmt->execute([':u' => $this->username]);
                $row = $stmt->fetch();

                if (!$row || !password_verify($subcurpass, $row['password'])) {
                    $this->form->setError('curpass', '* Contraseña actual incorrecta');
                }
            }

            if (strlen($subnewpass) < 8) {
                $this->form->setError('newpass', '* Nueva contraseña demasiado corta');
            }
        } elseif (!empty($subcurpass)) {
            $this->form->setError('newpass', '* Nueva contraseña no introducida');
        }

        if (!empty($subemail) && !filter_var($subemail, FILTER_VALIDATE_EMAIL)) {
            $this->form->setError('email', '* Email no válido');
        }

        if ($this->form->num_errors > 0) {
            return false;
        }

        if (!empty($subcurpass) && !empty($subnewpass)) {
            // ✅ password_hash() en lugar de md5()
            $newHash = password_hash($subnewpass, PASSWORD_BCRYPT, ['cost' => 12]);
            $this->updateUserFieldSecure($this->username, 'password', $newHash);
        }

        if (!empty($subemail)) {
            $this->updateUserFieldSecure($this->username, 'email', $subemail);
        }
        if (!empty($subname)) {
            $this->updateUserFieldSecure($this->username, 'name', $subname);
        }

        return true;
    }

    public function isAdmin(): bool
    {
        return $this->userlevel === ADMIN_LEVEL || $this->username === ADMIN_NAME;
    }

    public function isMaster(): bool
    {
        return $this->userlevel === MASTER_LEVEL;
    }

    public function isAgent(): bool
    {
        return $this->userlevel === AGENT_LEVEL;
    }

    public function isMember(): bool
    {
        return $this->userlevel === AGENT_MEMBER_LEVEL;
    }

    /**
     * ✅ CORREGIDO: random_int() en lugar de mt_rand()
     */
    public function generateRandID(): string
    {
        return hash('sha256', $this->generateRandStr(32) . bin2hex(random_bytes(16)));
    }

    public function generateRandStr(int $length): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max = strlen($chars) - 1;
        $str = '';

        for ($i = 0; $i < $length; $i++) {
            // ✅ random_int() en lugar de mt_rand()
            $str .= $chars[random_int(0, $max)];
        }

        return $str;
    }

    /* ---------- Métodos auxiliares (implementación depende de tu esquema) ---------- */

    private function confirmUserID(string $username, string $userid): int
    {
        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) FROM users WHERE username = :u AND user_id = :uid'
        );
        $stmt->execute([':u' => $username, ':uid' => $userid]);
        return (int) $stmt->fetchColumn();
    }

    public function getUserInfo(string $username): array
    {
        $stmt = $this->conn->prepare(
            'SELECT username, user_id, userlevel FROM users WHERE username = :u LIMIT 1'
        );
        $stmt->execute([':u' => $username]);
        return $stmt->fetch() ?: [];
    }

    private function addActiveUser(string $username, int $time): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO active_users (username, timestamp) VALUES (:u, :t)
        ON DUPLICATE KEY UPDATE timestamp = :t2'
        );
        $stmt->execute([':u' => $username, ':t' => $time, ':t2' => $time]);
    }

    private function addActiveGuest(string $ip, int $time): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO active_guests (ip, timestamp) VALUES (:ip, :t)
        ON DUPLICATE KEY UPDATE timestamp = :t2'
        );
        $stmt->execute([':ip' => $ip, ':t' => $time, ':t2' => $time]);
    }

    private function removeActiveUser(string $username): void
    {
        $stmt = $this->conn->prepare('DELETE FROM active_users WHERE username = :u');
        $stmt->execute([':u' => $username]);
    }

    private function removeActiveGuest(string $ip): void
    {
        $stmt = $this->conn->prepare('DELETE FROM active_guests WHERE ip = :ip');
        $stmt->execute([':ip' => $ip]);
    }

    private function removeInactiveUsers(): void
    {
        $timeout = time() - 1800;
        $stmt = $this->conn->prepare('DELETE FROM active_users WHERE timestamp < :t');
        $stmt->execute([':t' => $timeout]);
    }

    private function removeInactiveGuests(): void
    {
        $timeout = time() - 1800;
        $stmt = $this->conn->prepare('DELETE FROM active_guests WHERE timestamp < :t');
        $stmt->execute([':t' => $timeout]);
    }

    private function usernameTaken(string $username): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function usernameBanned(string $username): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM banned_users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function addNewUser(string $username, string $passwordHash, string $email): bool
    {
        try {
            $stmt = $this->conn->prepare(
                'INSERT INTO users (username, password, email, userlevel, timestamp)
            VALUES (:u, :p, :e, :lvl, :t)'
            );
            return $stmt->execute([
                ':u'   => $username,
                ':p'   => $passwordHash,
                ':e'   => $email,
                ':lvl' => USER_LEVEL,
                ':t'   => time(),
            ]);
        } catch (PDOException $e) {
            error_log('addNewUser error: ' . $e->getMessage());
            return false;
        }
    }

    /* ---------- Rate Limiting ---------- */
    private function checkLoginRateLimit(): bool
    {
        $maxAttempts = 5;
        $window = 300; // 5 minutos

        if (!isset($_SESSION['login_attempts'])) {
            return true;
        }

        $attempts = array_filter($_SESSION['login_attempts'], fn($t) => (time() - $t) < $window);
        $_SESSION['login_attempts'] = $attempts;

        return count($attempts) < $maxAttempts;
    }

    private function recordLoginAttempt(): void
    {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [];
        }
        $_SESSION['login_attempts'][] = time();
    }

    private function resetLoginRateLimit(): void
    {
        unset($_SESSION['login_attempts']);
    }
}
