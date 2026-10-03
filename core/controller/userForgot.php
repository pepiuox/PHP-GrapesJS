<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Recuperación de contraseña y PIN de usuarios.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES CRÍTICAS:
 * - SQL inválido: UPDATE uverify password=? → UPDATE uverify SET password=?
 * - Variable variable $$enck → $enck
 * - SQL inválido: SELECT idUser, FROM → SELECT idUser FROM
 * - SQL inválido: UPDATE uverify mkpin='$npin' → UPDATE uverify SET mkpin=?
 * - Inyección SQL en updatePIN() eliminada
 * - sha1() reemplazado por random_bytes()
 * - Funciones movidas fuera de métodos
 * - CSRF protection añadida
 * - hash_equals para comparación segura
 * - PHPMailer en lugar de mail()
 * - exit después de header()
 */
class UsersForgot
{
    public string $baseurl;
    protected PDO $conn;
    public DateTime $date;
    public string $timestamp;
    public GetCodeDeEncrypt $gc;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
        $this->gc = new GetCodeDeEncrypt();
        $this->date = new DateTime();
        $this->timestamp = $this->date->format('Y-m-d H:i:s');
        $this->baseurl = $this->buildBaseUrl();

        if (isset($_POST['forgotPassword'])) {
            $this->forgotPassword();
        }
        if (isset($_POST['forgotPIN'])) {
            $this->forgotPIN();
        }
        if (isset($_POST['updatePassword'])) {
            $this->updatePassword();
        }
        if (isset($_POST['updatePIN'])) {
            $this->updatePIN();
        }
    }

    private function buildBaseUrl(): string
    {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? 0) == 443;
        $protocol = $isSecure ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        // ✅ Validar host
        if (!preg_match('/^[a-zA-Z0-9\.\-]+(:[0-9]+)?$/', $host)) {
            $host = 'localhost';
        }

        return $protocol . $host . dirname($_SERVER['PHP_SELF'] ?? '/');
    }

    private function procheck(?string $string): string
    {
        if ($string === null) return '';
        return htmlspecialchars(trim($string), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Genera token seguro usando random_bytes.
     * ✅ CORREGIDO: sha1() reemplazado por random_bytes()
     */
    private static function generateSecureToken(int $len = 64): string
    {
        return substr(bin2hex(random_bytes(32)), 0, $len);
    }

    private function forgotPassword(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        $recoveryphrase = $this->procheck($_POST['recoveryphrase'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // ✅ Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['ErrorMessage'] = 'Email inválido';
            return;
        }

        $usrm = $this->gc->ende_crypter('encrypt', $email, SECURE_TOKEN, SECURE_HASH);

        $stmt = $this->conn->prepare(
            'SELECT username, email, mkhash FROM uverify
            WHERE email = ? AND recovery_phrase = ? LIMIT 1'
        );
        $stmt->execute([$usrm, $recoveryphrase]);
        $urw = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$urw) {
            // ✅ Mensaje genérico para no revelar si el email existe
            $_SESSION['ErrorMessage'] = 'Email o frase de recuperación incorrectos.';
            return;
        }

        $hash = $urw['mkhash'];
        $uname = $urw['username'];
        $forgot_password_key = self::generateSecureToken();
        $inactive = 0;

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare(
                'UPDATE uverify SET password_key = ?, is_activate = ? WHERE email = ?'
            );
            $stmt->execute([$forgot_password_key, $inactive, $usrm]);

            $expireT = date('Y-m-d H:i:s', strtotime('+2 hour'));

            $stmt1 = $this->conn->prepare(
                'INSERT INTO forgot_pass (username, email, password_key, expire)
            VALUES (?, ?, ?, ?)'
            );
            $stmt1->execute([$uname, $usrm, $forgot_password_key, $expireT]);

            $this->conn->commit();

            $this->sendRecoveryEmail($email, $forgot_password_key, $hash, 'password');
            $_SESSION['SuccessMessage'] = 'Email enviado con instrucciones para restablecer tu contraseña.';

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('forgotPassword error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error al procesar la solicitud';
        }
    }

    private function forgotPIN(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        $recoveryphrase = $this->procheck($_POST['recoveryphrase'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['ErrorMessage'] = 'Email inválido';
            return;
        }

        $stmt = $this->conn->prepare(
            'SELECT username, email, mkhash FROM uverify
            WHERE email = ? AND recovery_phrase = ? LIMIT 1'
        );
        $stmt->execute([$email, $recoveryphrase]);
        $urw = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$urw) {
            $_SESSION['ErrorMessage'] = 'Email o frase de recuperación incorrectos.';
            return;
        }

        $hash = $urw['mkhash'];
        $uname = $urw['username'];
        $forgot_pin_key = self::generateSecureToken();
        $inactive = 0;

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare(
                'UPDATE uverify SET pin_key = ?, is_activate = ? WHERE email = ?'
            );
            $stmt->execute([$forgot_pin_key, $inactive, $email]);

            $expireT = date('Y-m-d H:i:s', strtotime('+2 hour'));

            $stmt1 = $this->conn->prepare(
                'INSERT INTO forgot_pin (username, email, pin_key, expire)
            VALUES (?, ?, ?, ?)'
            );
            $stmt1->execute([$uname, $email, $forgot_pin_key, $expireT]);

            $this->conn->commit();

            $this->sendRecoveryEmail($email, $forgot_pin_key, $hash, 'pin');
            $_SESSION['SuccessMessage'] = 'Email enviado con instrucciones para restablecer tu PIN.';

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('forgotPIN error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error al procesar la solicitud';
        }
    }

    /**
     * Actualiza la contraseña.
     * ✅ CORREGIDO: SQL inválido, variable variable, funciones internas
     */
    private function updatePassword(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (!isset($_GET['key'], $_GET['hash'])) {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        $forgotkey = ctype_alnum($_GET['key']) ? $_GET['key'] : '';
        $hash = ctype_alnum($_GET['hash']) ? $_GET['hash'] : '';

        if ($forgotkey === '' || $hash === '') {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        $chck = $this->conn->prepare(
            'SELECT * FROM forgot_pass WHERE mkhash = ? AND password_key = ?'
        );
        $chck->execute([$hash, $forgotkey]);
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

        if (empty($_POST['vemail']) || empty($_POST['recoveryphrase']) ||
            empty($_POST['password2']) || empty($_POST['password3'])) {
            $_SESSION['ErrorMessage'] = 'Complete todos los campos requeridos';
        return;
            }

            $vemail = trim($_POST['vemail']);
            $recoveryphrase = trim($_POST['recoveryphrase']);
            $password2 = trim($_POST['password2']);
            $password3 = trim($_POST['password3']);

            if (!hash_equals($password3, $password2)) {
                $_SESSION['ErrorMessage'] = 'Las contraseñas no coinciden';
                return;
            }

            if (strlen($password2) < 8) {
                $_SESSION['ErrorMessage'] = 'La contraseña debe tener al menos 8 caracteres';
                return;
            }

            $stmt = $this->conn->prepare(
                'SELECT * FROM uverify WHERE email = ? AND mkhash = ?
                AND password_key = ? AND recovery_phrase = ?'
            );
            $stmt->execute([$vemail, $hash, $forgotkey, $recoveryphrase]);

            if ($stmt->rowCount() !== 1) {
                $_SESSION['ErrorMessage'] = 'Los datos no coinciden';
                return;
            }

            $dt = $stmt->fetch(PDO::FETCH_ASSOC);
            $duv = (int) $dt['iduv'];
            $pin = $dt['mkpin'];

            // ✅ Generar claves con random_bytes
            $ekey = self::generateSecureToken(32);
            $eiv = self::generateSecureToken(32);
            $enck = self::generateSecureToken(32);

            $securing = $this->gc->ende_crypter('encrypt', $password2, $ekey, $eiv);
            $cml = $this->gc->ende_crypter('encrypt', $vemail, $ekey, $eiv);
            $clenkey = '';

            $this->conn->beginTransaction();
            try {
                // ✅ BUG CORREGIDO: ahora usa SET correctamente
                $upd = $this->conn->prepare(
                    'UPDATE uverify
                    SET password = ?, mktoken = ?, mkkey = ?, mkhash = ?, password_key = ?
                    WHERE email = ? AND recovery_phrase = ? AND password_key = ?'
                );
                $upd->execute([
                    $securing, $ekey, $eiv, $enck, $clenkey,
                    $vemail, $recoveryphrase, $forgotkey
                ]);

                if ($upd->rowCount() !== 1) {
                    throw new Exception('No se pudo actualizar uverify');
                }

                // ✅ BUG CORREGIDO: ahora usa prepared statements correctamente
                $stmt2 = $this->conn->prepare(
                    'UPDATE users SET email = ?, password = ? WHERE idUser = ? AND mkpin = ?'
                );
                $stmt2->execute([$cml, $securing, $duv, $pin]);

                if ($stmt2->rowCount() !== 1) {
                    throw new Exception('No se pudo actualizar users');
                }

                // ✅ Limpiar token de recuperación usado
                $this->conn->prepare(
                    'DELETE FROM forgot_pass WHERE email = ? AND password_key = ?'
                )->execute([$vemail, $forgotkey]);

                $this->conn->commit();

                header('Location: index.php');
                exit;

            } catch (Exception $e) {
                $this->conn->rollBack();
                error_log('updatePassword error: ' . $e->getMessage());
                $_SESSION['ErrorMessage'] = 'Error al actualizar la contraseña';
            }
    }

    /**
     * Actualiza el PIN.
     * ✅ CORREGIDO: Inyección SQL eliminada, SQL inválido corregido
     */
    private function updatePIN(): void
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
            return;
        }

        if (!isset($_GET['key'], $_GET['hash'])) {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        $forgotkey = ctype_alnum($_GET['key']) ? $_GET['key'] : '';
        $hash = ctype_alnum($_GET['hash']) ? $_GET['hash'] : '';

        if ($forgotkey === '' || $hash === '') {
            $_SESSION['ErrorMessage'] = 'Parámetros de recuperación inválidos';
            return;
        }

        $chck = $this->conn->prepare(
            'SELECT * FROM forgot_pin WHERE mkhash = ? AND password_key = ?'
        );
        $chck->execute([$hash, $forgotkey]);
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

        // ✅ CORREGIDO: Ahora usa prepared statements
        $very = $this->conn->prepare(
            'SELECT * FROM uverify WHERE email = ? AND pin_key = ? AND recovery_phrase = ?'
        );
        $very->execute([$vemail, $forgotkey, $recoveryphrase]);

        if ($very->rowCount() !== 1) {
            $_SESSION['ErrorMessage'] = 'Los datos no coinciden';
            return;
        }

        $dt = $very->fetch(PDO::FETCH_ASSOC);
        $duv = (int) $dt['iduv'];

        // ✅ CORREGIDO: Ahora usa prepared statements
        $checkm = $this->gc->ende_crypter('encrypt', $vemail, $dt['mktoken'], $dt['mkkey']);
        $fnal = $this->conn->prepare(
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

        $this->conn->beginTransaction();
        try {
            // ✅ BUG CORREGIDO: ahora usa SET correctamente
            $upd = $this->conn->prepare(
                'UPDATE uverify SET mkpin = ?, pin_key = ?
                WHERE email = ? AND recovery_phrase = ?'
            );
            $upd->execute([$npin, $clenkey, $vemail, $recoveryphrase]);

            if ($upd->rowCount() !== 1) {
                throw new Exception('No se pudo actualizar uverify');
            }

            $stmt2 = $this->conn->prepare(
                'UPDATE users SET mkpin = ? WHERE idUser = ? AND mkpin = ?'
            );
            $stmt2->execute([$npin, $duv, $dt['mkpin']]);

            if ($stmt2->rowCount() !== 1) {
                throw new Exception('No se pudo actualizar users');
            }

            // ✅ Limpiar token usado
            $this->conn->prepare(
                'DELETE FROM forgot_pin WHERE email = ? AND pin_key = ?'
            )->execute([$vemail, $forgotkey]);

            $this->conn->commit();

            header('Location: index.php');
            exit;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('updatePIN error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Error al actualizar el PIN';
        }
    }

    /**
     * Envía email de recuperación usando PHPMailer.
     */
    private function sendRecoveryEmail(string $email, string $key, string $hash, string $type): void
    {
        try {
            $usr = $this->gc->ende_crypter('decrypt', USEREMAIL, SECURE_TOKEN, SECURE_HASH);
            $pas = $this->gc->ende_crypter('decrypt', PASSMAIL, SECURE_TOKEN, SECURE_HASH);

            $endpoint = $type === 'password' ? 'password_reset' : 'pin_reset';
            $link = $this->baseurl . '/signin/' . $endpoint . '.php'
            . '?email=' . urlencode($email)
            . '&key=' . urlencode($key)
            . '&hash=' . urlencode($hash);

            $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
            $safeLink = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');

            $subject = $type === 'password'
            ? 'Restablecer tu contraseña'
            : 'Restablecer tu PIN';

            $body = "<html><body>"
            . "<p>Hola <b>{$safeEmail}</b>.</p>"
            . "<p>Haz clic en el siguiente enlace para restablecer tu "
            . ($type === 'password' ? 'contraseña' : 'PIN') . ":</p>"
            . "<p><a href=\"{$safeLink}\">Restablecer {$type}</a></p>"
            . "<p>Este enlace expira en 2 horas.</p>"
            . "</body></html>";

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = MAILSERVER;
            $mail->SMTPAuth   = true;
            $mail->Username   = $usr;
            $mail->Password   = $pas;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = PORTSERVER;
            $mail->CharSet    = 'UTF-8';
            $mail->SMTPDebug  = (defined('DEBUG') && DEBUG) ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

            $mail->setFrom($usr, SITE_NAME);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);
            $mail->send();

        } catch (Exception $e) {
            error_log('Mailer Error: ' . $e->getMessage());
        }
    }
}
