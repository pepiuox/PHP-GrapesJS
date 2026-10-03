<?php
declare(strict_types=1);

/**
 * Sistema de renderizado de páginas.
 * Migrado a PDO con protección contra LFI y path traversal.
 *
 * CORRECCIONES CRÍTICAS:
 * - LFI potencial eliminado: ahora usa lista blanca de rutas
 * - Validación estricta de segmentos de URL
 * - Protección contra path traversal
 * - Inyección de dependencias PDO
 * - Validación de permisos por sección
 */
class PageSystem
{
    private PDO $conn;
    private array $tempURL;
    private string $basePath;

    /**
     * ✅ Lista blanca de rutas permitidas por sección.
     * Previene LFI al restringir qué archivos pueden incluirse.
     */
    private const ALLOWED_ROUTES = [
        'signin' => [
            'login', 'logout', 'register',
            'forgot-username', 'forgot-password',
            'forgot-email', 'forgot-pin'
        ],
        'admin' => [
            'dashboard', 'builder'
        ],
        'profile' => [
            'userprofile', 'settings', 'security', 'activity'
        ],
        'error' => [
            '404', '403', '500'
        ]
    ];

    public function __construct(PDO $connection, string $basePath = '')
    {
        $this->conn = $connection;
        $this->basePath = $basePath ?: (defined('URL') ? URL : __DIR__ . '/../../..');
        $this->tempURL = $this->parseRequestUri();
    }

    /**
     * Parsea y valida la URI de la petición.
     *
     * ✅ Protección contra path traversal
     */
    private function parseRequestUri(): array
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // ✅ Eliminar caracteres peligrosos
        $uri = str_replace(['..', "\0", '//'], '', $uri);
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';

        // ✅ Dividir en segmentos y validar cada uno
        $segments = array_filter(explode('/', trim($uri, '/')), function($seg) {
            return $this->isValidSegment($seg);
        });

