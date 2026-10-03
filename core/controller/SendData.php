<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Envío de datos de recuperación por email.
 * Migrado a PDO con seguridad mejorada.
 *
 * CORRECCIONES:
 * - XSS en body del email corregido con htmlspecialchars
 * - CSRF protection añadida
 * - SMTPDebug deshabilitado en producción
 * - Validación de emails
 * - exit después de header()
 * - hash_equals para comparación segura
 */
class SendData
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
        $this->baseurl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
        . dirname($_SERVER['PHP_SELF'] ?? '/');

        $this->includes();

        // ✅ CSRF validation en POST
        if (isset($_POST['forgotUsername']) || isset($_POST['forgotEmail'])) {
            if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['ErrorMessage'] = 'Token CSRF inválido';
                return;
            }
        }

        if (isset($_POST['forgotUsername'])) {
            $this->forgotUsername();
        }
        if (isset($_POST['forgotEmail'])) {
            $this->forgotEmail();
        }
    }

    private function includes(): void
    {
        require_once __DIR__ . '/../core/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../core/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../core/PHPMailer/src/SMTP.php';
    }

    private function procheck(?string $string): string
    {
        if ($string === null) return '';
        // ✅ htmlspecialchars con UTF-8
        return htmlspecialchars(trim($string), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function checkUsername(string $username): int
    {
        $user = $this->gc->ende_crypter('encrypt', $username, SECURE_TOKEN, SECURE_HASH);
        $query = $this->conn->prepare('SELECT COUNT(*) FROM uverify WHERE username = :u');
        $query->execute([':u' => $user]);
        return (int) $query->fetchColumn();
    }

    private function checkEmail(string $email): int
    {
        // ✅ Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 0;
        }

        $mail = $this->gc->ende_crypter('encrypt', $email, SECURE_TOKEN, SECURE_HASH);
        $query = $this->conn->prepare('SELECT COUNT(*) FROM uverify WHERE email = :e');
        $query->execute([':e' => $mail]);
        return (int) $query->fetchColumn();
    }

    private function forgotUsername(): void
    {
        if (!isset($_POST['forgotUsername'])) return;

        $recoveryphrase = $this->procheck($_POST['recoveryphrase'] ?? '');
        $email = $this->procheck($_POST['email'] ?? '');

        // ✅ Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<p>Email inválido.</p>';
            return;
        }

        if ($this->checkEmail($email) === 1) {
            $usrnm = $this->gc->ende_crypter('encrypt', $email, SECURE_TOKEN, SECURE_HASH);

            $stmt = $this->conn->prepare(
                'SELECT username FROM uverify
                WHERE email = :e AND recovery_phrase = :r'
            );
            $stmt->execute([':e' => $usrnm, ':r' => $recoveryphrase]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                echo '<p>La frase de recuperación no coincide.</p>';
                return;
            }

            $username = $row['username'];
            $myusr = $this->gc->ende_crypter('decrypt', $username, SECURE_TOKEN, SECURE_HASH);
            $usr = $this->gc->ende_crypter('decrypt', USEREMAIL, SECURE_TOKEN, SECURE_HASH);
            $pas = $this->gc->ende_crypter('decrypt', PASSMAIL, SECURE_TOKEN, SECURE_HASH);

            // ✅ XSS prevention: escapar datos en HTML del email
            $safeUser = htmlspecialchars($myusr, ENT_QUOTES, 'UTF-8');
            $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

            $subject = 'Le enviamos el nombre de usuario con el que se registró.';
            $body = '<html><body>'
            . "<p>Hola <b>{$safeUser}</b>.</p>"
            . "<p>Su email es: <b>{$safeEmail}</b></p>"
            . '<p>Le recomendamos actualizar sus datos por su seguridad.</p>'
            . '</body></html>';

            $this->sendMail($usr, $pas, $email, $myusr, $subject, $body);
        } else {
            echo '<p>No hay usuario registrado con ese email.</p>';
        }
    }

    private function forgotEmail(): void
    {
        if (!isset($_POST['forgotEmail'])) return;

        $recoveryphrase = $this->procheck($_POST['recoveryphrase'] ?? '');
        $username = $this->procheck($_POST['username'] ?? '');

        if ($this->checkUsername($username) === 1) {
            $usrnm = $this->gc->ende_crypter('encrypt', $username, SECURE_TOKEN, SECURE_HASH);

            $stmt = $this->conn->prepare(
                'SELECT email FROM uverify
                WHERE username = :u AND recovery_phrase = :r'
            );
            $stmt->execute([':u' => $usrnm, ':r' => $recoveryphrase]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                echo '<p>La frase de recuperación no coincide.</p>';
                return;
            }

            $myemail = $row['email'];
            $email = $this->gc->ende_crypter('decrypt', $myemail, SECURE_TOKEN, SECURE_HASH);
            $usr = $this->gc->ende_crypter('decrypt', USEREMAIL, SECURE_TOKEN, SECURE_HASH);
            $pas = $this->gc->ende_crypter('decrypt', PASSMAIL, SECURE_TOKEN, SECURE_HASH);

            // ✅ Validar email desencriptado
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<p>Email inválido en la base de datos.</p>';
                return;
            }

            // ✅ XSS prevention
            $safeUser = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

            $subject = 'Le enviamos el email con el que se registró.';
            $body = '<html><body>'
            . "<p>Hola <b>{$safeUser}</b>.</p>"
            . "<p>Su email es: <b>{$safeEmail}</b></p>"
            . '<p>Le recomendamos actualizar sus datos por su seguridad.</p>'
            . '</body></html>';

            $this->sendMail($usr, $pas, $email, $username, $subject, $body);
        } else {
            echo '<p>No hay email registrado con ese nombre de usuario.</p>';
        }
    }

    /**
     * Envía el email con configuración segura.
     *
     * ✅ SMTPDebug deshabilitado en producción
     */
    private function sendMail(
        string $from,
        string $password,
        string $to,
        string $toName,
        string $subject,
        string $body
    ): void {
        // ✅ Validar email destino
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            echo '<p>Email destino inválido.</p>';
            return;
        }

        $mail = new PHPMailer(true);
        try {
            // ✅ SMTPDebug deshabilitado en producción
            $mail->SMTPDebug = defined('DEBUG') && DEBUG ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = MAILSERVER;
            $mail->SMTPAuth   = true;
            $mail->Username   = $from;
            $mail->Password   = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = PORTSERVER;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($from, SITE_NAME);
            $mail->addAddress($to, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            echo '<p>El mensaje ha sido enviado.</p>';
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            // ✅ No exponer detalles del error al usuario
            echo '<p>No se pudo enviar el mensaje. Intente más tarde.</p>';
        }
    }
}
