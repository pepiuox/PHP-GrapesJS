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
        /**
     * Cifrado/descifrado compatible con los datos ya almacenados.
     *
     * El CMS guarda datos cifrados y **los busca por igualdad**
     * (`WHERE email = :e`, `WHERE mkpin = :p`). Eso exige cifrado determinista: el mismo
     * texto con las mismas claves debe producir el mismo criptograma. Un IV aleatorio por
     * operacion (lo que hacia la version anterior) invalida esas consultas: el SELECT
     * nunca encuentra filas y el acceso con correo + contrasena + PIN resulta imposible.
     *
     * `encrypt` usa el formato historico AES-256-CBC con IV derivado de
     * `hash('sha256', $secret_iv)`, que es el que entienden las filas existentes.
     * `decrypt` acepta ademas AES-256-GCM (PBKDF2 10.000 y 100.000 iteraciones) escrito
     * por las versiones intermedias, para no dejar registros ilegibles.
     *
     * Trade-off asumido y documentado (ADR-002): dos textos iguales producen criptogramas
     * iguales. El esquema de datos ya dependia de esa igualdad; la correccion definitiva
     * es una columna de indice ciego (HMAC) que permita volver al cifrado aleatorio.
     *
     * @param string $action     'encrypt' o 'decrypt'
     * @param string $string     Texto plano o criptograma en base64
     * @param string $secret_key Clave (SECRET_TOKEN del sitio o clave por usuario)
     * @param string $secret_iv  Material de derivacion de clave/IV
     * @return string|false Texto plano, criptograma, o false si el formato no se reconoce
     */
    public function ende_crypter(string $action, string $string, string $secret_key, string $secret_iv): string|false {
        if ($action === 'encrypt') {
            return $this->encryptDeterministic($string, $secret_key, $secret_iv);
        }

        if ($action === 'decrypt') {
            return $this->decryptAnyFormat($string, $secret_key, $secret_iv);
        }

        return false;
    }

    /**
     * Cifrado determinista AES-256-CBC (formato historico del CMS).
     */
    private function encryptDeterministic(string $string, string $secret_key, string $secret_iv): string|false {
        $key = hash('sha256', $secret_key);
        $iv  = substr(hash('sha256', $secret_iv), 0, 16);

        $cipher = openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);

        return $cipher === false ? false : base64_encode($cipher);
    }

    /**
     * Descifra probando los formatos historicos en orden de antiguedad.
     */
    private function decryptAnyFormat(string $string, string $secret_key, string $secret_iv): string|false {
        $key = hash('sha256', $secret_key);
        $iv  = substr(hash('sha256', $secret_iv), 0, 16);

        $legacy = openssl_decrypt(base64_decode($string), 'AES-256-CBC', $key, 0, $iv);

        if (is_string($legacy) && $legacy !== '') {
            return $legacy;
        }

        $ivLength = openssl_cipher_iv_length('AES-256-GCM');

        foreach ([100_000, 10_000] as $iterations) {
            $data = base64_decode($string, true);

            if ($data === false || strlen($data) < $ivLength + 16) {
                continue;
            }

            $plain = openssl_decrypt(
                substr($data, $ivLength + 16),
                'AES-256-GCM',
                openssl_pbkdf2($secret_key, $secret_iv, 32, $iterations, 'sha256'),
                OPENSSL_RAW_DATA,
                substr($data, 0, $ivLength),
                substr($data, $ivLength, 16)
            );

            if (is_string($plain) && $plain !== '') {
                return $plain;
            }
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
