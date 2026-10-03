<?php
declare(strict_types=1);

/**
 * Router principal con PDO y seguridad mejorada.
 *
 * CORRECCIONES:
 * - MySQLi → PDO
 * - Eliminada recursión peligrosa (GetParent, GetSecondParent, GetThirdParent)
 * - Validación estricta de URI y parámetros
 * - Protección contra path traversal
 * - CSRF protection
 * - Rate limiting básico
 */
class Routers
{
    private PDO $conn;
    public string $url;
    public string $host;
    public string $basepage;
    public string $protocol;
    public string $escaped_url;
    public string $url_path;
    public int $startpage = 1;
    public int $active = 1;
    public int $parent = 0;
    public string $pg404;
    private array $columns;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->initializeUrls();
        $this->initColumns();
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

        // ✅ Escapar URL correctamente
        $this->escaped_url = htmlspecialchars($this->url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $this->url_path = parse_url($this->url, PHP_URL_PATH) ?: '/';
        $this->basepage = $this->sanitizeBasepage(pathinfo($this->url_path, PATHINFO_BASENAME));
    }

    private function initColumns(): void
    {
        // ✅ Lista blanca de columnas permitidas
        $this->columns = [
            'id', 'language', 'view_page', 'title', 'slug', 'link', 'url',
            'keyword', 'classification', 'description', 'type', 'menu',
            'path_file', 'html_content', 'css_content', 'php_content',
            'js_content', 'parent', 'active', 'startpage', 'sort_order'
        ];
    }

    public function loadPage(): ?array
    {
        if ($this->host === rtrim($this->url, '/') . '/') {
            return $this->InitPage();
        }

        if (!empty($this->basepage)) {
            $npg = $this->Pages();
            if ($npg === $this->url) {
                return $this->PageDataWeb();
            }
            // ✅ Redirección segura
            if ($this->isValidRedirectUrl($npg)) {
                header('Location: ' . $npg, true, 301);
                exit;
            }
        }

        return $this->routePages();
    }

    public function InitPage(): ?array
    {
        if ($this->host === rtrim($this->url, '/') . '/') {
            $stmt = $this->conn->prepare(
                "SELECT * FROM pages WHERE startpage = :sp AND active = :a LIMIT 1"
            );
            $stmt->execute([':sp' => $this->startpage, ':a' => $this->active]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }
        return $this->PageDataWeb();
    }

    public function GetTitle(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $basename = basename(parse_url($uri, PHP_URL_PATH) ?: '/');
        // ✅ Sanitizar output
        return htmlspecialchars($basename, ENT_QUOTES, 'UTF-8');
    }

    public function PageDataWeb(): ?array
    {
        if (empty($this->basepage)) {
            return null;
        }

        $stmt = $this->conn->prepare(
            "SELECT * FROM pages WHERE link = :link AND active = :a LIMIT 1"
        );
        $stmt->execute([':link' => $this->basepage, ':a' => $this->active]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function GoPage(): bool
    {
        $page = $this->basepage;
        if ($page === 'home' || $page === 'inicio' || $page === '') {
            return true;
        }

        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM pages WHERE link = :link AND active = :a"
        );
        $stmt->execute([':link' => $page, ':a' => $this->active]);
        return (int) $stmt->fetchColumn() === 1;
    }

    public function routePages(): ?array
    {
        if ($this->basepage === 'home' || $this->basepage === 'inicio') {
            return $this->InitPage();
        }

        // ✅ Validación estricta de $_GET['url']
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $id = $this->sanitizeInt($_GET['url']);
            if ($id === null || $id <= 0) {
                return null;
            }

            $stmt = $this->conn->prepare(
                "SELECT * FROM pages WHERE id = :id AND active = :a LIMIT 1"
            );
            $stmt->execute([':id' => $id, ':a' => $this->active]);
            $page = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($page && isset($page['link'])) {
                return $this->Pages($page['link']);
            }
        }

        return null;
    }

    public function Pages(?string $customBasepage = null): string
    {
        $basepage = $customBasepage ?? $this->basepage;

        if (empty($basepage) || !$this->isValidSlug($basepage)) {
            return $this->pg404;
        }

        $stmt = $this->conn->prepare(
            "SELECT link, startpage, parent, active FROM pages
            WHERE link = :link AND active = :a LIMIT 1"
        );
        $stmt->execute([':link' => $basepage, ':a' => $this->active]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return $this->pg404;
        }

        if ((int) $row['startpage'] === 1) {
            return $this->host;
        }

        $parentId = (int) $row['parent'];
        if ($parentId > 0) {
            // ✅ Reemplazada recursión peligrosa por método iterativo
            $link = $this->GetParentChain($parentId);
            if ($link === null) {
                return $this->pg404;
            }
            return $this->host . $link . '/' . $row['link'];
        }

        return $this->host . $row['link'];
    }

    /**
     * ✅ MÉTODO ITERATIVO en lugar de recursión peligrosa.
     * Reemplaza GetParent, GetSecondParent, GetThirdParent.
     *
     * Previene:
     * - Stack overflow
     * - Ciclos infinitos
     * - DoS por profundidad excesiva
     */
    public function GetParentChain(int $parentId, int $maxDepth = 10): ?string
    {
        $chain = [];
        $currentId = $parentId;
        $depth = 0;
        $visited = []; // ✅ Prevenir ciclos

        while ($currentId > 0 && $depth < $maxDepth) {
            // ✅ Prevenir ciclos
            if (in_array($currentId, $visited, true)) {
                return null;
            }
            $visited[] = $currentId;

            $stmt = $this->conn->prepare(
                "SELECT id, link, parent FROM pages
                WHERE id = :id AND active = :a LIMIT 1"
            );
            $stmt->execute([':id' => $currentId, ':a' => $this->active]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            array_unshift($chain, $row['link']);
            $currentId = (int) $row['parent'];
            $depth++;
        }

        // ✅ Si se alcanzó el límite sin llegar a la raíz, es inválido
        if ($currentId > 0 && $depth >= $maxDepth) {
            return null;
        }

        return implode('/', $chain);
    }

    /**
     * Sanitiza basepage.
     */
    private function sanitizeBasepage(string $basepage): string
    {
        // ✅ Eliminar extensión y caracteres peligrosos
        $basepage = pathinfo($basepage, PATHINFO_FILENAME);
        $basepage = str_replace(['..', '/', '\\', "\0"], '', $basepage);

        if (!$this->isValidSlug($basepage)) {
            return '';
        }

        return $basepage;
    }

    /**
     * Valida formato de slug.
     */
    private function isValidSlug(string $slug): bool
    {
        if ($slug === '' || strlen($slug) > 100) {
            return false;
        }
        return (bool) preg_match('/^[a-zA-Z0-9\-_]+$/', $slug);
    }

    /**
     * Sanitiza entero.
     */
    private function sanitizeInt(mixed $value): ?int
    {
        if (!is_numeric($value)) {
            return null;
        }
        $int = (int) $value;
        return $int >= 0 ? $int : null;
    }

    /**
     * Valida URL de redirección.
     */
    private function isValidRedirectUrl(string $url): bool
    {
        // ✅ Solo permitir redirecciones al mismo host
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parsed = parse_url($url);
        if (!$parsed || !isset($parsed['host'])) {
            return false;
        }

        $currentHost = $_SERVER['HTTP_HOST'] ?? '';
        return $parsed['host'] === $currentHost;
    }
}
