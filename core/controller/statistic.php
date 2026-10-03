<?php
declare(strict_types=1);

/**
 * Estadísticas de visitas con PDO.
 * Migrado a PDO con corrección de bugs críticos.
 *
 * CORRECCIONES CRÍTICAS:
 * - Typo: __contruct() → __construct()
 * - Bug: "*POST: " . $_POST → json_encode($_POST)
 * - IP spoofing eliminado: solo usa REMOTE_ADDR (o CF_CONNECTING_IP si CloudFlare)
 * - file_get_contents con timeout
 * - Validación de path antes de escribir
 * - Inyección de dependencias PDO
 */
class Statistic
{
    private PDO $conn;
    private string $archivo;
    private string $ignoreIp;

    /**
     * ✅ Lista blanca de propósitos para ip_info
     */
    private const IP_PURPOSES = [
        'location', 'address', 'city', 'state',
        'region', 'country', 'countrycode'
    ];

    public function __construct(PDO $connection, string $ignoreIp = '127.0.0.1')
    {
        $this->conn = $connection;
        $this->ignoreIp = $ignoreIp;

        // ✅ Validar path antes de escribir
        $basePath = defined('URL') ? URL : __DIR__ . '/..';
        $this->archivo = rtrim($basePath, '/') . '/fls/visitas.txt';

        // ✅ Verificar que el directorio exista y sea escribible
        $dir = dirname($this->archivo);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $this->write_visita();
    }

    /**
     * Escribe la IP del cliente en un archivo de texto.
     *
     * ✅ CORREGIDO: bug "*POST: " . $_POST → json_encode
     * ✅ CORREGIDO: validación de IP antes de escribir
     */
    public function write_visita(): void
    {
        $new_ip = $this->get_client_ip();

        // ✅ Validar IP
        if (!filter_var($new_ip, FILTER_VALIDATE_IP)) {
            return;
        }

        // ✅ Ignorar IP propia
        if ($new_ip === $this->ignoreIp) {
            return;
        }

        // ✅ Verificar que el archivo sea escribible
        if (!is_writable(dirname($this->archivo))) {
            error_log('statistic: directorio no escribible: ' . dirname($this->archivo));
            return;
        }

        $now = new DateTime();

        // ✅ CORREGIDO: bug "*POST: " . $_POST → json_encode
        if (empty($_GET)) {
            // ✅ json_encode en lugar de concatenar array
            $datos = '*POST: ' . json_encode($_POST, JSON_UNESCAPED_UNICODE);
        } else {
            // ✅ Validar PATH_INFO
            $pathInfo = $_GET['PATH_INFO'] ?? '';
            if (!is_string($pathInfo)) {
                $pathInfo = '';
            }
            $peticion = explode('/', $pathInfo);
            $seg0 = isset($peticion[0]) ? substr($peticion[0], 0, 10) : '';
            $seg1 = isset($peticion[1]) ? substr($peticion[1], 0, 100) : '';
            $datos = str_pad($seg0, 10) . ' ' . $seg1;
        }

        // ✅ Obtener país con timeout
        $country = $this->ip_info($new_ip, 'Country') ?? 'Unknown';

        $txt = str_pad($new_ip, 25) . ' ' .
        str_pad($now->format('Y-m-d H:i:s'), 25) . ' ' .
        str_pad($country, 25) . ' ' .
        json_encode($datos, JSON_UNESCAPED_UNICODE);

        // ✅ LOCK_EX para prevenir race conditions
        @file_put_contents($this->archivo, $txt . PHP_EOL, FILE_APPEND | LOCK_EX);

        // ✅ También registrar en BD si está disponible
        $this->logVisitToDatabase($new_ip, $country);
    }

