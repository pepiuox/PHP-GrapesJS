<?php
declare(strict_types=1);

class Utils
{
    /**
     * Cifrado AES-256-CBC con IV aleatorio por cada operación.
     * Devuelve "IV(16) + ciphertext" en base64.
     */
    public static function encrypt(string $data): string
    {
        $method = defined('ENCRYPTION_METHOD') ? ENCRYPTION_METHOD : 'AES-256-CBC';
        $key    = hash('sha256', SECRET_KEY, true);
        $iv     = random_bytes(16); // ✅ IV aleatorio y único

        $cipher = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
        if ($cipher === false) {
            throw new RuntimeException('Encryption failed');
        }
        return base64_encode($iv . $cipher);
    }

    public static function decrypt(string $data): string
    {
        $method = defined('ENCRYPTION_METHOD') ? ENCRYPTION_METHOD : 'AES-256-CBC';
        $key    = hash('sha256', SECRET_KEY, true);
        $raw    = base64_decode($data, true);
        if ($raw === false || strlen($raw) < 17) {
            throw new RuntimeException('Invalid ciphertext');
        }

        $iv       = substr($raw, 0, 16);
        $cipher   = substr($raw, 16);
        $plain    = openssl_decrypt($cipher, $method, $key, OPENSSL_RAW_DATA, $iv);
        if ($plain === false) {
            throw new RuntimeException('Decryption failed');
        }
        return $plain;
    }

    /**
     * Slug compatible con UTF-8 (acentos, ñ).
     */
    public static function generateSlug(string $string): string
    {
        $string = mb_strtolower($string, 'UTF-8');
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        $slug   = preg_replace('/[^a-z0-9-]+/', '-', $string);
        $slug   = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    public static function validateEmail(string $email): string|false
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function sanitize(mixed $input): mixed
    {
        if (is_array($input)) {
            foreach ($input as $k => $v) $input[$k] = self::sanitize($v);
            return $input;
        }
        $input = trim((string) $input);
        $input = stripslashes($input);
        return htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function generateCSRFToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCSRFToken(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['csrf_token'])) return false;
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
