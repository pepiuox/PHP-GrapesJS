<?php
declare(strict_types=1);

/**
 * Controlador de usuarios con validaciones y RBAC.
 *
 * CORRECCIONES:
 * - Validación de permisos antes de mostrar perfil
 * - CSRF protection en operaciones POST
 * - Inyección de dependencias PDO
 * - Sanitización de salida
 * - Tipado estricto
 */
class UserController
{
    private PDO $db;
    private UserModel $userModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new UserModel($db);
    }

    /**
     * Muestra el perfil del usuario.
     *
     * ✅ Validación de permisos
     */
    public function profile(): void
    {
        // ✅ Verificar que el usuario esté logueado
        if (!SessionManager::isLoggedIn()) {
            http_response_code(401);
            echo '<p>Debe iniciar sesión para ver su perfil.</p>';
            return;
        }

        $userId = SessionManager::getUserId();
        if ($userId === null) {
            http_response_code(401);
            return;
        }

        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            http_response_code(404);
            echo '<p>Usuario no encontrado.</p>';
            return;
        }

        // ✅ Sanitización de salida (XSS prevention)
        echo '<div class="profile">';
        echo '<h1>Perfil de Usuario</h1>';
        echo '<p><strong>Nombre:</strong> ' . $this->escape($user['username']) . '</p>';
        echo '<p><strong>Email:</strong> ' . $this->escape($user['email']) . '</p>';
        echo '<p><strong>Nombre completo:</strong> ' . $this->escape($user['full_name'] ?? '') . '</p>';
        echo '<p><strong>Rol:</strong> ' . $this->escape($user['role'] ?? 'user') . '</p>';
        echo '<p><strong>Miembro desde:</strong> ' . $this->escape($user['created_at'] ?? '') . '</p>';
        echo '</div>';
    }

    /**
     * Actualiza el perfil del usuario.
     *
     * ✅ CSRF + validación de permisos
     */
    public function updateProfile(): bool
    {
        if (!SessionManager::isLoggedIn()) {
            return false;
        }

        // ✅ CSRF validation
        if (!SessionManager::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return false;
        }

        $userId = SessionManager::getUserId();
        if ($userId === null) {
            return false;
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        // ✅ Validaciones
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['ErrorMessage'] = 'Email inválido';
            return false;
        }

        if (strlen($fullName) < 2 || strlen($fullName) > 100) {
            $_SESSION['ErrorMessage'] = 'Nombre completo inválido';
            return false;
        }

        return $this->userModel->updateProfile($userId, $fullName, $email, $bio);
    }

    /**
     * Lista usuarios (solo admin).
     *
     * ✅ Validación de rol admin
     */
    public function listUsers(): void
    {
        if (!SessionManager::isAdmin()) {
            http_response_code(403);
            echo '<p>Acceso denegado.</p>';
            return;
        }

        $limit = max(1, min((int) ($_GET['limit'] ?? 50), 200));
        $users = $this->userModel->getUsers($limit);

        echo '<h1>Lista de Usuarios</h1>';
        echo '<table class="table">';
        echo '<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Rol</th></tr></thead>';
        echo '<tbody>';

        foreach ($users as $user) {
            echo '<tr>';
            echo '<td>' . $this->escape((string) $user['id']) . '</td>';
            echo '<td>' . $this->escape($user['username']) . '</td>';
            echo '<td>' . $this->escape($user['email']) . '</td>';
            echo '<td>' . $this->escape($user['role'] ?? '') . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    /**
     * Sanitiza salida HTML.
     */
    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