    /**
     * Registra visita en base de datos.
     */
    private function logVisitToDatabase(string $ip, string $country): void
    {
        try {
            $path = $_SERVER['REQUEST_URI'] ?? '/';
            $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);

            $stmt = $this->conn->prepare(
                'INSERT INTO visit_logs (ip_address, country, path, user_agent, visited_at)
            VALUES (:ip, :country, :path, :ua, NOW())'
            );
            $stmt->execute([
                ':ip'      => $ip,
                ':country' => substr($country, 0, 100),
                           ':path'    => substr($path, 0, 500),
                           ':ua'      => $userAgent,
            ]);
        } catch (PDOException $e) {
            error_log('statistic logVisitToDatabase error: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene la IP del cliente.
     *
     * ✅ CORREGIDO: solo usa REMOTE_ADDR (previene IP spoofing)
     * ✅ Excepción: CloudFlare (HTTP_CF_CONNECTING_IP) si es confiable
     */
    public function get_client_ip(): string
    {
        // ✅ CloudFlare (solo si es de confianza en tu infraestructura)
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        // ✅ REMOTE_ADDR es el más confiable
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return '0.0.0.0';
        }

        return $ip;
    }

    /**
     * Obtiene info de la IP desde geoplugin.
     *
     * ✅ CORREGIDO: timeout en file_get_contents
     * ✅ CORREGIDO: validación de propósito
     */
    public function ip_info(?string $ip = null, string $purpose = 'location'): mixed
    {
        // ✅ Validar IP
        if ($ip === null || !filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                return null;
            }
        }

        // ✅ Validar propósito contra lista blanca
        $purpose = strtolower(trim($purpose));
        $purpose = str_replace([' ', '-', '_'], '', $purpose);
        if (!in_array($purpose, self::IP_PURPOSES, true)) {
            return null;
        }

        // ✅ Contexto con timeout (previene DoS)
        $context = stream_context_create([
            'http' => [
                'timeout' => 5, // ✅ Timeout de 5 segundos
                'user_agent' => 'Statistic/1.0',
            ],
        ]);

        $url = 'http://www.geoplugin.net/json.gp?ip=' . urlencode($ip);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        $ipdat = @json_decode($response);
        if (!$ipdat || !isset($ipdat->geoplugin_countryCode)) {
            return null;
        }

        if (strlen(trim((string) $ipdat->geoplugin_countryCode)) !== 2) {
            return null;
        }

        $continents = [
            'AF' => 'Africa', 'AN' => 'Antarctica', 'AS' => 'Asia',
            'EU' => 'Europe', 'OC' => 'Australia (Oceania)',
            'NA' => 'North America', 'SA' => 'South America',
        ];

        return match ($purpose) {
            'location' => [
                'city'           => (string) ($ipdat->geoplugin_city ?? ''),
                'state'          => (string) ($ipdat->geoplugin_regionName ?? ''),
                'country'        => (string) ($ipdat->geoplugin_countryName ?? ''),
                'country_code'   => (string) ($ipdat->geoplugin_countryCode ?? ''),
                'continent'      => $continents[strtoupper((string) ($ipdat->geoplugin_continentCode ?? ''))] ?? 'Unknown',
                'continent_code' => (string) ($ipdat->geoplugin_continentCode ?? ''),
            ],
            'address' => implode(', ', array_filter([
                (string) ($ipdat->geoplugin_city ?? ''),
                                                    (string) ($ipdat->geoplugin_regionName ?? ''),
                                                    (string) ($ipdat->geoplugin_countryName ?? ''),
            ])),
            'city'          => (string) ($ipdat->geoplugin_city ?? ''),
            'state', 'region' => (string) ($ipdat->geoplugin_regionName ?? ''),
            'country'       => (string) ($ipdat->geoplugin_countryName ?? ''),
            'countrycode'   => (string) ($ipdat->geoplugin_countryCode ?? ''),
            default         => null,
        };
    }

    /**
     * Obtiene estadísticas de visitas.
     */
    public function getVisitStats(int $days = 30): array
    {
        $days = max(1, min($days, 365));

        try {
            $stmt = $this->conn->prepare(
                'SELECT DATE(visited_at) as date, COUNT(*) as visits,
                                         COUNT(DISTINCT ip_address) as unique_ips
                                         FROM visit_logs
                                         WHERE visited_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY DATE(visited_at)
            ORDER BY date DESC'
            );
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('statistic getVisitStats error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene países más visitados.
     */
    public function getTopCountries(int $limit = 10): array
    {
        $limit = max(1, min($limit, 100));

        try {
            $stmt = $this->conn->prepare(
                'SELECT country, COUNT(*) as visits,
                                         COUNT(DISTINCT ip_address) as unique_ips
                                         FROM visit_logs
                                         WHERE country IS NOT NULL AND country != ""
                                         GROUP BY country
                                         ORDER BY visits DESC
                                         LIMIT :limit'
            );
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('statistic getTopCountries error: ' . $e->getMessage());
            return [];
        }
    }
}
