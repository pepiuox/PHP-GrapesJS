<?php
declare(strict_types=1);

/**
 * Consulta de configuraciones de seguridad de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación de sesión
 * - Inyección de dependencias
 */
class UsersSecures
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_secures';

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
     * Obtiene los datos de seguridad del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uSecures(): ?array
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
     * Verifica si el usuario tiene 2FA activado.
     */
    public function has2FA(): bool
    {
        $data = $this->uSecures();
        return $data !== null && isset($data['two_factor_enabled']) && (int) $data['two_factor_enabled'] === 1;
    }

    /**
     * Obtiene la última fecha de cambio de contraseña.
     */
    public function getLastPasswordChange(): ?string
    {
        $data = $this->uSecures();
        return $data['last_password_change'] ?? null;
    }
}
