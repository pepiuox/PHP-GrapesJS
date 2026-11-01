<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
//  MIGRATED & SECURED:
//  - Removed rand(), mt_rand(), str_shuffle() (not cryptographically secure)
//  - Removed openssl_random_pseudo_bytes() in favor of random_bytes()
//  - Fixed bug: $this->charSet → $this->characters
//  - Unified all token generators under generateSecureToken()
//  - ende_crypter() now uses random IV per encryption + AES-256-GCM with auth tag
//
class GetCodeDeEncrypt {
    public string $characters;

    public function __construct() {
        $this->characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#$%&@[]{|}';
    }

    /**
     * Escapa HTML de forma segura (reemplaza procheck original).
     * Eliminado el doble escape htmlentities + htmlspecialchars.
     */
    public function procheck(?string $string): string {
        return htmlspecialchars((string)($string ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Cifrado/descifrado seguro con AES-256-GCM.
     * - IV aleatorio por cada operación (no derivado de hash)
     * - Tag de autenticación para detectar manipulación
     * - Key derivada con PBKDF2 (10,000 iteraciones, SHA-256)
     */
    public function ende_crypter(string $action, string $string, string $secret_key, string $secret_iv): string|false {
        $encrypt_method = 'AES-256-GCM';
        $iv_len = openssl_cipher_iv_length($encrypt_method);

        // Derivación de clave segura (PBKDF2)
        $key = openssl_pbkdf2($secret_key, $secret_iv, 32, 100_000, 'sha256');

        if ($action === 'encrypt') {
            $iv  = random_bytes($iv_len);
            $tag = '';
            $encrypted = openssl_encrypt(
                $string,
                $encrypt_method,
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
            if ($encrypted === false) return false;
            // Formato: IV (12) + TAG (16) + ciphertext
            return base64_encode($iv . $tag . $encrypted);
        }

        if ($action === 'decrypt') {
            $data = base64_decode($string, true);
            if ($data === false || strlen($data) < $iv_len + 16) return false;

            $iv         = substr($data, 0, $iv_len);
            $tag        = substr($data, $iv_len, 16);
            $ciphertext = substr($data, $iv_len + 16);

            return openssl_decrypt(
                $ciphertext,
                $encrypt_method,
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
        }

        return false;
    }

    /* ============================================================
     *  MÉTODOS DE GENERACIÓN DE TOKENS (TODOS CSPRNG)
     * ============================================================ */

    /**
     * Generador unificado de tokens seguros.
     * Reemplaza: randToken, randKey, randHash, iRandHash, getRandKey,
     *            getKeyCode, getIdCode, randString, getRandCode, etc.
     *
     * @param int    $length Longitud deseada
     * @param string $mode   hex | base64 | hash | alphanum
     */
    public function generateSecureToken(int $length = 64, string $mode = 'hex'): string {
        if ($length < 1 || $length > 1024) {
            throw new InvalidArgumentException('Length must be between 1 and 1024');
        }
        $bytes = random_bytes((int)ceil($length / 2));
        return match ($mode) {
            'hex'      => substr(bin2hex($bytes), 0, $length),
            'base64'   => substr(str_replace(['+', '/', '='], '', base64_encode($bytes)), 0, $length),
            'hash'     => substr(hash('sha256', $bytes), 0, $length),
            'alphanum' => $this->bytesToAlphanumeric($bytes, $length),
            default    => substr(bin2hex($bytes), 0, $length),
        };
    }

    /**
     * Genera un string aleatorio con alfabeto personalizado (CSPRNG).
     * Reemplaza: iRandKey, getRandomString, generateRandStr, getRandomCode
     *
     * @param int         $length  Longitud (1-1024)
     * @param string|null $alphabet Caracteres permitidos (opcional)
     */
    public function generateSecureRandomString(int $length, ?string $alphabet = null): string {
        if ($length < 1 || $length > 1024) {
            throw new InvalidArgumentException('Length must be between 1 and 1024');
        }
        $alphabet = $alphabet ?? $this->characters; // ← Bug corregido (era $this->charSet)
        $alphabetLength = strlen($alphabet);
        if ($alphabetLength < 2) {
            throw new InvalidArgumentException('Alphabet must contain at least 2 characters');
        }

        $result   = '';
        $maxIndex = $alphabetLength - 1;
        for ($i = 0; $i < $length; $i++) {
            $result .= $alphabet[random_int(0, $maxIndex)];
        }
        return $result;
    }

    /* ============================================================
     *  MÉTODOS LEGACY (reescritos sobre CSPRNG, mantienen firma)
     * ============================================================ */

    /** @deprecated Usa generateSecureToken(64, 'base64') */
    public function randToken(): string {
        return $this->generateSecureToken(64, 'base64');
    }

    /** @deprecated Usa generateSecureToken(64, 'base64') */
    public function randKey(): string {
        return $this->generateSecureToken(64, 'base64');
    }

    /** @deprecated Usa generateSecureToken(64, 'hash') */
    public function randHash(): string {
        return $this->generateSecureToken(64, 'hash');
    }

    /** @deprecated Usa generateSecureToken(64, 'hash') */
    public function iRandHash(): string {
        return $this->generateSecureToken(64, 'hash');
    }

    /** @deprecated Usa generateSecureRandomString($length) */
    public function iRandKey(int $length): string {
        return $this->generateSecureRandomString($length);
    }

    /** @deprecated Usa generateSecureRandomString($length) */
    public function getRandomString(int $length, bool $crypto_secure = true): string {
        return $this->generateSecureRandomString($length);
    }

    /** @deprecated Usa generateSecureToken($length, 'hex') */
    public function getRandKey(): string {
        return $this->generateSecureToken(64, 'hex');
    }

    /** @deprecated Usa generateSecureRandomString(56) . random_int(...) */
    public function getRandomCode(): string {
        return $this->generateSecureRandomString(56) . random_int(10_000_000, 99_999_999);
    }

    /** @deprecated Usa generateSecureRandomString(64, '0123456789...') */
    public function getRandCode(): string {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $this->generateSecureRandomString(64, $pool);
    }

    /** @deprecated Usa generateSecureToken(64, 'hex') */
    public function getKeyCode(): string {
        return bin2hex(random_bytes(32));
    }

    /** @deprecated Usa generateSecureToken(64, 'hex') */
    public function getIdCode(): string {
        return bin2hex(random_bytes(32));
    }

    /** @deprecated Usa generateSecureToken($leng * 2, 'hex') */
    public function randString(int $leng): string {
        return bin2hex(random_bytes($leng));
    }

    /** @deprecated Usa generateSecureToken($len, 'hash') */
    public function randLengthString(int $len): string {
        $secret = bin2hex(random_bytes(17)) . bin2hex(random_bytes(13));
        return substr(hash('sha256', $secret), 0, $len);
    }

    /** @deprecated Usa generateSecureRandomString($length, '0-9a-zA-Z') */
    public function generateRandStr(int $length): string {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $this->generateSecureRandomString($length, $pool);
    }

    /* ============================================================
     *  HELPERS INTERNOS
     * ============================================================ */

    private function bytesToAlphanumeric(string $bytes, int $length): string {
        $alphabet = $this->characters;
        $maxIndex = strlen($alphabet) - 1;
        $result = '';
        $bytesLen = strlen($bytes);
        for ($i = 0; $i < $length; $i++) {
            $result .= $alphabet[ord($bytes[$i % $bytesLen]) % ($maxIndex + 1)];
        }
        return $result;
    }
}
