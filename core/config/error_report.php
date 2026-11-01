<?php
declare(strict_types=1);

/**
 * Configuración de errores de la aplicación.
 *
 * ⚠️ PROBLEMA CORREGIDO (divulgación de información en producción)
 * --------------------------------------------------------------
 * La versión anterior hacía, en cada petición y sin condiciones:
 *
 *     ini_set('display_errors', 1);
 *     ini_set('display_startup_errors', 1);
 *
 * `ini_set()` se ejecuta **después** de que Apache aplique `php_flag display_errors Off`
 * del `.htaccess`, así que lo **sobrescribía**: cualquier visitante veía rutas del
 * servidor, fragmentos de SQL, trazas y versiones — información muy útil para un
 * atacante. Además llamaba a `error_reporting()` cuatro veces (sólo contaba la última)
 * y a `ini_set("error_reporting", …)`, que no es una directiva válida y se ignoraba.
 *
 * COMPORTAMIENTO ACTUAL
 * ---------------------
 * - Los errores se **registran siempre** (`log_errors`).
 * - Sólo se **muestran** si la aplicación está en modo desarrollo, detectado por:
 *     1. la constante `DEBUG` (si está definida),
 *     2. la variable de entorno `APP_DEBUG` (anula a la anterior),
 *     3. `APP_ENV` con valor `local`/`dev`/`development`/`testing` (activa),
 *        o `production`/`prod` (desactiva, tiene la máxima prioridad).
 * - **Por defecto: producción** (no mostrar). Es el valor seguro.
 *
 * Para ver los errores en desarrollo, define en el entorno (o la constante `DEBUG`):
 *
 *     APP_DEBUG=true      # o  APP_ENV=local
 *
 * @see docs/SECURITY.md §12
 */

// --- Detección del modo de desarrollo (por defecto: producción) -------------

$appDebug = defined('DEBUG') ? (bool) DEBUG : false;

$envDebug = getenv('APP_DEBUG');
if ($envDebug === false || $envDebug === '') {
    $envDebug = $_SERVER['APP_DEBUG'] ?? '';
}
if ($envDebug !== '') {
    $appDebug = filter_var($envDebug, FILTER_VALIDATE_BOOLEAN);
}

$appEnv = getenv('APP_ENV');
if ($appEnv === false || $appEnv === '') {
    $appEnv = $_SERVER['APP_ENV'] ?? '';
}
$appEnv = strtolower(trim((string) $appEnv));

if (in_array($appEnv, ['local', 'dev', 'development', 'testing'], true)) {
    $appDebug = true;
}

// `production` manda sobre cualquier otra señal.
if (in_array($appEnv, ['production', 'prod'], true)) {
    $appDebug = false;
}

// --- Aplicación de la configuración ----------------------------------------

// Se informa de todo: el filtrado se hace con display_errors y con el log.
error_reporting(E_ALL);

// Registrar siempre: es la única traza fiable en producción.
ini_set('log_errors', '1');

// Mostrar sólo en desarrollo.
ini_set('display_errors', $appDebug ? '1' : '0');
ini_set('display_startup_errors', $appDebug ? '1' : '0');

// Nota: `expose_php` es PHP_INI_SYSTEM — NO se puede cambiar con ini_set() en tiempo de
// ejecución. Para ocultar la versión de PHP hay que fijarlo en php.ini o vía
// `php_flag expose_php Off` (ya está en el .htaccess de este proyecto).
