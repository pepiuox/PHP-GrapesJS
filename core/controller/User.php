<?php
declare(strict_types=1);

/**
 * Gestión de usuarios individuales.
 * Migrado a PDO con validaciones mejoradas.
 */
class User
{
    private PDO $conn;
    private string $table_name = "users";

    public ?int $id = null;
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password_hash = null;
    public ?string $full_name = null;
    public ?string $bio = null;
    public ?int $is_active = null;
    public ?string $role = null;
    public ?string $permissions = null;
    public ?string $created_at = null;
    public ?int $is_banned = null;
    public ?string $banned_reason = null;
    public ?string $last_login = null;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getUserById(int $user_id): ?array
    {
        $query = "SELECT id, username, email, full_name, bio, created_at, updated_at
        FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateProfile(int $user_id, string $full_name, string $email, string $bio = ''): bool
    {
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Verificar si el email ya existe para otro usuario
        $check_query = "SELECT id FROM {$this->table_name} WHERE email = :email AND id != :id";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->execute([':email' => $email, ':id' => $user_id]);

        if ($check_stmt->rowCount() > 0) {
            return false;
        }

        $query = "UPDATE {$this->table_name} SET
        full_name = :full_name,
        email = :email,
        bio = :bio,
        updated_at = NOW()
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':full_name' => htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8'),
                              ':email' => $email,
                              ':bio' => htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'),
                              ':id' => $user_id
        ]);
    }

    public function changePassword(int $user_id, string $current_password, string $new_password): bool
    {
        // Validar longitud mínima de nueva contraseña
        if (strlen($new_password) < 8) {
            return false;
        }

        $query = "SELECT password_hash FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $user_id]);

        if ($stmt->rowCount() !== 1) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña actual
        if (!password_verify($current_password, $row['password_hash'])) {
            return false;
        }

        // Generar nuevo hash
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

        $update_query = "UPDATE {$this->table_name} SET
        password_hash = :password_hash,
        updated_at = NOW()
        WHERE id = :id";

        $update_stmt = $this->conn->prepare($update_query);
        return $update_stmt->execute([
            ':password_hash' => $new_password_hash,
            ':id' => $user_id
        ]);
    }

    public function deleteAccount(int $user_id, string $password): bool
    {
        $query = "SELECT password_hash FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $user_id]);

        if ($stmt->rowCount() !== 1) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($password, $row['password_hash'])) {
            return false;
        }

        $delete_query = "DELETE FROM {$this->table_name} WHERE id = :id";
        $delete_stmt = $this->conn->prepare($delete_query);
        return $delete_stmt->execute([':id' => $user_id]);
    }

    public function create(): bool
    {
        // Validaciones
        if (!$this->isValidUsername($this->username)) {
            return false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $query = "INSERT INTO {$this->table_name}
        (username, email, password_hash, full_name, bio, role, permissions)
        VALUES (:username, :email, :password_hash, :full_name, :bio, :role, :permissions)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->username = htmlspecialchars(strip_tags($this->username), ENT_QUOTES, 'UTF-8');
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->full_name = htmlspecialchars(strip_tags($this->full_name), ENT_QUOTES, 'UTF-8');
        $this->bio = htmlspecialchars(strip_tags($this->bio), ENT_QUOTES, 'UTF-8');

        // Encriptar contraseña
        $this->password_hash = password_hash($this->password_hash, PASSWORD_BCRYPT);

        return $stmt->execute([
            ':username' => $this->username,
            ':email' => $this->email,
            ':password_hash' => $this->password_hash,
            ':full_name' => $this->full_name,
            ':bio' => $this->bio,
            ':role' => $this->role,
            ':permissions' => $this->permissions
        ]);
    }

    public function readAll(): PDOStatement
    {
        $query = "SELECT * FROM {$this->table_name} ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne(): void
    {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $this->id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->full_name = $row['full_name'];
            $this->bio = $row['bio'];
            $this->is_active = (int) $row['is_active'];
            $this->role = $row['role'];
            $this->permissions = $row['permissions'];
            $this->created_at = $row['created_at'];
            $this->is_banned = (int) $row['is_banned'];
            $this->banned_reason = $row['banned_reason'];
            $this->last_login = $row['last_login'];
        }
    }

    public function update(): bool
    {
        $query = "UPDATE {$this->table_name} SET
        username = :username,
        email = :email,
        full_name = :full_name,
        bio = :bio,
        is_active = :is_active,
        role = :role,
        permissions = :permissions,
        is_banned = :is_banned,
        banned_reason = :banned_reason
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->username = htmlspecialchars(strip_tags($this->username), ENT_QUOTES, 'UTF-8');
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->full_name = htmlspecialchars(strip_tags($this->full_name), ENT_QUOTES, 'UTF-8');
        $this->bio = htmlspecialchars(strip_tags($this->bio), ENT_QUOTES, 'UTF-8');
        $this->banned_reason = htmlspecialchars(strip_tags($this->banned_reason), ENT_QUOTES, 'UTF-8');

        return $stmt->execute([
            ':username' => $this->username,
            ':email' => $this->email,
            ':full_name' => $this->full_name,
            ':bio' => $this->bio,
            ':is_active' => $this->is_active,
            ':role' => $this->role,
            ':permissions' => $this->permissions,
            ':is_banned' => $this->is_banned,
            ':banned_reason' => $this->banned_reason,
            ':id' => $this->id
        ]);
    }

    public function delete(): bool
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $this->id]);
    }

    public function search(string $keywords): PDOStatement
    {
        $query = "SELECT * FROM {$this->table_name}
        WHERE username LIKE :kw OR email LIKE :kw OR full_name LIKE :kw
        ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $keywords = htmlspecialchars(strip_tags($keywords), ENT_QUOTES, 'UTF-8');
        $keywords = "%{$keywords}%";
        $stmt->execute([':kw' => $keywords]);
        return $stmt;
    }

    public function emailExists(): bool
    {
        $query = "SELECT id FROM {$this->table_name} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $stmt->execute([':email' => $this->email]);
        return $stmt->rowCount() > 0;
    }

    private function isValidUsername(?string $username): bool
    {
        if ($username === null || strlen($username) < 7 || strlen($username) > 30) {
            return false;
        }
        return ctype_alnum($username);
    }
}
