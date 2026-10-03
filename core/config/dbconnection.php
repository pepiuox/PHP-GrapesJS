<?php
declare(strict_types=1);

/**
 * Inicialización de la conexión a la base de datos y configuración del sistema.
 * Migrado completamente a PDO.
 */

// Incluir reporte de errores
if (file_exists(__DIR__ . '/error_report.php')) {
    include __DIR__ . '/error_report.php';
}

// Incluir clase Database
if (!file_exists(__DIR__ . '/Database.php')) {
    throw new RuntimeException('Database.php no encontrado');
}
include __DIR__ . '/Database.php';

// Crear instancia de Database y obtener conexión PDO
$link = new Database();
$conn = $link->getConnection(); // ✅ Ahora devuelve PDO

// Incluir funciones y definiciones
if (file_exists(__DIR__ . '/function.php')) {
    require_once __DIR__ . '/function.php';
}
if (file_exists(__DIR__ . '/define.php')) {
    include_once __DIR__ . '/define.php';
}

// Determinar protocolo
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ||
($_SERVER["SERVER_PORT"] ?? 0) == 443 ? "https://" : "http://";

// Determinar URL base
if (defined('SITE_PATH') && !empty(SITE_PATH)) {
    $siteinstall = SITE_PATH;
    $base = SITE_PATH;
} else {
    $base = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/';
}

// Información de la página actual
$fname = basename($_SERVER['REQUEST_URI'] ?? '');
$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'] ?? '';

// Manejo de idioma
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$lang = '';
$lg = '';

if (!isset($_SESSION['translation'])) {
    $lg = 'es';
} else {
    // ✅ Validación estricta del idioma
    $allowedLangs = ['es', 'en'];
    $lg = in_array($_SESSION['translation'], $allowedLangs, true) ? $_SESSION['translation'] : 'es';
}

if (!empty($lg)) {
    $_SESSION['translation'] = $lg;
}

if (isset($_SESSION['translation'])) {
    $lg = $_SESSION['translation'];
    $langFile = __DIR__ . '/language/lang/' . basename($lg) . '.php';

    // ✅ Validación de existencia del archivo de idioma
    if (file_exists($langFile)) {
        require_once $langFile;
    } else {
        require_once __DIR__ . '/language/lang/es.php';
    }
} else {
    require_once __DIR__ . '/language/lang/es.php';
}
