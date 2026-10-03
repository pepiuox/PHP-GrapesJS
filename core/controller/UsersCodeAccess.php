<?php
declare(strict_types=1);

/**
 * Acceso y actualización de códigos de verificación de usuarios.
 * Migrado a PDO con corrección de bugs SQL críticos.
 *
 * CORRECCIONES:
 * - Bug SQL: paréntesis extra en WHERE usercode=?) → WHERE usercode = :uc
 * - Transacciones opcionales para operaciones en lote
 * - Inyección de dependencias PDO
 * - Tipado estricto en todos los parámetros
 */
class UsersCodeAccess
{
    private PDO $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    /* =========================================================
     *  Métodos individuales (llamados desde UsersVerify, etc.)
     * ========================================================= */

    /**
     * Actualiza verificación en users_verifications.
     * ✅ BUG CORREGIDO: paréntesis extra eliminado
     */
    public function UpVerify(string $uscod, string $ver): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_verifications SET verification = :v WHERE usercode = :uc"
        );
        return $stmt->execute([':v' => $ver, ':uc' => $uscod]);
    }

    /**
     * Actualiza verificación en users_plans.
     * ✅ BUG CORREGIDO: paréntesis extra eliminado
     */
    public function UpPlans(string $uscod, int $verst): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_plans SET verification = :v WHERE usercode = :uc"
        );
        return $stmt->execute([':v' => $verst, ':uc' => $uscod]);
    }

    /**
     * Actualiza acciones del usuario.
     */
    public function UpActions(string $uscod, string $cchng, string $ver, string $apr): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_actions
            SET validation = :val, action = :act, approval = :apr
            WHERE usercode = :uc"
        );
        return $stmt->execute([
            ':val' => $cchng,
            ':act' => $ver,
            ':apr' => $apr,
            ':uc'  => $uscod
        ]);
    }

    /**
     * Actualiza estado activo del usuario.
     */
    public function UpActive(string $uscod, int $verified): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_active SET is_active = :ia WHERE usercode = :uc"
        );
        return $stmt->execute([':ia' => $verified, ':uc' => $uscod]);
    }

    /**
     * Actualiza privacidad del usuario.
     */
    public function UpPrivacy(string $uscod, int $uid, string $ver): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_privacy SET verification = :v WHERE usercode = :uc AND userid = :uid"
        );
        return $stmt->execute([':v' => $ver, ':uc' => $uscod, ':uid' => $uid]);
    }

    /**
     * Actualiza seguridad del usuario.
     */
    public function UpSecures(string $uscod, int $uid, string $folder, string $cchng): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_secures
            SET folder = :f, secure_code = :sc
            WHERE usercode = :uc AND userid = :uid"
        );
        return $stmt->execute([
            ':f'   => $folder,
            ':sc'  => $cchng,
            ':uc'  => $uscod,
            ':uid' => $uid
        ]);
    }

    /* =========================================================
     *  Operación en lote con transacción
     * ========================================================= */

    /**
     * Ejecuta todas las actualizaciones de activación en una transacción atómica.
     *
     * @throws RuntimeException Si alguna actualización falla
     */
    public function activateAll(
        string $uscod,
        int    $uid,
        string $cchng,
        string $ver,
        string $apr,
        string $folder
    ): bool {
        $this->conn->beginTransaction();
        try {
            $results = [
                $this->UpVerify($uscod, $ver),
                $this->UpPlans($uscod, 1),
                $this->UpActions($uscod, $cchng, $ver, $apr),
                $this->UpActive($uscod, 1),
                $this->UpPrivacy($uscod, $uid, $ver),
                $this->UpSecures($uscod, $uid, $folder, $cchng),
            ];

            if (in_array(false, $results, true)) {
                throw new RuntimeException('Una o más actualizaciones fallaron');
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('UsersCodeAccess::activateAll error: ' . $e->getMessage());
            throw new RuntimeException('Error en activación en lote: ' . $e->getMessage());
        }
    }
}
