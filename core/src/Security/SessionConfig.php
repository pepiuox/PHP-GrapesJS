<?php
declare(strict_types=1);

/**
 * Configuración endurecida de la sesión PHP.
 *
 * **Debe aplicarse ANTES de `session_start()`.** Las directivas `session.*` y
 * `session_set_cookie_params()` sólo tienen efecto si la sesión todavía no está activa.
 *
 * Motivo de existir: la configuración "segura" estaba repartida en tres controladores
 * (`Session`, `SessionManager`, `SecurityAccessClass`) y llegaba **demasiado tarde**,
 * porque el bootstrap (`core/config/loader.php` → `core/config/dbconnection.php`) ya
 * había llamado a `session_start()`. Resultado real:
 *
 *  - `SecurityAccessClass::configureSession()` no aplicaba nada (su guarda
 *    `session_status() === PHP_SESSION_NONE` era falsa).
 *  - `Session::startSession()` y `SessionManager::configureSession()` llamaban a
 *    `session_set_cookie_params()` con la sesión activa → aviso y **sin efecto**.
 *
 * Es decir: `httponly`, `samesite`, `use_strict_mode` y `use_only_cookies` **nunca
 * llegaban a aplicarse** en tiempo de ejecución.
 *
 * Además, los tres forzaban `secure = true` sin condiciones, lo que **impide iniciar
 * sesión por HTTP** (el navegador no envía la cookie). Aquí `secure` se activa sólo
 * cuando la petición es realmente HTTPS.
 *
 * @see docs/SECURITY.md §9.4 (P6)
 */

namespace PepiuoX\Security;

final class SessionConfig
{
    /** ¿La petición actual llega por HTTPS? */
    public static function isHttps(): bool
    {
        if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
            return true;
        }

        // No se mira `X-Forwarded-Proto`: es una cabecera que controla el cliente y
        // marcarla como fiable sin un proxy de confianza permitiría forzar el flag.
        return (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
    }

    /**
     * Aplica la configuración de sesión. No hace nada si la sesión ya está activa.
     *
     * @param int $lifetime 0 = cookie de sesión (se borra al cerrar el navegador).
     */
    public static function apply(int $lifetime = 0): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        // Si ya se enviaron cabeceras, ni los parámetros de cookie ni las directivas
        // `session.*` se pueden cambiar: intentarlo sólo genera avisos.
        // (Ocurre en endpoints que imprimen HTML antes de cargar el bootstrap, como
        // core/managers/build.php.) La sesión se arranca igual desde start().
        if (headers_sent()) {
            return;
        }

        $secure = self::isHttps();

        $params = [
            'lifetime' => $lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ];

        if (PHP_VERSION_ID >= 70300) {
            session_set_cookie_params($params);
        } else {
            session_set_cookie_params(
                $params['lifetime'],
                $params['path'],
                $params['domain'],
                $secure,
                true
            );
        }

        // Sin esto, PHP acepta cualquier ID de sesión enviado por el cliente.
        ini_set('session.use_strict_mode', '1');
        // Sólo cookies: evita que el ID viaje en la URL (fuga por Referer/logs).
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.cookie_httponly', '1');
        // `secure` SÓLO sobre HTTPS: forzarlo en HTTP impediría iniciar sesión.
        ini_set('session.cookie_secure', $secure ? '1' : '0');
        // `Lax` bloquea los POST cross-site (lo que importa para CSRF) sin romper la
        // navegación normal desde enlaces externos, a diferencia de `Strict`.
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.cookie_lifetime', (string) $lifetime);
        ini_set('session.cookie_path', '/');
    }

    /**
     * Aplica la configuración y arranca la sesión si no está activa.
     */
    public static function start(int $lifetime = 0): void
    {
        self::apply($lifetime);

        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        // ✅ Si ya se enviaron cabeceras no se puede iniciar la sesión: hacerlo sólo
        // genera un aviso. Ocurre en endpoints que imprimen HTML antes de cargar el
        // bootstrap (p. ej. core/managers/build.php).
        if (headers_sent()) {
            return;
        }

        session_start();
    }
}
