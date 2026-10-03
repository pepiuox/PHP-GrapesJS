<?php
/**
 * PageCacheManager - Sistema de caché inteligente para páginas
 * Migrado a PDO & Secured by AI Assistant
 *
 * @author PEPIUOX
 * @version 3.0
 */
class PageCacheManager {
    private PDO $conn;
    private string $cacheDir;
    private string $metaDir;
    private string $contentDir;
    private int $cacheTTL;
    private bool $enableCompression;
    private int $cacheHits = 0;
    private int $cacheMisses = 0;

    // Estados de caché
    const CACHE_VALID = 'valid';
    const CACHE_EXPIRED = 'expired';
    const CACHE_MISSING = 'missing';
    const CACHE_CHANGED = 'changed';

    /**
     * Constructor
     */
    public function __construct(PDO $conn, array $config = []) {
        $this->conn = $conn;

        // Configuración por defecto
        $this->cacheDir = $config['cache_dir'] ?? __DIR__ . '/../cache/pages';
        $this->cacheTTL = $config['cache_ttl'] ?? 3600; // 1 hora por defecto
        $this->enableCompression = $config['enable_compression'] ?? true;

        // Crear subdirectorios
        $this->metaDir = $this->cacheDir . '/meta';
        $this->contentDir = $this->cacheDir . '/content';
        $this->initializeDirectories();
    }

