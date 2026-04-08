<?php

// classes/UserManager.php

class UserManager {

    private $table = 'users';
    private $conn;
    private $table_name = "uverify";


    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        // Registrar intento de login
        $this->logLoginAttempt($username, false);

        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password_hash'])) {
                // Actualizar último login
                $this->updateLastLogin($user['id']);

                // Registrar login exitoso
                $this->logLoginAttempt($username, true);

                return $user;
            }
        }

        return false;
    }

    private function logLoginAttempt($username, $success) {
        $query = "INSERT INTO login_attempts (username, ip_address, successful) 
                  VALUES (:username, :ip_address, :successful)";

        $stmt = $this->conn->prepare($query);
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':ip_address', $ip_address);
        $stmt->bindParam(':successful', $success, PDO::PARAM_BOOL);

        $stmt->execute();
    }

    private function updateLastLogin($user_id) {
        $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
    }

    public function getAllUsers() {
        $query = "SELECT id, username, email, role, created_at, last_login, is_active 
                  FROM " . $this->table . " 
                  ORDER BY role, username";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // Obtener usuario por ID
    public function getUserById($iduv) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE iduv = :iduv";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':iduv', $iduv);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserRole($user_id, $new_role) {
        $allowed_roles = ['admin', 'manager', 'editor', 'guest'];

        if (!in_array($new_role, $allowed_roles)) {
            return false;
        }

        $query = "UPDATE " . $this->table . " SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $new_role);
        $stmt->bindParam(':id', $user_id);

        return $stmt->execute();
    }

    public function getLoginAttempts($limit = 100) {
        $query = "SELECT * FROM login_attempts 
                  ORDER BY created_at DESC 
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   

    // Obtener todos los usuarios con paginación
    public function getUsers($page = 1, $records_per_page = 10, $search = '', $filter_level = '') {
        $offset = ($page - 1) * $records_per_page;
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params = array();
        
        if (!empty($search)) {
            $query .= " AND (username LIKE :search OR email LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        if (!empty($filter_level)) {
            $query .= " AND level = :level";
            $params[':level'] = $filter_level;
        }
        
        $query .= " ORDER BY timestamp DESC LIMIT :offset, :records_per_page";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar usuario
    public function updateUser($iduv, $data) {
        $set_clause = "";
        $params = array();
        
        foreach ($data as $key => $value) {
            if ($key !== 'iduv') {
                $set_clause .= "$key = :$key, ";
                $params[":$key"] = $value;
            }
        }
        
        $set_clause = rtrim($set_clause, ', ');
        $query = "UPDATE " . $this->table_name . " SET $set_clause WHERE iduv = :iduv";
        $params[':iduv'] = $iduv;
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    // Eliminar usuario
    public function deleteUser($iduv) {
        $query = "DELETE FROM " . $this->table_name . " WHERE iduv = :iduv";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':iduv', $iduv);
        return $stmt->execute();
    }

    // Obtener estadísticas
    public function getStatistics() {
        $stats = array();
        
        // Total usuarios
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Usuarios activados
        $query = "SELECT COUNT(*) as activated FROM " . $this->table_name . " WHERE is_activate = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['activated_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['activated'];
        
        // Usuarios baneados
        $query = "SELECT COUNT(*) as banned FROM " . $this->table_name . " WHERE banned = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['banned_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['banned'];
        
        // Usuarios por nivel
        $query = "SELECT level, COUNT(*) as count FROM " . $this->table_name . " GROUP BY level";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['users_by_level'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Registros últimos 30 días
        $query = "SELECT DATE(timestamp) as date, COUNT(*) as count 
                  FROM " . $this->table_name . " 
                  WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
                  GROUP BY DATE(timestamp) 
                  ORDER BY date";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['last_30_days'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    // Obtener niveles disponibles
    public function getLevels() {
        $query = "SELECT DISTINCT level FROM " . $this->table_name . " ORDER BY level";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Contar total de usuarios para paginación
    public function countUsers($search = '', $filter_level = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE 1=1";
        $params = array();
        
        if (!empty($search)) {
            $query .= " AND (username LIKE :search OR email LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        if (!empty($filter_level)) {
            $query .= " AND level = :level";
            $params[':level'] = $filter_level;
        }
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>