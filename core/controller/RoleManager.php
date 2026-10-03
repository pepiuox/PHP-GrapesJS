<?php
declare(strict_types=1);

/**
 * Gestor de roles y permisos con PDO.
 *
 * CORRECCIONES:
 * - Validación estricta de user_id
 * - Logging de acciones críticas
 * - CSRF protection en operaciones de modificación
 * - Lista blanca de roles y permisos
 * - Validación de inputs
 * - Inyección de dependencias PDO
 */
class RoleManager
{
    private PDO $conn;

    private const ROLES = [
        'admin' => [
            'name' => 'Administrador',
            'permissions' => [
                'manage_users' => true, 'manage_pages' => true,
                'manage_templates' => true, 'manage_settings' => true,
                'view_reports' => true, 'clear_cache' => true,
                'ban_users' => true, 'edit_any_page' => true,
                'delete_any_page' => true, 'publish_any_page' => true,
                'access_dashboard' => true,
            ],
            'level' => 100,
        ],
        'manager' => [
            'name' => 'Manager',
            'permissions' => [
                'manage_users' => false, 'manage_pages' => true,
                'manage_templates' => true, 'manage_settings' => false,
                'view_reports' => true, 'clear_cache' => true,
                'ban_users' => false, 'edit_any_page' => true,
                'delete_any_page' => true, 'publish_any_page' => true,
                'access_dashboard' => true,
            ],
            'level' => 80,
        ],
        'editor' => [
            'name' => 'Editor',
            'permissions' => [
                'manage_users' => false, 'manage_pages' => true,
                'manage_templates' => false, 'manage_settings' => false,
                'view_reports' => false, 'clear_cache' => false,
                'ban_users' => false, 'edit_any_page' => false,
                'delete_any_page' => false, 'publish_any_page' => false,
                'access_dashboard' => true,
            ],
            'level' => 60,
        ],
        'guest' => [
            'name' => 'Invitado',
            'permissions' => [
                'manage_users' => false, 'manage_pages' => false,
                'manage_templates' => false, 'manage_settings' => false,
                'view_reports' => false, 'clear_cache' => false,
                'ban_users' => false, 'edit_any_page' => false,
                'delete_any_page' => false, 'publish_any_page' => false,
                'access_dashboard' => false,
            ],
            'level' => 10,
        ],
    ];

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getRoleInfo(string $role): ?array
    {
        // ✅ Validar que el rol exista
        if (!isset(self::ROLES[$role])) {
            return null;
        }
        return self::ROLES[$role];
    }

    /**
     * Verifica si un usuario tiene un permiso específico.
     */
    public function hasPermission(int $user_id, string $permission): bool
    {
        // ✅ Validar user_id
        if ($user_id <= 0) {
            return false;
        }

        $user = $this->getUserWithRole($user_id);
        if (!$user) {
            return false;
        }

        // ✅ Usuario baneado no tiene permisos
        if ((int) ($user['is_banned'] ?? 0) === 1) {
            return false;
        }

        $role = $user['role'] ?? 'guest';
        if (!isset(self::ROLES[$role])) {
            return false;
        }

        // ✅ Validar que el permiso exista
        if (!isset(self::ROLES[$role]['permissions'][$permission])) {
            return false;
        }

        return (bool) self::ROLES[$role]['permissions'][$permission];
    }

