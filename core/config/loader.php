<?php
declare(strict_types=1);

/**
 * Cargador principal de la aplicación (bootstrap).
 *
 * Orden de arranque:
 *   1. Sesión PHP.
 *   2. **Autoloader único** (`core/Autoload.php`) — antes de cualquier `new`.
 *   3. Comprobación de la instalación (si falta `server.php` → instalador).
 *   4. Conexión PDO canónica (`core/config/dbconnection.php`) → `$conn`, `$link`.
 *
 * Correcciones respecto a la versión anterior:
 * - Apuntaba a `core/config/Autoload.php`, que **no existe** → lanzaba
 *   `RuntimeException('Archivo Autoload.php no encontrado')` en cada petición.
 * - Rutas normalizadas con `dirname(__DIR__)` en lugar de encadenar `../core/`.
 */

$coreDir = dirname(__DIR__);            // <root>/core

// ✅ 1. Autoloader único del sistema (PSR-4 + clases legadas).
$autoloadFile = $coreDir . '/Autoload.php';

if (!is_file($autoloadFile)) {
    throw new RuntimeException('Archivo Autoload.php no encontrado: ' . $autoloadFile);
}

require_once $autoloadFile;

// ✅ 2. Sesión endurecida ANTES de arrancarla.
// Las directivas `session.*` y session_set_cookie_params() sólo tienen efecto si la
// sesión todavía no está activa; si se configuran después (como hacían los
// controladores) se ignoran en silencio.
\PepiuoX\Security\SessionConfig::start();

// ✅ 2. ¿Está instalada la aplicación?
$connserv = $coreDir . '/config/server.php';

if (!is_file($connserv)) {
    $_SESSION['PathInstall'] = "http://{$_SERVER['HTTP_HOST']}/";

    $installPath = $coreDir . '/application/installer/install.php';

    if (is_file($installPath)) {
        header('Location: ../core/application/installer/install.php');
        exit;
    }

    throw new RuntimeException('Instalador no encontrado. Por favor, suba los archivos de instalación.');
}

// ✅ 3. Conexión a la base de datos ($link, $conn), funciones y definiciones.
$connfile = $coreDir . '/config/dbconnection.php';

if (!is_file($connfile)) {
    throw new RuntimeException('Archivo de conexión no encontrado: ' . $connfile);
}

require_once $connfile;
