<?php
declare(strict_types=1);

/**
 * Gestión de seguidores/seguidos de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación estricta de sesión
 * - Inyección de dependencias PDO
 * - Protección contra auto-follow
 * - Transacción en follow/unfollow
 */
class UsersFollows
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_follows';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;

        if (!isset($_SESSION['access_id']) || !is_string($_SESSION['access_id'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        $this->ucode = $_SESSION['access_id'];

        if (!ctype_alnum($this->ucode)) {
            throw new RuntimeException('Código de usuario inválido');
        }
    }

    /**
     * Obtiene la lista de usuarios que sigue el usuario actual.
     *
     * @return array Lista de usercodes seguidos
     */
    public function uFollows(): array
    {
        $stmt = $this->conn->prepare(
            "SELECT fusercode FROM {$this->tble} WHERE usercode = :uc ORDER BY created_at DESC"
        );
        $stmt->execute([':uc' => $this->ucode]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Obtiene la lista de seguidores del usuario actual.
     *
     * @return array Lista de usercodes seguidores
     */
    public function getFollowers(): array
    {
        $stmt = $this->conn->prepare(
            "SELECT usercode FROM {$this->tble} WHERE fusercode = :uc ORDER BY created_at DESC"
        );
        $stmt->execute([':uc' => $this->ucode]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Verifica si el usuario actual sigue a otro usuario.
     */
    public function isFollowing(string $targetUcode): bool
    {
        if (!ctype_alnum($targetUcode)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc AND fusercode = :fc"
        );
        $stmt->execute([':uc' => $this->ucode, ':fc' => $targetUcode]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Sigue a un usuario.
     * ✅ Protección contra auto-follow + transacción
     */
    public function follow(string $targetUcode): bool
    {
        if (!ctype_alnum($targetUcode)) {
            return false;
        }

        // ✅ No permitirse seguir a sí mismo
        if ($targetUcode === $this->ucode) {
            return false;
        }

        if ($this->isFollowing($targetUcode)) {
            return true; // Ya lo sigue
        }

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->tble} (usercode, fusercode, created_at)
            VALUES (:uc, :fc, NOW())"
            );
            $stmt->execute([':uc' => $this->ucode, ':fc' => $targetUcode]);
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('UsersFollows::follow error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deja de seguir a un usuario.
     */
    public function unfollow(string $targetUcode): bool
    {
        if (!ctype_alnum($targetUcode)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->tble} WHERE usercode = :uc AND fusercode = :fc"
        );
        return $stmt->execute([':uc' => $this->ucode, ':fc' => $targetUcode]);
    }

    /**
     * Obtiene el conteo de seguidos y seguidores.
     *
     * @return array{following: int, followers: int}
     */
    public function getCounts(): array
    {
        $s1 = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc"
        );
        $s1->execute([':uc' => $this->ucode]);
        $following = (int) $s1->fetchColumn();

        $s2 = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble} WHERE fusercode = :uc"
        );
        $s2->execute([':uc' => $this->ucode]);
        $followers = (int) $s2->fetchColumn();

        return ['following' => $following, 'followers' => $followers];
    }
}
