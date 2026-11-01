<?php
declare(strict_types=1);

/**
 * Inicio del caché de página (fragmento opcional).
 *
 * Se incluye al PRINCIPIO de una página y `cacheend.php` al FINAL.
 *
 * Correcciones de seguridad respecto a la versión anterior:
 * - La clave de caché se derivaba de `basename($_SERVER['PHP_SELF'])`, un valor
 *   influenciable por el usuario, y se usaba como nombre de archivo. Ahora se usa un
 *   **hash** de script + query string, así que nunca se escribe una ruta arbitraria.
 * - La ruta era relativa (`cache/…`), dependiente del directorio de trabajo. Ahora es
 *   absoluta (`__DIR__`).
 * - `unlink()` se ejecutaba sin comprobar la existencia del archivo.
 * - **Envenenamiento de caché / XSS almacenado:** antes se cacheaba también contenido
 *   generado a partir de la petición (p. ej. `$_GET` reflejado) y se servía a otros
 *   usuarios. Ahora sólo se cachean respuestas **GET sin sesión iniciada**.
 * - La escritura es **atómica** (archivo temporal + `rename`) para evitar servir un
 *   caché a medio escribir.
 *
 * Nota: estos dos archivos no están referenciados en el proyecto; se corrigen para que
 * sean seguros si se llegan a usar.
 *
 * @see docs/SECURITY.md §7 (S10)
 */

// Sólo se cachean GET anónimos: una respuesta personalizada no debe servirse a otros.
$cacheEligible = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && session_status() === PHP_SESSION_NONE;

$cacheTime = 3600; // segundos de vigencia
$cacheKey = hash('sha256', basename((string) ($_SERVER['SCRIPT_NAME'] ?? 'index.php')) . '|' . (string) ($_SERVER['QUERY_STRING'] ?? ''));
$cacheFile = __DIR__ . '/' . $cacheKey . '.cache';

if ($cacheEligible && is_file($cacheFile) && (time() - filemtime($cacheFile)) <= $cacheTime) {
    readfile($cacheFile);
    exit;
}

// Sólo se descarta el caché caducado; nunca se borra a ciegas.
if (is_file($cacheFile) && (time() - filemtime($cacheFile)) > $cacheTime) {
    @unlink($cacheFile);
}

// `cacheend.php` necesita saber si debe guardar y dónde.
$GLOBALS['__page_cache_file'] = $cacheEligible ? $cacheFile : null;

ob_start();
