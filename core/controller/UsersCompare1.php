<?php
declare(strict_types=1);

/**
 * Comparación de perfiles de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación estricta de sesión
 * - Validación de existencia de usuarios comparados
 * - Inyección de dependencias PDO
 */
class UsersCompare
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_compare';

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
     * Obtiene la lista de comparaciones guardadas por el usuario.
     *
     * @return array Lista de comparaciones
     */
    public function uCompare(): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->tble} WHERE usercode = :uc ORDER BY created_at DESC"
        );
        $stmt->execute([':uc' => $this->ucode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Agrega un usuario a la lista de comparación.
     * ✅ Validación: no compararse consigo mismo + límite máximo
     */
    public function addToCompare(string $targetUcode): bool
    {
        if (!ctype_alnum($targetUcode)) {
            return false;
        }

        // No compararse consigo mismo
        if ($targetUcode === $this->ucode) {
            $_SESSION['ErrorMessage'] = 'No puedes compararte contigo mismo.';
            return false;
        }

        // Verificar que el usuario objetivo existe
        $check = $this->conn->prepare(
            "SELECT COUNT(*) FROM users_profiles WHERE usercode = :uc"
        );
        $check->execute([':uc' => $targetUcode]);
        if ((int) $check->fetchColumn() === 0) {
            $_SESSION['ErrorMessage'] = 'El usuario no existe.';
            return false;
        }

        // Verificar que no esté ya en la lista
        $exists = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc AND compare_code = :cc"
        );
        $exists->execute([':uc' => $this->ucode, ':cc' => $targetUcode]);
        if ((int) $exists->fetchColumn() > 0) {
            return true; // Ya existe
        }

        // ✅ Límite máximo de comparaciones (ej: 5)
        $count = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc"
        );
        $count->execute([':uc' => $this->ucode]);
        if ((int) $count->fetchColumn() >= 5) {
            $_SESSION['ErrorMessage'] = 'Máximo 5 usuarios en comparación.';
            return false;
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->tble} (usercode, compare_code, created_at)
        VALUES (:uc, :cc, NOW())"
        );
        return $stmt->execute([':uc' => $this->ucode, ':cc' => $targetUcode]);
    }

    /**
     * Elimina un usuario de la lista de comparación.
     */
    public function removeFromCompare(string $targetUcode): bool
    {
        if (!ctype_alnum($targetUcode)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->tble} WHERE usercode = :uc AND compare_code = :cc"
        );
        return $stmt->execute([':uc' => $this->ucode, ':cc' => $targetUcode]);
    }

    /**
     * Obtiene los perfiles completos de los usuarios en comparación.
     *
     * @return array Perfiles de usuarios comparados
     */
    public function getComparedProfiles(): array
    {
        $stmt = $this->conn->prepare(
            "SELECT p.* FROM users_profiles p
            INNER JOIN {$this->tble} c ON p.usercode = c.compare_code
            WHERE c.usercode = :uc
            ORDER BY c.created_at DESC"
        );
        $stmt->execute([':uc' => $this->ucode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Limpia toda la lista de comparación del usuario.
     */
    public function clearAll(): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->tble} WHERE usercode = :uc"
        );
        return $stmt->execute([':uc' => $this->ucode]);
    }

    /**
     * Obtiene el número de usuarios en la lista de comparación.
     */
    public function countCompared(): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc"
        );
        $stmt->execute([':uc' => $this->ucode]);
        return (int) $stmt->fetchColumn();
    }
}
