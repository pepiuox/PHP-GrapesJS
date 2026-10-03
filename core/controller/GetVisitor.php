<?php
declare(strict_types=1);

/**
 * Gestor de visitantes con tracking de IP, páginas vistas y sesiones.
 */
class GetVisitor
{
    protected PDO $conn;
    protected string $getip;
    public string $baseurl;
    private string $timestamp;
    public DateTime $date;
    protected string $hash;
    protected string $token;
    private string $session = '';
    public GetCodeDeEncrypt $gc;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->date = new DateTime();
        $this->timestamp = $this->date->format('Y-m-d H:i:s');
        $this->hash = defined('SECURE_HASH') ? SECURE_HASH : '';
        $this->token = defined('SECURE_TOKEN') ? SECURE_TOKEN : '';
        $this->gc = new GetCodeDeEncrypt();

        $this->getip = $this->getUserIP();

        // Encriptar IP para sesión
        $this->session = $this->gc->ende_crypter(
            'encrypt',
            $this->getip,
            $this->hash,
            $this->token
        );
        $_SESSION['session_visit'] = $this->session;

        $this->visitUpdate($this->getip);

        if (!empty($this->session)) {
            $this->guestOnline();
        }
    }

    /**
     * Obtiene el número de páginas registradas.
     */
    public function numpages(): int
    {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM pages");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Verifica si una IP ya existe en la base de datos.
     */
    public function checkUserIP(string $ip): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM visitor WHERE ip = :ip"
        );
        $stmt->execute([':ip' => $ip]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Busca la última visita de una IP.
     */
    public function findUserIP(string $ip): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM visitor WHERE ip = :ip ORDER BY updated_at DESC LIMIT 1"
        );
        $stmt->execute([':ip' => $ip]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    /**
     * Actualiza o inserta la visita del usuario.
     */
    public function visitUpdate(string $ip): void
    {
        $row = $this->findUserIP($ip);

        if ($row !== null) {
            $startdate = $row['updated_at'];
            $enddate = $this->timestamp;
            $dif = $this->differenceInHours($startdate, $enddate);

            if ($dif >= 24) {
                $stmt = $this->conn->prepare(
                    "INSERT INTO visitor (ip) VALUES (:ip)"
                );
                $stmt->execute([':ip' => $this->getip]);
                $this->counterVisitor();
            } else {
                $stmt = $this->conn->prepare(
                    "UPDATE visitor SET updated_at = :enddate
                    WHERE ip = :ip AND updated_at = :startdate"
                );
                $stmt->execute([
                    ':enddate'   => $enddate,
                    ':ip'        => $this->getip,
                    ':startdate' => $startdate,
                ]);
            }
        } else {
            // Primera visita
            $this->conn->beginTransaction();
            try {
                $stmt = $this->conn->prepare(
                    "INSERT INTO visitor (ip) VALUES (:ip)"
                );
                $stmt->execute([':ip' => $this->getip]);

                $stmt = $this->conn->prepare(
                    "INSERT INTO active_guests (ip) VALUES (:ip)"
                );
                $stmt->execute([':ip' => $this->getip]);

                $this->conn->commit();
                $this->counterVisitor();
            } catch (PDOException $e) {
                $this->conn->rollBack();
                error_log('GetVisitor visitUpdate error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Obtiene la IP real del visitante de forma segura.
     *
     * 🔒 SEGURIDAD: Solo confía en headers de proxy si están configurados.
     * Previene IP spoofing mediante HTTP_X_FORWARDED_FOR falso.
     */
    public function getUserIP(): string
    {
        // CloudFlare
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])
            && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
            }

            // Si hay un proxy de confianza configurado
            $trustedProxies = defined('TRUSTED_PROXIES') ? TRUSTED_PROXIES : [];
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        if (in_array($remoteAddr, $trustedProxies, true)) {
            // Solo confiar en X-Forwarded-For si el proxy es de confianza
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $clientIp = trim($ips[0]);
                if (filter_var($clientIp, FILTER_VALIDATE_IP)) {
                    return $clientIp;
                }
            }
        }

        // Fallback: REMOTE_ADDR (la más confiable sin proxy)
        if (filter_var($remoteAddr, FILTER_VALIDATE_IP)) {
            return $remoteAddr;
        }

        return '0.0.0.0';
    }

    /**
     * Incrementa el contador global de visitas.
     */
    public function counterVisitor(): void
    {
        try {
            $this->conn->exec("UPDATE counter SET counter = counter + 1");
        } catch (PDOException $e) {
            error_log('GetVisitor counterVisitor error: ' . $e->getMessage());
        }
    }

    /**
     * Calcula la diferencia en horas entre dos fechas.
     */
    public function differenceInHours(string $startdate, string $enddate): float
    {
        $start = strtotime($startdate);
        $end = strtotime($enddate);

        if ($start === false || $end === false) {
            return 0.0;
        }

        return round(abs($end - $start) / 3600, 2);
    }

    /**
     * Registra la visita a una página específica.
     */
    public function pageViews(string $title): void
    {
        $stmt = $this->conn->prepare(
            "SELECT date_view FROM pageviews
            WHERE page = :page AND ip = :ip
            ORDER BY date_view DESC LIMIT 1"
        );
        $stmt->execute([
            ':page' => $title,
            ':ip'   => $this->getip,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $dif = $this->differenceInHours($row['date_view'], $this->timestamp);
            if ($dif < 24) {
                return; // Ya visitó esta página en las últimas 24h
            }
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO pageviews (page, ip) VALUES (:page, :ip)"
        );
        $stmt->execute([
            ':page' => $title,
            ':ip'   => $this->getip,
        ]);
    }

    /**
     * Registra/actualiza la sesión del visitante online.
     */
    public function guestOnline(): void
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM total_visitors WHERE session = :session"
        );
        $stmt->execute([':session' => $this->session]);
        $exists = (int) $stmt->fetchColumn() > 0;

        if (!$exists && $this->session !== '') {
            $stmt = $this->conn->prepare(
                "INSERT INTO total_visitors (session, time) VALUES (:session, :time)"
            );
            $stmt->execute([
                ':session' => $this->session,
                ':time'    => $this->timestamp,
            ]);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE total_visitors SET time = :time WHERE session = :session"
            );
            $stmt->execute([
                ':time'    => $this->timestamp,
                ':session' => $this->session,
            ]);
        }
    }

    /**
     * Obtiene el total de usuarios online en la última hora.
     */
    public function totalOnline(): int
    {
        $timeout = date('Y-m-d H:i:s', time() - 3600);

        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM total_visitors WHERE time >= :timeout"
        );
        $stmt->execute([':timeout' => $timeout]);
        return (int) $stmt->fetchColumn();
    }
}
