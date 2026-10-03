<?php
// classes/Auth.php - VERSIÓN MEJORADA CON PDO
session_start();

class Auth {
    private PDO $conn;
    private RoleManager $roleManager;

    public function __construct(PDO $db) {
        $this->conn = $db;
        $this->roleManager = new RoleManager($db);
    }

    /* ========== MÉTODOS ESTÁTICOS DE SESIÓN ========== */

    public static function check(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function getUserId(): ?int {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public static function getUsername(): ?string {
        return $_SESSION['username'] ?? null;
    }

    /* ========== GESTIÓN DE SESIÓN ========== */

    /**
     * Extiende la duración de la sesión para "Recordarme"
     */
    public function extendSession(): bool {
        if (!self::check()) {
            return false;
        }

        // Configurar cookie de sesión por 30 días
        $sessionParams = session_get_cookie_params();
        setcookie(
            session_name(),
                  session_id(),
                  time() + (30 * 24 * 60 * 60),
                  $sessionParams['path'],
                  $sessionParams['domain'],
                  $sessionParams['secure'],
                  $sessionParams['httponly']
        );

        // Guardar token en base de datos
        $token = bin2hex(random_bytes(32));
        $query = "UPDATE users
        SET remember_token = :token,
        remember_expires = DATE_ADD(NOW(), INTERVAL 30 DAY)
        WHERE id = :user_id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':token' => $token,
            ':user_id' => self::getUserId(),
        ]);
    }

    /**
     * Login con token de "Recordarme"
     */
    public function loginWithToken(string $token): array {
        $query = "SELECT id, username, email, level
        FROM users
        WHERE remember_token = :token
        AND remember_expires > NOW()
        AND is_banned = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $_SESSION['user_id'] = (int) $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['level'] = $row['level'];
            $_SESSION['logged_in'] = true;

            $this->updateLastLogin((int) $row['id']);
            return ['success' => true, 'user' => $row];
        }

        return ['success' => false];
    }

    /* ========== AUTENTICACIÓN 2FA ========== */

    /**
     * Verifica si el usuario necesita autenticación de dos factores
     */
    public function requires2FA(int $userId): bool {
        $query = "SELECT two_factor_enabled FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row && (int) $row['two_factor_enabled'] === 1;
    }

    /* ========== SEGURIDAD: INTENTOS FALLIDOS ========== */

    /**
     * Registra un intento fallido de login
     */
    public function logFailedAttempt(string $username, string $ipAddress): void {
        $query = "INSERT INTO login_attempts (username, ip_address, successful)
        VALUES (:username, :ip, 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':ip' => $ipAddress,
        ]);

        // Bloquear IP después de 5 intentos fallidos en 15 minutos
        $query = "SELECT COUNT(*) as attempts
        FROM login_attempts
        WHERE ip_address = :ip
        AND successful = 0
        AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':ip' => $ipAddress]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ((int) $result['attempts'] >= 5) {
            $this->blockIP($ipAddress);
        }
    }

    /**
     * Bloquea una IP por 1 hora
     */
    private function blockIP(string $ipAddress): bool {
        $query = "INSERT INTO blocked_ips (ip_address, reason, blocked_until)
        VALUES (:ip, 'Demasiados intentos fallidos', DATE_ADD(NOW(), INTERVAL 1 HOUR))
        ON DUPLICATE KEY UPDATE blocked_until = DATE_ADD(NOW(), INTERVAL 1 HOUR)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':ip' => $ipAddress]);
    }

    /**
     * Verifica si una IP está bloqueada
     */
    public function isIPBlocked(string $ipAddress): bool {
        $query = "SELECT id FROM blocked_ips
        WHERE ip_address = :ip AND blocked_until > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':ip' => $ipAddress]);
        return $stmt->fetch() !== false;
    }

    /* ========== RECUPERACIÓN DE CONTRASEÑA ========== */

    /**
     * Genera un token de recuperación de contraseña
     */
    public function generateRecoveryToken(string $email): ?string {
        $query = "SELECT id FROM users WHERE email = :email AND is_banned = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora

        $query = "UPDATE users
        SET recovery_token = :token, recovery_expires = :expires
        WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':token' => $token,
            ':expires' => $expires,
            ':id' => $row['id'],
        ]);

        return $token;
    }

    /**
     * Valida un token de recuperación
     */
    public function validateRecoveryToken(string $token): ?array {
        $query = "SELECT id, email FROM users
        WHERE recovery_token = :token
        AND recovery_expires > NOW()
        AND is_banned = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':token' => $token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /* ========== UTILIDADES DE USUARIO ========== */

    /**
     * Actualiza el último login del usuario
     */
    public function updateLastLogin(int $userId): bool {
        $query = "UPDATE users
        SET last_login = NOW(), login_count = login_count + 1
        WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $userId]);
    }

    public function getCurrentUserRole(): ?string {
        return $_SESSION['level'] ?? null;
    }

    /**
     * Obtiene el ID del usuario actual (corregido)
     */
    public function getCurrentUserId(): ?int {
        if (isset($_SESSION['user_id'])) {
            return (int) $_SESSION['user_id'];
        }

        if (isset($_SESSION['username'])) {
            $query = "SELECT id FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':username' => $_SESSION['username']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $_SESSION['user_id'] = (int) $row['id'];
                return (int) $row['id'];
            }
        }

        return null;
    }

    /* ========== VERIFICACIÓN DE ROLES ========== */

    public function isAdmin(): bool {
        return $this->getCurrentUserRole() === 'admin';
    }

    public function isManager(): bool {
        return in_array($this->getCurrentUserRole(), ['admin', 'manager'], true);
    }

    public function isEditor(): bool {
        return in_array($this->getCurrentUserRole(), ['admin', 'manager', 'editor'], true);
    }

    public function hasPermission(string $permission): bool {
        $userId = $this->getCurrentUserId();
        if (!$userId) return false;
        return $this->roleManager->hasPermission($userId, $permission);
    }

    public function canEditPage(int $pageId): bool {
        $userId = $this->getCurrentUserId();
        if (!$userId) return false;
        return $this->roleManager->canEditPage($userId, $pageId);
    }

    /* ========== ESTADO DE SESIÓN ========== */

    public function isLoggedIn(): bool {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function logout(): void {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                      '',
                      time() - 42000,
                      $params["path"],
                      $params["domain"],
                      $params["secure"],
                      $params["httponly"]
            );
        }

        session_unset();
        session_destroy();
    }

    /* ========== PROTECCIÓN DE RUTAS ========== */

    public function requireLogin(): void {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'] ?? '/';
            header("Location: login.php");
            exit();
        }
    }

    public function requireAdmin(): void {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            http_response_code(403);
            header("Location: dashboard.php");
            exit();
        }
    }

    public function requireRole(string ...$roles): void {
        $this->requireLogin();
        if (!in_array($this->getCurrentUserRole(), $roles, true)) {
            http_response_code(403);
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>
