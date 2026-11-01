<?php
declare(strict_types=1);

/**
 * Generación de valores aleatorios **criptográficamente seguros**.
 *
 * Sustituye a `rand()`, `mt_rand()` y `uniqid()` en cualquier uso relacionado con
 * seguridad: tokens de sesión, PINs, claves, recuperación de contraseña, nombres de
 * archivo de caché.
 *
 * `rand()`/`mt_rand()` son **predecibles** (Mersenne Twister): conocer unas pocas
 * salidas permite reconstruir el estado interno y adivinar los siguientes valores.
 *
 * Uso:
 *
 *     $token = Random::token(32);        // 64 caracteres hex
 *     $pin   = Random::digits(6);        // "481902"
 *     $id    = Random::id(16);           // identificador opaco
 *
 * @see docs/SECURITY.md §7 (S4)
 */

namespace PepiuoX\Security;

use InvalidArgumentException;

final class Random
{
    /** Sin caracteres ambiguos (0/O, 1/l/I) para claves legibles por humanos. */
    private const UNAMBIGUOUS = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';

    /**
     * Bytes aleatorios crudos.
     *
     * @throws \Random\RandomException
     */
    public static function bytes(int $length = 32): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('La longitud debe ser >= 1.');
        }

        return random_bytes($length);
    }

    /**
     * Token hexadecimal (para tokens de sesión, CSRF, recuperación…).
     *
     * @param int $bytes Nº de bytes de entropía (el resultado tiene el doble de caracteres).
     * @throws \Random\RandomException
     */
    public static function token(int $bytes = 32): string
    {
        return bin2hex(self::bytes($bytes));
    }

    /**
     * Entero aleatorio seguro.
     *
     * @throws \Random\RandomException
     */
    public static function int(int $min, int $max): int
    {
        if ($min > $max) {
            throw new InvalidArgumentException('Rango inválido.');
        }

        return random_int($min, $max);
    }

    /**
     * PIN numérico con ceros a la izquierda (p. ej. "004821").
     *
     * @throws \Random\RandomException
     */
    public static function digits(int $length = 6): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('La longitud debe ser >= 1.');
        }

        $pin = '';

        for ($i = 0; $i < $length; $i++) {
            $pin .= (string) random_int(0, 9);
        }

        return $pin;
    }

    /**
     * Cadena alfanumérica segura, útil como clave o identificador.
     *
     * @throws \Random\RandomException
     */
    public static function string(int $length = 32, bool $unambiguousOnly = false): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('La longitud debe ser >= 1.');
        }

        $alphabet = $unambiguousOnly
            ? self::UNAMBIGUOUS
            : 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max = strlen($alphabet) - 1;
        $out = '';

        for ($i = 0; $i < $length; $i++) {
            $out .= $alphabet[random_int(0, $max)];
        }

        return $out;
    }

    /**
     * Identificador opaco apto para nombres de archivo.
     *
     * @throws \Random\RandomException
     */
    public static function id(int $length = 16): string
    {
        return self::string($length);
    }

    /**
     * Comparación de cadenas en **tiempo constante**.
     *
     * Obligatorio para comparar tokens, hashes o PINs: `==`/`===` abandonan en el primer
     * byte distinto y filtran información por temporización.
     */
    public static function equals(string $known, string $given): bool
    {
        return hash_equals($known, $given);
    }
}
