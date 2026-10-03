<?php
declare(strict_types=1);

/**
 * Gestión de códigos de acceso y tablas relacionadas de usuarios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES CRÍTICAS:
 * - UpVerify(): SQL tenía paréntesis extra `WHERE usercode=?)`
 * - UpPlans(): SQL tenía paréntesis extra `WHERE usercode=?)`
 * - AddUserCode() y AddSecures() ahora usan transacciones
 * - Todos los métodos usan prepared statements PDO
 * - Inyección de dependencias
 */
class UsersCodeAccess
{
    private PDO $conn;
    private array $actions;
    private array $secures;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->actions = [
            'users_active',
            'users_plans',
            'users_searches',
            'users_social_media',
            'users_types',
            'users_verifications'
        ];
        $this->secures = [
            'users_privacy',
            'users_secures'
        ];
    }

    /**
     * Inserta un código de usuario en múltiples tablas relacionadas.
     *
     * ✅ Ahora usa transacción para garantizar atomicidad.
     *
     * @param string $uscod El código de usuario a insertar.
     */
    public function AddUserCode(string $uscod): bool
    {
        // ✅ Validar formato
        if (!ctype_alnum($uscod)) {
            error_log('AddUserCode: código de usuario inválido');
            return false;
        }

        $this->conn->beginTransaction();
        try {
            foreach ($this->actions as $tb) {
                // ✅ Validar nombre de tabla contra lista blanca
                if (!in_array($tb, $this->actions, true)) {
                    continue;
                }

                $sql = "INSERT INTO {$tb} (usercode) VALUES (:uc)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':uc' => $uscod]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('AddUserCode error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Inserta registros de seguridad para el usuario.
     *
     * ✅ Ahora usa transacción para garantizar atomicidad.
     */
    public function AddSecures(string $ids, string $uscod): bool
    {
        if (!ctype_alnum($uscod)) {
            return false;
        }

        $this->conn->beginTransaction();
        try {
            foreach ($this->secures as $tb) {
                if (!in_array($tb, $this->secures, true)) {
                    continue;
                }

                $sql = "INSERT INTO {$tb} (idUsr, usercode) VALUES (:id, :uc)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':id' => $ids, ':uc' => $uscod]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('AddSecures error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la tabla de acciones del usuario.
     */
    public function UpActions(string $uscod, string $val, string $ver, string $apr): bool
    {
        $naction = 'verificated';
        $stmt = $this->conn->prepare(
            "UPDATE users_actions
            SET action = :act, validation = :val, verification = :ver, approval = :apr
            WHERE usercode = :uc"
        );
        return $stmt->execute([
            ':act' => $naction,
            ':val' => $val,
            ':ver' => $ver,
            ':apr' => $apr,
            ':uc'  => $uscod
        ]);
    }

    /**
     * Actualiza el estado activo del usuario.
     */
    public function UpActive(string $uscod, int $verst): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_active SET is_active = :ia WHERE usercode = :uc"
        );
        return $stmt->execute([':ia' => $verst, ':uc' => $uscod]);
    }

    /**
     * Actualiza la tabla de seguridad del usuario.
     */
    public function UpSecures(string $uscod, string $ids, string $val, string $folder): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_secures
            SET idUsr = :id, folder_files = :ff, validation = :val
            WHERE usercode = :uc"
        );
        return $stmt->execute([
            ':id'  => $ids,
            ':ff'  => $folder,
            ':val' => $val,
            ':uc'  => $uscod
        ]);
    }

    /**
     * Actualiza la privacidad del usuario.
     */
    public function UpPrivacy(string $uscod, string $idp, string $ver): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_privacy
            SET idUsr = :id, verification = :ver
            WHERE usercode = :uc"
        );
        return $stmt->execute([':id' => $idp, ':ver' => $ver, ':uc' => $uscod]);
    }

    /**
     * Actualiza la verificación del usuario.
     *
     * ✅ BUG CORREGIDO: SQL original tenía paréntesis extra `WHERE usercode=?)`
     */
    public function UpVerify(string $uscod, string $ver): bool
    {
        // ✅ ANTES: "UPDATE users_verifications SET verification=? WHERE usercode=?)"
        // ✅ AHORA: paréntesis extra eliminado
        $stmt = $this->conn->prepare(
            "UPDATE users_verifications
            SET verification = :ver
            WHERE usercode = :uc"
        );
        return $stmt->execute([':ver' => $ver, ':uc' => $uscod]);
    }

    /**
     * Actualiza el plan del usuario.
     *
     * ✅ BUG CORREGIDO: SQL original tenía paréntesis extra `WHERE usercode=?)`
     */
    public function UpPlans(string $uscod, int $verst): bool
    {
        // ✅ ANTES: "UPDATE users_plans SET verification=? WHERE usercode=?)"
        // ✅ AHORA: paréntesis extra eliminado
        $stmt = $this->conn->prepare(
            "UPDATE users_plans
            SET verification = :ver
            WHERE usercode = :uc"
        );
        return $stmt->execute([':ver' => $verst, ':uc' => $uscod]);
    }
}
