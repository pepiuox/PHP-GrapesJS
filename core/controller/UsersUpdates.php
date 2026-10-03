<?php
declare(strict_types=1);

/**
 * Actualización de datos de verificación de usuarios.
 * Migrado a PDO con transacciones atómicas.
 *
 * CORRECCIONES:
 * - Bug SQL: faltaba coma entre `banned = ?` y `activation_code = ?`
 * - Transacción para garantizar atomicidad de 3 UPDATEs
 * - Comparación segura con hash_equals()
 * - Validación estricta de parámetros GET
 * - exit() después de header('Location: ...')
 */
class UsersUpdates
{
    private PDO $conn;
    private UsersCodeAccess $uca;
    private GetCodeDeEncrypt $gc;
    private Protect $pt;
    private string $code = '';
    private string $hash = '';

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->uca  = new UsersCodeAccess();
        $this->gc   = new GetCodeDeEncrypt();
        $this->pt   = new Protect();

        // Validación estricta de parámetros GET
        if (isset($_GET['vcode'], $_GET['usr'])) {
            $vcode = $this->pt->secureStr($_GET['vcode']);
            $usr   = $this->pt->secureStr($_GET['usr']);

            // Validar formato alfanumérico antes de guardar en sesión
            if (ctype_alnum($vcode) && ctype_alnum($usr)) {
                $_SESSION['vcode'] = $this->code = $vcode;
                $_SESSION['urs']   = $this->hash = $usr;
            } else {
                $_SESSION['ErrorMessage'] = 'Parámetros de verificación inválidos.';
                return;
            }
        }

        if (isset($_POST['bverify'])) {
            $this->uVerify();
        }
    }

    /**
     * Verifica y actualiza datos del usuario.
     */
    private function uVerify(): void
    {
        if (empty($_POST['code']) || empty($_POST['hash'])) {
            $_SESSION['ErrorMessage'] = 'Datos de verificación incompletos.';
            return;
        }

        $actCode  = $this->pt->secureStr($_POST['code']);
        $hashCode = $this->pt->secureStr($_POST['hash']);

        // Comparación segura contra timing-attacks
        if (!hash_equals($this->code, $actCode) || !hash_equals($this->hash, $hashCode)) {
            $_SESSION['ErrorMessage'] = 'Código o hash inválidos.';
            return;
        }

        $this->UpdateUverify($this->hash, $actCode);
    }

    /**
     * Actualiza tabla uverify con transacción atómica.
     */
    private function UpdateUverify(string $hash_code, string $act_code): void
    {
        // Buscar el registro primero
        $stmt = $this->conn->prepare(
            "SELECT iduv FROM uverify
            WHERE mkhash = :h AND activation_code = :c"
        );
        $stmt->execute([':h' => $hash_code, ':c' => $act_code]);

        if ($stmt->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'No se encontró el registro de verificación.';
            return;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $uid = (int) $row['iduv'];

        $mhash    = $this->gc->randHash();
        $verified = 1;
        $bann     = 0;
        $cchng    = '';

        // ✅ TRANSACCIÓN ATÓMICA
        $this->conn->beginTransaction();
        try {
            // ✅ BUG CORREGIDO: ahora tiene coma entre banned y activation_code
            $s1 = $this->conn->prepare(
                "UPDATE uverify
                SET mkhash = :mh, is_activate = :ia, banned = :b, activation_code = :ac
                WHERE iduv = :id AND mkhash = :oh AND activation_code = :oc"
            );
            $s1->execute([
                ':mh' => $mhash,
                ':ia' => $verified,
                ':b'  => $bann,
                ':ac' => $cchng,
                ':id' => $uid,
                ':oh' => $hash_code,
                ':oc' => $act_code
            ]);

            if ($s1->rowCount() !== 1) {
                throw new Exception('No se pudo actualizar uverify');
            }

            $up = $this->UpdateProfiles($uid, $mhash);
            $uv = $this->UpdateUsers($uid, $act_code);

            if (!$up || !$uv) {
                throw new Exception('No se pudieron actualizar perfiles o usuarios');
            }

            $this->conn->commit();
            header('Location: verify.php');
            exit; // ✅ exit() agregado

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('UsersUpdates transaction failed: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error al verificar la activación de tu cuenta.';
        }
    }

    private function UpdateProfiles(int $uid, string $mhash): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE users_profiles
            SET mkhash = :mh
            WHERE idp = :id AND email_verified = :ev"
        );
        $stmt->execute([':mh' => $mhash, ':id' => $uid, ':ev' => $mhash]);
        return $stmt->rowCount() === 1;
    }

    private function UpdateUsers(int $uid, string $act_code): bool
    {
        $verified = 1;
        $status   = 1;
        $cchng    = '';

        $stmt = $this->conn->prepare(
            "UPDATE users
            SET verified = :v, status = :st, email_verified = :ev
            WHERE idUser = :id AND email_verified = :oev"
        );
        $stmt->execute([
            ':v'   => $verified,
            ':st'  => $status,
            ':ev'  => $cchng,
            ':id'  => $uid,
            ':oev' => $act_code
        ]);
        return $stmt->rowCount() === 1;
    }
}
