<?php
declare(strict_types=1);

/**
 * Gestión masiva de usuarios.
 * Migrado a PDO con validaciones de seguridad.
 */
class UserManager
{
    private PDO $conn;
    private string $table = 'users';
    private string $table_name = "uverify";

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function login(string $username, string $password): array|false
    {
        // Validar email
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $this->logLoginAttempt($username, false);
            return false;
        }

        $this->logLoginAttempt($username, false);

        $query = "SELECT * FROM {$this->table} WHERE email = :email AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $username]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password_hash'])) {
                $this->updateLastLogin((int) $user['id']);
                $this->logLoginAttempt($username, true);
                return $user;
            }
        }

        return false;
    }

    private function logLoginAttempt(string $username, bool $success): void
    {
        $query = "INSERT INTO login_attempts (username, ip_address, successful)
        VALUES (:username, :ip_address, :successful)";
        $stmt = $this->conn->prepare($query);
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // Validar IP
        if (!filter_var($ip_address, FILTER_VALIDATE_IP)) {
            $ip_address = '0.0.0.0';
        }

        $stmt->execute([
            ':username' => $username,
            ':ip_address' => $ip_address,
            ':successful' => $success ? 1 : 0
        ]);
    }

    private function updateLastLogin(int $user_id): void
    {
        $query = "UPDATE {$this->table} SET last_login = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $user_id]);
    }

    public function getAllUsers(): array
    {
        $query = "SELECT id, username, email, role, created_at, last_login, is_active
        FROM {$this->table}
        ORDER BY role, username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $iduv): ?array
    {
        $query = "SELECT * FROM {$this->table_name} WHERE iduv = :iduv";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':iduv' => $iduv]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateUserRole(int $user_id, string $new_role): bool
    {
        // ✅ Lista blanca de roles permitidos
        $allowed_roles = ['admin', 'manager', 'editor', 'guest'];
        if (!in_array($new_role, $allowed_roles, true)) {
            return false;
        }

        $query = "UPDATE {$this->table} SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':role' => $new_role, ':id' => $user_id]);
    }

    public function getLoginAttempts(int $limit = 100): array
    {
        // Validar límite
        $limit = max(1, min($limit, 1000));

        $query = "SELECT * FROM login_attempts
        ORDER BY created_at DESC
        LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':limit' => $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsers(int $page = 1, int $records_per_page = 10, string $search = '', string $filter_level = ''): array
    {
        // Validar paginación
        $page = max(1, $page);
        $records_per_page = max(1, min($records_per_page, 100));
        $offset = ($page - 1) * $records_per_page;

        $query = "SELECT * FROM {$this->table_name} WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (username LIKE :search OR email LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        if (!empty($filter_level)) {
            // ✅ Validar formato de nivel
            if (ctype_alnum($filter_level)) {
                $query .= " AND level = :level";
                $params[':level'] = $filter_level;
            }
        }

        $query .= " ORDER BY timestamp DESC LIMIT :offset, :records_per_page";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ✅ CORREGIDO: Ahora usa lista blanca de campos permitidos
     */
    public function updateUser(int $iduv, array $data): bool
    {
        // ✅ Lista blanca de campos permitidos
        $allowed_fields = ['username', 'email', 'level', 'is_activate', 'banned'];

        $set_parts = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed_fields, true) && $key !== 'iduv') {
                $set_parts[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        if (empty($set_parts)) {
            return false;
        }

        $set_clause = implode(', ', $set_parts);
        $query = "UPDATE {$this->table_name} SET {$set_clause} WHERE iduv = :iduv";
        $params[':iduv'] = $iduv;

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function deleteUser(int $iduv): bool
    {
        $query = "DELETE FROM {$this->table_name} WHERE iduv = :iduv";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':iduv' => $iduv]);
    }

    public function getStatistics(): array
    {
        $stats = [];

        // Total usuarios
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM {$this->table_name}");
        $stats['total_users'] = (int) $stmt->fetchColumn();

        // Usuarios activados
        $stmt = $this->conn->query("SELECT COUNT(*) FROM {$this->table_name} WHERE is_activate = 1");
        $stats['activated_users'] = (int) $stmt->fetchColumn();

        // Usuarios baneados
        $stmt = $this->conn->query("SELECT COUNT(*) FROM {$this->table_name} WHERE banned = 1");
        $stats['banned_users'] = (int) $stmt->fetchColumn();

        // Usuarios por nivel
        $stmt = $this->conn->query("SELECT level, COUNT(*) as count FROM {$this->table_name} GROUP BY level");
        $stats['users_by_level'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Registros últimos 30 días
        $stmt = $this->conn->query("SELECT DATE(timestamp) as date, COUNT(*) as count
        FROM {$this->table_name}
        WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(timestamp)
        ORDER BY date");
        $stats['last_30_days'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    public function getLevels(): array
    {
        $stmt = $this->conn->query("SELECT DISTINCT level FROM {$this->table_name} ORDER BY level");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function countUsers(string $search = '', string $filter_level = ''): int
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (username LIKE :search OR email LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        if (!empty($filter_level) && ctype_alnum($filter_level)) {
            $query .= " AND level = :level";
            $params[':level'] = $filter_level;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}
