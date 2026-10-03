<?php
declare(strict_types=1);

/**
 * Clase de prueba de roles y permisos.
 * Migrado de MySQLi a PDO con validaciones.
 *
 * CORRECCIONES:
 * - MySQLi → PDO
 * - Validación de sesión
 * - Validación de inputs
 * - Inyección de dependencias PDO
 * - Tipado estricto
 */
class TestClass
{
    private PDO $connection;
    private ?int $user_id;
    private ?string $level;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

        // ✅ Validación de sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
        $this->level = isset($_SESSION['levels']) && is_string($_SESSION['levels'])
        ? $_SESSION['levels']
        : null;
    }

    /**
     * Obtiene información del rol.
     *
     * ✅ Validación de nivel
     */
    public function roles(?string $level = null): ?array
    {
        $levelToCheck = $level ?? $this->level;

        if ($levelToCheck === null || !ctype_alnum($levelToCheck)) {
            return null;
        }

        try {
            $stmt = $this->connection->prepare(
                'SELECT idRol, name, default_role FROM users_roles WHERE name = :name LIMIT 1'
            );
            $stmt->execute([':name' => $levelToCheck]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('TestClass::roles error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene el nivel de acceso del usuario.
     *
     * ✅ Validación de sesión y formato
     */
    public function levels(): ?int
    {
        if ($this->user_id === null || $this->level === null) {
            return null;
        }

        if (!ctype_alnum($this->level)) {
            return null;
        }

        try {
            $stmt = $this->connection->prepare(
                'SELECT iduv, level FROM uverify WHERE iduv = :id AND level = :level LIMIT 1'
            );
            $stmt->execute([':id' => $this->user_id, ':level' => $this->level]);
            $lvls = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$lvls) {
                return null;
            }

            $userrole = $this->roles($this->level);
            if (!$userrole) {
                return null;
            }

            $rol = $userrole['name'];
            $rold = (int) $userrole['default_role'];

            if ($lvls['level'] === $rol) {
                if ($rold === 9) {
                    return 9;
                } elseif ($rold === 5) {
                    return 5;
                } else {
                    return 1;
                }
            }

            return null;

        } catch (PDOException $e) {
            error_log('TestClass::levels error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene el rol por defecto.
     */
    public function DefaulRoles(?string $level = null): ?int
    {
        $userrole = $this->roles($level);
        if (!$userrole || !isset($userrole['default_role'])) {
            return null;
        }
        return (int) $userrole['default_role'];
    }

    /**
     * Obtiene el ID del rol.
     */
    public function getRols(?string $level = null): ?int
    {
        $userrole = $this->roles($level);
        if (!$userrole || !isset($userrole['idRol'])) {
            return null;
        }
        return (int) $userrole['idRol'];
    }

    /**
     * Obtiene permisos de un rol.
     *
     * ✅ Validación de ID de rol
     */
    public function permissions(int $idr): array
    {
        if ($idr <= 0) {
            return [];
        }

        try {
            $stmt = $this->connection->prepare(
                'SELECT id, permission_id FROM role_permissions WHERE role_id = :id'
            );
            $stmt->execute([':id' => $idr]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('TestClass::permissions error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Verifica si el usuario tiene un permiso específico.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->user_id === null) {
            return false;
        }

        $roleId = $this->getRols();
        if ($roleId === null) {
            return false;
        }

        $permissions = $this->permissions($roleId);
        foreach ($permissions as $perm) {
            if ($perm['permission_id'] === $permission) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene el ID del usuario actual.
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * Obtiene el nivel del usuario actual.
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }
}