    /**
     * Inicializa la estructura de directorios
     */
    private function initializeDirectories(): void {
        foreach ([$this->cacheDir, $this->metaDir, $this->contentDir] as $dir) {
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    throw new Exception("No se pudo crear el directorio: {$dir}");
                }
            }
        }
    }

    /**
     * Obtiene una página (con caché automático)
     */
    public function getPage(string $slug, bool $forceRefresh = false): ?array {
        $slug = $this->normalizeSlug($slug);
        $cacheKey = $this->generateCacheKey($slug);

        // Verificar si debemos usar caché
        if (!$forceRefresh) {
            $cachedData = $this->loadFromCache($cacheKey, $slug);
            if ($cachedData !== null) {
                $this->cacheHits++;
                return $cachedData;
            }
        }

        $this->cacheMisses++;

        // Cargar desde base de datos
        $pageData = $this->loadPageFromDatabase($slug);
        if ($pageData) {
            // Guardar en caché
            $this->saveToCache($cacheKey, $slug, $pageData);
            return $pageData;
        }

        return null;
    }

    /**
     * Obtiene múltiples páginas con una sola consulta
     */
    public function getMultiplePages(array $slugs): array {
        $results = [];
        $missedSlugs = [];

        // Intentar cargar de caché primero
        foreach ($slugs as $slug) {
            $slug = $this->normalizeSlug($slug);
            $cacheKey = $this->generateCacheKey($slug);
            $cachedData = $this->loadFromCache($cacheKey, $slug);

            if ($cachedData !== null) {
                $results[$slug] = $cachedData;
                $this->cacheHits++;
            } else {
                $missedSlugs[] = $slug;
                $this->cacheMisses++;
            }
        }

        // Cargar páginas faltantes de una vez
        if (!empty($missedSlugs)) {
            $pagesFromDB = $this->loadMultiplePagesFromDatabase($missedSlugs);
            foreach ($pagesFromDB as $slug => $pageData) {
                $cacheKey = $this->generateCacheKey($slug);
                $this->saveToCache($cacheKey, $slug, $pageData);
                $results[$slug] = $pageData;
            }
        }

        return $results;
    }

    /**
     * Carga página desde caché (con verificación de integridad)
     */
    private function loadFromCache(string $cacheKey, string $slug): ?array {
        $metaFile = $this->metaDir . '/' . $cacheKey . '.meta';
        $contentFile = $this->contentDir . '/' . $cacheKey . '.cache';

        // Verificar si existe el caché
        if (!file_exists($metaFile) || !file_exists($contentFile)) {
            return null;
        }

        // Cargar metadatos (usar JSON en lugar de serialize para seguridad)
        $meta = json_decode(file_get_contents($metaFile), true);
        if (!$meta) {
            return null;
        }

        // Verificar expiración por TTL
        if (time() - $meta['timestamp'] > $this->cacheTTL) {
            $this->invalidateCache($cacheKey);
            return null;
        }

        // Verificar cambios en base de datos
        $hasChanges = $this->checkForDatabaseChanges($slug, $meta['last_db_update']);
        if ($hasChanges) {
            $this->invalidateCache($cacheKey);
            return null;
        }

        // Cargar contenido
        $content = file_get_contents($contentFile);
        if ($this->enableCompression) {
            $content = gzuncompress($content);
        }

        // Verificar integridad con hash
        $data = json_decode($content, true);
        if (!$data || !isset($data['hash'])) {
            return null;
        }

        // Verificar que el hash coincida
        $expectedHash = $data['hash'];
        unset($data['hash']);
        $actualHash = hash('sha256', json_encode($data));

        if ($expectedHash !== $actualHash) {
            error_log("PageCache: Hash mismatch for {$slug}");
            $this->invalidateCache($cacheKey);
            return null;
        }

        return $data;
    }

    /**
     * Guarda página en caché (con hash de integridad)
     */
    public function saveToCache(string $cacheKey, string $slug, array $pageData): bool {
        // Preparar metadatos
        $meta = [
            'slug' => $slug,
            'cache_key' => $cacheKey,
            'timestamp' => time(),
            'last_db_update' => $pageData['last_modified'] ?? time(),
            'page_id' => $pageData['id'] ?? null,
            'version' => $pageData['version'] ?? 1,
            'expires_at' => time() + $this->cacheTTL
        ];

        // Guardar metadatos (usar JSON)
        $metaFile = $this->metaDir . '/' . $cacheKey . '.meta';
        if (file_put_contents($metaFile, json_encode($meta), LOCK_EX) === false) {
            return false;
        }

        // Añadir hash de integridad al contenido
        $pageData['hash'] = hash('sha256', json_encode($pageData));

        $content = json_encode($pageData);
        if ($this->enableCompression) {
            $content = gzcompress($content, 9);
        }

        $contentFile = $this->contentDir . '/' . $cacheKey . '.cache';
        return file_put_contents($contentFile, $content, LOCK_EX) !== false;
    }

    /**
     * Verifica cambios en la base de datos (migrado a PDO)
     */
    private function checkForDatabaseChanges(string $slug, int $lastKnownTime): bool {
        $stmt = $this->conn->prepare("
        SELECT
        MAX(GREATEST(
            COALESCE(p.updated_at, '1970-01-01'),
                                     COALESCE(pc.updated_at, '1970-01-01')
        )) as last_modified
        FROM pages p
        LEFT JOIN pages_contents pc ON p.id = pc.idPage
        WHERE (p.slug = :slug1 OR p.link = :slug2)
        AND p.active = 1
        ORDER BY pc.version DESC
        LIMIT 1
        ");

        $stmt->execute([
            ':slug1' => $slug,
            ':slug2' => $slug
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['last_modified']) {
            $lastModified = strtotime($row['last_modified']);
            return $lastModified > $lastKnownTime;
        }

        return true; // Si no encontramos la página, consideramos que hay cambios
    }

    /**
     * Carga página desde base de datos (migrado a PDO)
     */
    private function loadPageFromDatabase(string $slug): ?array {
        $stmt = $this->conn->prepare("
        SELECT
        p.*,
        pc.html_content,
        pc.css_content,
        pc.php_content,
        pc.js_content,
        pc.version,
        GREATEST(p.updated_at, pc.updated_at) as last_modified
        FROM pages p
        LEFT JOIN pages_contents pc ON p.id = pc.idPage
        WHERE (p.slug = :slug1 OR p.link = :slug2)
        AND p.active = 1
        ORDER BY pc.version DESC
        LIMIT 1
        ");

        $stmt->execute([
            ':slug1' => $slug,
            ':slug2' => $slug
        ]);

        $pageData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no hay contenido en pages_contents, usar valores por defecto
        if ($pageData && !isset($pageData['html_content'])) {
            $pageData['html_content'] = '';
            $pageData['css_content'] = '';
            $pageData['php_content'] = '';
            $pageData['js_content'] = '';
            $pageData['version'] = 1;
            $pageData['last_modified'] = $pageData['updated_at'] ?? date('Y-m-d H:i:s');
        }

        return $pageData ?: null;
    }

    /**
     * Carga múltiples páginas con una consulta (migrado a PDO)
     */
    private function loadMultiplePagesFromDatabase(array $slugs): array {
        if (empty($slugs)) return [];

        $placeholders = implode(',', array_fill(0, count($slugs), '?'));

        $stmt = $this->conn->prepare("
        SELECT
        p.*,
        pc.html_content,
        pc.css_content,
        pc.php_content,
        pc.js_content,
        pc.version,
        COALESCE(p.slug, p.link) as slug,
                                     GREATEST(p.updated_at, pc.updated_at) as last_modified
                                     FROM pages p
                                     LEFT JOIN pages_contents pc ON p.id = pc.idPage
                                     WHERE (p.slug IN ({$placeholders}) OR p.link IN ({$placeholders}))
        AND p.active = 1
        ORDER BY pc.version DESC
        ");

        $params = array_merge($slugs, $slugs);
        $stmt->execute($params);

        $pages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pages[$row['slug']] = $row;
        }

        return $pages;
    }

    /**
     * Invalida caché de una página específica
     */
    public function invalidateCache(string $cacheKey): bool {
        // Si es un slug, generar cache key
        if (strpos($cacheKey, '/') !== false || strpos($cacheKey, '.') === false) {
            $cacheKey = $this->generateCacheKey($this->normalizeSlug($cacheKey));
        }

        $metaFile = $this->metaDir . '/' . $cacheKey . '.meta';
        $contentFile = $this->contentDir . '/' . $cacheKey . '.cache';

        $success = true;

        if (file_exists($metaFile)) {
            $success = $success && unlink($metaFile);
        }

        if (file_exists($contentFile)) {
            $success = $success && unlink($contentFile);
        }

        return $success;
    }

    /**
     * Invalida caché de todas las páginas
     */
    public function invalidateAllCache(): int {
        $count = 0;

        foreach (glob($this->metaDir . '/*.meta') as $file) {
            if (unlink($file)) $count++;
        }

        foreach (glob($this->contentDir . '/*.cache') as $file) {
            if (unlink($file)) $count++;
        }

        return $count;
    }

    /**
     * Limpia caché expirado
     */
    public function cleanExpiredCache(): int {
        $count = 0;
        $now = time();

        foreach (glob($this->metaDir . '/*.meta') as $metaFile) {
            $meta = json_decode(file_get_contents($metaFile), true);

            if ($meta && $meta['expires_at'] < $now) {
                $cacheKey = basename($metaFile, '.meta');
                if ($this->invalidateCache($cacheKey)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Precalienta caché para páginas populares
     */
    public function warmupCache(array $slugs): array {
        $results = [
            'success' => 0,
            'failed' => 0,
            'pages' => []
        ];

        foreach ($slugs as $slug) {
            $pageData = $this->loadPageFromDatabase($slug);

            if ($pageData) {
                $cacheKey = $this->generateCacheKey($slug);
                if ($this->saveToCache($cacheKey, $slug, $pageData)) {
                    $results['success']++;
                    $results['pages'][] = $slug;
                } else {
                    $results['failed']++;
                }
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }

    /**
     * Obtiene estadísticas del caché
     */
    public function getCacheStats(): array {
        $totalFiles = count(glob($this->contentDir . '/*.cache'));
        $totalSize = 0;
        $oldestFile = null;
        $newestFile = null;

        foreach (glob($this->contentDir . '/*.cache') as $file) {
            $size = filesize($file);
            $totalSize += $size;

            $mtime = filemtime($file);

            if ($oldestFile === null || $mtime < $oldestFile['mtime']) {
                $oldestFile = ['file' => basename($file), 'mtime' => $mtime];
            }

            if ($newestFile === null || $mtime > $newestFile['mtime']) {
                $newestFile = ['file' => basename($file), 'mtime' => $mtime];
            }
        }

        return [
            'total_cached_pages' => $totalFiles,
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / 1048576, 2),
            'cache_hits' => $this->cacheHits,
            'cache_misses' => $this->cacheMisses,
            'hit_ratio' => ($this->cacheHits + $this->cacheMisses) > 0
            ? round(($this->cacheHits / ($this->cacheHits + $this->cacheMisses)) * 100, 2)
            : 0,
            'oldest_cache' => $oldestFile ? date('Y-m-d H:i:s', $oldestFile['mtime']) : null,
            'newest_cache' => $newestFile ? date('Y-m-d H:i:s', $newestFile['mtime']) : null,
            'cache_ttl' => $this->cacheTTL,
            'compression_enabled' => $this->enableCompression
        ];
    }

    /**
     * Genera clave única para caché
     */
    public function generateCacheKey(string $slug): string {
        return md5($slug . '_page_cache_v3');
    }

    /**
     * Normaliza el slug para búsqueda consistente
     */
    private function normalizeSlug(string $slug): string {
        // Eliminar barra inicial/final
        $slug = trim($slug, '/');

        // Si está vacío, es home
        if (empty($slug)) {
            $slug = 'home';
        }

        // Eliminar parámetros GET
        $slug = strtok($slug, '?');

        return $slug;
    }

    /**
     * Destructor - Guardar estadísticas si es necesario
     */
    public function __destruct() {
        // Opcional: Guardar estadísticas en log
        if (($this->cacheHits + $this->cacheMisses) > 0) {
            error_log(sprintf(
                "PageCache Stats - Hits: %d, Misses: %d, Ratio: %.2f%%",
                $this->cacheHits,
                $this->cacheMisses,
                ($this->cacheHits + $this->cacheMisses) > 0
                ? ($this->cacheHits / ($this->cacheHits + $this->cacheMisses)) * 100
                : 0
            ));
        }
    }
}
