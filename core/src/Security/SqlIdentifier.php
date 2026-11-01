<?php
declare(strict_types=1);

/**
 * Validación y escapado de IDENTIFICADORES SQL (tablas, columnas, alias).
 *
 * Un identificador (nombre de tabla/columna) **no se puede parametrizar** con
 * placeholders: `SELECT * FROM ?` no es SQL válido. Por eso, cuando el nombre de una
 * tabla o columna viene de fuera (POST/GET/BD de metadatos), hay que:
 *
 *   1. validar contra un patrón estricto, y
 *   2. entrecomillarlo con backticks.
 *
 * Esta clase centraliza esa única responsabilidad para no repetir (ni inventar)
 * validaciones distintas en cada archivo.
 *
 * Uso típico:
 *
 *     $tbl = SqlIdentifier::assert($_POST['tbl']);          // lanza si no es válido
 *     $sql = 'SELECT * FROM ' . SqlIdentifier::quote($tbl) . ' WHERE id = :id';
 *     $stmt = $conn->prepare($sql);
 *     $stmt->execute([':id' => $id]);
 *
 * Para listas dinámicas (p. ej. columnas de un `INSERT`), usar `fromWhitelist()`
 * con las columnas reales leídas del esquema o de una lista permitida.
 *
 * @see docs/SECURITY.md §3.1
 */

namespace PepiuoX\Security;

use InvalidArgumentException;

final class SqlIdentifier
{
    /** Un identificador simple: empieza por letra o `_` y sigue con alfanuméricos o `_`. */
    public const PATTERN = '/^[A-Za-z_][A-Za-z0-9_]*\z/';

    /**
     * Nombre de BASE DE DATOS.
     *
     * MySQL permite nombres de base de datos con guiones y `$` (p. ej. `mi-cms`),
     * siempre que vayan entrecomillados con backticks. El patrón estricto de
     * identificador los rechazaría, así que aquí se usa uno algo más permisivo que
     * sigue excluyendo todo lo peligroso (backticks, comillas, espacios, `;`, nulos…).
     */
    public const DATABASE_PATTERN = '/^[A-Za-z0-9_$][A-Za-z0-9_$-]*\z/';

    /** Longitud máxima razonable (límite de MySQL para nombres es 64). */
    private const MAX_LENGTH = 64;

    /**
     * ¿Es un identificador SQL seguro?
     */
    public static function isValid(string $name): bool
    {
        return $name !== ''
            && strlen($name) <= self::MAX_LENGTH
            && preg_match(self::PATTERN, $name) === 1;
    }

    /**
     * Valida un identificador o lanza excepción.
     *
     * @throws InvalidArgumentException
     */
    public static function assert(string $name): string
    {
        if (!self::isValid($name)) {
            throw new InvalidArgumentException('Identificador SQL inválido.');
        }

        return $name;
    }

    /**
     * Valida que el identificador esté en una lista blanca y lo devuelve.
     *
     * Es la opción recomendada cuando los posibles valores son finitos
     * (columnas conocidas, tablas del CMS, `ORDER BY` permitidos…).
     *
     * @param list<string> $allowed
     * @throws InvalidArgumentException
     */
    public static function fromWhitelist(string $name, array $allowed): string
    {
        if (!in_array($name, $allowed, true)) {
            throw new InvalidArgumentException('Identificador SQL no permitido.');
        }

        return $name;
    }

    /**
     * Entrecomilla un identificador simple: `columna`.
     */
    public static function quote(string $name): string
    {
        return '`' . self::assert($name) . '`';
    }

    /**
     * ¿Es un nombre de base de datos seguro (admite guiones y `$`)?
     */
    public static function isValidDatabaseName(string $name): bool
    {
        return $name !== ''
            && strlen($name) <= 64
            && preg_match(self::DATABASE_PATTERN, $name) === 1;
    }

    /**
     * Entrecomilla un nombre de BASE DE DATOS para `CREATE DATABASE`: `mi-cms`.
     *
     * @throws InvalidArgumentException
     */
    public static function quoteDatabase(string $name): string
    {
        if (!self::isValidDatabaseName($name)) {
            throw new InvalidArgumentException('Nombre de base de datos inválido.');
        }

        return '`' . $name . '`';
    }

    /**
     * Entrecomilla cada parte de un nombre cualificado: `tabla`.`columna`.
     *
     * Admite `tabla.columna` y también `*` como última parte (para `SELECT tabla.*`).
     */
    public static function quoteQualified(string $name): string
    {
        $parts = explode('.', $name);
        $quoted = [];

        foreach ($parts as $index => $part) {
            $isLast = $index === count($parts) - 1;

            if ($isLast && $part === '*') {
                $quoted[] = '*';
                continue;
            }

            $quoted[] = self::quote($part);
        }

        return implode('.', $quoted);
    }

    /**
     * Convierte una lista de identificadores en una lista entrecomillada.
     *
     * @param list<string> $names
     * @return list<string>
     */
    public static function quoteList(array $names): array
    {
        return array_map(static fn (string $n): string => self::quote($n), $names);
    }

    /**
     * Filtra una lista dejando sólo identificadores válidos (y no vacíos).
     *
     * Útil para normalizar `explode(',', $_POST['cols'])`.
     *
     * @param list<string> $names
     * @return list<string>
     */
    public static function filter(array $names): array
    {
        return array_values(array_filter(
            array_map('trim', $names),
            static fn (string $n): bool => self::isValid($n)
        ));
    }
}
