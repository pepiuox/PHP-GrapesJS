<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Registro de miembros con validación robusta y envío de email seguro.
 */
class MembersRegister
{
    protected PDO $conn;
    private GetCodeDeEncrypt $gc;
    private string $baseurl;

    // Constantes de validación
    private const MIN_USERNAME_LENGTH = 3;
    private const MAX_USERNAME_LENGTH = 30;
    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_PASSWORD_LENGTH = 128;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->gc = new GetCodeDeEncrypt();
        $this->baseurl = defined('SITE_PATH') ? SITE_PATH : '';
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function register(): array
    {
        // Validar que sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'error' => 'Invalid request method'];
        }

        // Obtener y sanitizar datos
        $username  = trim($_POST['username'] ?? '');
        $password  = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $email     = trim($_POST['email'] ?? '');

        // Validaciones
        $validation = $this->validateInput($username, $password, $password2, $email);
        if ($validation !== true) {
            return ['success' => false, 'error' => $validation];
        }

        // Verificar disponibilidad
        if ($this->usernameOrEmailExists($username, $email)) {
            $_SESSION['ErrorMessage'] = 'Username or email is already taken!';
            return ['success' => false, 'error' => 'Username or email already taken'];
        }

        // Hashear contraseña con bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $activationCode = $this->gc->getIdCode();

        // Insertar usuario
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO users (username, email, password, activation_code, created_at)
            VALUES (:username, :email, :password, :activation_code, NOW())"
            );
            $stmt->execute([
                ':username'        => $username,
                ':email'           => $email,
                ':password'        => $hashedPassword,
                ':activation_code' => $activationCode,
            ]);

            // Enviar email de activación
            $emailSent = $this->sendActivationEmail($email, $username, $activationCode);

            if ($emailSent) {
                $_SESSION['SuccessMessage'] = 'A message was sent to your mailbox to activate your new account!';
            } else {
                $_SESSION['ErrorMessage'] = 'Account created, but activation email could not be sent.';
            }

            return [
                'success' => true,
                'message' => 'User registered successfully',
            ];
        } catch (PDOException $e) {
            error_log('MembersRegister error: ' . $e->getMessage());
            $_SESSION['ErrorMessage'] = 'Registration failed. Please try again.';
            return ['success' => false, 'error' => 'Database error'];
        }
    }

    /**
     * Valida los datos de entrada.
     *
     * @return true|string True si es válido, mensaje de error si no
     */
    private function validateInput(string $username, string $password, string $password2, string $email)
    {
        // Campos obligatorios
        if ($username === '' || $password === '' || $email === '') {
            return 'Please complete all fields!';
        }

        // Longitud de username
        $usernameLen = strlen($username);
        if ($usernameLen < self::MIN_USERNAME_LENGTH || $usernameLen > self::MAX_USERNAME_LENGTH) {
            return sprintf(
                'Username must be between %d and %d characters',
                self::MIN_USERNAME_LENGTH,
                self::MAX_USERNAME_LENGTH
            );
        }

        // Username: solo alfanumérico, guiones y guiones bajos
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $username)) {
            return 'Username can only contain letters, numbers, underscores and hyphens';
        }

        // Longitud de password
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return sprintf('Password must be at least %d characters', self::MIN_PASSWORD_LENGTH);
        }

        if (strlen($password) > self::MAX_PASSWORD_LENGTH) {
            return sprintf('Password must be at most %d characters', self::MAX_PASSWORD_LENGTH);
        }

        // Contraseñas coinciden
        if ($password !== $password2) {
            return 'Passwords do not match!';
        }

        // Validar email con filter_var (más robusto que regex)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please insert a valid email address';
        }

        // Prevenir emails demasiado largos
        if (strlen($email) > 254) {
            return 'Email address is too long';
        }

        return true;
    }

    /**
     * Verifica si el username o email ya existen.
     */
    private function usernameOrEmailExists(string $username, string $email): bool
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email"
        );
        $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
        ]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Envía el email de activación usando PHPMailer.
     */
    private function sendActivationEmail(string $to, string $username, string $code): bool
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = defined('MAILSERVER') ? MAILSERVER : 'localhost';
            $mail->SMTPAuth   = true;
            $mail->CharSet    = 'UTF-8';

            if (defined('USEREMAIL') && defined('PASSMAIL')) {
                $gc = $this->gc;
                $mail->Username = $gc->ende_crypter('decrypt', USEREMAIL, SECURE_TOKEN, SECURE_HASH);
                $mail->Password = $gc->ende_crypter('decrypt', PASSMAIL, SECURE_TOKEN, SECURE_HASH);
            }

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = defined('PORTSERVER') ? PORTSERVER : 465;

            $fromEmail = $mail->Username ?? 'noreply@example.com';
            $fromName  = defined('SITE_NAME') ? SITE_NAME : 'Site Registration';

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to, $username);
            $mail->isHTML(true);

            $activationUrl = $this->baseurl . '/verify.php?id='
            . urlencode($to) . '&code=' . urlencode($code);

            $mail->Subject = 'Activate your account';
            $mail->Body    = $this->buildActivationEmailBody($username, $activationUrl);
            $mail->AltBody = "Hello {$username},\n\n"
            . "To activate your account, visit: {$activationUrl}\n\n"
            . "If you did not register, please ignore this email.";

            return $mail->send();
        } catch (Exception $e) {
            error_log('MembersRegister email error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Construye el cuerpo HTML del email de activación.
     */
    private function buildActivationEmailBody(string $username, string $url): string
    {
        $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8"></head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6;">
        <h2>Welcome, {$safeUsername}!</h2>
        <p>Thank you for registering. Please click the button below to activate your account:</p>
        <p style="text-align: center; margin: 30px 0;">
        <a href="{$safeUrl}"
        style="background-color: #007bff; color: white; padding: 12px 30px;
        text-decoration: none; border-radius: 5px; display: inline-block;">
        Activate Account
        </a>
        </p>
        <p>Or copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #007bff;">{$safeUrl}</p>
        <p style="color: #666; font-size: 12px;">
        If you did not register for this account, please ignore this email.
        </p>
        </body>
        </html>
        HTML;
    }

    /**
     * Verifica y activa un usuario con el código de activación.
     */
    public function activateUser(string $email, string $code): array
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT id, username FROM users
                WHERE email = :email AND activation_code = :code AND activated = 0
                LIMIT 1"
            );
            $stmt->execute([
                ':email' => $email,
                ':code'  => $code,
            ]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return ['success' => false, 'error' => 'Invalid activation code'];
            }

            $stmt = $this->conn->prepare(
                "UPDATE users SET activated = 1, activation_code = NULL,
                activated_at = NOW()
            WHERE id = :id"
            );
            $stmt->execute([':id' => $user['id']]);

            return [
                'success'  => true,
                'username' => $user['username'],
            ];
        } catch (PDOException $e) {
            error_log('MembersRegister activateUser error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Activation failed'];
        }
    }
}
