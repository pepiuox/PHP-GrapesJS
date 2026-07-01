<?php

declare(strict_types=1);

/**
 * CryptoGenerator - Clase segura para operaciones criptográficas y generación de tokens
 *
 * @package Security
 * @author Enhanced Version
 * @version 2.0.0
 *
 * Características:
 * - Generación criptográficamente segura de strings aleatorios
 * - Cifrado AES-256-GCM con autenticación
 * - Sanitización segura de datos
 * - Eliminación de código duplicado
 * - Type hints y validaciones robustas
 */

final class CryptoGenerator {
    // Constantes de clase
    private const DEFAULT_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&@[]{|}';
    private const DEFAULT_LENGTH = 64;
    private const MAX_LENGTH = 1024;
    private const CIPHER_METHOD = 'aes-256-gcm';
    private const PBKDF2_ITERATIONS = 100000;
    private const HASH_ALGO = 'sha256';

    // Propiedades privadas
    private string $charSet;
    private int $defaultLength;

    /**
     * Constructor de la clase
     *
     * @param string|null $charSet Conjunto de caracteres personalizado (opcional)
     * @param int $defaultLength Longitud por defecto para generaciones
     * @throws InvalidArgumentException Si la longitud es inválida
     */
    public function __construct(?string $charSet = null, int $defaultLength = self::DEFAULT_LENGTH) {
        $this->validateLength($defaultLength, 1, self::MAX_LENGTH);
        $this->charSet = $charSet ?? self::DEFAULT_CHARS;
        $this->defaultLength = $defaultLength;

        // Verificar que el conjunto de caracteres tenga al menos 2 caracteres
        if (strlen($this->charSet) < 2) {
            throw new InvalidArgumentException('Character set must contain at least 2 characters');
        }
    }

    // ============================================
    // MÉTODOS PÚBLICOS PRINCIPALES
    // ============================================

    /**
     * Método unificado para generar strings aleatorios seguros
     *
     * @param int $length Longitud deseada
     * @param string $type Tipo de generación: 'string', 'hex', 'base64', 'hash'
     * @return string String generado
     * @throws InvalidArgumentException Si el tipo o longitud son inválidos
     */
    public function random(int $length = self::DEFAULT_LENGTH, string $type = 'string'): string {
        $this->validateLength($length, 1, self::MAX_LENGTH);
        $this->validateType($type);

        return match($type) {
            'hex' => $this->generateHex($length),
            'base64' => $this->generateBase64($length),
            'hash' => $this->generateHash($length),
            default => $this->generateString($length)
        };
    }

    /**
     * Genera un token seguro (para CSRF, sesiones, etc.)
     */
    public function token(): string {
        return $this->random(64, 'base64');
    }

    /**
     * Genera una clave API segura
     */
    public function apiKey(): string {
        return $this->random(32, 'hex');
    }

    /**
     * Genera un hash seguro
     */
    public function secureHash(): string {
        return $this->random(64, 'hash');
    }

    /**
     * Genera un código de verificación (6-8 dígitos)
     *
     * @param int $digits Número de dígitos (4-10)
     * @return string Código numérico
     */
    public function verificationCode(int $digits = 6): string {
        $this->validateLength($digits, 4, 10);
        $min = (int) str_pad('1', $digits, '0');
        $max = (int) str_pad('', $digits, '9');
        return (string) random_int($min, $max);
    }

