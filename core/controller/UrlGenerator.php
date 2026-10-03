<?php
declare(strict_types=1);

/**
 * Generador de URLs con seguridad mejorada.
 * Implementación completa con validaciones.
 *
 * CORRECCIONES:
 * - Implementación completa de métodos vacíos
 * - Validación de inputs
 * - Protección contra XSS
 * - Soporte para URLs canónicas
 * - Sanitización de parámetros
 */
class UrlGenerator
{
    private PDO $conn;
    private string $baseUrl;
    private string $protocol;
    private string $host;

    public function __construct(PDO $connection, ?string $baseUrl = null)
    {
        $this->conn = $connection;

        // ✅ Determinar protocolo
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? 0) == 443
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

        $this->protocol = $isSecure ? 'https://' : 'http://';

        // ✅ Validar host
        $httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
        if (!preg_match('/^[a-zA-Z0-9\.\-]+(:[0-9]+)?$/', $httpHost)) {
            $httpHost = 'localhost';
        }
        $this->host = $httpHost;

        $this->baseUrl = $baseUrl ?? ($this->protocol . $httpHost . '/');
    }

    /**
     * Genera URL para una página.
     */
    public function generateUrl(array|string $page, array $params = []): string
    {
        if (is_array($page)) {
            $slug = $page['slug'] ?? $page['link'] ?? '';
        } else {
            $slug = $page;
        }

        // ✅ Validar slug
        if (!$this->isValidSlug($slug)) {
            return $this->baseUrl;
        }

        // ✅ Construir URL base
        $url = rtrim($this->baseUrl, '/') . '/' . $slug;

        // ✅ Añadir parámetros de forma segura
        if (!empty($params)) {
            $safeParams = $this->sanitizeParams($params);
            if (!empty($safeParams)) {
                $url .= '?' . http_build_query($safeParams, '', '&', PHP_QUERY_RFC3986);
            }
        }

        return $url;
    }

    /**
     * Obtiene URL canónica para SEO.
     */
    public function getCanonicalUrl(array $page): string
    {
        $slug = $page['slug'] ?? $page['link'] ?? '';

        if (!$this->isValidSlug($slug)) {
            return $this->baseUrl;
        }

        // ✅ URL canónica sin parámetros innecesarios
        return $this->protocol . $this->host . '/' . $slug;
    }

    /**
     * Genera URL para asset (CSS, JS, imágenes).
     */
    public function asset(string $path): string
    {
        // ✅ Prevenir path traversal
        $path = str_replace(['..', "\0"], '', $path);
        $path = ltrim($path, '/');

        if (!preg_match('/^[a-zA-Z0-9\/\.\-_]+$/', $path)) {
            return '';
        }

        return rtrim($this->baseUrl, '/') . '/assets/' . $path;
    }

    /**
     * Genera URL absoluta.
     */
    public function absolute(string $path): string
    {
        // ✅ Prevenir path traversal
        $path = str_replace(['..', "\0"], '', $path);
        $path = ltrim($path, '/');

        if (!preg_match('/^[a-zA-Z0-9\/\.\-_]+$/', $path)) {
            return $this->baseUrl;
        }

        return rtrim($this->baseUrl, '/') . '/' . $path;
    }

    /**
     * Genera URL para ruta con parámetros nombrados.
     * Ejemplo: route('user.profile', ['id' => 5]) → /user/5/profile
     */
    public function route(string $name, array $params = []): string
    {
        $routes = $this->getNamedRoutes();

        if (!isset($routes[$name])) {
            return $this->baseUrl;
        }

        $pattern = $routes[$name];

        // ✅ Reemplazar parámetros
        foreach ($params as $key => $value) {
            $safeValue = $this->sanitizeParamValue($value);
            $pattern = str_replace('{' . $key . '}', $safeValue, $pattern);
        }

        // ✅ Verificar que no queden parámetros sin resolver
        if (preg_match('/\{[a-zA-Z_]+\}/', $pattern)) {
            return $this->baseUrl;
        }

        return rtrim($this->baseUrl, '/') . '/' . ltrim($pattern, '/');
    }

    /**
     * Genera URL con query string.
     */
    public function query(string $path, array $params = []): string
    {
        $url = $this->absolute($path);

        if (!empty($params)) {
            $safeParams = $this->sanitizeParams($params);
            if (!empty($safeParams)) {
                $url .= '?' . http_build_query($safeParams, '', '&', PHP_QUERY_RFC3986);
            }
        }

        return $url;
    }

    /**
     * Genera URL para página anterior (referrer).
     */
    public function back(): string
    {
        $referrer = $_SERVER['HTTP_REFERER'] ?? $this->baseUrl;

        // ✅ Validar que sea del mismo host
        $parsed = parse_url($referrer);
        if (!$parsed || !isset($parsed['host']) || $parsed['host'] !== $this->host) {
            return $this->baseUrl;
        }

        return $referrer;
    }

    /**
     * Genera URL de logout con token CSRF.
     */
    public function logoutUrl(): string
    {
        $token = $this->generateCSRFToken();
        return $this->absolute('logout') . '?token=' . urlencode($token);
    }

    /**
     * Obtiene el host actual.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Obtiene el protocolo actual.
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * Obtiene la URL base.
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Obtiene la URL actual completa.
     */
    public function current(): string
    {
        return $this->protocol . $this->host . ($_SERVER['REQUEST_URI'] ?? '/');
    }

    /**
     * Rutas nombradas.
     */
    private function getNamedRoutes(): array
    {
        return [
            'home'           => '/',
            'login'          => 'signin/login',
            'register'       => 'signin/register',
            'logout'         => 'logout',
            'profile'        => 'profile/{username}',
            'user.profile'   => 'user/{id}/profile',
            'admin.dashboard'=> 'admin/dashboard',
            'admin.users'    => 'admin/users',
            'product.show'   => 'product/{slug}',
            'category.show'  => 'category/{slug}',
            'page.show'      => '{slug}',
        ];
    }

    /**
     * Sanitiza array de parámetros.
     */
    private function sanitizeParams(array $params): array
    {
        $safe = [];
        foreach ($params as $key => $value) {
            // ✅ Validar key
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', (string) $key)) {
                continue;
            }
            $safe[(string) $key] = $this->sanitizeParamValue($value);
        }
        return $safe;
    }

    /**
     * Sanitiza valor de parámetro.
     */
    private function sanitizeParamValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }
        if (is_string($value)) {
            // ✅ Limitar longitud
            $value = substr($value, 0, 200);
            // ✅ Eliminar caracteres peligrosos
            $value = str_replace(["\0", "\r", "\n"], '', $value);
            return $value;
        }
        return '';
    }

    /**
     * Valida formato de slug.
     */
    private function isValidSlug(string $slug): bool
    {
        if ($slug === '' || strlen($slug) > 200) {
            return false;
        }
        return (bool) preg_match('/^[a-zA-Z0-9\-_\/]+$/', $slug);
    }

    /**
     * Genera token CSRF.
     */
    private function generateCSRFToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}
