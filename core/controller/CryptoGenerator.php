<?php
declare(strict_types=1);

/**
 * CryptoGenerator - Clase segura para operaciones criptográficas y generación de tokens
 *
 * @package Security
 * @version 2.1.0
 *
 * Mejoras v2.1:
 * - Timing-safe comparison para validación de tags
 * - Limpieza segura de memoria para claves derivadas
 * - Validación de entrada más robusta
 * - Prevención de padding oracle attacks
 */
final class CryptoGenerator
{
    // Constantes de clase
    private const DEFAULT_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&@[]{|}';
    private const DEFAULT_LENGTH = 64;
    private const MAX_LENGTH = 1024;
    private const CIPHER_METHOD = 'aes-256-gcm';
    private const PBKDF2_ITERATIONS = 100_000;
    private const HASH_ALGO = 'sha256';
    private const TAG_LENGTH = 16;

    private string $charSet;
    private int $defaultLength;

    public function __construct(?string $charSet = null, int $defaultLength = self::DEFAULT_LENGTH)
    {
        $this->validateLength($defaultLength, 1, self::MAX_LENGTH);
        $this->charSet = $charSet ?? self::DEFAULT_CHARS;
        $this->defaultLength = $defaultLength;

        if (strlen($this->charSet) < 2) {
            throw new InvalidArgumentException('Character set must contain at least 2 characters');
        }

        // Verificar soporte de extensiones
        if (!extension_loaded('openssl')) {
            throw new RuntimeException('OpenSSL extension is required');
        }
    }

    // ============================================
    // MÉTODOS PÚBLICOS PRINCIPALES
    // ============================================

    public function random(int $length = self::DEFAULT_LENGTH, string $type = 'string'): string
    {
        $this->validateLength($length, 1, self::MAX_LENGTH);
        $this->validateType($type);

        return match ($type) {
            'hex'    => $this->generateHex($length),
            'base64' => $this->generateBase64($length),
            'hash'   => $this->generateHash($length),
            default  => $this->generateString($length),
        };
    }

    public function token(): string
    {
        return $this->random(64, 'base64');
    }

    public function apiKey(): string
    {
        return $this->random(32, 'hex');
    }

    public function secureHash(): string
    {
        return $this->random(64, 'hash');
    }

    public function verificationCode(int $digits = 6): string
    {
        $this->validateLength($digits, 4, 10);
        $min = (int) str_pad('1', $digits, '0');
        $max = (int) str_pad('', $digits, '9');
        return (string) random_int($min, $max);
    }

