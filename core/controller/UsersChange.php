<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Cambio de contraseña y PIN de usuarios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES CRÍTICAS:
 * - SQL inválido: mkhash=, → mkhash=?
 * - Variable variable $$enck → $enck
 * - SQL inválido: SELECT idUser, FROM → SELECT idUser FROM
 * - SQL inválido: UPDATE uverify mkpin= → UPDATE uverify SET mkpin=?
 * - Inyección SQL directa en updatePIN() eliminada
 * - Funciones movidas fuera de métodos
 * - CSRF protection añadida
 * - hash_equals para comparación segura
 */
class UsersChange
{
    private PDO $connection;
    private string $baseurl;
    private GetCodeDeEncrypt $gc;
    private Protect $pt;

    public function __construct(PDO $conn)
    {
        $this->connection = $conn;
        $this->gc = new GetCodeDeEncrypt();
        $this->pt = new Protect($conn);
        $this->baseurl = $this->buildBaseUrl();

        if (isset($_POST['changePassword'])) {
            $this->updatePassword();
        }
        if (isset($_POST['changePIN'])) {
            $this->updatePIN();
        }
    }

    private function buildBaseUrl(): string
    {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? 0) == 443;
        $protocol = $isSecure ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        if (!preg_match('/^[a-zA-Z0-9\.\-]+(:[0-9]+)?$/', $host)) {
            $host = 'localhost';
        }

