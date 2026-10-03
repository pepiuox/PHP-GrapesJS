<?php
declare(strict_types=1);

/**
 * Generador de contraseñas seguras.
 * Mejorado con tipado estricto y validaciones.
 */
class PasswordGenerator
{
    public const LETTERS = 'abcdefghijklmnopqrstuvwxyz';
    public const DIGITS = '0123456789';
    public const SPECIAL_CHARS = '!@#$%^&*()_+-={}[]|:;"<>,.?/';
    public const MAX_SIMILARITY_PERC = 20;

    private int $minLength;
    private int $maxLength;
    private array $diffStrings;

    public function __construct(int $minLength = 8, int $maxLength = 32, array $diffStrings = [])
    {
        // ✅ Validación de parámetros
        if ($minLength < 8) {
            throw new InvalidArgumentException('La longitud mínima debe ser al menos 8');
        }
        if ($maxLength < $minLength) {
            throw new InvalidArgumentException('La longitud máxima debe ser mayor o igual a la mínima');
        }
        if ($maxLength > 128) {
            throw new InvalidArgumentException('La longitud máxima no puede exceder 128');
        }

        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        $this->diffStrings = $diffStrings;
    }

    /**
     * Genera una contraseña segura.
     */
    public function generate(): string
    {
        $chars = self::LETTERS . mb_strtoupper(self::LETTERS) . self::DIGITS . self::SPECIAL_CHARS;
        $passwordReady = false;
        $maxAttempts = 1000; // ✅ Prevenir bucle infinito
        $attempts = 0;

        while (!$passwordReady && $attempts < $maxAttempts) {
            $attempts++;
            $password = '';
            $hasLowercase = false;
            $hasUppercase = false;
            $hasDigit = false;
            $hasSpecialChar = false;

            $length = random_int($this->minLength, $this->maxLength);

            while ($length > 0) {
                $length--;
                $index = random_int(0, mb_strlen($chars) - 1);
                $char = $chars[$index];
                $password .= $char;

                $hasLowercase = $hasLowercase || (mb_strpos(self::LETTERS, $char) !== false);
                $hasUppercase = $hasUppercase || (mb_strpos(mb_strtoupper(self::LETTERS), $char) !== false);
                $hasDigit = $hasDigit || (mb_strpos(self::DIGITS, $char) !== false);
                $hasSpecialChar = $hasSpecialChar || (mb_strpos(self::SPECIAL_CHARS, $char) !== false);
            }

            $passwordReady = ($hasLowercase && $hasUppercase && $hasDigit && $hasSpecialChar);

            if ($passwordReady) {
                foreach ($this->diffStrings as $string) {
                    similar_text($password, $string, $similarityPerc);
                    $passwordReady = $passwordReady && ($similarityPerc < self::MAX_SIMILARITY_PERC);
                }
            }
        }

        if (!$passwordReady) {
            throw new RuntimeException('No se pudo generar una contraseña válida después de múltiples intentos');
        }

        return $password;
    }

    /**
     * Obtiene la longitud mínima.
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * Obtiene la longitud máxima.
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }
}
