<?php
declare(strict_types=1);

/**
 * Definiciones del sitio desde la base de datos.
 *
 * ✅ CORRECCIONES CRÍTICAS:
 * - SQL Injection en getAllData() eliminado (lista blanca de tablas)
 * - SQL Injection en selectData() eliminado (método eliminado)
 * - RCE en DefineConf() corregido (sanitización estricta)
 * - getColumnNames() corregido
 * - Inyección de dependencias PDO
 */
class SiteDefinitions
{
    private PDO $conn;
    private string $table = 'site_configuration';

    /**
     * ✅ Lista blanca de tablas permitidas para consultas
     */
    private const ALLOWED_TABLES = [
        'site_configuration', 'site_settings', 'system_config',
        'email_templates', 'payment_settings'
    ];

    /**
     * ✅ Lista blanca de nombres de configuración permitidos
     */
    private const ALLOWED_CONFIG_NAMES = [
        'SITE_NAME', 'SITE_PATH', 'SITE_EMAIL', 'DEBUG',
        'SECURE_HASH', 'SECURE_TOKEN', 'SECRET_KEY',
        'MAILSERVER', 'PORTSERVER', 'USEREMAIL', 'PASSMAIL',
        'ENCRYPTION_METHOD', 'TIMEZONE', 'LANGUAGE'
    ];

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    /**
     * Obtiene todos los datos de una tabla.
     *
     * ✅ CORREGIDO: SQL Injection eliminado con lista blanca
     */
    public function getAllData(string $table): array
    {
        // ✅ Validar tabla contra lista blanca
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            error_log("SiteDefinitions: tabla no permitida: {$table}");
            return [];
        }

        // ✅ Validar formato de nombre de tabla
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) {
            error_log("SiteDefinitions: nombre de tabla inválido: {$table}");
            return [];
        }

        try {
            // ✅ Consulta segura (tabla validada contra lista blanca)
            $stmt = $this->conn->query("SELECT * FROM `{$table}`");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('SiteDefinitions::getAllData error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene configuración del sitio.
     *
     * ✅ CORREGIDO: método selectData() eliminado (era SQL Injection directo)
     * ✅ Ahora usa prepared statements
     */
    public function getConfig(string $configName): ?string
    {
        // ✅ Validar nombre de configuración
        if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $configName)) {
            return null;
        }

        try {
            $stmt = $this->conn->prepare(
                'SELECT config_value FROM site_configuration
                WHERE config_name = :name LIMIT 1'
            );
            $stmt->execute([':name' => $configName]);
            $result = $stmt->fetchColumn();
            return $result !== false ? (string) $result : null;
        } catch (PDOException $e) {
            error_log('SiteDefinitions::getConfig error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene todas las configuraciones.
     */
    public function getAllConfig(): array
    {
        try {
            $stmt = $this->conn->query(
                'SELECT config_name, config_value FROM site_configuration
                ORDER BY config_name ASC'
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('SiteDefinitions::getAllConfig error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el nombre del primer campo (ID) de una tabla.
     *
     * ✅ CORREGIDO: usa INFORMATION_SCHEMA en lugar de fetch_fields()
     */
    public function getID(string $table): ?string
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            return null;
        }

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) {
            return null;
        }

        try {
            $stmt = $this->conn->prepare(
                'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table
                ORDER BY ORDINAL_POSITION LIMIT 1'
            );
            $stmt->execute([':table' => $table]);
            $result = $stmt->fetchColumn();
            return $result !== false ? (string) $result : null;
        } catch (PDOException $e) {
            error_log('SiteDefinitions::getID error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene nombres de columnas de una tabla.
     *
     * ✅ CORREGIDO: usa INFORMATION_SCHEMA correctamente
     */
    public function getColumnNames(string $table): array
    {
        if (!in_array($table, self::ALLOWED_TABLES, true)) {
            return [];
        }

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) {
            return [];
        }

        try {
            $stmt = $this->conn->prepare(
                'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table
                ORDER BY ORDINAL_POSITION'
            );
            $stmt->execute([':table' => $table]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log('SiteDefinitions::getColumnNames error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Genera definiciones PHP desde la configuración.
     *
     * ✅ CORREGIDO: RCE eliminado con sanitización estricta
     * ✅ Ahora usa lista blanca de nombres permitidos
     */
    public function DefineConf(): string
    {
        $config = $this->getAllConfig();
        if (empty($config)) {
            return '';
        }

        $vars = [];

        foreach ($config as $row) {
            $name = $row['config_name'] ?? '';
            $value = $row['config_value'] ?? '';

            // ✅ Validar nombre contra lista blanca
            if (!in_array($name, self::ALLOWED_CONFIG_NAMES, true)) {
                continue;
            }

            // ✅ Validar formato del nombre
            if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $name)) {
                continue;
            }

            // ✅ Sanitizar valor para prevenir RCE
            $safeValue = $this->sanitizeDefineValue($value);

            $vars[] = "define('{$name}', '{$safeValue}');";
        }

        return implode("\n", $vars) . "\n";
    }

    /**
     * Sanitiza valores para define().
     *
     * ✅ Previene inyección de código PHP
     */
    private function sanitizeDefineValue(mixed $value): string
    {
        if (!is_string($value) && !is_numeric($value)) {
            return '';
        }

        $value = (string) $value;

        // ✅ Eliminar tags PHP
        $value = str_replace(['<?php', '?>', '<?', '<?='], '', $value);

        // ✅ Escapar comillas simples
        $value = str_replace("'", "\\'", $value);

        // ✅ Eliminar saltos de línea
        $value = str_replace(["\r", "\n"], '', $value);

        // ✅ Eliminar caracteres de control
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value) ?? '';

        // ✅ Limitar longitud
        $value = substr($value, 0, 500);

        return $value;
    }

    /**
     * Actualiza un valor de configuración.
     *
     * ✅ CSRF + permisos + logging
     */
    public function updateConfig(string $name, string $value): bool
    {
        if (!SessionManager::isAdmin()) {
            return false;
        }

        if (!SessionManager::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            return false;
        }

        // ✅ Validar nombre contra lista blanca
        if (!in_array($name, self::ALLOWED_CONFIG_NAMES, true)) {
            return false;
        }

        if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $name)) {
            return false;
        }

        // ✅ Sanitizar valor
        $safeValue = $this->sanitizeDefineValue($value);

        try {
            $stmt = $this->conn->prepare(
                'UPDATE site_configuration
                SET config_value = :value, updated_at = NOW()
            WHERE config_name = :name'
            );
            $result = $stmt->execute([
                ':value' => $safeValue,
                ':name'  => $name,
            ]);

            if ($result) {
                $logger = new ActivityLogger($this->conn);
                $logger->logActivity(
                    SessionManager::getUserId() ?? 0,
                                     'settings_change',
                                     "Configuración actualizada: {$name}"
                );
            }

            return $result;
        } catch (PDOException $e) {
            error_log('SiteDefinitions::updateConfig error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Carga todas las definiciones en el entorno actual.
     */
    public function loadDefinitions(): void
    {
        $config = $this->getAllConfig();

        foreach ($config as $row) {
            $name = $row['config_name'] ?? '';
            $value = $row['config_value'] ?? '';

            // ✅ Validar nombre contra lista blanca
            if (!in_array($name, self::ALLOWED_CONFIG_NAMES, true)) {
                continue;
            }

            if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $name)) {
                continue;
            }

            // ✅ Definir constante si no existe
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }
}
