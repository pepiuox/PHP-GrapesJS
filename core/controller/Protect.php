<?php
declare(strict_types=1);

/**
 * Clase de protección y sanitización de datos.
 * Migrado a PDO con eliminación de doble escape.
 *
 * CORRECCIONES:
 * - Eliminado mysqli_real_escape_string (no necesario con PDO prepared statements)
 * - Eliminado doble escape: htmlentities + htmlspecialchars
 * - Métodos más seguros y eficientes
 * - Inyección de dependencias PDO
 */
class Protect
{
    private PDO $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    /**
     * Sanitiza una cadena para salida HTML.
     *
     * ✅ CORREGIDO: eliminado doble escape
     * ✅ AHORA: solo usa htmlspecialchars (suficiente para HTML)
     */
    public function secureStr(?string $string): string
    {
        if ($string === null || $string === '') {
            return '';
        }

        $string = trim($string);

        // ✅ Solo htmlspecialchars es necesario para prevenir XSS
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Sanitiza una cadena para almacenamiento en BD.
     *
     * ✅ CORREGIDO: eliminado mysqli_real_escape_string
     * ✅ AHORA: solo sanitización HTML (PDO prepared statements manejan SQL)
     */
    public function protectStr(?string $str): string
    {
        if ($str === null || $str === '') {
            return '';
        }

        $str = trim($str);
        $str = stripslashes($str);

        // ✅ Solo htmlspecialchars es necesario
        // ✅ mysqli_real_escape_string NO es necesario con PDO prepared statements
        return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Sanitiza un email.
     */
    public function sanitizeEmail(?string $email): string
    {
        if ($email === null || $email === '') {
            return '';
        }

        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }

    /**
     * Valida un email.
     */
    public function validateEmail(?string $email): bool
    {
        if ($email === null || $email === '') {
            return false;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Sanitiza una URL.
     */
    public function sanitizeUrl(?string $url): string
    {
        if ($url === null || $url === '') {
            return '';
        }

        $url = trim($url);
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return $url;
    }

    /**
     * Valida una URL.
     */
    public function validateUrl(?string $url): bool
    {
        if ($url === null || $url === '') {
            return false;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Sanitiza un número entero.
     */
    public function sanitizeInt(mixed $value): int
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitiza un número flotante.
     */
    public function sanitizeFloat(mixed $value): float
    {
        return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Sanitiza un array recursivamente.
     */
    public function sanitizeArray(array $input): array
    {
        $sanitized = [];
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value);
            } else {
                $sanitized[$key] = $this->secureStr((string) $value);
            }
        }
        return $sanitized;
    }

    /**
     * Valida que una cadena sea alfanumérica.
     */
    public function isAlphanumeric(?string $str): bool
    {
        if ($str === null || $str === '') {
            return false;
        }

        return ctype_alnum($str);
    }

    /**
     * Valida que una cadena sea numérica.
     */
    public function isNumeric(?string $str): bool
    {
        if ($str === null || $str === '') {
            return false;
        }

        return is_numeric($str);
    }

    /**
     * Escapa una cadena para usar en JSON.
     */
    public function escapeJson(?string $str): string
    {
        if ($str === null || $str === '') {
            return '';
        }

        return json_encode($str, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
}