    /**
     * Verifica si puede editar una página específica.
     */
    public function canEditPage(int $user_id, int $page_id): bool
    {
        if ($user_id <= 0 || $page_id <= 0) {
            return false;
        }

        // ✅ Si tiene permiso para editar cualquier página
        if ($this->hasPermission($user_id, 'edit_any_page')) {
            return true;
        }

        // ✅ Verificar si es el dueño de la página
        try {
            $query = "SELECT user_id FROM pages WHERE id = :page_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':page_id' => $page_id]);

            if ($stmt->rowCount() > 0) {
                $page = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int) $page['user_id'] === $user_id;
            }
        } catch (PDOException $e) {
            error_log('RoleManager::canEditPage error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Verifica nivel mínimo.
     */
    public function hasMinimumLevel(int $user_id, int $required_level): bool
    {
        if ($user_id <= 0 || $required_level <= 0) {
            return false;
        }

        $user = $this->getUserWithRole($user_id);
        if (!$user) {
            return false;
        }

        $role = $user['role'] ?? 'guest';
        $user_level = self::ROLES[$role]['level'] ?? 0;

        return $user_level >= $required_level;
    }

    /**
     * Obtiene todos los roles disponibles.
     */
    public function getAllRoles(): array
    {
        return self::ROLES;
    }

    /**
     * Actualiza rol de usuario.
     *
     * ✅ CSRF validation + logging
     */
    public function updateUserRole(int $user_id, string $new_role): bool
    {
        // ✅ CSRF validation
        if (!isset($_POST['csrf_token']) ||
            !SessionManager::verifyCSRFToken($_POST['csrf_token'])) {
            throw new RuntimeException('Token CSRF inválido');
            }

            // ✅ Validar rol contra lista blanca
            if (!isset(self::ROLES[$new_role])) {
                return false;
            }

            if ($user_id <= 0) {
                return false;
            }

            try {
                $query = "UPDATE users SET role = :role WHERE id = :user_id";
                $stmt = $this->conn->prepare($query);
                $result = $stmt->execute([
                    ':role'    => $new_role,
                    ':user_id' => $user_id,
                ]);

                if ($result) {
                    // ✅ Logging de acción crítica
                    $this->logActivity(
                        SessionManager::getUserId() ?? 0,
                                       'role_change',
                                       "User {$user_id} role changed to {$new_role}"
                    );
                }

                return $result;
            } catch (PDOException $e) {
                error_log('RoleManager::updateUserRole error: ' . $e->getMessage());
                return false;
            }
    }

    /**
     * Obtiene usuarios por rol.
     */
    public function getUsersByRole(string $role, int $limit = 100): array
    {
        // ✅ Validar rol
        if (!isset(self::ROLES[$role])) {
            return [];
        }

        // ✅ Limitar rango de limit
        $limit = max(1, min($limit, 1000));

        try {
            $query = "SELECT id, username, email, full_name, created_at, last_login
            FROM users
            WHERE role = :role AND is_banned = 0
            ORDER BY created_at DESC
            LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':role', $role);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('RoleManager::getUsersByRole error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene estadísticas de usuarios por rol.
     */
    public function getUserStatsByRole(): array
    {
        try {
            $query = "SELECT
            role,
            COUNT(*) as total,
            COUNT(CASE WHEN is_banned = 1 THEN 1 END) as banned,
            COUNT(CASE WHEN is_banned = 0 THEN 1 END) as active,
            MAX(created_at) as last_created
            FROM users
            GROUP BY role";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('RoleManager::getUserStatsByRole error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Banea/desbanea usuario.
     *
     * ✅ CSRF validation + logging
     */
    public function toggleBanUser(int $user_id, bool $ban = true, string $reason = ''): bool
    {
        // ✅ CSRF validation
        if (!isset($_POST['csrf_token']) ||
            !SessionManager::verifyCSRFToken($_POST['csrf_token'])) {
            throw new RuntimeException('Token CSRF inválido');
            }

            if ($user_id <= 0) {
                return false;
            }

            // ✅ Limitar longitud de razón
            $reason = mb_substr(trim($reason), 0, 500);

        try {
            $query = "UPDATE users
            SET is_banned = :banned, banned_reason = :reason
            WHERE id = :user_id";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':banned'  => $ban ? 1 : 0,
                ':reason'  => $reason,
                ':user_id' => $user_id,
            ]);

            if ($result) {
                // ✅ Logging de acción crítica
                $action = $ban ? 'user_banned' : 'user_unbanned';
                $this->logActivity(
                    SessionManager::getUserId() ?? 0,
                                   $action,
                                   "User {$user_id} " . ($ban ? "banned: {$reason}" : 'unbanned')
                );
            }

            return $result;
        } catch (PDOException $e) {
            error_log('RoleManager::toggleBanUser error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene usuario con información de rol.
     */
    private function getUserWithRole(int $user_id): ?array
    {
        if ($user_id <= 0) {
            return null;
        }

        try {
            $query = "SELECT id, username, email, role, is_banned
            FROM users WHERE id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':user_id' => $user_id]);

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log('RoleManager::getUserWithRole error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Registra actividad.
     */
    public function logActivity(int $user_id, string $action, string $details = ''): bool
    {
        // ✅ Limitar longitud de detalles
        $details = mb_substr($details, 0, 1000);

        // ✅ Sanitizar IP
        $ip_address = filter_var(
            $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            FILTER_VALIDATE_IP
        ) ?: '0.0.0.0';

        // ✅ Sanitizar user agent
        $user_agent = mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);

        try {
            $query = "INSERT INTO activity_logs
            (user_id, action, details, ip_address, user_agent)
            VALUES (:user_id, :action, :details, :ip, :ua)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':user_id' => $user_id,
                ':action'  => $action,
                ':details' => $details,
                ':ip'      => $ip_address,
                ':ua'      => $user_agent,
            ]);
        } catch (PDOException $e) {
            error_log('RoleManager::logActivity error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene logs de actividad.
     */
    public function getActivityLogs(int $limit = 100, ?int $user_id = null): array
    {
        // ✅ Validar límite
        $limit = max(1, min($limit, 1000));

        try {
            $query = "SELECT al.*, u.username, u.email
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            WHERE 1=1";
            $params = [];

            if ($user_id !== null && $user_id > 0) {
                $query .= " AND al.user_id = :user_id";
                $params[':user_id'] = $user_id;
            }

            $query .= " ORDER BY al.created_at DESC LIMIT :limit";

            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('RoleManager::getActivityLogs error: ' . $e->getMessage());
            return [];
        }
    }
}
