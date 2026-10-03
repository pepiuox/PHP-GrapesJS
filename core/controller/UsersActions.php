<?php
declare(strict_types=1);

/**
 * Consulta de acciones de usuarios.
 * Migrado a PDO con validación de sesión.
 */
class UsersActions
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_actions';

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
     * Obtiene los datos de acciones del usuario.
     */
    public function uActions(): ?array
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
     * Verifica si el usuario tiene una acción específica activa.
     */
    public function hasAction(string $action): bool
    {
        $data = $this->uActions();
        return $data !== null && isset($data['action']) && $data['action'] === $action;
    }

    /**
     * Obtiene la última acción del usuario.
     */
    public function getLastAction(): ?string
    {
        $data = $this->uActions();
        return $data['action'] ?? null;
    }
}
