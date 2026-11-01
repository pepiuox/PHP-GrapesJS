<?php
declare(strict_types=1);

use PepiuoX\Database\Connection;

/**
 * Utilidad de línea de comandos para gestionar la caché de páginas.
 *
 * Correcciones aplicadas (el script no podía ejecutarse):
 *  1. `PageCacheManager` vive en `core/controller/`, no en `core/includes/`
 *     → la ruta anterior no existía (require_once fallaba).
 *  2. El archivo de conexión es `config/Database.php` con **D** mayúscula
 *     (`database.php` funcionaba sólo en sistemas de archivos insensibles a mayúsculas).
 *  3. `getDatabaseConnection()` no está definida en ninguna parte del proyecto.
 *     Se usa la conexión canónica PDO (`PepiuoX\Database\Connection`).
 *  4. La clase se cargaba a mano; ahora lo resuelve el autoloader único.
 */

require_once dirname(__DIR__) . '/Autoload.php';

$link = new Connection();
$conn = $link->getConnection();

$cache = new PageCacheManager($conn, [
    'cache_dir' => dirname(__DIR__) . '/cache/pages',
    'cache_ttl' => 7200,
    'enable_compression' => true
]);

$command = $argv[1] ?? 'stats';

switch ($command) {
    case 'stats':
        $stats = $cache->getCacheStats();
        echo "\n=== ESTADÍSTICAS DE CACHÉ ===\n";
        echo "Páginas cacheadas: {$stats['total_cached_pages']}\n";
        echo "Tamaño total: {$stats['total_size_mb']} MB\n";
        echo "Hit ratio: {$stats['hit_ratio']}%\n";
        echo "TTL: {$stats['cache_ttl']} segundos\n";
        echo "Compresión: " . ($stats['compression_enabled'] ? 'Activada' : 'Desactivada') . "\n";
        break;

    case 'clean':
        $cleaned = $cache->cleanExpiredCache();
        echo "Limpiadas $cleaned páginas expiradas\n";
        break;

    case 'clear':
        $cleared = $cache->invalidateAllCache();
        echo "Eliminadas $cleared entradas de caché\n";
        break;

    case 'warmup':
        $pages = array_slice($argv, 2);
        if (empty($pages)) {
            echo "Uso: php cache_manager.php warmup home about contact\n";
            exit(1);
        }
        $result = $cache->warmupCache($pages);
        echo "Precalentadas {$result['success']} de {$result['failed']} páginas\n";
        break;

    default:
        echo "Comandos disponibles: stats, clean, clear, warmup\n";
}
