<?php
declare(strict_types=1);

/**
 * Router con control de acceso basado en roles.
 * Migrado a PDO con validación de URI.
 *
 * CORRECCIONES:
 * - Validación estricta de URI (prevenir path traversal)
 * - Validación de $_SESSION['user_role']
 * - Validación de existencia de clases antes de instanciar
 * - exit después de http_response_code()
 * - Inyección de dependencias PDO
 */
class Router
{
    private array $routes = [];
    private PDO $db;
    private string $controllersNamespace = '';

    public function __construct(PDO $db, string $namespace = '')
    {
        $this->db = $db;
        $this->controllersNamespace = $namespace;
        $this->initRoutes();
    }

    private function initRoutes(): void
    {
        // ✅ Rutas con roles permitidos
        $this->routes['/admin/dashboard'] = ['AdminController', 'dashboard', ['admin']];
        $this->routes['/user/profile']    = ['UserController', 'profile', ['user', 'admin']];
        $this->routes['/admin/config']    = ['SystemController', 'config', ['admin']];
    }

    /**
     * Maneja la petición entrante.
     */
    public function route(string $uri): mixed
    {
        // ✅ Validar y sanitizar URI
        $uri = $this->sanitizeUri($uri);

        if (!array_key_exists($uri, $this->routes)) {
            http_response_code(404);
            echo 'Page Not Found';
            exit;
        }

        [$controllerName, $method, $allowedRoles] = $this->routes[$uri];

        // ✅ Verificar autorización
        if (!$this->authorize($allowedRoles)) {
            http_response_code(403);
            echo 'Access Denied';
            exit;
        }

        // ✅ Validar que la clase exista antes de instanciar
        $fullClassName = $this->controllersNamespace . $controllerName;
        if (!class_exists($fullClassName)) {
            error_log("Controller not found: {$fullClassName}");
            http_response_code(500);
            echo 'Internal Server Error';
            exit;
        }

        // ✅ Validar que el método exista
        $controller = new $fullClassName($this->db);
        if (!method_exists($controller, $method)) {
            error_log("Method not found: {$fullClassName}::{$method}");
            http_response_code(500);
            echo 'Internal Server Error';
            exit;
        }

        return $controller->$method();
    }

    /**
     * Sanitiza la URI para prevenir path traversal.
     */
    private function sanitizeUri(string $uri): string
    {
        // ✅ Eliminar caracteres peligrosos
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        $uri = str_replace(['..', '//'], '', $uri);
        $uri = '/' . trim($uri, '/');

        // ✅ Validar formato: solo caracteres permitidos
        if (!preg_match('#^/[a-zA-Z0-9/_-]+$#', $uri)) {
            $uri = '/';
        }

        return $uri;
    }

    /**
     * Verificación de autorización basada en roles.
     */
    private function authorize(array $allowedRoles): bool
    {
        // ✅ Validar que la sesión exista
        if (!isset($_SESSION['user_role']) || !is_string($_SESSION['user_role'])) {
            return false;
        }

        $userRole = $_SESSION['user_role'];

        // ✅ Validar formato del rol
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $userRole)) {
            return false;
        }

        return in_array($userRole, $allowedRoles, true);
    }

    /**
     * Añade una ruta dinámicamente.
     */
    public function addRoute(string $uri, string $controller, string $method, array $roles): void
    {
        $uri = $this->sanitizeUri($uri);
        $this->routes[$uri] = [$controller, $method, $roles];
    }

    /**
     * Obtiene todas las rutas registradas.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
