<?php
declare(strict_types=1);

/**
 * Consulta de tipos de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación de sesión
 * - Inyección de dependencias
 */
class UsersTypes
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_types';

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
     * Obtiene los datos del tipo de usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uTypes(): ?array
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
     * Obtiene el tipo de usuario como string.
     */
    public function getUserType(): ?string
    {
        $data = $this->uTypes();
        return $data['user_type'] ?? null;
    }

    /**
     * Verifica si el usuario es de un tipo específico.
     */
    public function isUserType(string $type): bool
    {
        $userType = $this->getUserType();
        return $userType !== null && $userType === $type;
    }

    /**
     * Obtiene el valor numérico del tipo de usuario.
     */
    public function getUserTypeValue(): ?int
    {
        $data = $this->uTypes();
        return isset($data['val_user']) ? (int) $data['val_user'] : null;
    }
}