        return array_values($segments);
    }

    /**
     * Valida un segmento de URL.
     */
    private function isValidSegment(string $segment): bool
    {
        if ($segment === '' || strlen($segment) > 100) {
            return false;
        }
        // ✅ Solo permite caracteres seguros
        return (bool) preg_match('/^[a-zA-Z0-9\-_]+$/', $segment);
    }

    /**
     * Renderiza la página del sistema.
     */
    public function viewPageSystem(): void
    {
        $tempBASE = $this->tempURL[0] ?? '';
        $tempURI = $this->tempURL[1] ?? '';

        // ✅ Definir constantes de forma segura
        $this->defineConstants();

        // ✅ Validar que la ruta esté en la lista blanca
        if (!$this->isRouteAllowed($tempBASE, $tempURI)) {
            $this->render404();
            return;
        }

        switch ($tempBASE) {
            case 'signin':
                $this->renderSignIn($tempURI);
                break;
            case 'admin':
                $this->renderAdmin($tempURI);
                break;
            case 'profile':
                $this->renderProfile($tempURI);
                break;
            case 'error':
                $this->renderError($tempURI);
                break;
            default:
                $this->render404();
        }
    }

    /**
     * Define constantes desde la URL de forma segura.
     */
    private function defineConstants(): void
    {
        // ✅ Solo definir si el segmento es válido
        if (isset($this->tempURL[2]) && $this->isValidSegment($this->tempURL[2])) {
            if (!defined('CMS')) {
                define('CMS', $this->tempURL[2]);
            }
        }

        if (isset($this->tempURL[3])) {
            $seg = $this->tempURL[3];
            if ($this->isValidSegment($seg)) {
                if (is_numeric($seg)) {
                    if (!defined('IDP')) define('IDP', (int) $seg);
                } else {
                    if (!defined('WS')) define('WS', $seg);
                }
            }
        }

        if (isset($this->tempURL[4]) && $this->isValidSegment($this->tempURL[4])) {
            $seg = $this->tempURL[4];
            if (is_numeric($seg)) {
                if (!defined('IDP')) define('IDP', (int) $seg);
            } else {
                if (!defined('TBL')) define('TBL', $seg);
            }
        }

        if (isset($this->tempURL[5]) && $this->isValidSegment($this->tempURL[5])) {
            if (is_numeric($this->tempURL[5])) {
                if (!defined('IDP')) define('IDP', (int) $this->tempURL[5]);
            }
        }
    }

    /**
     * Verifica si una ruta está permitida.
     *
     * ✅ Previene LFI verificando contra lista blanca
     */
    private function isRouteAllowed(string $base, string $uri): bool
    {
        if ($base === '' || !isset(self::ALLOWED_ROUTES[$base])) {
            return false;
        }
        return in_array($uri, self::ALLOWED_ROUTES[$base], true);
    }

    /**
     * Construye path de archivo de forma segura.
     *
     * ✅ Previene path traversal
     */
    private function buildSafePath(string $relativePath): string
    {
        $fullPath = realpath($this->basePath . '/' . $relativePath);

        if ($fullPath === false) {
            return '';
        }

        // ✅ Verificar que el path está dentro del basePath
        $realBase = realpath($this->basePath);
        if ($realBase === false || strpos($fullPath, $realBase) !== 0) {
            return '';
        }

        return $fullPath;
    }

    /**
     * Incluye un archivo de forma segura.
     */
    private function safeInclude(string $relativePath): void
    {
        $path = $this->buildSafePath($relativePath);
        if ($path !== '' && is_file($path)) {
            include $path;
        } else {
            error_log("PageSystem: archivo no encontrado: {$relativePath}");
        }
    }

    private function renderSignIn(string $tempURI): void
    {
        $this->safeInclude('core/elements/top.php');
        echo '</head><body><div id="wrapper">';
        echo '<div class="container-fluid min-h-screen" id="content-page">';
        $this->safeInclude('core/elements/menu.php');

        // ✅ Mapeo seguro de rutas a archivos
        $fileMap = [
            'login'           => 'core/pages/login/login.php',
            'logout'          => 'core/pages/login/logout.php',
            'register'        => 'core/pages/register/register.php',
            'forgot-username' => 'core/pages/forgot/forgot-username.php',
            'forgot-password' => 'core/pages/forgot/forgot-password.php',
            'forgot-email'    => 'core/pages/forgot/forgot-email.php',
            'forgot-pin'      => 'core/pages/forgot/forgot-pin.php',
        ];

        if (isset($fileMap[$tempURI])) {
            $this->safeInclude($fileMap[$tempURI]);
        }

        echo '</div>';
        $this->safeInclude('core/elements/footer.php');
        echo '</div></body></html>';
    }

    private function renderAdmin(string $tempURI): void
    {
        // ✅ Verificar permisos de administrador
        if (!$this->hasAdminAccess()) {
            $this->render403();
            return;
        }

        if ($tempURI === 'dashboard') {
            $this->safeInclude('core/elements/header_dashboard.php');
            echo '</head><body class="hold-transition sidebar-mini"><div class="wrapper">';
            $this->safeInclude('core/managers/dashboard.php');
            echo '</div></body></html>';
        } elseif ($tempURI === 'builder') {
            if (isset($this->tempURL[2]) && $this->isValidSegment($this->tempURL[2])) {
                if (!defined('PAG')) define('PAG', $this->tempURL[2]);
            }
            $this->safeInclude('core/elements/top_build.php');
            echo '</head><body id="builder">';
            $this->safeInclude('core/managers/builder.php');
            echo '</body></html>';
        }
    }

    private function renderProfile(string $tempURI): void
    {
        // ✅ Verificar que el usuario esté logueado
        if (!$this->isLoggedIn()) {
            header('Location: ' . $this->basePath . '/signin/login');
            exit;
        }

        if (isset($this->tempURL[2]) && $this->isValidSegment($this->tempURL[2])) {
            if (!defined('USR')) define('USR', $this->tempURL[2]);
        }
        if (isset($this->tempURL[3]) && $this->isValidSegment($this->tempURL[3])) {
            if (!defined('WS')) define('WS', $this->tempURL[3]);
        }

        $this->safeInclude('core/elements/header.php');
        echo '</head><body class="hold-transition sidebar-mini"><div class="wrapper">';

        // ✅ Validar que el archivo exista en la lista blanca
        $allowedProfiles = ['userprofile', 'settings', 'security', 'activity'];
        if (in_array($tempURI, $allowedProfiles, true)) {
            $this->safeInclude("core/users/{$tempURI}.php");
        } else {
            $this->render404();
            return;
        }

        echo '</div></body></html>';
    }

    private function renderError(string $tempURI): void
    {
        $this->safeInclude('core/elements/top.php');
        echo '</head><body><div id="wrapper">';
        echo '<div class="container-fluid min-h-screen" id="content-page">';
        $this->safeInclude('core/elements/menu.php');

        $allowedErrors = ['404', '403', '500'];
        if (in_array($tempURI, $allowedErrors, true)) {
            $this->safeInclude("core/pages/error_pages/{$tempURI}.php");
        } else {
            $this->safeInclude('core/pages/error_pages/404.php');
        }

        echo '</div>';
        $this->safeInclude('core/elements/footer.php');
        echo '</div></body></html>';
    }

    private function render404(): void
    {
        http_response_code(404);
        $this->renderError('404');
    }

    private function render403(): void
    {
        http_response_code(403);
        $this->renderError('403');
    }

    /**
     * Verifica si el usuario tiene acceso de administrador.
     */
    private function hasAdminAccess(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['levels'])
        && in_array($_SESSION['levels'], ['admin', 'master'], true);
    }

    /**
     * Verifica si el usuario está logueado.
     */
    private function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['user_id']);
    }
}
