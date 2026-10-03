<?php
declare(strict_types=1);

/**
 * Configuración de tablas.
 * Migrado de MySQLi a PDO.
 *
 * CORRECCIONES:
 * - MySQLi → PDO
 * - Validación de nombre de tabla
 * - Lista blanca de tablas permitidas
 * - Sanitización de salida
 * - Tipado estricto
 */
class TableSettings
{
    private PDO $conn;

    /**
     * ✅ Lista blanca de tablas permitidas
     */
    private const ALLOWED_TABLES = [
        'products', 'categories', 'subcategories', 'brands',
        'customers', 'suppliers', 'orders', 'order_items',
        'users', 'pages', 'templates', 'settings'
    ];

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    /**
     * Verifica si un valor es 1 (true).
     */
    public function checkList(mixed $value): bool
    {
        return $value === 1 || $value === '1' || $value === true;
    }

    public function checkView(mixed $value): bool
    {
        return $this->checkList($value);
    }

    public function checkAdd(mixed $value): bool
    {
        return $this->checkList($value);
    }

    public function checkUpdate(mixed $value): bool
    {
        return $this->checkList($value);
    }

    public function checkDelete(mixed $value): bool
    {
        return $this->checkList($value);
    }

    public function checkSecure(mixed $value): bool
    {
        return $this->checkList($value);
    }

    /**
     * Obtiene la configuración de una tabla.
     *
     * ✅ CORREGIDO: ahora usa PDO prepared statements
     * ✅ Validación de nombre de tabla contra lista blanca
     */
    public function tblSettings(string $tname): ?string
    {
        // ✅ Validar tabla contra lista blanca
        if (!in_array($tname, self::ALLOWED_TABLES, true)) {
            error_log("TableSettings: tabla no permitida: {$tname}");
            return null;
        }

        // ✅ Validar formato de nombre de tabla
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tname)) {
            error_log("TableSettings: nombre de tabla inválido: {$tname}");
            return null;
        }

        try {
            // ✅ Prepared statement (previene SQL Injection)
            $stmt = $this->conn->prepare(
                'SELECT * FROM table_settings WHERE table_name = :table LIMIT 1'
            );
            $stmt->execute([':table' => $tname]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // ✅ JSON con flags de seguridad
                return json_encode($row, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
            }

            return null;

        } catch (PDOException $e) {
            error_log('TableSettings::tblSettings error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene todas las configuraciones de tablas.
     */
    public function getAllSettings(): array
    {
        try {
            $stmt = $this->conn->query(
                'SELECT * FROM table_settings ORDER BY table_name ASC, col_order ASC'
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('TableSettings::getAllSettings error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene configuración de una tabla específica.
     */
    public function getTableConfig(string $tname): array
    {
        if (!in_array($tname, self::ALLOWED_TABLES, true)) {
            return [];
        }

        try {
            $stmt = $this->conn->prepare(
                'SELECT * FROM table_settings WHERE table_name = :table ORDER BY col_order ASC'
            );
            $stmt->execute([':table' => $tname]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('TableSettings::getTableConfig error: ' . $e->getMessage());
            return [];
        }
    }
}
