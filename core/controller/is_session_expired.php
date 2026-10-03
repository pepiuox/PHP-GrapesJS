<?php
declare(strict_types=1);

/**
 * Verifica si la sesión ha expirado.
 * Retorna "true" o "false" como string para compatibilidad con AJAX.
 */

// Asegurar que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevenir cacheo
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Type: text/plain; charset=utf-8');

try {
    // Verificar que la clase exista
    if (!class_exists('SESSION_handler')) {
        throw new RuntimeException('SESSION_handler class not found');
    }

    $session = new SESSION_handler();

    // Verificar expiración
    if ($session->session_expired()) {
        // Limpiar sesión expirada
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        echo 'true';
    } else {
        // Actualizar timestamp de última actividad
        if (isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
        }
        echo 'false';
    }
} catch (Throwable $e) {
    error_log('is_session_expired error: ' . $e->getMessage());
    // En caso de error, asumir sesión expirada por seguridad
    echo 'true';
}
exit;
