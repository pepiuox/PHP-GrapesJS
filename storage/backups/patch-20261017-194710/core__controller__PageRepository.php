<?php
declare(strict_types=1);

/**
 * Repositorio de páginas con PDO.
 * Implementación completa con seguridad mejorada.
 *
 * CORRECCIONES:
 * - Implementación completa de métodos vacíos
 * - PDO con prepared statements
 * - Validación de IDs y slugs
 * - Protección contra path traversal
 * - Caché en memoria para jerarquías
 */
class PageRepository
{
    private PDO $conn;
    private string $table = 'pages';
    private array $cache = [];

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    /**
     * Busca página por ID.
     */
    public function findById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        if (isset($this->cache["id_{$id}"])) {
            return $this->cache["id_{$id}"];
        }

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id AND active = 1 LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($page !== null) {
            $this->cache["id_{$id}"] = $page;
        }

        return $page;
    }

    /**
     * Busca página por slug.
     *
     * ✅ Validación estricta de slug
     */
    public function findBySlug(string $slug): ?array
    {
        // ✅ Validar formato de slug
        if (!$this->isValidSlug($slug)) {
            return null;
        }

        if (isset($this->cache["slug_{$slug}"])) {
            return $this->cache["slug_{$slug}"];
        }

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table}
            WHERE (slug = :slug OR link = :slug2) AND active = 1
            LIMIT 1"
        );
        $stmt->execute([':slug' => $slug, ':slug2' => $slug]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($page !== null) {
            $this->cache["slug_{$slug}"] = $page;
        }

        return $page;
    }

    /**
     * Busca páginas por padre.
     */
    public function findByParent(int $parentId): array
    {
        if ($parentId < 0) {
            return [];
        }

        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table}
            WHERE parent = :parent AND active = 1
            ORDER BY sort_order ASC, title ASC"
        );
        $stmt->execute([':parent' => $parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene la jerarquía completa de una página.
     *
     * ✅ Protección contra DoS con límite de profundidad
     */
    public function getHierarchy(int $pageId, int $maxDepth = 10): array
    {
        $hierarchy = [];
        $currentId = $pageId;
        $depth = 0;
        $visited = []; // ✅ Prevenir ciclos

        while ($currentId > 0 && $depth < $maxDepth) {
            // ✅ Prevenir ciclos infinitos
            if (in_array($currentId, $visited, true)) {
                break;
            }
            $visited[] = $currentId;

            $page = $this->findById($currentId);
            if ($page === null) {
                break;
            }

            array_unshift($hierarchy, $page);
            $currentId = (int) ($page['parent'] ?? 0);
            $depth++;
        }

        return $hierarchy;
    }

    /**
     * Obtiene la página de inicio.
     */
    public function getHomePage(): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table}
            WHERE startpage = 1 AND active = 1
            LIMIT 1"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Busca página por ruta jerárquica.
     *
     * ✅ Protección contra path traversal
     */
    public function findByPath(string $path): ?array
    {
        // ✅ Sanitizar path
        $path = $this->sanitizePath($path);
        if ($path === '') {
            return null;
        }

        $segments = explode('/', trim($path, '/'));
        $currentParent = 0;
        $foundPage = null;

        foreach ($segments as $segment) {
            // ✅ Validar cada segmento
            if (!$this->isValidSlug($segment)) {
                return null;
            }

            $stmt = $this->conn->prepare(
                "SELECT id, link, parent FROM {$this->table}
                WHERE link = :link AND parent = :parent AND active = 1
                LIMIT 1"
            );
            $stmt->execute([':link' => $segment, ':parent' => $currentParent]);
            $page = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$page) {
                return null;
            }

            $foundPage = $page;
            $currentParent = (int) $page['id'];
        }

        return $foundPage;
    }

    /**
     * Obtiene páginas para sitemap.
     */
    public function getSitemapPages(int $limit = 1000): array
    {
        $limit = max(1, min($limit, 10000));

        $stmt = $this->conn->prepare(
            "SELECT id, slug, link, updated_at
            FROM {$this->table}
            WHERE active = 1
            ORDER BY updated_at DESC
            LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Valida formato de slug.
     */
    private function isValidSlug(string $slug): bool
    {
        if ($slug === '' || strlen($slug) > 200) {
            return false;
        }
        // ✅ Solo permite caracteres seguros
        return (bool) preg_match('/^[a-zA-Z0-9\-_]+$/', $slug);
    }

    /**
     * Sanitiza path para prevenir traversal.
     */
    private function sanitizePath(string $path): string
    {
        // ✅ Eliminar caracteres peligrosos
        $path = str_replace(['..', '//', "\0"], '', $path);
        $path = '/' . trim($path, '/');

        // ✅ Validar formato
        if (!preg_match('#^/[a-zA-Z0-9/_-]+$#', $path)) {
            return '';
        }

        return $path;
    }
}
