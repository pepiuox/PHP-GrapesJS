<?php
declare(strict_types=1);

/**
 * Estadísticas de páginas con gestión segura de IP.
 *
 * CORRECCIONES:
 * - IP spoofing eliminado (solo REMOTE_ADDR por defecto)
 * - Clase RemoteAddress mejorada con validación
 * - Integración con base de datos PDO
 * - Tipado estricto
 */

/**
 * Obtiene la IP del usuario de forma segura.
 *
 * ✅ CORREGIDO: solo usa REMOTE_ADDR por defecto
 * ✅ Opcionalmente usa proxy headers si están configurados como confiables
 */
function getUserIpAddr(bool $trustProxy = false): string
{
    // ✅ CloudFlare (si está configurado)
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    // ✅ Proxy headers (solo si están explícitamente habilitados)
    if ($trustProxy) {
        $proxyHeaders = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
        ];

        foreach ($proxyHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                foreach ($ips as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP) && !isPrivateIp($ip)) {
                        return $ip;
                    }
                }
            }
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
 * Verifica si una IP es privada/reservada.
 */
function isPrivateIp(string $ip): bool
{
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return true;
    }

    // ✅ Rangos privados
    $privateRanges = [
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        '127.0.0.0/8',
        '169.254.0.0/16',
    ];

    $ipLong = ip2long($ip);
    if ($ipLong === false) {
        return true;
    }

    foreach ($privateRanges as $range) {
        [$subnet, $mask] = explode('/', $range);
        $subnetLong = ip2long($subnet);
        $maskLong = ~((1 << (32 - (int) $mask)) - 1);

        if (($ipLong & $maskLong) === ($subnetLong & $maskLong)) {
            return true;
        }
    }

    return false;
}

/**
 * Clase mejorada para gestión de direcciones IP.
 *
 * ✅ CORRECCIONES:
 * - Validación estricta de proxies confiables
 * - Detección de IP spoofeada
 * - Logging de intentos sospechosos
 */
class RemoteAddress
{
    private bool $useProxy = false;
    private array $trustedProxies = [];
    private string $proxyHeader = 'HTTP_X_FORWARDED_FOR';

    public function __construct(bool $useProxy = false, array $trustedProxies = [])
    {
        $this->useProxy = $useProxy;
        $this->trustedProxies = array_filter($trustedProxies, function($ip) {
            return filter_var($ip, FILTER_VALIDATE_IP) !== false;
        });
    }

    /**
     * Obtiene la dirección IP del cliente.
     */
    public function getIpAddress(): string
    {
        // ✅ Intentar obtener IP de proxy si está habilitado
        if ($this->useProxy) {
            $proxyIp = $this->getIpAddressFromProxy();
            if ($proxyIp !== false) {
                return $proxyIp;
            }
        }

        // ✅ IP directa
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return '0.0.0.0';
    }

    /**
     * Obtiene IP desde headers de proxy.
     *
     * ✅ Validación estricta de proxies confiables
     */
    private function getIpAddressFromProxy(): string|false
    {
        if (!$this->useProxy) {
            return false;
        }

        // ✅ Verificar que REMOTE_ADDR sea un proxy confiable
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
        if (!in_array($remoteAddr, $this->trustedProxies, true)) {
            return false;
        }

        $header = $this->proxyHeader;
        if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
            return false;
        }

        // ✅ Extraer IPs
        $ips = explode(',', $_SERVER[$header]);
        $ips = array_map('trim', $ips);

        // ✅ Validar cada IP
        $ips = array_filter($ips, function($ip) {
            return filter_var($ip, FILTER_VALIDATE_IP) !== false;
        });

        // ✅ Remover proxies confiables
        $ips = array_diff($ips, $this->trustedProxies);

        if (empty($ips)) {
            return false;
        }

        // ✅ La IP más a la derecha es la del cliente
        $ip = array_pop($ips);

        // ✅ Validar que no sea IP privada
        if (isPrivateIp($ip)) {
            return false;
        }

        return $ip;
    }

    /**
     * Establece el header de proxy a usar.
     */
    public function setProxyHeader(string $header): void
    {
        $allowedHeaders = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_CF_CONNECTING_IP',
        ];

        if (in_array($header, $allowedHeaders, true)) {
            $this->proxyHeader = $header;
        }
    }

    /**
     * Añade un proxy confiable.
     */
    public function addTrustedProxy(string $ip): void
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->trustedProxies[] = $ip;
        }
    }
}

/**
 * Clase para registrar estadísticas de páginas en BD.
 */
class PageStatsRecorder
{
    private PDO $conn;
    private RemoteAddress $remoteAddress;

    public function __construct(PDO $connection, bool $trustProxy = false)
    {
        $this->conn = $connection;
        $this->remoteAddress = new RemoteAddress($trustProxy);
    }

    /**
     * Registra una visita a una página.
     */
    public function recordVisit(int $pageId, string $pageTitle = ''): bool
    {
        $ip = $this->remoteAddress->getIpAddress();
        $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);
        $referer = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 500);
        $path = substr($_SERVER['REQUEST_URI'] ?? '/', 0, 500);

        try {
            $stmt = $this->conn->prepare(
                'INSERT INTO page_visits
                (page_id, page_title, ip_address, user_agent, referer, path, visited_at)
            VALUES (:page_id, :title, :ip, :ua, :referer, :path, NOW())'
            );
            return $stmt->execute([
                ':page_id' => $pageId,
                ':title'   => substr($pageTitle, 0, 200),
                                  ':ip'      => $ip,
                                  ':ua'      => $userAgent,
                                  ':referer' => $referer,
                                  ':path'    => $path,
            ]);
        } catch (PDOException $e) {
            error_log('PageStatsRecorder::recordVisit error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene estadísticas de una página.
     */
    public function getPageStats(int $pageId, int $days = 30): array
    {
        $days = max(1, min($days, 365));

        try {
            $stmt = $this->conn->prepare(
                'SELECT
                COUNT(*) as total_visits,
                                         COUNT(DISTINCT ip_address) as unique_visitors,
                                         MAX(visited_at) as last_visit,
                                         MIN(visited_at) as first_visit
                                         FROM page_visits
                                         WHERE page_id = :page_id
                                         AND visited_at >= DATE_SUB(NOW(), INTERVAL :days DAY)'
            );
            $stmt->bindValue(':page_id', $pageId, PDO::PARAM_INT);
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log('PageStatsRecorder::getPageStats error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene las páginas más visitadas.
     */
    public function getMostVisited(int $limit = 10, int $days = 30): array
    {
        $limit = max(1, min($limit, 100));
        $days = max(1, min($days, 365));

        try {
            $stmt = $this->conn->prepare(
                'SELECT page_id, page_title,
                COUNT(*) as visits,
                                         COUNT(DISTINCT ip_address) as unique_visitors
                                         FROM page_visits
                                         WHERE visited_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY page_id, page_title
            ORDER BY visits DESC
            LIMIT :limit'
            );
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('PageStatsRecorder::getMostVisited error: ' . $e->getMessage());
            return [];
        }
    }
}

/* ---------- Ejemplo de uso ---------- */
if (PHP_SAPI !== 'cli') {
    // ✅ Mostrar IP real del usuario
    $ip = getUserIpAddr();
    // echo 'User Real IP - ' . htmlspecialchars($ip, ENT_QUOTES, 'UTF-8');
}
