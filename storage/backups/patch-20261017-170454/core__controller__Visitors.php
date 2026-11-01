<?php
declare(strict_types=1);

/**
 * Gestión de visitantes, sesiones activas y contador de páginas.
 * Migrado de MySQLi a PDO con inyección de dependencias.
 */
class Visitors
{
    private PDO $db;
    private string $baseurl;
    private string $timestamp;
    private string $hash;
    private string $token;
    private string $userIp;
    private string $session;

    public function __construct(PDO $connection, string $baseUrl = '')
    {
        $this->db        = $connection;
        $this->baseurl   = $baseUrl;
        $this->timestamp = (new DateTime())->format('Y-m-d H:i:s');
        $this->hash      = defined('SECURE_HASH')  ? SECURE_HASH  : '';
        $this->token     = defined('SECURE_TOKEN') ? SECURE_TOKEN : '';
        $this->userIp    = $this->resolveUserIp();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['session_visit'] = $this->endeCrypter(
            'encrypt', $this->userIp, $this->hash, $this->token
        );
        $this->session = (string) $_SESSION['session_visit'];

        $this->visitUpdate($this->userIp);

        if ($this->session !== '') {
            $this->guestOnline();
        }
    }

    /* ---------- Contadores ---------- */
    public function numPages(): int
    {
        return (int) $this->db->query("SELECT COUNT(id) FROM pages")->fetchColumn();
    }

    public function numVisitor(): int
    {
        return (int) $this->db->query("SELECT COUNT(ip) FROM active_guests")->fetchColumn();
    }

    public function numUsers(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(id) FROM users WHERE verified = :v");
        $stmt->execute([':v' => 1]);
        return (int) $stmt->fetchColumn();
    }

    /* ---------- Criptografía ---------- */
    private function endeCrypter(string $action, string $string, string $key, string $ivSource): string
    {
        $method = 'AES-256-CBC';
        $keyHash = hash('sha256', $key, true);
        $iv = substr(hash('sha256', $ivSource, true), 0, 16);

        if ($action === 'encrypt') {
            return base64_encode(openssl_encrypt($string, $method, $keyHash, 0, $iv));
        }
        return (string) openssl_decrypt(base64_decode($string), $method, $keyHash, 0, $iv);
    }

    /* ---------- IP del visitante (segura) ---------- */
    private function resolveUserIp(): string
    {
        // CloudFlare (solo si es de confianza en tu infraestructura)
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }

        // Rechazamos HTTP_CLIENT_IP y HTTP_X_FORWARDED_FOR por ser spoofeables
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = '0.0.0.0';
        }
        return $ip;
    }

    /* ---------- Visitas ---------- */
    public function checkUserIp(string $ip): int
    {
        return $this->findUserIp($ip)->rowCount();
    }

    private function findUserIp(string $ip): PDOStatement
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM visitor WHERE ip = :ip ORDER BY updated_at DESC LIMIT 1"
        );
        $stmt->execute([':ip' => $ip]);
        return $stmt;
    }

    public function visitUpdate(string $ip): void
    {
        $row = $this->findUserIp($ip)->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $diffHours = $this->differenceInHours($row['updated_at'], $this->timestamp);
            if ($diffHours >= 24) {
                $stmt = $this->db->prepare("INSERT INTO visitor (ip, updated_at) VALUES (:ip, :ts)");
                $stmt->execute([':ip' => $ip, ':ts' => $this->timestamp]);
                $this->counterVisitor();
            } else {
                $stmt = $this->db->prepare(
                    "UPDATE visitor SET updated_at = :end WHERE ip = :ip AND updated_at = :start"
                );
                $stmt->execute([':end' => $this->timestamp, ':ip' => $ip, ':start' => $row['updated_at']]);
            }
        } else {
            $this->db->beginTransaction();
            try {
                $s1 = $this->db->prepare("INSERT INTO visitor (ip, updated_at) VALUES (:ip, :ts)");
                $s1->execute([':ip' => $ip, ':ts' => $this->timestamp]);

                $s2 = $this->db->prepare("INSERT INTO active_guests (ip) VALUES (:ip)");
                $s2->execute([':ip' => $ip]);

                $this->db->commit();
                $this->counterVisitor();
            } catch (PDOException $e) {
                $this->db->rollBack();
                error_log('visitUpdate error: ' . $e->getMessage());
            }
        }
    }

    public function counterVisitor(): void
    {
        $this->db->query("UPDATE counter SET counter = counter + 1");
    }

    private function differenceInHours(string $start, string $end): float
    {
        $s = strtotime($start);
        $e = strtotime($end);
        if ($s === false || $e === false) return 0.0;
        return round(abs($e - $s) / 3600, 2);
    }

    /* ---------- Vistas por página ---------- */
    public function pageViews(string $title): void
    {
        $stmt = $this->db->prepare(
            "SELECT date_view FROM pagesviews
            WHERE page = :page AND ip = :ip
            ORDER BY date_view DESC LIMIT 1"
        );
        $stmt->execute([':page' => $title, ':ip' => $this->userIp]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $this->differenceInHours($row['date_view'], $this->timestamp) < 24) {
            return;
        }

        $ins = $this->db->prepare("INSERT INTO pageviews (page, ip, date_view) VALUES (:p, :i, :d)");
        $ins->execute([':p' => $title, ':i' => $this->userIp, ':d' => $this->timestamp]);
    }

    /* ---------- Sesiones online ---------- */
    public function guestOnline(): void
    {
        $stmt = $this->db->prepare("SELECT 1 FROM total_visitors WHERE session = :s");
        $stmt->execute([':s' => $this->session]);

        if ($stmt->fetchColumn() === false) {
            $ins = $this->db->prepare(
                "INSERT INTO total_visitors (session, time) VALUES (:s, :t)"
            );
            $ins->execute([':s' => $this->session, ':t' => $this->timestamp]);
        } else {
            $upd = $this->db->prepare(
                "UPDATE total_visitors SET time = :t WHERE session = :s"
            );
            $upd->execute([':t' => $this->timestamp, ':s' => $this->session]);
        }
    }

    public function totalOnline(): int
    {
        $timeout = date('Y-m-d H:i:s', strtotime($this->timestamp) - 3600);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM total_visitors WHERE time >= :t");
        $stmt->execute([':t' => $timeout]);
        return (int) $stmt->fetchColumn();
    }
}
