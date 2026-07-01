<?php

require_once __DIR__ . '/../includes/PageCacheManager.php';
require_once __DIR__ . '/../config/database.php';

$conn = getDatabaseConnection(); // Tu función de conexión

$cache = new PageCacheManager($conn, [
    'cache_dir' => __DIR__ . '/../cache/pages',
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