    public function uuid(): string
    {
        $bytes = random_bytes(16);
        // Versión 4 (aleatorio)
        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
        // Variante RFC 4122
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    // ============================================
    // MÉTODOS DE CIFRADO
    // ============================================

    /**
     * Cifra datos usando AES-256-GCM con autenticación.
     */
    public function encrypt(string $data, string $key, ?string $aad = null): string
    {
        if ($data === '') {
            throw new InvalidArgumentException('Data cannot be empty');
        }
        if (strlen($key) < 16) {
            throw new InvalidArgumentException('Key must be at least 16 characters');
        }

        $derivedKey = $this->deriveKey($key);
        $ivLength = openssl_cipher_iv_length(self::CIPHER_METHOD);
        $iv = random_bytes($ivLength);
        $tag = '';

        try {
            $encrypted = openssl_encrypt(
                $data,
                self::CIPHER_METHOD,
                $derivedKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                $aad ?? '',
                self::TAG_LENGTH
            );
        } finally {
            // Limpieza de memoria de la clave derivada
            $this->secureWipe($derivedKey);
        }

        if ($encrypted === false) {
            throw new RuntimeException('Encryption failed: ' . openssl_error_string());
        }

        // Formato: [IV][TAG][CIPHERTEXT]
        return base64_encode($iv . $tag . $encrypted);
    }

    /**
     * Descifra datos cifrados con encrypt().
     */
    public function decrypt(string $encryptedData, string $key, ?string $aad = null): string|false
    {
        try {
            $decoded = base64_decode($encryptedData, true);
            if ($decoded === false) {
                return false;
            }

            $derivedKey = $this->deriveKey($key);
            $ivLength = openssl_cipher_iv_length(self::CIPHER_METHOD);

            // Validar longitud mínima
            if (strlen($decoded) < $ivLength + self::TAG_LENGTH) {
                $this->secureWipe($derivedKey);
                return false;
            }

            $iv = substr($decoded, 0, $ivLength);
            $tag = substr($decoded, $ivLength, self::TAG_LENGTH);
            $ciphertext = substr($decoded, $ivLength + self::TAG_LENGTH);

            try {
                $result = openssl_decrypt(
                    $ciphertext,
                    self::CIPHER_METHOD,
                    $derivedKey,
                    OPENSSL_RAW_DATA,
                    $iv,
                    $tag,
                    $aad ?? ''
                );
            } finally {
                $this->secureWipe($derivedKey);
            }

            return $result;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Compara dos strings de forma segura (timing-safe).
     * Útil para comparar tokens, hashes, etc.
     */
    public function secureCompare(string $known, string $user): bool
    {
        return hash_equals($known, $user);
    }

    /**
     * Genera un hash de contraseña seguro usando bcrypt.
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verifica una contraseña contra un hash.
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    // ============================================
    // MÉTODOS DE SANITIZACIÓN
    // ============================================

    public function sanitize(string $input, bool $stripSlashes = false): string
    {
        $result = trim($input);
        if ($stripSlashes) {
            $result = stripslashes($result);
        }
        return htmlspecialchars($result, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function sanitizeForSql(string $input): string
    {
        return addcslashes($input, "\x00\n\r\\'\"\x1a");
    }

    public function sanitizeEmail(string $email): string|false
    {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ?: false;
    }

    public function sanitizeUrl(string $url): string|false
    {
        $url = filter_var(trim($url), FILTER_SANITIZE_URL);
        return filter_var($url, FILTER_VALIDATE_URL) ?: false;
    }

    // ============================================
    // MÉTODOS ESPECÍFICOS
    // ============================================

    public function customString(int $length, ?string $customChars = null): string
    {
        $chars = $customChars ?? $this->charSet;
        $max = strlen($chars) - 1;
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, $max)];
        }
        return $result;
    }

    public function getRandomString(int $length): string
    {
        return $this->customString($length);
    }

    // ============================================
    // MÉTODOS PRIVADOS
    // ============================================

    private function secureBytes(int $length): string
    {
        return random_bytes($length);
    }

    private function generateHex(int $length): string
    {
        $bytes = $this->secureBytes((int) ceil($length / 2));
        return substr(bin2hex($bytes), 0, $length);
    }

    private function generateBase64(int $length): string
    {
        $bytes = $this->secureBytes($length);
        $base64 = strtr(base64_encode($bytes), '+/', '-_');
        $base64 = rtrim($base64, '=');
        return substr($base64, 0, $length);
    }

    private function generateHash(int $length): string
    {
        $bytes = $this->secureBytes(32);
        return substr(hash(self::HASH_ALGO, $bytes), 0, $length);
    }

    private function generateString(int $length): string
    {
        return $this->customString($length);
    }

    private function deriveKey(string $key): string
    {
        $salt = hash(self::HASH_ALGO, $this->charSet, true);
        return openssl_pbkdf2(
            $key,
            $salt,
            32,
            self::PBKDF2_ITERATIONS,
            self::HASH_ALGO
        );
    }

    /**
     * Limpia de forma segura una variable de memoria.
     */
    private function secureWipe(string &$var): void
    {
        $length = strlen($var);
        if ($length > 0) {
            $var = str_repeat("\0", $length);
        }
        $var = '';
    }

    private function validateLength(int $length, int $min, int $max): void
    {
        if ($length < $min || $length > $max) {
            throw new InvalidArgumentException(sprintf(
                'Length must be between %d and %d, got %d',
                $min, $max, $length
            ));
        }
    }

    private function validateType(string $type): void
    {
        $validTypes = ['string', 'hex', 'base64', 'hash'];
        if (!in_array($type, $validTypes, true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid type. Must be one of: %s',
                implode(', ', $validTypes)
            ));
        }
    }
}
