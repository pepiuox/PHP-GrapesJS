<?php
declare(strict_types=1);

/**
 * Gestión de rutas y paths de páginas.
 * Migrado a PDO con validaciones de seguridad.
 *
 * CORRECCIONES:
 * - Validación estricta de slug
 * - Protección contra path traversal
 * - Límite de profundidad de jerarquía
 * - Inyección de dependencias PDO
 * - Detección de ciclos
 */
class Paths
{
    private PDO $conn;
    public string $url;
    public string $host;
    public string $basename;
    public string $protocol;
    public string $escaped_url;
    public string $url_path;
    public int $startpage = 1;
    public int $active = 1;
    public int $parent = 0;
    public string $pg404;

    public const MAX_DEPTH = 10;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->initializeUrls();
    }

    private function initializeUrls(): void
    {
        // ✅ Determinar protocolo de forma segura
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? 0) == 443
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

        $this->protocol = $isSecure ? 'https://' : 'http://';

        // ✅ Validar HTTP_HOST
        $httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
        if (!preg_match('/^[a-zA-Z0-9\.\-]+(:[0-9]+)?$/', $httpHost)) {
            $httpHost = 'localhost';
        }

        $this->host = $this->protocol . $httpHost . '/';
        $this->pg404 = $this->host . 'error/404';

        // ✅ Sanitizar REQUEST_URI
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->url = $this->protocol . $httpHost . $requestUri;

        $this->escaped_url = htmlspecialchars($this->url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $this->url_path = parse_url($this->escaped_url, PHP_URL_PATH) ?: '/';
        $this->basename = $this->sanitizeBasename(pathinfo($this->url_path, PATHINFO_BASENAME));
    }

    /**
     * Obtiene el path completo de una página.
     *
     * ✅ Validación estricta de slug
     */
    public function PagesPath(string $plink): ?string
    {
        // ✅ Validar formato de slug
        if (!$this->isValidSlug($plink)) {
            return $this->pg404;
        }

        $pg = $this->conn->prepare(
            'SELECT view_page, link, startpage, type, path_file, parent, active
            FROM pages WHERE link = ? AND active = ? LIMIT 1'
        );
        $pg->execute([$plink, $this->active]);
        $row = $pg->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return $this->pg404;
        }

        if ($row['view_page'] !== 'public') {
            return $this->pg404;
        }

        $parentId = (int) $row['parent'];
        if ($parentId > 0) {
            // ✅ Método iterativo con detección de ciclos
            $link = $this->GetParentChain($parentId);
            if ($link === null) {
                return $this->pg404;
            }
            return $this->host . $link . '/' . $row['link'];
        }

        return $this->host . $row['link'];
    }

    /**
     * Obtiene la cadena de padres de forma iterativa.
     *
     * ✅ Reemplaza recursión peligrosa con método iterativo
     * ✅ Detecta ciclos y limita profundidad
     */
    public function GetParentChain(int $parentId, int $maxDepth = self::MAX_DEPTH): ?string
    {
        $chain = [];
        $currentId = $parentId;
        $depth = 0;
        $visited = []; // ✅ Prevenir ciclos

        while ($currentId > 0 && $depth < $maxDepth) {
            // ✅ Prevenir ciclos
            if (in_array($currentId, $visited, true)) {
                error_log("Paths: ciclo detectado en página ID {$currentId}");
                return null;
            }
            $visited[] = $currentId;

            $pr = $this->conn->prepare(
                'SELECT id, link, parent, active FROM pages
                WHERE id = ? AND active = ? LIMIT 1'
            );
            $pr->execute([$currentId, $this->active]);
            $row = $pr->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            array_unshift($chain, $row['link']);
            $currentId = (int) $row['parent'];
            $depth++;
        }

        // ✅ Si se alcanzó el límite sin llegar a la raíz, es inválido
        if ($currentId > 0 && $depth >= $maxDepth) {
            error_log("Paths: profundidad máxima alcanzada");
            return null;
        }

        return implode('/', $chain);
    }

    /**
     * Obtiene página por ID.
     */
    public function getPageById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $stmt = $this->conn->prepare(
            'SELECT * FROM pages WHERE id = ? AND active = ? LIMIT 1'
        );
        $stmt->execute([$id, $this->active]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Obtiene página por slug.
     */
    public function getPageBySlug(string $slug): ?array
    {
        if (!$this->isValidSlug($slug)) {
            return null;
        }

        $stmt = $this->conn->prepare(
            'SELECT * FROM pages WHERE (slug = ? OR link = ?) AND active = ? LIMIT 1'
        );
        $stmt->execute([$slug, $slug, $this->active]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Obtiene hijos de una página.
     */
    public function getChildren(int $parentId): array
    {
        if ($parentId < 0) {
            return [];
        }

        $stmt = $this->conn->prepare(
            'SELECT * FROM pages WHERE parent = ? AND active = ?
            ORDER BY sort_order ASC, title ASC'
        );
        $stmt->execute([$parentId, $this->active]);
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
     * Sanitiza basename para prevenir path traversal.
     */
    private function sanitizeBasename(string $basename): string
    {
        // ✅ Eliminar extensión y caracteres peligrosos
        $basename = pathinfo($basename, PATHINFO_FILENAME);
        $basename = str_replace(['..', '/', '\\', "\0"], '', $basename);

        if (!$this->isValidSlug($basename)) {
            return '';
        }

        return $basename;
    }

    /**
     * Obtiene la URL completa de una página.
     */
    public function getFullUrl(string $slug): string
    {
        if (!$this->isValidSlug($slug)) {
            return $this->pg404;
        }
        return $this->host . $slug;
    }
}
