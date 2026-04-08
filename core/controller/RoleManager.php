<?php
// classes/RoleManager.php
class RoleManager {
    private $conn;
    
    // Definición de roles y permisos
    private $roles = [
        'admin' => [
            'name' => 'Administrador',
            'permissions' => [
                'manage_users' => true,
                'manage_pages' => true,
                'manage_templates' => true,
                'manage_settings' => true,
                'view_reports' => true,
                'clear_cache' => true,
                'ban_users' => true,
                'edit_any_page' => true,
                'delete_any_page' => true,
                'publish_any_page' => true,
                'access_dashboard' => true
            ],
            'level' => 100
        ],
        'manager' => [
            'name' => 'Manager',
            'permissions' => [
                'manage_users' => false,
                'manage_pages' => true,
                'manage_templates' => true,
                'manage_settings' => false,
                'view_reports' => true,
                'clear_cache' => true,
                'ban_users' => false,
                'edit_any_page' => true,
                'delete_any_page' => true,
                'publish_any_page' => true,
                'access_dashboard' => true
            ],
            'level' => 80
        ],
        'editor' => [
            'name' => 'Editor',
            'permissions' => [
                'manage_users' => false,
                'manage_pages' => true,
                'manage_templates' => false,
                'manage_settings' => false,
                'view_reports' => false,
                'clear_cache' => false,
                'ban_users' => false,
                'edit_any_page' => false,
                'delete_any_page' => false,
                'publish_any_page' => false,
                'access_dashboard' => true
            ],
            'level' => 60
        ],
        'guest' => [
            'name' => 'Invitado',
            'permissions' => [
                'manage_users' => false,
                'manage_pages' => false,
                'manage_templates' => false,
                'manage_settings' => false,
                'view_reports' => false,
                'clear_cache' => false,
                'ban_users' => false,
                'edit_any_page' => false,
                'delete_any_page' => false,
                'publish_any_page' => false,
                'access_dashboard' => false
            ],
            'level' => 10
        ]
    ];
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtener información de un rol
     */
    public function getRoleInfo($role) {
        return isset($this->roles[$role]) ? $this->roles[$role] : null;
    }
    
    /**
     * Verificar si un usuario tiene permiso
     */
    public function hasPermission($user_id, $permission) {
        $user = $this->getUserWithRole($user_id);
        
        if (!$user) {
            return false;
        }
        
        $role = $user['role'];
        
        // Verificar si el rol existe
        if (!isset($this->roles[$role])) {
            return false;
        }
        
        // Verificar si el usuario está baneado
        if ($user['is_banned']) {
            return false;
        }
        
        // Verificar permiso en el rol
        return isset($this->roles[$role]['permissions'][$permission]) 
               ? $this->roles[$role]['permissions'][$permission] 
               : false;
    }
    
    /**
     * Verificar si puede editar una página específica
     */
    public function canEditPage($user_id, $page_id) {
        // Obtener información del usuario
        $user = $this->getUserWithRole($user_id);
        
        if (!$user) {
            return false;
        }
        
        // Si tiene permiso para editar cualquier página
        if ($this->hasPermission($user_id, 'edit_any_page')) {
            return true;
        }
        
        // Verificar si es el dueño de la página
        $query = "SELECT user_id FROM pages WHERE id = :page_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $page = $stmt->fetch(PDO::FETCH_ASSOC);
            return $page['user_id'] == $user_id;
        }
        
        return false;
    }
    
    /**
     * Verificar nivel mínimo
     */
    public function hasMinimumLevel($user_id, $required_level) {
        $user = $this->getUserWithRole($user_id);
        
        if (!$user) {
            return false;
        }
        
        $role = $user['role'];
        $user_level = isset($this->roles[$role]['level']) ? $this->roles[$role]['level'] : 0;
        
        return $user_level >= $required_level;
    }
    
    /**
     * Obtener todos los roles disponibles
     */
    public function getAllRoles() {
        return $this->roles;
    }
    
    /**
     * Actualizar rol de usuario
     */
    public function updateUserRole($user_id, $new_role) {
        // Verificar que el rol exista
        if (!isset($this->roles[$new_role])) {
            return false;
        }
        
        $query = "UPDATE users SET role = :role WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $new_role);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener usuarios por rol
     */
    public function getUsersByRole($role, $limit = 100) {
        $query = "SELECT id, username, email, full_name, created_at, last_login 
                  FROM users WHERE role = :role AND is_banned = 0 
                  ORDER BY created_at DESC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener estadísticas de usuarios por rol
     */
    public function getUserStatsByRole() {
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
    }
    
    /**
     * Banear/desbanear usuario
     */
    public function toggleBanUser($user_id, $ban = true, $reason = '') {
        $query = "UPDATE users SET is_banned = :banned, banned_reason = :reason WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':banned', $ban, PDO::PARAM_BOOL);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener usuario con información de rol
     */
    private function getUserWithRole($user_id) {
        $query = "SELECT id, username, email, role, is_banned FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return null;
    }
    
    /**
     * Registrar actividad
     */
    public function logActivity($user_id, $action, $details = '') {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $query = "INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent) 
                  VALUES (:user_id, :action, :details, :ip, :ua)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':ip', $ip_address);
        $stmt->bindParam(':ua', $user_agent);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener logs de actividad
     */
    public function getActivityLogs($limit = 100, $user_id = null) {
        $query = "SELECT 
                    al.*,
                    u.username,
                    u.email
                  FROM activity_logs al
                  LEFT JOIN users u ON al.user_id = u.id
                  WHERE 1=1";
        
        $params = [];
        
        if ($user_id) {
            $query .= " AND al.user_id = :user_id";
            $params[':user_id'] = $user_id;
        }
        
        $query .= " ORDER BY al.created_at DESC LIMIT :limit";
        $params[':limit'] = $limit;
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            if ($key === ':limit') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>