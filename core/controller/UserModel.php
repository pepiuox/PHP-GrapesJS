<?php
declare(strict_types=1);

/**
 * Modelo de usuarios con PDO.
 *
 * CORRECCIONES:
 * - Composición en vez de herencia (SRP)
 * - Inyección de dependencias PDO
 * - Prepared statements
 * - Validación de inputs
 * - Tipado estricto
 */
class UserModel
{
    private PDO $conn;
    private string $table = 'users';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Obtiene usuarios con límite.
     */
    public function getUsers(int $limit = 100): array
    {
        // ✅ Validar límite
        $limit = max(1, min($limit, 1000));

        $query = "SELECT id, username, email, full_name, role,
        is_active, created_at, last_login
        FROM {$this->table}
        ORDER BY id ASC
        LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene usuario por ID.
     */
    public function getUserById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $query = "SELECT id, username, email, full_name, bio, role,
        is_active, is_banned, created_at, last_login
        FROM {$this->table}
        WHERE id = :id
        LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Obtiene usuario por username.
     */
    public function getUserByUsername(string $username): ?array
    {
        if (empty($username)) {
            return null;
        }

        $query = "SELECT * FROM {$this->table}
        WHERE username = :username
        LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':username' => $username]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Obtiene usuario por email.
     */
    public function getUserByEmail(string $email): ?array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $query = "SELECT * FROM {$this->table}
        WHERE email = :email
        LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Cuenta total de usuarios.
     */
    public function countUsers(): int
    {
        $query = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->conn->query($query);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Busca usuarios por keyword.
     */
    public function searchUsers(string $keyword, int $limit = 50): array
    {
        if (empty($keyword)) {
            return [];
        }

        $limit = max(1, min($limit, 100));
        $searchTerm = '%' . $keyword . '%';

        $query = "SELECT id, username, email, full_name, role, is_active
        FROM {$this->table}
        WHERE username LIKE :kw1
        OR email LIKE :kw2
        OR full_name LIKE :kw3
        ORDER BY username ASC
        LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':kw1', $searchTerm);
        $stmt->bindValue(':kw2', $searchTerm);
        $stmt->bindValue(':kw3', $searchTerm);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