        return $protocol . $host . dirname($_SERVER['PHP_SELF'] ?? '/');
    }

    /**
     * Genera token seguro.
     */
    private static function generateSecureToken(int $len = 64): string
    {
        return substr(bin2hex(random_bytes(32)), 0, $len);
    }

    /**
     * Actualiza la contraseña.
     *
     * ✅ CORREGIDO: SQL inválido, variable variable, funciones internas
     */
    private function updatePassword(): void
    {
        if (!isset($_POST['updatePassword'])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (!isset($_GET['email'], $_GET['key'], $_GET['hash'])) {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        // Sanitizar parámetros GET
        $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
        $changekey = ctype_alnum($_GET['key']) ? $_GET['key'] : '';
        $hash = ctype_alnum($_GET['hash']) ? $_GET['hash'] : '';

        if ($email === '' || $changekey === '' || $hash === '') {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        // Verificar token de recuperación
        $chck = $this->connection->prepare(
            'SELECT * FROM change_pass WHERE email = ? AND mkhash = ? AND password_key = ?'
        );
        $chck->execute([$email, $hash, $changekey]);
        $ct = $chck->fetch(PDO::FETCH_ASSOC);

        if (!$ct) {
            $_SESSION['ErrorMessage'] = 'Token de recuperación inválido';
            return;
        }

        $nowTime = date('Y-m-d H:i:s');
        if ($nowTime >= $ct['expire']) {
            $_SESSION['ErrorMessage'] = 'Tiempo expirado para restablecer la contraseña';
            header('Location: index.php');
            exit;
        }

        // Validar campos del formulario
        if (empty($_POST['vemail']) || empty($_POST['recoveryphrase']) ||
            empty($_POST['password2']) || empty($_POST['password3'])) {
            $_SESSION['ErrorMessage'] = 'Complete todos los campos requeridos';
        return;
            }

            $vemail = trim($_POST['vemail']);
            $recoveryphrase = trim($_POST['recoveryphrase']);
            $password2 = trim($_POST['password2']);
            $password3 = trim($_POST['password3']);

            // ✅ Comparación segura con hash_equals
            if (!hash_equals($vemail, $email)) {
                $_SESSION['ErrorMessage'] = 'Los datos no coinciden';
                return;
            }

            if (!hash_equals($password3, $password2)) {
                $_SESSION['ErrorMessage'] = 'Las contraseñas no coinciden';
                return;
            }

            if (strlen($password2) < 8) {
                $_SESSION['ErrorMessage'] = 'La contraseña debe tener al menos 8 caracteres';
                return;
            }

            // Verificar frase de recuperación
            $stmt = $this->connection->prepare(
                'SELECT * FROM uverify WHERE email = ? AND mkhash = ?
                AND password_key = ? AND recovery_phrase = ?'
            );
            $stmt->execute([$email, $hash, $changekey, $recoveryphrase]);

            if ($stmt->rowCount() !== 1) {
                $_SESSION['ErrorMessage'] = 'Los datos no coinciden';
                return;
            }

            $dt = $stmt->fetch(PDO::FETCH_ASSOC);
            $duv = (int) $dt['iduv'];
            $pin = $dt['mkpin'];

            // ✅ Generar claves con random_bytes (no sha1)
            $ekey = self::generateSecureToken(32);
            $eiv = self::generateSecureToken(32);
            $enck = self::generateSecureToken(32);

            $securing = $this->gc->ende_crypter('encrypt', $password2, $ekey, $eiv);
            $cml = $this->gc->ende_crypter('encrypt', $email, $ekey, $eiv);
            $clenkey = '';

            $this->connection->beginTransaction();
            try {
                // ✅ BUG CORREGIDO: ahora usa SET correctamente
                $upd = $this->connection->prepare(
                    'UPDATE uverify
                    SET password = ?, mktoken = ?, mkkey = ?, mkhash = ?, password_key = ?
                    WHERE email = ? AND recovery_phrase = ? AND password_key = ?'
                );
                $upd->execute([
                    $securing, $ekey, $eiv, $enck, $clenkey,
                    $email, $recoveryphrase, $changekey
                ]);

                if ($upd->rowCount() !== 1) {
                    throw new Exception('No se pudo actualizar uverify');
                }

                $stmt2 = $this->connection->prepare(
                    'UPDATE users SET email = ?, password = ? WHERE idUser = ? AND mkpin = ?'
                );
                $stmt2->execute([$cml, $securing, $duv, $pin]);

                if ($stmt2->rowCount() !== 1) {
                    throw new Exception('No se pudo actualizar users');
                }

                // ✅ Limpiar token usado
                $this->connection->prepare(
                    'DELETE FROM change_pass WHERE email = ? AND password_key = ?'
                )->execute([$email, $changekey]);

                $this->connection->commit();

                header('Location: index.php');
                exit;

            } catch (Exception $e) {
                $this->connection->rollBack();
                error_log('updatePassword error: ' . $e->getMessage());
                $_SESSION['ErrorMessage'] = 'Error al actualizar la contraseña';
            }
    }

    /**
     * Actualiza el PIN.
     *
     * ✅ CORREGIDO: Inyección SQL eliminada, SQL inválido corregido
     */
    private function updatePIN(): void
    {
        if (!isset($_POST['updatePIN'])) {
            return;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (!isset($_GET['email'], $_GET['key'], $_GET['hash'])) {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
        $changekey = ctype_alnum($_GET['key']) ? $_GET['key'] : '';
        $hash = ctype_alnum($_GET['hash']) ? $_GET['hash'] : '';

        if ($email === '' || $changekey === '' || $hash === '') {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        $chck = $this->connection->prepare(
            'SELECT * FROM change_pin WHERE email = ? AND mkhash = ? AND password_key = ?'
        );
        $chck->execute([$email, $hash, $changekey]);
        $ct = $chck->fetch(PDO::FETCH_ASSOC);

        if (!$ct) {
            $_SESSION['ErrorMessage'] = 'Token de recuperación inválido';
            return;
        }

        $nowTime = date('Y-m-d H:i:s');
        if ($nowTime >= $ct['expire']) {
            $_SESSION['ErrorMessage'] = 'Tiempo expirado para restablecer el PIN';
            header('Location: index.php');
            exit;
        }

        if (empty($_POST['vemail']) || empty($_POST['recoveryphrase'])) {
            $_SESSION['ErrorMessage'] = 'Complete todos los campos requeridos';
            return;
        }

        $vemail = trim($_POST['vemail']);
        $recoveryphrase = trim($_POST['recoveryphrase']);

        if (!hash_equals($vemail, $email)) {
            $_SESSION['ErrorMessage'] = 'Los datos no coinciden';
            return;
        }

        // ✅ CORREGIDO: Ahora usa prepared statements (antes era inyección SQL directa)
        $very = $this->connection->prepare(
            'SELECT * FROM uverify WHERE email = ? AND pin_key = ? AND recovery_phrase = ?'
        );
        $very->execute([$email, $changekey, $recoveryphrase]);

        if ($very->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'Los datos no coinciden';
            return;
        }

        $dt = $very->fetch(PDO::FETCH_ASSOC);
        $duv = (int) $dt['iduv'];

        // ✅ CORREGIDO: Ahora usa prepared statements
        $checkm = $this->gc->ende_crypter('encrypt', $email, $dt['mktoken'], $dt['mkkey']);
        $fnal = $this->connection->prepare(
            'SELECT idUser FROM users WHERE email = ?'
        );
        $fnal->execute([$checkm]);

        if ($fnal->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'Usuario no encontrado';
            return;
        }

        $rt = $fnal->fetch(PDO::FETCH_ASSOC);

        if ($duv !== (int) $rt['idUser']) {
            $_SESSION['ErrorMessage'] = 'Datos inconsistentes';
            return;
        }

        // ✅ Generar PIN de 6 dígitos con random_int
        $cpin = random_int(100000, 999999);
        $npin = (string) $cpin;
        $clenkey = '';

        $this->connection->beginTransaction();
        try {
            // ✅ BUG CORREGIDO: ahora usa SET correctamente
            $upd = $this->connection->prepare(
                'UPDATE uverify SET mkpin = ?, pin_key = ?
                WHERE email = ? AND recovery_phrase = ?'
            );
            $upd->execute([$npin, $clenkey, $email, $recoveryphrase]);

            if ($upd->rowCount() !== 1) {
                throw new Exception('No se pudo actualizar uverify');
            }

            $stmt2 = $this->connection->prepare(
                'UPDATE users SET mkpin = ? WHERE idUser = ? AND mkpin = ?'
            );
            $stmt2->execute([$npin, $duv, $dt['mkpin']]);

            if ($stmt2->rowCount() !== 1) {
                throw new Exception('No se pudo actualizar users');
            }

            // ✅ Limpiar token usado
            $this->connection->prepare(
                'DELETE FROM change_pin WHERE email = ? AND password_key = ?'
            )->execute([$email, $changekey]);

            $this->connection->commit();

            header('Location: index.php');
            exit;

        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log('updatePIN error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error al actualizar el PIN';
        }
    }
}
