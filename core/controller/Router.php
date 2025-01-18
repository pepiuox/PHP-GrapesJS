<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class Router {

    private $routes = [];
    private $db;

    // Constructor to initialize database connection and routes
    public function __construct($db) {
        $this->db = $db;
        $this->initRoutes();
    }

    // Initialize routes with access controls
    private function initRoutes() {
        // Database Pages
        $this->routes['/db/admin/dashboard'] = ['AdminController', 'dashboard', ['admin']];
        $this->routes['/db/user/profile'] = ['UserController', 'profile', ['user', 'admin']];

        // System Files (Caution: Implement with extreme security measures)
        $this->routes['/sys/admin/config'] = ['SystemController', 'config', ['admin']];
        // $this->routes['/sys/user/files'] = ['SystemController', 'userFiles', ['user', 'admin']]; // Uncomment with caution
    }

    // Handle incoming request
    public function route($uri) {
        if (array_key_exists($uri, $this->routes)) {
            list($controller, $method, $allowedRoles) = $this->routes[$uri];
            if ($this->authorize($allowedRoles)) {
                $controller = new $controller($this->db);
                return $controller->$method();
            } else {
                http_response_code(403);
                echo "Access Denied";
                exit;
            }
        } else {
            http_response_code(404);
            echo "Page Not Found";
            exit;
        }
    }

    // Simple role-based authorization check
    private function authorize($allowedRoles) {
        // Assuming $_SESSION['user_role'] is set by your authentication system
        return in_array($_SESSION['user_role'], $allowedRoles);
    }
}
