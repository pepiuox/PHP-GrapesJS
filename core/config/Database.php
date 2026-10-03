<?php
declare(strict_types=1);

/**
 * Clase de conexión a base de datos usando PDO exclusivamente.
 * Migrado completamente de MySQLi a PDO.
 */
class Database
{
    private array $config;
    private string $host;
    private string $dbnm;
    private string $user;
    private string $pass;
    private int $port;
    private string $charset;
    private ?PDO $conn = null;
    private array $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];

    public function __construct()
    {
        $settings = '';
        require_once __DIR__ . '/server.php';
        $this->config = $settings;
    }

    /**
     * Obtiene la conexión PDO (singleton pattern).
     */
    public function getConnection(): PDO
    {
        if ($this->conn !== null) {
            return $this->conn;
        }

        $default = $this->config['default-connection'];
        $data = $this->config["connections"][$default];

        $this->host = $data['server'];
        $this->dbnm = $data['database'];
        $this->user = $data['username'];
        $this->pass = $data['password'];
        $this->port = (int) $data['port'];
        $this->charset = $data['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$this->host};dbname={$this->dbnm};charset={$this->charset};port={$this->port}";

        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $this->options);
            $this->conn->exec("SET time_zone = '+00:00'");
        } catch (PDOException $exception) {
            error_log("Error de conexión a la base de datos: " . $exception->getMessage());
            if (defined('DEBUG') && DEBUG) {
                throw new RuntimeException("Error de conexión: " . $exception->getMessage());
            } else {
                throw new RuntimeException("Error de conexión a la base de datos. Por favor, intente más tarde.");
            }
        }

        return $this->conn;
    }

    /**
     * Obtiene una conexión PDO (alias para compatibilidad).
     */
    public function PdoConnection(string $db = ''): PDO
    {
        return $this->getConnection();
    }

    /**
     * Prueba la conexión a la base de datos.
     */
    public function testConnection(): bool
    {
        try {
            $conn = $this->getConnection();
            $conn->query("SELECT 1");
            return true;
        } catch (Exception $e) {
            error_log("Error en testConnection: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene información de la base de datos.
     */
    public function getDatabaseInfo(): array
    {
        try {
            $conn = $this->getConnection();
            $info = [];

            $stmt = $conn->query("SELECT VERSION() as version");
            $info['version'] = $stmt->fetchColumn();

            $stmt = $conn->query("SHOW VARIABLES LIKE 'character_set_database'");
            $info['charset'] = $stmt->fetchColumn(1);

            $stmt = $conn->query("SHOW VARIABLES LIKE 'collation_database'");
            $info['collation'] = $stmt->fetchColumn(1);

            return $info;
        } catch (PDOException $e) {
            error_log("Error obteniendo información de la base de datos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el contenido de una página.
     */
    public function getPageContent(int $id): array|string
    {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare("SELECT id, title, slug, content_css, content_html, status FROM pages WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $page = $stmt->fetch();

            if (!$page) {
                return '<div class="container"><h1>Página no encontrada</h1></div>';
            }

            return $page;
        } catch (PDOException $e) {
            error_log("Error al cargar página: " . $e->getMessage());
            return '<div class="container"><h1>Error al cargar la página</h1></div>';
        }
    }

    /**
     * Ejecuta una consulta SELECT y devuelve todos los resultados.
     *
     * ✅ MIGRADO A PDO: ahora usa PDO en lugar de MySQLi
     */
    public function select(string $query = "", array $params = []): array
    {
        try {
            $conn = $this->getConnection();
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            error_log("Error en select: " . $e->getMessage());
            throw new RuntimeException("Error en la consulta: " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta y devuelve el statement.
     *
     * ✅ MIGRADO A PDO: ahora usa PDO en lugar de MySQLi
     */
    private function executeStatement(string $query = "", array $params = []): PDOStatement
    {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                throw new RuntimeException("Unable to prepare statement: " . $query);
            }

            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }

            return $stmt;
        } catch (Exception $e) {
            error_log("Error en executeStatement: " . $e->getMessage());
            throw new RuntimeException("Error en la consulta: " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta INSERT/UPDATE/DELETE y devuelve el número de filas afectadas.
     */
    public function execute(string $query = "", array $params = []): int
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log("Error en execute: " . $e->getMessage());
            throw new RuntimeException("Error en la consulta: " . $e->getMessage());
        }
    }

    /**
     * Obtiene el último ID insertado.
     */
    public function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Inicia una transacción.
     */
    public function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * Confirma una transacción.
     */
    public function commit(): bool
    {
        return $this->getConnection()->commit();
    }

    /**
     * Revierte una transacción.
     */
    public function rollBack(): bool
    {
        return $this->getConnection()->rollBack();
    }
}
