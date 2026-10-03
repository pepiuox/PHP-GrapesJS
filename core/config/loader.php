<?php
declare(strict_types=1);

/**
 * Cargador principal de la aplicación.
 * Valida existencia de archivos críticos antes de incluirlos.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$connserv = __DIR__ . "/../core/config/server.php";
$connfile = __DIR__ . "/../core/config/dbconnection.php";

// ✅ Validar existencia del archivo de configuración del servidor
if (!file_exists($connserv)) {
    $_SESSION["PathInstall"] = "http://{$_SERVER['HTTP_HOST']}/";

    $installPath = __DIR__ . "/../core/application/installer/install.php";

    // ✅ Validar que el instalador exista
    if (file_exists($installPath)) {
        header("Location: ../core/application/installer/install.php");
        exit; // ✅ exit() agregado
    } else {
        throw new RuntimeException('Instalador no encontrado. Por favor, suba los archivos de instalación.');
    }
}

// ✅ Validar existencia del archivo de conexión
if (!file_exists($connfile)) {
    throw new RuntimeException('Archivo de conexión no encontrado: ' . $connfile);
}

// ✅ Incluir archivos de configuración
include_once $connfile;

// ✅ Validar existencia del autoload
$autoloadFile = __DIR__ . "/Autoload.php";
if (!file_exists($autoloadFile)) {
    throw new RuntimeException('Archivo Autoload.php no encontrado');
}

include_once $autoloadFile;
