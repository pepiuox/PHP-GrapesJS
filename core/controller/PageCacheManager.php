<?php
/**
 * PageCacheManager - Sistema de caché inteligente para páginas
 * Detecta automáticamente cambios en la base de datos y regenera caché
 *
 * @author PEPIUOX
 * @version 2.0
 */
class PageCacheManager {

    private $conn;
    private $cacheDir;
    private $metaDir;
    private $contentDir;
    private $cacheTTL;
    private $enableCompression;
    private $cacheHits = 0;
    private $cacheMisses = 0;

    // Estados de caché
    const CACHE_VALID = 'valid';
    const CACHE_EXPIRED = 'expired';
    const CACHE_MISSING = 'missing';
    const CACHE_CHANGED = 'changed';

    /**
     * Constructor
     *
     * @param mysqli $conn Conexión a base de datos
     * @param array $config Configuración opcional
     */
    public function __construct($conn, array $config = []) {
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
    private function initializeDirectories() {
        foreach ([$this->cacheDir, $this->metaDir, $this->contentDir] as $dir) {
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    throw new Exception("No se pudo crear el directorio: $dir");
                }
            }
        }
    }

    /**
     * Obtiene una página (con caché automático)
     *
     * @param string $slug Slug o URL de la página
     * @param bool $forceRefresh Forzar regeneración del caché
     * @return array|null Datos de la página o null si no existe
     */
    public function getPage($slug, $forceRefresh = false) {
        // Limpiar slug
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
     *
     * @param array $slugs Lista de slugs
     * @return array Páginas cacheadas o cargadas
     */
    public function getMultiplePages(array $slugs) {
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
     * Carga página desde caché
     *
     * @param string $cacheKey Clave única del caché
     * @param string $slug Slug de la página
     * @return array|null Datos cacheados o null
     */
    private function loadFromCache($cacheKey, $slug) {
        $metaFile = $this->metaDir . '/' . $cacheKey . '.meta';
        $contentFile = $this->contentDir . '/' . $cacheKey . '.cache';

        // Verificar si existe el caché
        if (!file_exists($metaFile) || !file_exists($contentFile)) {
            return null;
        }

        // Cargar metadatos
        $meta = unserialize(file_get_contents($metaFile));
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

        return unserialize($content);
    }

    /**
     * Guarda página en caché
     *
     * @param string $cacheKey Clave única del caché
     * @param string $slug Slug de la página
     * @param array $pageData Datos de la página
     * @return bool Éxito de la operación
     */
    private function saveToCache($cacheKey, $slug, array $pageData) {
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

        // Guardar metadatos
        $metaFile = $this->metaDir . '/' . $cacheKey . '.meta';
        if (file_put_contents($metaFile, serialize($meta), LOCK_EX) === false) {
            return false;
        }

        // Guardar contenido
        $content = serialize($pageData);
        if ($this->enableCompression) {
            $content = gzcompress($content, 9);
        }

        $contentFile = $this->contentDir . '/' . $cacheKey . '.cache';
        return file_put_contents($contentFile, $content, LOCK_EX) !== false;
    }

    /**
     * Verifica cambios en la base de datos
     *
     * @param string $slug Slug de la página
     * @param int $lastKnownTime Última modificación conocida
     * @return bool True si hay cambios
     */
    private function checkForDatabaseChanges($slug, $lastKnownTime) {
        $stmt = $this->conn->prepare("
        SELECT
        MAX(GREATEST(
            COALESCE(p.updated_at, '1970-01-01'),
                                     COALESCE(pc.updated_at, '1970-01-01')
        )) as last_modified
        FROM pages p
        LEFT JOIN pages_contents pc ON p.id = pc.idPage
        WHERE (p.slug = ? OR p.link = ?)
        AND p.active = 1
        ORDER BY pc.version DESC
        LIMIT 1
        ");

        $stmt->bind_param("ss", $slug, $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row && $row['last_modified']) {
            $lastModified = strtotime($row['last_modified']);
            return $lastModified > $lastKnownTime;
        }

        return true; // Si no encontramos la página, consideramos que hay cambios
    }

    /**
     * Carga página desde base de datos
     *
     * @param string $slug Slug de la página
     * @return array|null Datos de la página
     */
    private function loadPageFromDatabase($slug) {
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
        WHERE (p.slug = ? OR p.link = ?)
        AND p.active = 1
        ORDER BY pc.version DESC
        LIMIT 1
        ");

        $stmt->bind_param("ss", $slug, $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        $pageData = $result->fetch_assoc();
        $stmt->close();

        // Si no hay contenido en pages_contents, usar valores por defecto
        if ($pageData && !isset($pageData['html_content'])) {
            $pageData['html_content'] = '';
            $pageData['css_content'] = '';
            $pageData['php_content'] = '';
            $pageData['js_content'] = '';
            $pageData['version'] = 1;
            $pageData['last_modified'] = $pageData['updated_at'] ?? date('Y-m-d H:i:s');
        }

        return $pageData;
    }

    /**
     * Carga múltiples páginas con una consulta
     *
     * @param array $slugs Lista de slugs
     * @return array Páginas cargadas
     */
    private function loadMultiplePagesFromDatabase(array $slugs) {
        if (empty($slugs)) return [];

        $placeholders = implode(',', array_fill(0, count($slugs), '?'));
        $types = str_repeat('s', count($slugs));

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
                                     WHERE (p.slug IN ($placeholders) OR p.link IN ($placeholders))
        AND p.active = 1
        ORDER BY pc.version DESC
        ");

        $params = array_merge($slugs, $slugs);
        $stmt->bind_param($types . $types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $pages = [];
        while ($row = $result->fetch_assoc()) {
            $pages[$row['slug']] = $row;
        }

        $stmt->close();
        return $pages;
    }

    /**
     * Invalida caché de una página específica
     *
     * @param string $cacheKey Clave del caché o slug
     * @return bool Éxito
     */
    public function invalidateCache($cacheKey) {
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
     *
     * @return int Número de archivos eliminados
     */
    public function invalidateAllCache() {
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
     *
     * @return int Número de archivos eliminados
     */
    public function cleanExpiredCache() {
        $count = 0;
        $now = time();

        foreach (glob($this->metaDir . '/*.meta') as $metaFile) {
            $meta = unserialize(file_get_contents($metaFile));
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
     *
     * @param array $slugs Lista de slugs a precargar
     * @return array Resultados de la precarga
     */
    public function warmupCache(array $slugs) {
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
     *
     * @return array Estadísticas
     */
    public function getCacheStats() {
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
            'hit_ratio' => $this->cacheHits + $this->cacheMisses > 0
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
     *
     * @param string $slug Slug normalizado
     * @return string Clave MD5
     */
    private function generateCacheKey($slug) {
        return md5($slug . '_page_cache_v2');
    }

    /**
     * Normaliza el slug para búsqueda consistente
     *
     * @param string $slug Slug original
     * @return string Slug normalizado
     */
    private function normalizeSlug($slug) {
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
        if ($this->cacheHits + $this->cacheMisses > 0) {
            error_log(sprintf(
                "PageCache Stats - Hits: %d, Misses: %d, Ratio: %.2f%%",
                $this->cacheHits,
                $this->cacheMisses,
                $this->cacheHits + $this->cacheMisses > 0
                ? ($this->cacheHits / ($this->cacheHits + $this->cacheMisses)) * 100
                : 0
            ));
        }
    }
}