    /**
     * Genera un UUID v4 compatible
     */
    public function uuid(): string {
        $bytes = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40); // versión 4
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80); // variante RFC 4122

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    // ============================================
    // MÉTODOS DE CIFRADO
    // ============================================

    /**
     * Cifra datos usando AES-256-GCM
     *
     * @param string $data Datos a cifrar
     * @param string $key Clave secreta
     * @param string|null $aad Datos adicionales autenticados (opcional)
     * @return string Datos cifrados en base64 (IV + Tag + Ciphertext)
     * @throws RuntimeException Si el cifrado falla
     */
    public function encrypt(string $data, string $key, ?string $aad = null): string {
        $derivedKey = $this->deriveKey($key);
        $ivLength = openssl_cipher_iv_length(self::CIPHER_METHOD);
        $iv = random_bytes($ivLength);

        $tag = '';
        $encrypted = openssl_encrypt(
            $data,
            self::CIPHER_METHOD,
            $derivedKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            $aad ?? ''
        );

        if ($encrypted === false) {
            throw new RuntimeException('Encryption failed');
        }

        // Formato: [IV][TAG][CIPHERTEXT]
        return base64_encode($iv . $tag . $encrypted);
    }

    /**
     * Descifra datos cifrados con encrypt()
     *
     * @param string $encryptedData Datos cifrados en base64
     * @param string $key Clave secreta
     * @param string|null $aad Datos adicionales autenticados (debe coincidir con el cifrado)
     * @return string|false Datos descifrados o false si falla
     */
    public function decrypt(string $encryptedData, string $key, ?string $aad = null): string|false {
        try {
            $decoded = base64_decode($encryptedData, true);
            if ($decoded === false) {
                return false;
            }

            $derivedKey = $this->deriveKey($key);
            $ivLength = openssl_cipher_iv_length(self::CIPHER_METHOD);

            $iv = substr($decoded, 0, $ivLength);
            $tag = substr($decoded, $ivLength, 16);
            $ciphertext = substr($decoded, $ivLength + 16);

            return openssl_decrypt(
                $ciphertext,
                self::CIPHER_METHOD,
                $derivedKey,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                $aad ?? ''
            );
        } catch (Exception $e) {
            return false;
        }
    }

    // ============================================
    // MÉTODOS DE SANITIZACIÓN
    // ============================================

    /**
     * Sanitiza strings para prevenir XSS y otros ataques
     *
     * @param string $input String a sanitizar
     * @param bool $stripSlashes Si debe eliminar backslashes
     * @return string String sanitizado
     */
    public function sanitize(string $input, bool $stripSlashes = false): string {
        $result = trim($input);

        if ($stripSlashes) {
            $result = stripslashes($result);
        }

        return htmlspecialchars($result, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Sanitiza para uso en SQL (complementario a prepared statements)
     */
    public function sanitizeForSql(string $input): string {
        // Nota: Siempre usar prepared statements, esto es solo un complemento
        return addcslashes($input, "\x00\n\r\\'\"\x1a");
    }

    /**
     * Valida y limpia email
     */
    public function sanitizeEmail(string $email): string|false {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    /**
     * Sanitiza URL
     */
    public function sanitizeUrl(string $url): string|false {
        $url = filter_var(trim($url), FILTER_SANITIZE_URL);
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : false;
    }

    // ============================================
    // MÉTODOS ESPECÍFICOS PARA COMPATIBILIDAD
    // ============================================

    /**
     * Genera string aleatorio con caracteres personalizados
     *
     * @param int $length Longitud del string
     * @param string|null $customChars Caracteres personalizados (opcional)
     * @return string String generado
     */
    public function customString(int $length, ?string $customChars = null): string {
        $chars = $customChars ?? $this->charSet;
        $max = strlen($chars) - 1;
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, $max)];
        }

        return $result;
    }

    /**
     * Genera string con longitud exacta (alias para compatibilidad)
     */
    public function getRandomString(int $length): string {
        return $this->customString($length);
    }

    // ============================================
    // MÉTODOS PRIVADOS
    // ============================================

    /**
     * Genera bytes aleatorios criptográficamente seguros
     */
    private function secureBytes(int $length): string {
        return random_bytes($length);
    }

    /**
     * Genera string hexadecimal
     */
    private function generateHex(int $length): string {
        // Hex necesita el doble de bytes para la longitud deseada
        $bytes = $this->secureBytes(ceil($length / 2));
        return substr(bin2hex($bytes), 0, $length);
    }

    /**
     * Genera string base64 URL-safe
     */
    private function generateBase64(int $length): string {
        $bytes = $this->secureBytes($length);
        $base64 = str_replace(['+', '/', '='], '', base64_encode($bytes));
        return substr($base64, 0, $length);
    }

    /**
     * Genera hash
     */
    private function generateHash(int $length): string {
        $bytes = $this->secureBytes(32);
        return substr(hash(self::HASH_ALGO, $bytes), 0, $length);
    }

    /**
     * Genera string alfanumérico con caracteres permitidos
     */
    private function generateString(int $length): string {
        return $this->customString($length);
    }

    /**
     * Deriva una clave segura usando PBKDF2
     */
    private function deriveKey(string $key): string {
        // Salt fijo pero único por instancia
        $salt = hash(self::HASH_ALGO, $this->charSet, true);
        return openssl_pbkdf2($key, $salt, 32, self::PBKDF2_ITERATIONS, self::HASH_ALGO);
    }

    /**
     * Valida que la longitud esté dentro del rango permitido
     */
    private function validateLength(int $length, int $min, int $max): void {
        if ($length < $min || $length > $max) {
            throw new InvalidArgumentException(sprintf(
                'Length must be between %d and %d, got %d',
                $min,
                $max,
                $length
            ));
        }
    }

    /**
     * Valida que el tipo sea válido
     */
    private function validateType(string $type): void {
        $validTypes = ['string', 'hex', 'base64', 'hash'];
        if (!in_array($type, $validTypes, true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid type. Must be one of: %s',
                implode(', ', $validTypes)
            ));
        }
    }
}

// ============================================
// EJEMPLOS DE USO
// ============================================

/*
 / /* Instanciar la clase
 $crypto = new CryptoGenerator();

 // Generar diferentes tipos de strings
 $token = $crypto->token();                    // Token CSRF
 $apiKey = $crypto->apiKey();                  // API Key
 $hash = $crypto->secureHash();                // Hash seguro
 $uuid = $crypto->uuid();                      // UUID v4
 $code = $crypto->verificationCode(6);         // Código de 6 dígitos

 // Generar con opciones específicas
 $hexString = $crypto->random(32, 'hex');      // String hexadecimal
 $base64String = $crypto->random(48, 'base64'); // Base64 URL-safe
 $custom = $crypto->customString(50, 'ABC123'); // Solo caracteres personalizados

 // Cifrar/Descifrar datos sensibles
 $secretKey = 'mi-clave-super-segura-123';
 $data = 'Información confidencial';

 $encrypted = $crypto->encrypt($data, $secretKey);
 $decrypted = $crypto->decrypt($encrypted, $secretKey);

 // Sanitizar entradas de usuario
 $userInput = '<script>alert("xss")</script> Hola mundo';
 $clean = $crypto->sanitize($userInput); // &lt;script&gt;...

 // Validar email
 $email = $crypto->sanitizeEmail('usuario@ejemplo.com');
 if ($email) {
     echo "Email válido: $email";
     }
     */
