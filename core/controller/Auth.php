<?php

// classes/Auth.php - VERSIÓN ACTUALIZADA
session_start();

class Auth {

    private $conn;
    private $roleManager;

    public function __construct($db) {
        $this->conn = $db;
        $this->roleManager = new RoleManager($db);
    }

    /*
     * Funcion de check
     */

    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    /*
     * Extender duración de sesión para "Recordarme"
     */

    public function extendSession() {
        // Configurar cookie de sesión por 30 días
        $sessionParams = session_get_cookie_params();
        setcookie(
                session_name(),
                session_id(),
                time() + (30 * 24 * 60 * 60), // 30 días
                $sessionParams['path'],
                $sessionParams['domain'],
                $sessionParams['secure'],
                $sessionParams['httponly']
        );

        // También guardar en base de datos
        $query = "UPDATE users SET remember_token = :token, remember_expires = DATE_ADD(NOW(), INTERVAL 30 DAY) 
              WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $token = bin2hex(random_bytes(32));
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
    }

    /**
     * Login con token de recordar
     */
    public function loginWithToken($token) {
        $query = "SELECT id, username, email, level FROM users 
              WHERE remember_token = :token 
              AND remember_expires > NOW() 
              AND is_banned = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['level'] = $row['level'];
            $_SESSION['logged_in'] = true;

            // Actualizar último login
            $this->updateLastLogin($row['id']);

            return ['success' => true];
        }

        return ['success' => false];
    }

    /**
     * Verificar si usuario necesita 2FA
     */
    public function requires2FA($user_id) {
        $query = "SELECT two_factor_enabled FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['two_factor_enabled'] == 1;
        }

        return false;
    }

    /**
     * Registrar intento fallido de login
     */
    public function logFailedAttempt($username, $ip_address) {
        $query = "INSERT INTO login_attempts (username, ip_address, successful) 
              VALUES (:username, :ip, 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':ip', $ip_address);
        $stmt->execute();

        // Bloquear IP después de 5 intentos fallidos en 15 minutos
        $query = "SELECT COUNT(*) as attempts 
              FROM login_attempts 
              WHERE ip_address = :ip 
              AND successful = 0 
              AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip_address);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['attempts'] >= 5) {
            $this->blockIP($ip_address);
        }
    }

    /**
     * Bloquear IP
     */
    private function blockIP($ip_address) {
        $query = "INSERT INTO blocked_ips (ip_address, reason, blocked_until) 
              VALUES (:ip, 'Demasiados intentos fallidos', DATE_ADD(NOW(), INTERVAL 1 HOUR))
              ON DUPLICATE KEY UPDATE blocked_until = DATE_ADD(NOW(), INTERVAL 1 HOUR)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip_address);
        $stmt->execute();
    }

    /**
     * Verificar si IP está bloqueada
     */
    public function isIPBlocked($ip_address) {
        $query = "SELECT id FROM blocked_ips 
              WHERE ip_address = :ip 
              AND blocked_until > NOW()";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ip', $ip_address);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Generar token de recuperación
     */
    public function generateRecoveryToken($email) {
        $query = "SELECT id FROM users WHERE email = :email AND is_banned = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora

            $query = "UPDATE users SET 
                  recovery_token = :token,
                  recovery_expires = :expires
                  WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires', $expires);
            $stmt->bindParam(':id', $row['id']);
            $stmt->execute();

            return $token;
        }

        return null;
    }

    /**
     * Validar token de recuperación
     */
    public function validateRecoveryToken($token) {
        $query = "SELECT id, email FROM users 
              WHERE recovery_token = :token 
              AND recovery_expires > NOW() 
              AND is_banned = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    public function updateLastLogin($user_id) {
        $query = "UPDATE users SET last_login = NOW(), login_count = login_count + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    public function getCurrentUserRole() {
        return isset($_SESSION['level']) ? $_SESSION['level'] : null;
    }

    public function getCurrentUserId() {
        $user_id = $_SESSION['username'];
        $query = "SELECT id FROM users 
              WHERE username = :user_id ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $row['id'];
            return $row['id'];
        } else {
            return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        }
    }

    public function isAdmin() {
        return $this->getCurrentUserRole() === 'admin';
    }

    public function isManager() {
        return in_array($this->getCurrentUserRole(), ['admin', 'manager']);
    }

    public function isEditor() {
        return in_array($this->getCurrentUserRole(), ['admin', 'manager', 'editor']);
    }

    public function hasPermission($permission) {
        $user_id = $this->getCurrentUserId();
        if (!$user_id)
            return false;

        return $this->roleManager->hasPermission($user_id, $permission);
    }

    public function canEditPage($page_id) {
        $user_id = $this->getCurrentUserId();
        if (!$user_id)
            return false;

        return $this->roleManager->canEditPage($user_id, $page_id);
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function logout() {
        $_SESSION = array();
        session_unset();
        session_destroy();
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: login.php");
            exit();
        }
    }

    public function requireAdmin() {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            header("Location: dashboard.php");
            exit();
        }
    }
}

?>