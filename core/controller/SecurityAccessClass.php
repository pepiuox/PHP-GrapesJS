<?php
declare(strict_types=1);

/**
 * Clase de acceso seguro con autenticación y RBAC.
 * Migrado a PDO con seguridad mejorada.
 *
 * CORRECCIONES CRÍTICAS:
 * - md5() reemplazado por password_hash()/password_verify()
 * - CSRF protection añadida
 * - Rate limiting básico
 * - Cookies seguras (HttpOnly, Secure, SameSite)
 * - Validación de entrada
 * - exit después de header()
 */
class SecurityAccess
{
    private string $dbHost;
    private string $dbName;
    private string $dbUser;
    private string $dbPass;
    private string $userSessionKey = 'securedUserSession';
    private PDO $conn;

    public function __construct(
        string $host = '',
        string $db = '',
        string $user = '',
        string $pass = ''
    ) {
        // ✅ Validar que las credenciales no estén vacías
        if (empty($host) || empty($db) || empty($user)) {
            throw new InvalidArgumentException('Credenciales de BD incompletas');
        }

        $this->dbHost = $host;
        $this->dbName = $db;
        $this->dbUser = $user;
        $this->dbPass = $pass;

        $this->connectDB();
        $this->configureSession();
    }

    /**
     * Configura la sesión con parámetros seguros.
     */
    private function configureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // ✅ Configuración segura de sesión
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', '1');
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_lifetime', '0');

            session_start();
        }

        // ✅ Regenerar ID de sesión periódicamente
        if (!isset($_SESSION['_last_regeneration'])) {
            session_regenerate_id(true);
            $_SESSION['_last_regeneration'] = time();
        } elseif (time() - $_SESSION['_last_regeneration'] > 300) {
            session_regenerate_id(true);
            $_SESSION['_last_regeneration'] = time();
        }
    }

    private function connectDB(): void
    {
        try {
            $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->dbUser, $this->dbPass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log('DB Connection failed: ' . $e->getMessage());
            // ✅ No exponer detalles al usuario
            throw new RuntimeException('Error de conexión a la base de datos');
        }
    }

    /**
     * Registra un nuevo usuario.
     *
     * ✅ CORREGIDO: ahora usa password_hash() en lugar de md5()
     */
    public function registerUser(
        string $username,
        string $email,
        string $password,
        string $role = 'user'
    ): string|bool {
        // ✅ Validación de entrada
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Email inválido';
        }
        if (strlen($username) < 3 || strlen($username) > 50) {
            return 'Nombre de usuario debe tener entre 3 y 50 caracteres';
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return 'Nombre de usuario solo puede contener letras, números y guiones bajos';
        }
        if (strlen($password) < 8) {
            return 'La contraseña debe tener al menos 8 caracteres';
        }

        // ✅ Lista blanca de roles
        $allowedRoles = ['user', 'admin', 'editor', 'moderator'];
        if (!in_array($role, $allowedRoles, true)) {
            return 'Rol inválido';
        }

        try {
            // ✅ password_hash() en lugar de md5()
            $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            $stmt = $this->conn->prepare(
                "INSERT INTO users (username, email, password, role, created_at)
            VALUES (:username, :email, :password, :role, NOW())"
            );
            $stmt->execute([
                ':username' => $username,
                ':email'    => $email,
                ':password' => $passwordHash,
                ':role'     => $role,
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('Registration error: ' . $e->getMessage());
            if ($e->getCode() === '23000') {
                return 'El nombre de usuario o email ya existe';
            }
            return 'Error en el registro';
        }
    }

    /**
     * Login con rate limiting básico.
     *
     * ✅ CORREGIDO: ahora usa password_verify() en lugar de md5()
     */
    public function loginUser(string $username, string $password): string|bool
    {
        // ✅ Rate limiting
        if (!$this->checkRateLimit()) {
            return 'Demasiados intentos. Intente más tarde.';
        }

        try {
            $stmt = $this->conn->prepare(
                "SELECT id, username, email, password, role
                FROM users WHERE username = :username LIMIT 1"
            );
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            // ✅ password_verify() en lugar de comparación md5
            if ($user && password_verify($password, $user['password'])) {
                // ✅ Regenerar ID de sesión tras login exitoso
                session_regenerate_id(true);

                // ✅ No almacenar password en sesión
                unset($user['password']);
                $_SESSION[$this->userSessionKey] = $user;
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['last_activity'] = time();

                $this->resetRateLimit();
                return true;
            }

            $this->recordFailedAttempt();
            return 'Usuario o contraseña inválidos';
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            return 'Error en el login';
        }
    }

    public function isUserLoggedIn(): bool
    {
        if (!isset($_SESSION[$this->userSessionKey])) {
            return false;
        }
        // ✅ Verificar timeout de sesión
        $timeout = 1800; // 30 minutos
        if (isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity']) > $timeout) {
            $this->logoutUser();
        return false;
            }
            $_SESSION['last_activity'] = time();
            return true;
    }

    public function getLoggedInUser(): ?array
    {
        return $this->isUserLoggedIn() ? $_SESSION[$this->userSessionKey] : null;
    }

    public function logoutUser(): bool
    {
        if ($this->isUserLoggedIn()) {
            $_SESSION = [];

            // ✅ Eliminar cookie de sesión
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                          '',
                          time() - 42000,
                          $params['path'],
                          $params['domain'],
                          $params['secure'],
                          $params['httponly']
                );
            }

            session_destroy();
            return true;
        }
        return false;
    }

    /**
     * Control de acceso basado en roles (RBAC).
     */
    public function hasAccess(string $requiredRole): bool
    {
        $user = $this->getLoggedInUser();
        if (!$user || !isset($user['role'])) {
            return false;
        }
        // ✅ Admin tiene acceso a todo
        return in_array($user['role'], [$requiredRole, 'admin'], true);
    }

    /**
     * Genera un token CSRF.
     */
    public function generateCSRFToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica un token CSRF.
     */
    public function verifyCSRFToken(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /* ---------- Rate Limiting ---------- */
    private function checkRateLimit(): bool
    {
        $maxAttempts = 5;
        $windowSeconds = 300; // 5 minutos

        if (!isset($_SESSION['login_attempts'])) {
            return true;
        }

        $attempts = $_SESSION['login_attempts'];
        $now = time();

        // Limpiar intentos antiguos
        $attempts = array_filter($attempts, fn($t) => ($now - $t) < $windowSeconds);
        $_SESSION['login_attempts'] = $attempts;

        return count($attempts) < $maxAttempts;
    }

    private function recordFailedAttempt(): void
    {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [];
        }
        $_SESSION['login_attempts'][] = time();
    }

    private function resetRateLimit(): void
    {
        unset($_SESSION['login_attempts']);
    }
}
