<?php

// classes/User.php
class User {

    private $conn;
    private $table_name = "users";
    
    public $id;
    public $username;
    public $email;
    public $password_hash;
    public $full_name;
    public $bio;
    public $is_active;
    public $role;
    public $permissions;
    public $created_at;
    public $is_banned;
    public $banned_reason;
    Public $last_login;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserById($user_id) {
        $query = "SELECT id, username, email, full_name, bio, created_at, updated_at 
                  FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($user_id, $full_name, $email, $bio = '') {
        // Verificar si el email ya existe para otro usuario
        $check_query = "SELECT id FROM users WHERE email = :email AND id != :id";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':email', $email);
        $check_stmt->bindParam(':id', $user_id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            return false;
        }

        $query = "UPDATE users SET 
                  full_name = :full_name,
                  email = :email,
                  bio = :bio,
                  updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':bio', $bio);

        return $stmt->execute();
    }

    public function changePassword($user_id, $current_password, $new_password) {
        // Obtener hash actual
        $query = "SELECT password_hash FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar contraseña actual
            if (password_verify($current_password, $row['password_hash'])) {
                // Generar nuevo hash
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                $update_query = "UPDATE users SET 
                                password_hash = :password_hash,
                                updated_at = NOW()
                                WHERE id = :id";

                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->bindParam(':id', $user_id);
                $update_stmt->bindParam(':password_hash', $new_password_hash);

                return $update_stmt->execute();
            }
        }

        return false;
    }

    public function deleteAccount($user_id, $password) {
        // Verificar contraseña
        $query = "SELECT password_hash FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $row['password_hash'])) {
                // Eliminar usuario (con CASCADE eliminará sus páginas también)
                $delete_query = "DELETE FROM users WHERE id = :id";
                $delete_stmt = $this->conn->prepare($delete_query);
                $delete_stmt->bindParam(':id', $user_id);

                return $delete_stmt->execute();
            }
        }

        return false;
    }

    // Crear usuario
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 (username, email, password_hash, full_name, bio, role, permissions) 
                 VALUES (:username, :email, :password_hash, :full_name, :bio, :role, :permissions)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->bio = htmlspecialchars(strip_tags($this->bio));

        // Encriptar contraseña
        $this->password_hash = password_hash($this->password_hash, PASSWORD_BCRYPT);

        // Vincular parámetros
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password_hash", $this->password_hash);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":bio", $this->bio);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":permissions", $this->permissions);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los usuarios
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo usuario
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->full_name = $row['full_name'];
            $this->bio = $row['bio'];
            $this->is_active = $row['is_active'];
            $this->role = $row['role'];
            $this->permissions = $row['permissions'];
            $this->created_at = $row['created_at'];
            $this->is_banned = $row['is_banned'];
            $this->banned_reason = $row['banned_reason'];
            $this->last_login = $row['last_login'];
        }
    }

    // Actualizar usuario
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET username = :username,
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
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->bio = htmlspecialchars(strip_tags($this->bio));
        $this->banned_reason = htmlspecialchars(strip_tags($this->banned_reason));

        // Vincular parámetros
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":bio", $this->bio);
        $stmt->bindParam(":is_active", $this->is_active);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":permissions", $this->permissions);
        $stmt->bindParam(":is_banned", $this->is_banned);
        $stmt->bindParam(":banned_reason", $this->banned_reason);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar usuario
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Buscar usuarios
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE username LIKE ? OR email LIKE ? OR full_name LIKE ? 
                 ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();
        return $stmt;
    }

    // Verificar si email existe
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}

?>