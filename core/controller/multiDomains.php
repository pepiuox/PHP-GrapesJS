<?php
declare(strict_types=1);

/**
 * Gestor de dominios múltiples con detección automática de URL base.
 */
class MultiDomains
{
    protected PDO $conn;
    private ?array $cachedDomain = null;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Construye la URL base del sitio.
     *
     * @param bool $atRoot   Si true, retorna la raíz del dominio
     * @param bool $atCore   Si true, incluye el directorio core
     * @param bool $parse    Si true, retorna array parseado
     * @return string|array  URL base o array parseado
     */
    public function baseUrl(bool $atRoot = false, bool $atCore = false, bool $parse = false)
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            $baseUrl = 'http://localhost/';
            return $parse ? parse_url($baseUrl) : $baseUrl;
        }

        $http = $this->isSecureConnection() ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $dir = str_replace(basename($_SERVER['SCRIPT_NAME'] ?? ''), '', $_SERVER['SCRIPT_NAME'] ?? '');

        $coreParts = preg_split(
            '@/@',
            str_replace($_SERVER['DOCUMENT_ROOT'] ?? '', '', realpath(dirname(__FILE__)) ?: ''),
                                -1,
                                PREG_SPLIT_NO_EMPTY
        );
        $core = $coreParts[0] ?? '';

        if ($atRoot) {
            $baseUrl = $atCore
            ? sprintf('%s://%s/%s/', $http, $hostname, $core)
            : sprintf('%s://%s/', $http, $hostname);
        } else {
            $baseUrl = $atCore
            ? sprintf('%s://%s/%s/', $http, $hostname, $core)
            : sprintf('%s://%s%s', $http, $hostname, $dir);
        }

        if ($parse) {
            $parsed = parse_url($baseUrl);
            if (isset($parsed['path']) && $parsed['path'] === '/') {
                $parsed['path'] = '';
            }
            return $parsed;
        }

        return $baseUrl;
    }

    /**
     * Verifica si la conexión es HTTPS.
     */
    private function isSecureConnection(): bool
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        }
        if (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) {
            return true;
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])
            && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
            }
            return false;
    }

    /**
     * Verifica si el dominio actual está registrado en la base de datos.
     *
     * @return array|null Datos del dominio o null si no existe
     */
    public function checkDomain(): ?array
    {
        // Cachear resultado para evitar múltiples consultas
        if ($this->cachedDomain !== null) {
            return $this->cachedDomain;
        }

        $domainName = $this->baseUrl(true);

        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM domains WHERE domain_name = :domain_name LIMIT 1"
            );
            $stmt->execute([':domain_name' => $domainName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->cachedDomain = $result !== false ? $result : null;
            return $this->cachedDomain;
        } catch (PDOException $e) {
            error_log('MultiDomains checkDomain error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene la configuración del dominio actual.
     */
    public function getDomainConfig(): array
    {
        $domain = $this->checkDomain();

        if ($domain === null) {
            return [
                'active' => false,
                'theme' => 'default',
                'language' => 'es',
            ];
        }

        return [
            'active' => (bool) ($domain['active'] ?? true),
            'theme' => $domain['theme'] ?? 'default',
            'language' => $domain['language'] ?? 'es',
            'settings' => $domain,
        ];
    }

    /**
     * Lista todos los dominios registrados.
     */
    public function getAllDomains(): array
    {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM domains ORDER BY domain_name ASC"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('MultiDomains getAllDomains error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Registra un nuevo dominio.
     */
    public function registerDomain(string $domainName, array $config = []): array
    {
        // Validar formato de dominio
        $domainName = rtrim(trim($domainName), '/') . '/';

        if (!filter_var($domainName, FILTER_VALIDATE_URL)) {
            return ['success' => false, 'error' => 'Invalid domain format'];
        }

        try {
            // Verificar si ya existe
            $stmt = $this->conn->prepare(
                "SELECT id FROM domains WHERE domain_name = :domain_name LIMIT 1"
            );
            $stmt->execute([':domain_name' => $domainName]);

            if ($stmt->fetch() !== false) {
                return ['success' => false, 'error' => 'Domain already registered'];
            }

            // Insertar nuevo dominio
            $stmt = $this->conn->prepare(
                "INSERT INTO domains (domain_name, theme, language, active, created_at)
            VALUES (:domain_name, :theme, :language, :active, NOW())"
            );
            $stmt->execute([
                ':domain_name' => $domainName,
                ':theme' => $config['theme'] ?? 'default',
                ':language' => $config['language'] ?? 'es',
                ':active' => $config['active'] ?? 1,
            ]);

            return [
                'success' => true,
                'id' => (int) $this->conn->lastInsertId(),
            ];
        } catch (PDOException $e) {
            error_log('MultiDomains registerDomain error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Database error'];
        }
    }
}
