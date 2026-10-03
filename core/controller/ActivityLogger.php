<?php
declare(strict_types=1);

/**
 * Logger de actividades de usuarios con PDO.
 *
 * CORRECCIONES:
 * - Validación estricta de user_id
 * - Sanitización de IP (previene spoofing)
 * - Validación de user_agent
 * - Límites en longitud de descripción
 * - Lista blanca de tipos de actividad
 * - Inyección de dependencias PDO
 */
class ActivityLogger
{
    private PDO $conn;
    private string $table = 'user_activities';

    /**
     * ✅ Lista blanca de tipos de actividad permitidos
     */
    private const ALLOWED_TYPES = [
        'login', 'logout', 'register', 'password_change',
        'profile_update', 'page_create', 'page_update', 'page_delete',
        'template_create', 'template_update', 'template_delete',
        'user_ban', 'user_unban', 'role_change',
        'cache_clear', 'settings_change', 'error',
    ];

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Registra una actividad.
     *
     * ✅ Validaciones estrictas
     */
    public function logActivity(
        int $user_id,
        string $activity_type,
        string $description
    ): bool {
        // ✅ Validar user_id
        if ($user_id < 0) {
            return false;
        }

        // ✅ Validar tipo contra lista blanca
        if (!in_array($activity_type, self::ALLOWED_TYPES, true)) {
            error_log("ActivityLogger: tipo de actividad no permitido: {$activity_type}");
            return false;
        }

        // ✅ Limitar longitud de descripción
        $description = mb_substr(trim($description), 0, 1000);

        // ✅ Sanitizar IP (previene spoofing)
        $ip_address = $this->getSecureIp();

        // ✅ Sanitizar user agent
        $user_agent = mb_substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 500);

        try {
            $query = "INSERT INTO {$this->table}
            (user_id, activity_type, description, ip_address, user_agent)
            VALUES (:user_id, :activity_type, :description, :ip_address, :user_agent)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':user_id'       => $user_id,
                ':activity_type' => $activity_type,
                ':description'   => $description,
                ':ip_address'    => $ip_address,
                ':user_agent'    => $user_agent,
            ]);
        } catch (PDOException $e) {
            error_log('ActivityLogger::logActivity error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene actividades de un usuario.
     */
    public function getUserActivities(?int $user_id = null, int $limit = 50): array
    {
        // ✅ Validar y limitar
        $limit = max(1, min($limit, 1000));

        try {
            $query = "SELECT ua.*, u.username, u.role,
            DATE_FORMAT(ua.created_at, '%d/%m/%Y %H:%i:%s') as formatted_date
            FROM {$this->table} ua
            LEFT JOIN users u ON ua.user_id = u.id";
            $params = [];

            if ($user_id !== null && $user_id > 0) {
                $query .= " WHERE ua.user_id = :user_id";
                $params[':user_id'] = $user_id;
            }

            $query .= " ORDER BY ua.created_at DESC LIMIT :limit";

            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ActivityLogger::getUserActivities error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene actividades por tipo.
     */
    public function getActivitiesByType(string $activity_type, int $limit = 50): array
    {
        // ✅ Validar tipo contra lista blanca
        if (!in_array($activity_type, self::ALLOWED_TYPES, true)) {
            return [];
        }

        $limit = max(1, min($limit, 1000));

        try {
            $query = "SELECT ua.*, u.username, u.role,
            DATE_FORMAT(ua.created_at, '%d/%m/%Y %H:%i:%s') as formatted_date
            FROM {$this->table} ua
            LEFT JOIN users u ON ua.user_id = u.id
            WHERE ua.activity_type = :activity_type
            ORDER BY ua.created_at DESC
            LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':activity_type', $activity_type);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ActivityLogger::getActivitiesByType error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene actividades por rango de fechas.
     *
     * ✅ Validación de fechas
     */
    public function getActivitiesByDateRange(string $start_date, string $end_date): array
    {
        // ✅ Validar formato de fechas
        if (!$this->isValidDate($start_date) || !$this->isValidDate($end_date)) {
            return [];
        }

        // ✅ Validar que start_date <= end_date
        if (strtotime($start_date) > strtotime($end_date)) {
            return [];
        }

        try {
            $query = "SELECT ua.*, u.username, u.role,
            DATE_FORMAT(ua.created_at, '%d/%m/%Y %H:%i:%s') as formatted_date
            FROM {$this->table} ua
            LEFT JOIN users u ON ua.user_id = u.id
            WHERE DATE(ua.created_at) BETWEEN :start_date AND :end_date
            ORDER BY ua.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':start_date' => $start_date,
                ':end_date'   => $end_date,
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ActivityLogger::getActivitiesByDateRange error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene estadísticas de actividad.
     */
    public function getActivityStatistics(int $days = 30): array
    {
        // ✅ Validar y limitar días
        $days = max(1, min($days, 365));

        try {
            $query = "SELECT activity_type, COUNT(*) as total, DATE(created_at) as date
            FROM {$this->table}
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
            GROUP BY activity_type, DATE(created_at)
            ORDER BY date DESC, total DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ActivityLogger::getActivityStatistics error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene IP de forma segura.
     *
     * ✅ Previene IP spoofing usando solo REMOTE_ADDR
     */
    private function getSecureIp(): string
    {
        // ✅ Usar solo REMOTE_ADDR (el más confiable)
        // HTTP_X_FORWARDED_FOR y HTTP_CLIENT_IP son spoofeables
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // ✅ Validar formato
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return '0.0.0.0';
        }

        return $ip;
    }

    /**
     * Valida formato de fecha.
     */
    private function isValidDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Obtiene tipos de actividad permitidos.
     */
    public function getAllowedTypes(): array
    {
        return self::ALLOWED_TYPES;
    }
}
