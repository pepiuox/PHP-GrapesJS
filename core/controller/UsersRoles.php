<?php
declare(strict_types=1);

/**
 * Consulta de roles de usuarios.
 * Migrado a PDO con validación de sesión.
 *
 * CORRECCIONES:
 * - Bug: $stmt->close() antes de get_result()
 * - Método ahora es público
 * - Validación de sesión
 * - Inyección de dependencias
 */
class UsersRoles
{
    private PDO $conn;
    private string $level;
    private string $tble = 'users_roles';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;

        // Validación estricta de sesión
        if (!isset($_SESSION['levels']) || !is_string($_SESSION['levels'])) {
            throw new RuntimeException('Sesión de nivel no válida');
        }

        $this->level = $_SESSION['levels'];

        // Validar formato alfanumérico
        if (!ctype_alnum($this->level)) {
            throw new RuntimeException('Nivel de usuario inválido');
        }
    }

    /**
     * Obtiene los datos del rol del usuario.
     *
     * @return array|null Array con datos o null si no existe
     */
    public function uRoles(): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tble} WHERE name = :name");
        $stmt->execute([':name' => $this->level]);

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->level === $roleName;
    }

    /**
     * Obtiene los permisos del rol actual.
     *
     * @return array Array de permisos o array vacío
     */
    public function getPermissions(): array
    {
        $data = $this->uRoles();
        if ($data === null || !isset($data['permissions'])) {
            return [];
        }

        return json_decode($data['permissions'], true) ?? [];
    }

    /**
     * Verifica si el usuario tiene un permiso específico.
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->getPermissions();
        return in_array($permission, $permissions, true);
    }
}
