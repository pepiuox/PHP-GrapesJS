<?php
declare(strict_types=1);

/**
 * Consulta de comparaciones de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público (antes era privado sin invocar)
 * - Validación de sesión
 * - Inyección de dependencias PDO
 * - Tipado estricto
 */
class UsersCompare
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_compare';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;

        // ✅ Validación estricta de sesión
        if (!isset($_SESSION['access_id']) || !is_string($_SESSION['access_id'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        $this->ucode = $_SESSION['access_id'];

        // ✅ Validar formato alfanumérico
        if (!ctype_alnum($this->ucode)) {
            throw new RuntimeException('Código de usuario inválido');
        }
    }

    /**
     * Obtiene los datos de comparación del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uCompare(): ?array
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
     * Añade un producto a la lista de comparación.
     */
    public function addCompareItem(int $productId): bool
    {
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            return false;
        }

        // Verificar si ya existe
        $check = $this->conn->prepare(
            "SELECT COUNT(*) FROM {$this->tble}
            WHERE usercode = :uc AND product_id = :pid"
        );
        $check->execute([':uc' => $this->ucode, ':pid' => $productId]);

        if ((int) $check->fetchColumn() > 0) {
            return false; // Ya existe
        }

        $sql = "INSERT INTO {$this->tble} (usercode, product_id, created_at)
        VALUES (:uc, :pid, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':uc' => $this->ucode, ':pid' => $productId]);
    }

    /**
     * Elimina un producto de la lista de comparación.
     */
    public function removeCompareItem(int $productId): bool
    {
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            return false;
        }

        $sql = "DELETE FROM {$this->tble}
        WHERE usercode = :uc AND product_id = :pid";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':uc' => $this->ucode, ':pid' => $productId]);
    }

    /**
     * Obtiene el número de productos en comparación.
     */
    public function getCompareCount(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->tble} WHERE usercode = :uc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uc' => $this->ucode]);
        return (int) $stmt->fetchColumn();
    }
}
