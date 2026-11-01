<?php
declare(strict_types=1);

/**
 * Fin del caché de página (fragmento opcional).
 *
 * Cierra el buffer abierto por `cachestart.php` y guarda el resultado.
 *
 * Correcciones respecto a la versión anterior:
 * - Antes volvía a calcular la ruta con `basename($_SERVER['PHP_SELF'])`, repetía la
 *   lógica de servido y **nunca cerraba el buffer** (`ob_start()` sin `ob_end_flush()`,
 *   y sin escribir el caché): era un fragmento incompleto.
 * - Ahora reutiliza el destino decidido por `cachestart.php` y escribe de forma
 *   **atómica** (temporal + `rename`), evitando servir un archivo a medio escribir.
 *
 * @see docs/SECURITY.md §7 (S10)
 */

$cacheFile = $GLOBALS['__page_cache_file'] ?? null;

if ($cacheFile !== null && ob_get_level() > 0) {
    $content = ob_get_contents();

    if (is_string($content) && $content !== '') {
        $tmpFile = $cacheFile . '.' . bin2hex(random_bytes(6)) . '.tmp';

        if (@file_put_contents($tmpFile, $content, LOCK_EX) !== false) {
            @rename($tmpFile, $cacheFile);
        } else {
            @unlink($tmpFile);
        }
    }

    ob_end_flush();
}
