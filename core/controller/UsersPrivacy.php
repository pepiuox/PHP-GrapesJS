<?php
declare(strict_types=1);

/**
 * Consulta de configuraciones de privacidad de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Método ahora es público
 * - Validación de sesión
 * - Inyección de dependencias
 */
class UsersPrivacy
{
    private PDO $conn;
    private string $ucode;
    private string $tble = 'users_privacy';

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
     * Obtiene los datos de privacidad del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uPrivacy(): ?array
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
     * Verifica si el perfil es público.
     */
    public function isProfilePublic(): bool
    {
        $data = $this->uPrivacy();
        return $data !== null && isset($data['profile_public']) && (int) $data['profile_public'] === 1;
    }

    /**
     * Verifica si el email es visible.
     */
    public function isEmailVisible(): bool
    {
        $data = $this->uPrivacy();
        return $data !== null && isset($data['email_visible']) && (int) $data['email_visible'] === 1;
    }

    /**
     * Verifica si el teléfono es visible.
     */
    public function isPhoneVisible(): bool
    {
        $data = $this->uPrivacy();
        return $data !== null && isset($data['phone_visible']) && (int) $data['phone_visible'] === 1;
    }

    /**
     * Obtiene todas las configuraciones de privacidad como booleanos.
     *
     * @return array Array con configuraciones de privacidad
     */
    public function getPrivacySettings(): array
    {
        $data = $this->uPrivacy();
        if ($data === null) {
            return [
                'profile_public' => false,
                'email_visible' => false,
                'phone_visible' => false,
                'show_activity' => false,
            ];
        }

        return [
            'profile_public' => (bool) ($data['profile_public'] ?? 0),
            'email_visible' => (bool) ($data['email_visible'] ?? 0),
            'phone_visible' => (bool) ($data['phone_visible'] ?? 0),
            'show_activity' => (bool) ($data['show_activity'] ?? 0),
        ];
    }
}
