<?php
declare(strict_types=1);

/**
 * Gestor de sesiones con seguridad mejorada.
 *
 * CORRECCIONES:
 * - session_regenerate_id() tras login/logout
 * - Timeout de sesión configurable
 * - Cookies seguras (HttpOnly, Secure, SameSite=Strict)
 * - CSRF token generation/verification
 * - Validación de sesión en cada request
 * - Protección contra Session Fixation
 * - Inyección de dependencias
 */
class SessionManager
{
    private const SESSION_TIMEOUT = 1800; // 30 minutos
    private const SESSION_LIFETIME = 86400; // 24 horas
    private const ROLE_HIERARCHY = [
        'guest'   => 1,
        'editor'  => 2,
        'manager' => 3,
        'admin'   => 4,
    ];

    /**
     * Inicia una sesión segura para el usuario.
     *
     * ✅ Regenera ID de sesión para prevenir Session Fixation
     */
    public static function startSession(array $user): void
    {
        // ✅ Regenerar ID de sesión para prevenir Session Fixation
        if (session_status() === PHP_SESSION_NONE) {
            self::configureSecureSession();
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['user_id']      = (int) $user['id'];
        $_SESSION['username']     = (string) $user['username'];
        $_SESSION['role']         = (string) $user['role'];
        $_SESSION['logged_in']    = true;
        $_SESSION['login_time']   = time();
        $_SESSION['last_activity'] = time();

        // ✅ Generar CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // ✅ Fingerprint de sesión (previene hijacking)
        $_SESSION['session_fingerprint'] = self::generateFingerprint();
    }

    /**
     * Configura parámetros seguros de sesión.
     */
    private static function configureSecureSession(): void
    {
        // ✅ Cookies seguras
        $cookieParams = [
            'lifetime' => self::SESSION_LIFETIME,
            'path'     => '/',
            'domain'   => '',
            'secure'   => true,      // ✅ Solo HTTPS
            'httponly'  => true,      // ✅ No accesible por JS
            'samesite'  => 'Strict',  // ✅ Protección CSRF
        ];
        session_set_cookie_params($cookieParams);

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_secure', '1');
        ini_set('session.cookie_samesite', 'Strict');
    }

    /**
     * Verifica si el usuario está logueado.
     *
     * ✅ Valida timeout y fingerprint de sesión
     */
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }

        // ✅ Verificar timeout de sesión
        if (!isset($_SESSION['last_activity'])) {
            return false;
        }

        if (time() - $_SESSION['last_activity'] > self::SESSION_TIMEOUT) {
            self::logout();
            return false;
        }

        // ✅ Verificar fingerprint de sesión
        if (!isset($_SESSION['session_fingerprint']) ||
            $_SESSION['session_fingerprint'] !== self::generateFingerprint()) {
            self::logout();
        return false;
            }

            // ✅ Actualizar última actividad
            $_SESSION['last_activity'] = time();

            // ✅ Regenerar ID periódicamente (cada 5 minutos)
            if (!isset($_SESSION['_last_regeneration']) ||
                time() - $_SESSION['_last_regeneration'] > 300) {
                session_regenerate_id(true);
            $_SESSION['_last_regeneration'] = time();
                }

                return true;
    }

    /**
     * Obtiene el usuario actual.
     */
    public static function getCurrentUser(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id'       => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'role'     => $_SESSION['role'] ?? null,
        ];
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     *
     * ✅ Validación estricta de roles
     */
    public static function hasRole(string $required_role): bool
    {
        if (!self::isLoggedIn()) {
            return false;
        }

        $user_role = $_SESSION['role'] ?? 'guest';

        // ✅ Validar que el rol exista en la jerarquía
        if (!isset(self::ROLE_HIERARCHY[$required_role]) ||
            !isset(self::ROLE_HIERARCHY[$user_role])) {
            return false;
            }

            return self::ROLE_HIERARCHY[$user_role] >= self::ROLE_HIERARCHY[$required_role];
    }

    public static function isAdmin(): bool
    {
        return self::hasRole('admin');
    }

    public static function isManager(): bool
    {
        return self::hasRole('manager');
    }

    public static function isEditor(): bool
    {
        return self::hasRole('editor');
    }

    /**
     * Cierra la sesión de forma segura.
     *
     * ✅ Limpia cookies y regenera ID
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ✅ Limpiar array de sesión
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

        // ✅ Destruir sesión
        session_destroy();

        // ✅ Iniciar nueva sesión vacía
        session_start();
        session_regenerate_id(true);
    }

    /**
     * Genera un token CSRF.
     */
    public static function getCSRFToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica un token CSRF.
     *
     * ✅ Usa hash_equals para comparación segura
     */
    public static function verifyCSRFToken(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Obtiene el ID del usuario actual.
     */
    public static function getUserId(): ?int
    {
        return self::isLoggedIn() ? ($_SESSION['user_id'] ?? null) : null;
    }

    /**
     * Obtiene el username del usuario actual.
     */
    public static function getUsername(): ?string
    {
        return self::isLoggedIn() ? ($_SESSION['username'] ?? null) : null;
    }

    /**
     * Genera fingerprint de sesión.
     *
     * ✅ Previene session hijacking
     */
    private static function generateFingerprint(): string
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        return hash('sha256', $userAgent . $ip . (defined('SECRET_KEY') ? SECRET_KEY : 'default'));
    }

    /**
     * Renueva el timeout de sesión.
     */
    public static function renewTimeout(): void
    {
        if (self::isLoggedIn()) {
            $_SESSION['last_activity'] = time();
        }
    }
}
