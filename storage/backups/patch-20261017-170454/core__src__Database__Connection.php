<?php
declare(strict_types=1);

/**
 * Conexión PDO canónica (singleton) y helpers de acceso a datos.
 *
 * Código nuevo con namespace. Mantiene **exactamente** la misma API pública que la
 * antigua clase global `Database` (`core/config/Database.php`), que ahora es un shim
 * con `class_alias()` hacia esta clase. Por eso todo el código existente
 * (`new Database()`, `$link->getConnection()`, `$link->select()`, …) sigue funcionando.
 *
 * Mejoras respecto a la versión anterior:
 * - El objeto `PDO` es realmente **compartido** (antes cada `new Database()` abría una
 *   conexión nueva; había 28 puntos que instanciaban la clase).
 * - `charset` validado con lista blanca y por defecto `utf8mb4`.
 * - Los mensajes de error nunca exponen credenciales ni el DSN al usuario final.
 * - Tipado estricto y propiedades promovidas/readonly.
 *
 * @see docs/PDO-MIGRATION.md §1 y §10
 */

namespace PepiuoX\Database;

use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

final class Connection
{
    /** Conexión compartida por todo el proceso. */
    private static ?PDO $pdo = null;

    /** @var array<string,mixed> Configuración cargada de config/server.php */
    private array $config = [];

    /** Charsets permitidos (evita inyección en el DSN). */
    private const ALLOWED_CHARSETS = ['utf8mb4', 'utf8', 'latin1', 'ascii'];

    /**
     * Opciones PDO por defecto.
     *
     * - `ERRMODE_EXCEPTION`: los errores lanzan `PDOException` (nunca fallan en silencio).
     * - `FETCH_ASSOC`: `$row['columna']` funciona directamente.
     * - `EMULATE_PREPARES = false`: prepares nativos reales.
     * - `STRINGIFY_FETCHES = false`: los tipos numéricos llegan como int/float.
     */
    private const PDO_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ];

    public function __construct()
    {
        $this->config = self::loadSettings();
    }

    /**
     * Carga la configuración de conexiones desde `core/config/server.php`.
     *
     * `server.php` (y el que genera el instalador) **define la variable `$settings`**
     * en lugar de retornar el array, por eso se pre-declara aquí: `require` se ejecuta
     * en el ámbito actual y la asignación queda en `$settings`.
     *
     * @return array<string,mixed>
     */
    private static function loadSettings(): array
    {
        $file = dirname(__DIR__, 2) . '/config/server.php';

        if (!is_file($file)) {
            throw new RuntimeException('No se encontró el archivo de configuración de la base de datos.');
        }

        // Estilo 1: server.php asigna $settings.
        $settings = '';

        // Estilo 2: server.php hace `return [...]`.
        $returned = require $file;

        if (is_array($returned) && $returned !== []) {
            $settings = $returned;
        }

        if (!is_array($settings) || $settings === []) {
            throw new RuntimeException('La configuración de la base de datos está vacía o es inválida.');
        }

        return $settings;
    }

    /**
     * Devuelve la conexión PDO compartida (la crea la primera vez).
     */
    public function getConnection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $default = $this->config['default-connection'] ?? null;
        $connections = $this->config['connections'] ?? [];

        if ($default === null || !isset($connections[$default])) {
            throw new RuntimeException('La conexión por defecto no está definida en la configuración.');
        }

        $data = $connections[$default];
        $host = (string) ($data['server'] ?? 'localhost');
        $dbname = (string) ($data['database'] ?? '');
        $user = (string) ($data['username'] ?? '');
        $pass = (string) ($data['password'] ?? '');
        $port = (int) ($data['port'] ?? 3306);
        $charset = self::sanitizeCharset((string) ($data['charset'] ?? 'utf8mb4'));

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset};port={$port}";

        try {
            self::$pdo = new PDO($dsn, $user, $pass, self::PDO_OPTIONS);
            // Zona horaria fija para que NOW()/CURRENT_TIMESTAMP sean predecibles.
            self::$pdo->exec("SET time_zone = '+00:00'");
        } catch (PDOException $exception) {
            // El detalle va al log; al usuario nunca se le muestran credenciales ni DSN.
            error_log('Error de conexión a la base de datos: ' . $exception->getMessage());

            throw new RuntimeException(
                (defined('DEBUG') && DEBUG)
                    ? 'Error de conexión: ' . $exception->getMessage()
                    : 'Error de conexión a la base de datos. Por favor, intente más tarde.'
            );
        }

        return self::$pdo;
    }

    /**
     * Alias de compatibilidad (API antigua).
     */
    public function PdoConnection(string $db = ''): PDO
    {
        return $this->getConnection();
    }

    /**
     * Comprueba que la conexión responde.
     */
    public function testConnection(): bool
    {
        try {
            $this->getConnection()->query('SELECT 1');

            return true;
        } catch (\Throwable $e) {
            error_log('Error en testConnection: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Versión, charset y collation del servidor.
     *
     * @return array<string,string>
     */
    public function getDatabaseInfo(): array
    {
        try {
            $conn = $this->getConnection();

            return [
                'version' => (string) $conn->query('SELECT VERSION()')->fetchColumn(),
                'charset' => (string) $conn->query("SHOW VARIABLES LIKE 'character_set_database'")->fetchColumn(1),
                'collation' => (string) $conn->query("SHOW VARIABLES LIKE 'collation_database'")->fetchColumn(1),
            ];
        } catch (PDOException $e) {
            error_log('Error obteniendo información de la base de datos: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Contenido de una página del CMS.
     *
     * @return array<string,mixed>|string
     */
    public function getPageContent(int $id): array|string
    {
        try {
            $stmt = $this->getConnection()->prepare(
                'SELECT id, title, slug, content_css, content_html, status FROM pages WHERE id = :id'
            );
            $stmt->execute([':id' => $id]);
            $page = $stmt->fetch();

            if ($page === false) {
                return '<div class="container"><h1>Página no encontrada</h1></div>';
            }

            return $page;
        } catch (PDOException $e) {
            error_log('Error al cargar página: ' . $e->getMessage());

            return '<div class="container"><h1>Error al cargar la página</h1></div>';
        }
    }

    /**
     * Ejecuta un SELECT y devuelve todas las filas.
     *
     * @param array<string|int,mixed> $params
     * @return list<array<string,mixed>>
     */
    public function select(string $query = '', array $params = []): array
    {
        return $this->executeStatement($query, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ejecuta INSERT/UPDATE/DELETE y devuelve el número de filas afectadas.
     *
     * @param array<string|int,mixed> $params
     */
    public function execute(string $query = '', array $params = []): int
    {
        return $this->executeStatement($query, $params)->rowCount();
    }

    /**
     * Prepara y ejecuta una sentencia.
     *
     * @param array<string|int,mixed> $params
     */
    private function executeStatement(string $query = '', array $params = []): PDOStatement
    {
        $stmt = $this->getConnection()->prepare($query);

        if ($stmt === false) {
            throw new RuntimeException('No se pudo preparar la consulta.');
        }

        $stmt->execute($params);

        return $stmt;
    }

    public function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->getConnection()->commit();
    }

    public function rollBack(): bool
    {
        return $this->getConnection()->rollBack();
    }

    public function inTransaction(): bool
    {
        return $this->getConnection()->inTransaction();
    }

    /**
     * Sólo admite charsets de la lista blanca.
     */
    private static function sanitizeCharset(string $charset): string
    {
        $charset = strtolower(trim($charset));

        return in_array($charset, self::ALLOWED_CHARSETS, true) ? $charset : 'utf8mb4';
    }
}
