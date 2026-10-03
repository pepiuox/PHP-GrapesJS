<?php
declare(strict_types=1);

/**
 * Manejador de sesiones personalizado con PDO.
 *
 * CORRECCIONES CRÍTICAS:
 * - Typo: setsession_set_save_handler → session_set_save_handler
 * - SQL Injection eliminado en read(), write(), destroy()
 * - MySQLi → PDO
 * - Prepared statements
 * - Validación de inputs
 * - Tipado estricto
 */
class SessionClass
{
    private PDO $conn;
    private static ?SessionClass $_instance = null;

    public static function getInstance(): SessionClass
    {
        if (!(self::$_instance instanceof SessionClass)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(?PDO $db = null)
    {
        if ($db === null) {
            throw new InvalidArgumentException('Conexión PDO requerida');
        }

        $this->conn = $db;

        // ✅ BUG CORREGIDO: setsession_set_save_handler → session_set_save_handler
        session_set_save_handler(
            [$this, "open"],
            [$this, "close"],
            [$this, "read"],
            [$this, "write"],
            [$this, "destroy"],
            [$this, "gc"]
        );

        // ✅ Crear tabla si no existe
        $this->createTable();

        // ✅ Registrar shutdown function para cerrar sesión
        register_shutdown_function('session_write_close');
    }

    private function createTable(): void
    {
        $createTable = "CREATE TABLE IF NOT EXISTS `setsession` (
            `ssID` VARCHAR(128) NOT NULL,
            `data` MEDIUMBLOB,
            `timestamp` INT UNSIGNED NOT NULL,
            `ip` VARCHAR(45) NOT NULL,
            PRIMARY KEY (`ssID`),
            KEY `idx_timestamp` (`timestamp`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->conn->exec($createTable);
    }

    public function __destruct()
    {
        session_write_close();
    }

    public function open(string $path, string $id): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    /**
     * Lee datos de sesión.
     *
     * ✅ CORREGIDO: SQL Injection eliminado, ahora usa prepared statements
     */
    public function read(string $id): string
    {
        // ✅ Validar formato de ID de sesión
        if (!preg_match('/^[a-zA-Z0-9,-]{22,256}$/', $id)) {
            return '';
        }

        try {
            // ✅ Prepared statement en lugar de interpolación
            $query = "SELECT data FROM setsession WHERE ssID = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() === 0) {
                // ✅ Insertar nueva sesión
                $timestamp = time();
                $ip = filter_var($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', FILTER_VALIDATE_IP) ?: '0.0.0.0';

                $insert = $this->conn->prepare(
                    "INSERT INTO setsession (ssID, timestamp, ip) VALUES (:id, :ts, :ip)"
                );
                $insert->execute([
                    ':id' => $id,
                    ':ts' => $timestamp,
                    ':ip' => $ip
                ]);

                return '';
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // ✅ Actualizar timestamp
            $update = $this->conn->prepare(
                "UPDATE setsession SET timestamp = :ts WHERE ssID = :id"
            );
            $update->execute([
                ':ts' => time(),
                             ':id' => $id
            ]);

            return (string) ($row['data'] ?? '');

        } catch (PDOException $e) {
            error_log('SessionClass::read error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Escribe datos de sesión.
     *
     * ✅ CORREGIDO: SQL Injection eliminado, ahora usa prepared statements
     */
    public function write(string $id, string $data): bool
    {
        // ✅ Validar formato de ID de sesión
        if (!preg_match('/^[a-zA-Z0-9,-]{22,256}$/', $id)) {
            return false;
        }

        try {
            $ip = filter_var($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', FILTER_VALIDATE_IP) ?: '0.0.0.0';
            $timestamp = time();

            // ✅ Prepared statement en lugar de interpolación
            $query = "REPLACE INTO setsession (ssID, data, ip, timestamp)
            VALUES (:id, :data, :ip, :ts)";
            $stmt = $this->conn->prepare($query);

            return $stmt->execute([
                ':id'   => $id,
                ':data' => $data,
                ':ip'   => $ip,
                ':ts'   => $timestamp
            ]);

        } catch (PDOException $e) {
            error_log('SessionClass::write error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Destruye una sesión.
     *
     * ✅ CORREGIDO: SQL Injection eliminado, ahora usa prepared statements
     */
    public function destroy(string $id): bool
    {
        // ✅ Validar formato de ID de sesión
        if (!preg_match('/^[a-zA-Z0-9,-]{22,256}$/', $id)) {
            return false;
        }

        try {
            // ✅ Prepared statement en lugar de interpolación
            $query = "DELETE FROM setsession WHERE ssID = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);

            return $stmt->rowCount() === 1;

        } catch (PDOException $e) {
            error_log('SessionClass::destroy error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Garbage collection.
     */
    public function gc(int $lifetime): bool
    {
        try {
            $query = "DELETE FROM setsession WHERE :now - timestamp > :lifetime";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':now'      => time(),
                           ':lifetime' => $lifetime
            ]);

            return true;

        } catch (PDOException $e) {
            error_log('SessionClass::gc error: ' . $e->getMessage());
            return false;
        }
    }
}
