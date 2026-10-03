<?php
declare(strict_types=1);

/**
 * Consulta de perfiles de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación de sesión
 * - Inyección de dependencias
 */
class UsersProfiles
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_profiles';

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
     * Obtiene los datos del perfil del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uProfiles(): ?array
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
     * Obtiene el nombre completo del usuario.
     */
    public function getFullName(): ?string
    {
        $data = $this->uProfiles();
        if ($data === null) {
            return null;
        }

        $firstName = $data['first_name'] ?? '';
        $lastName = $data['last_name'] ?? '';

        return trim("{$firstName} {$lastName}");
    }

    /**
     * Obtiene el avatar del usuario.
     */
    public function getAvatar(): ?string
    {
        $data = $this->uProfiles();
        return $data['avatar'] ?? null;
    }

    /**
     * Verifica si el perfil está completo.
     */
    public function isProfileComplete(): bool
    {
        $data = $this->uProfiles();
        if ($data === null) {
            return false;
        }

        $requiredFields = ['first_name', 'last_name', 'email', 'phone'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        return true;
    }
}
