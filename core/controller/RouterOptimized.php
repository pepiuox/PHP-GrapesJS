<?php
class RouterOptimized {
    private $conn;
    private $cache = [];
    private $pageCache = [];

    // Constantes para evitar magic numbers
    const CACHE_TTL = 3600;
    const MAX_PARENT_DEPTH = 10;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->initializeRequest();
    }

    private function initializeRequest() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ? 'https://' : 'http://';
        $this->host = $protocol . $_SERVER['HTTP_HOST'];
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->path = parse_url($this->requestUri, PHP_URL_PATH);
        $this->slug = trim($this->path, '/');
    }

    /**
     * Carga página con caché integrada
     */
    public function loadPage() {
        // Usar caché si está disponible
        $cacheKey = 'page_' . md5($this->slug);

        if (isset($this->pageCache[$cacheKey])) {
            return $this->pageCache[$cacheKey];
        }

        $page = $this->findPage();

        if ($page) {
            $this->pageCache[$cacheKey] = $page;
            return $page;
        }

        return $this->handle404();
    }

    /**
     * Búsqueda optimizada de páginas
     */
    private function findPage() {
        // Primero buscar por slug directo
        $stmt = $this->conn->prepare(
            "SELECT p.*, pc.* FROM pages p
            LEFT JOIN pages_contents pc ON p.id = pc.idPage
            WHERE (p.slug = ? OR p.link = ?)
        AND p.active = 1
        ORDER BY pc.version DESC LIMIT 1"
        );

        $stmt->bind_param("ss", $this->slug, $this->slug);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row;
        }

        // Si no se encuentra, buscar por ruta jerárquica
        return $this->findByPath();
    }

    /**
     * Búsqueda por ruta jerárquica optimizada
     */
    private function findByPath() {
        $segments = explode('/', $this->slug);
        $currentParent = 0;
        $foundPage = null;

        foreach ($segments as $segment) {
            $stmt = $this->conn->prepare(
                "SELECT id, link, parent FROM pages
                WHERE link = ? AND parent = ? AND active = 1"
            );
            $stmt->bind_param("si", $segment, $currentParent);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($page = $result->fetch_assoc()) {
                $foundPage = $page;
                $currentParent = $page['id'];
            } else {
                return null;
            }
        }

        return $foundPage;
    }

    private function handle404() {
        http_response_code(404);
        // Cargar página 404
        return null;
    }
}
