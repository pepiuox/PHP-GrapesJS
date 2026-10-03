<?php
declare(strict_types=1);

/**
 * Consulta de verificaciones de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público (antes era privado pero no se invocaba)
 * - Validación de existencia y formato de $_SESSION["access_id"]
 * - Inyección de dependencias PDO
 * - Manejo de errores con excepciones
 */
class UsersVerifications
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_verifications';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;

        // Validación estricta de sesión
        if (!isset($_SESSION['access_id']) || !is_string($_SESSION['access_id'])) {
            throw new RuntimeException('Sesión de usuario no válida');
        }

        $this->ucode = $_SESSION['access_id'];

        // Validar formato alfanumérico
        if (!ctype_alnum($this->ucode)) {
            throw new RuntimeException('Código de usuario inválido');
        }
    }

    /**
     * Obtiene los datos de verificación del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uVerifications(): ?array
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
     * Verifica si el usuario tiene verificación activa.
     */
    public function isVerified(): bool
    {
        $data = $this->uVerifications();
        return $data !== null && isset($data['verified']) && (int) $data['verified'] === 1;
    }
}
