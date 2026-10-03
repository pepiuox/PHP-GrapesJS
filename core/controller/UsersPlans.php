<?php
declare(strict_types=1);

/**
 * Consulta de planes de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación de sesión
 * - Inyección de dependencias
 */
class UsersPlans
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_plans';
    private int $oneweek = 7;
    private int $onemonth = 30;
    private int $threemonth = 90;

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
     * Obtiene los datos del plan del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uPlans(): ?array
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
     * Obtiene la duración del plan en días.
     */
    public function getPlanDuration(string $planType): int
    {
        return match ($planType) {
            'week' => $this->oneweek,
            'month' => $this->onemonth,
            'quarter' => $this->threemonth,
            default => 0,
        };
    }

    /**
     * Verifica si el usuario tiene un plan activo.
     */
    public function hasActivePlan(): bool
    {
        $data = $this->uPlans();
        return $data !== null && isset($data['status']) && (int) $data['status'] === 1;
    }
}
