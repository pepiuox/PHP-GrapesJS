<?php
declare(strict_types=1);

/**
 * Consulta de búsquedas de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación de sesión
 * - Inyección de dependencias
 */
class UsersSearches
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_searches';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;

        // Validación estricta de sesión
        if (!isset($_SESSION['access_id']) || !is_string($_SESSION['access_id'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        $this->ucode = $_SESSION['access_id'];

        if (!ctype_alnum($this->ucode)) {
            throw new RuntimeException('Código de usuario inválido');
        }
    }

    /**
     * Obtiene los datos de búsquedas del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uSearches(): ?array
    {
        $sql = "SELECT * FROM {$this->tble} WHERE usercode = :uc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uc' => $this->ucode]);

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    /**
     * Obtiene el historial de búsquedas recientes.
     */
    public function getRecentSearches(int $limit = 10): array
    {
        $sql = "SELECT * FROM {$this->tble}
        WHERE usercode = :uc
        ORDER BY created_at DESC
        LIMIT :lim";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uc' => $this->ucode, ':lim' => $limit]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
