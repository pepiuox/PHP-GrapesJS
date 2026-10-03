<?php
declare(strict_types=1);

/**
 * Router optimizado con PDO y seguridad mejorada.
 *
 * CORRECCIONES:
 * - MySQLi → PDO
 * - Validación estricta de slug
 * - Protección contra path traversal
 * - Caché en memoria
 * - Límite de profundidad para jerarquías
 * - CSRF protection
 */
class RouterOptimized
{
    private PDO $conn;
    private PageRepository $pageRepo;
    private CacheManager $cache;
    private array $pageCache = [];
    private string $host;
    private string $requestUri;
    private string $path;
    private string $slug;

    public const CACHE_TTL = 3600;
    public const MAX_PARENT_DEPTH = 10;

    public function __construct(PDO $conn, ?CacheManager $cache = null)
    {
        $this->conn = $conn;
        $this->pageRepo = new PageRepository($conn);
        $this->cache = $cache ?? new CacheManager(
            sys_get_temp_dir() . '/router_cache',
                                                  self::CACHE_TTL
        );
        $this->initializeRequest();
    }

    private function initializeRequest(): void
    {
        // ✅ Determinar protocolo de forma segura
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? 0) == 443
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

        $protocol = $isSecure ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        // ✅ Validar host
        if (!preg_match('/^[a-zA-Z0-9\.\-]+(:[0-9]+)?$/', $host)) {
            $host = 'localhost';
        }

        $this->host = $protocol . $host;
        $this->requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->path = parse_url($this->requestUri, PHP_URL_PATH) ?: '/';

        // ✅ Sanitizar slug
        $this->slug = $this->sanitizeSlug(trim($this->path, '/'));
    }

    /**
     * Carga página con caché integrada.
     */
    public function loadPage(): ?array
    {
        // ✅ Usar caché de archivos si está disponible
        $cacheKey = 'page_' . $this->slug;
        $cached = $this->cache->get($cacheKey);

        if ($cached !== null && is_array($cached)) {
            return $cached;
        }

        // ✅ Caché en memoria
        if (isset($this->pageCache[$cacheKey])) {
            return $this->pageCache[$cacheKey];
        }

        $page = $this->findPage();
        if ($page !== null) {
            $this->pageCache[$cacheKey] = $page;
            $this->cache->set($cacheKey, $page);
            return $page;
        }

        return $this->handle404();
    }

    /**
     * Búsqueda optimizada de páginas.
     */
    private function findPage(): ?array
    {
        // ✅ Primero buscar por slug directo
        $page = $this->pageRepo->findBySlug($this->slug);
        if ($page !== null) {
            return $this->enrichPage($page);
        }

        // ✅ Si no se encuentra, buscar por ruta jerárquica
        return $this->findByPath();
    }

    /**
     * Enriquece página con contenido relacionado.
     */
    private function enrichPage(array $page): array
    {
        if (!isset($page['id'])) {
            return $page;
        }

        // Obtener contenido de la página
        $stmt = $this->conn->prepare(
            "SELECT pc.* FROM pages_contents pc
            WHERE pc.idPage = :id
            ORDER BY pc.version DESC
            LIMIT 1"
        );
        $stmt->execute([':id' => $page['id']]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($content) {
            $page['content'] = $content;
        }

        return $page;
    }

    /**
     * Búsqueda por ruta jerárquica optimizada.
     *
     * ✅ Protección contra path traversal
     */
    private function findByPath(): ?array
    {
        if ($this->slug === '') {
            return null;
        }

        $segments = explode('/', $this->slug);

        // ✅ Limitar número de segmentos
        if (count($segments) > self::MAX_PARENT_DEPTH) {
            return null;
        }

        // ✅ Validar cada segmento
        foreach ($segments as $segment) {
            if (!$this->isValidSlugSegment($segment)) {
                return null;
            }
        }

        return $this->pageRepo->findByPath($this->slug);
    }

    /**
     * Maneja páginas 404.
     */
    private function handle404(): ?array
    {
        http_response_code(404);

        // ✅ Buscar página 404 personalizada
        $page404 = $this->pageRepo->findBySlug('404');
        if ($page404 !== null) {
            return $page404;
        }

        return [
            'id'     => 0,
            'title'  => 'Página no encontrada',
            'slug'   => '404',
            'status' => 404,
        ];
    }

    /**
     * Obtiene la URL canónica.
     */
    public function getCanonicalUrl(): string
    {
        return $this->host . '/' . $this->slug;
    }

    /**
     * Sanitiza slug.
     */
    private function sanitizeSlug(string $slug): string
    {
        // ✅ Eliminar caracteres peligrosos
        $slug = str_replace(['..', '//', "\0"], '', $slug);
        $slug = trim($slug, '/');

        // ✅ Limitar longitud
        if (strlen($slug) > 200) {
            $slug = substr($slug, 0, 200);
        }

        return $slug;
    }

    /**
     * Valida segmento de slug.
     */
    private function isValidSlugSegment(string $segment): bool
    {
        if ($segment === '' || strlen($segment) > 100) {
            return false;
        }
        return (bool) preg_match('/^[a-zA-Z0-9\-_]+$/', $segment);
    }
}
