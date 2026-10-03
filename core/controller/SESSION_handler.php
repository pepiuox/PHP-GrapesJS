<?php
declare(strict_types=1);

/**
 * Handler de sesiones mejorado.
 *
 * CORRECCIONES CRÍTICAS:
 * - Bug: `undefined` no existe en PHP → null
 * - Lógica invertida corregida
 * - Validación de sesión mejorada
 * - Tipado estricto
 */
class SESSION_handler
{
    public static int $gc_maxlifetime;
    public static int $cookie_lifetime;

    public function __construct()
    {
        self::$gc_maxlifetime = (int) ini_get('session.gc_maxlifetime');
        self::$cookie_lifetime = (int) ini_get('session.cookie_lifetime');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Obtiene valor de sesión.
     *
     * ✅ CORREGIDO: `undefined` → null
     * ✅ CORREGIDO: lógica invertida
     */
    public function get(string $session_key): mixed
    {
        // ✅ CORREGIDO: antes verificaba PHP_SESSION_NONE pero luego accedía a $_SESSION
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return null;
        }

        if (!empty($_SESSION[$session_key])) {
            $_SESSION['time_at_last_session'] = time();
            return $_SESSION[$session_key];
        }

        // ✅ ANTES: return undefined (no existe en PHP)
        // ✅ AHORA: return null
        return null;
    }

    /**
     * Establece valor de sesión.
     *
     * ✅ CORREGIDO: `undefined` → null
     * ✅ CORREGIDO: lógica invertida
     */
    public function set(string $session_key, mixed $session_value): mixed
    {
        // ✅ CORREGIDO: antes verificaba PHP_SESSION_NONE pero luego accedía a $_SESSION
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return null;
        }

        $_SESSION[$session_key] = $session_value;
        $_SESSION['time_at_last_session'] = time();

        return $_SESSION[$session_key];
    }

    /**
     * Verifica si la sesión ha expirado.
     *
     * ✅ CORREGIDO: lógica mejorada
     */
    public function session_expired(): bool
    {
        // ✅ CORREGIDO: antes verificaba PHP_SESSION_NONE pero luego accedía a $_SESSION
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return true; // Problema de sesión
        }

        $time = $_SESSION['time_at_last_session'] ?? 0;

        // Si no hay timestamp, considerar expirada
        if ($time === 0) {
            return true;
        }

        // Verificar si ha expirado
        if (time() - $time > self::$gc_maxlifetime) {
            return true; // Sesión expirada
        }

        return false; // Sesión NO expirada
    }

    /**
     * Renueva el timestamp de última actividad.
     */
    public function renewActivity(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['time_at_last_session'] = time();
        }
    }

    /**
     * Destruye la sesión completamente.
     */
    public function destroySession(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];

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

            return session_destroy();
        }

        return false;
    }
}
