<?php
declare(strict_types=1);

/**
 * Ejemplo de envío de email con PHPMailer.
 *
 * CORRECCIONES:
 * - Error tipográfico "PH PMailer" → "PHPMailer"
 * - Tags HTML con espacios "<b >" → "<b>"
 * - Credenciales desde constantes (no hardcodeadas)
 * - Validación de emails
 * - SMTPDebug configurable
 */

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Envía un email usando PHPMailer con configuración segura.
 */
function sendSecureMail(
    string $from,
    string $fromName,
    string $to,
    string $toName,
    string $subject,
    string $bodyHtml,
    string $bodyText = '',
    array $attachments = [],
    array $cc = [],
    array $bcc = []
): bool {
    // ✅ Validar emails
    if (!filter_var($from, FILTER_VALIDATE_EMAIL) ||
        !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Email inválido');
        }

        foreach (array_merge($cc, $bcc) as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException("Email CC/BCC inválido: {$email}");
            }
        }

        // ✅ Credenciales desde constantes (no hardcodeadas)
        $smtpHost     = defined('MAILSERVER') ? MAILSERVER : '';
        $smtpUser     = defined('USEREMAIL')  ? USEREMAIL  : '';
        $smtpPassword = defined('PASSMAIL')   ? PASSMAIL   : '';
        $smtpPort     = defined('PORTSERVER') ? (int) PORTSERVER : 465;

        if (empty($smtpHost) || empty($smtpUser)) {
            throw new RuntimeException('Configuración SMTP incompleta');
        }

        // ✅ Crear instancia con exceptions habilitadas
        $mail = new PHPMailer(true);

        try {
            // Server settings
            // ✅ SMTPDebug configurable (desactivado en producción)
            $mail->SMTPDebug = (defined('DEBUG') && DEBUG) ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUser;
            $mail->Password   = $smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $smtpPort;
            $mail->CharSet    = 'UTF-8';

            // Recipients
            $mail->setFrom($from, $fromName);
            $mail->addAddress($to, $toName);

            foreach ($cc as $email) {
                $mail->addCC($email);
            }
            foreach ($bcc as $email) {
                $mail->addBCC($email);
            }

            // Attachments (validar existencia)
            foreach ($attachments as $attachment) {
                $path = $attachment['path'] ?? '';
                $name = $attachment['name'] ?? '';

                if (!file_exists($path)) {
                    throw new RuntimeException("Archivo adjunto no encontrado: {$path}");
                }

                // ✅ Prevenir path traversal en nombres de archivo
                $safeName = basename($name ?: $path);
                $mail->addAttachment($path, $safeName);
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $bodyHtml;
            $mail->AltBody = $bodyText ?: strip_tags($bodyHtml);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            throw new RuntimeException('Error al enviar el email');
        }
}

/* ---------- Ejemplo de uso ---------- */
if (PHP_SAPI !== 'cli' && isset($_POST['send_mail'])) {
    // ✅ CSRF validation
    if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido');
    }

    try {
        $result = sendSecureMail(
            from:     'from@example.com',
            fromName: 'Mailer',
            to:       'joe@example.net',
            toName:   'Joe User',
            subject:  'Here is the subject',
            bodyHtml: 'This is the HTML message body <b>in bold!</b>',
            bodyText: 'This is the body in plain text for non-HTML mail clients'
        );

        echo $result ? 'Message has been sent' : 'Message could not be sent';
    } catch (Exception $e) {
        echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
