<?php
declare(strict_types=1);

/**
 * Estadísticas de páginas con PDO.
 * Migrado con inyección de dependencias y validaciones.
 *
 * CORRECCIONES:
 * - Inyección de dependencias PDO (no crea new Database() internamente)
 * - Validación de filtros
 * - Tipado estricto
 * - Sanitización de fechas
 * - Límites de paginación seguros
 */
class PageStatistics
{
    private PDO $conn;
    private string $table = 'page_statistics';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Registra una visita a una página.
     */
    public function registerVisit(int $page_id, string $page_version = '1.0'): bool
    {
        if ($page_id <= 0) {
            return false;
        }

        // ✅ Validar versión
        if (!preg_match('/^[a-zA-Z0-9\.\-]+$/', $page_version)) {
            $page_version = '1.0';
        }

        try {
            $this->conn->beginTransaction();

            $query = "SELECT id, visits FROM {$this->table} WHERE page_id = :page_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':page_id' => $page_id]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $new_visits = (int) $row['visits'] + 1;

                $updateQuery = "UPDATE {$this->table}
                SET visits = :visits, last_visit = NOW(),
                version = :version, updated_at = NOW()
                WHERE page_id = :page_id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $result = $updateStmt->execute([
                    ':visits'   => $new_visits,
                    ':version'  => $page_version,
                    ':page_id'  => $page_id,
                ]);
            } else {
                $insertQuery = "INSERT INTO {$this->table}
                (page_id, visits, version, last_visit, created_at, updated_at)
                VALUES (:page_id, 1, :version, NOW(), NOW(), NOW())";
                $insertStmt = $this->conn->prepare($insertQuery);
                $result = $insertStmt->execute([
                    ':page_id' => $page_id,
                    ':version' => $page_version,
                ]);
            }

            $this->conn->commit();
            return (bool) $result;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('Error registering visit: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene estadísticas de una página específica.
     */
    public function getPageStatistics(int $page_id): array|false
    {
        if ($page_id <= 0) {
            return false;
        }

        try {
            $query = "SELECT ps.*, p.title, p.slug, p.created_at as page_created,
            p.updated_at as page_updated, p.status
            FROM {$this->table} ps
            JOIN pages p ON ps.page_id = p.id
            WHERE ps.page_id = :page_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':page_id' => $page_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting page statistics: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todas las estadísticas con filtros.
     * ✅ Validación de filtros
     */
    public function getAllStatistics(array $filters = []): array|false
    {
        try {
            $whereConditions = [];
            $params = [];

            $query = "SELECT ps.*, p.title, p.slug, p.created_at as page_created,
            p.updated_at as page_updated, p.status
            FROM {$this->table} ps
            JOIN pages p ON ps.page_id = p.id";

            // ✅ Validar fechas
            if (!empty($filters['start_date']) && $this->isValidDate($filters['start_date'])) {
                $whereConditions[] = "ps.created_at >= :start_date";
                $params[':start_date'] = $filters['start_date'];
            }
            if (!empty($filters['end_date']) && $this->isValidDate($filters['end_date'])) {
                $whereConditions[] = "ps.created_at <= :end_date";
                $params[':end_date'] = $filters['end_date'];
            }
            if (!empty($filters['version']) && preg_match('/^[a-zA-Z0-9\.\-]+$/', $filters['version'])) {
                $whereConditions[] = "ps.version = :version";
                $params[':version'] = $filters['version'];
            }
            if (!empty($filters['status']) && in_array($filters['status'], ['published', 'draft', 'archived'], true)) {
                $whereConditions[] = "p.status = :status";
                $params[':status'] = $filters['status'];
            }

            if (!empty($whereConditions)) {
                $query .= " WHERE " . implode(" AND ", $whereConditions);
            }

            $query .= " ORDER BY ps.visits DESC, ps.last_visit DESC";

            // ✅ Límites seguros
            $limit = isset($filters['limit']) ? max(1, min((int) $filters['limit'], 1000)) : 100;
            $offset = isset($filters['offset']) ? max(0, (int) $filters['offset']) : 0;
            $query .= " LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log('Error getting all statistics: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene estadísticas resumidas.
     */
    public function getSummaryStatistics(): array|false
    {
        try {
            $query = "SELECT
            COUNT(DISTINCT ps.page_id) as total_pages_tracked,
            SUM(ps.visits) as total_visits,
            AVG(ps.visits) as average_visits_per_page,
            MAX(ps.visits) as max_visits,
            MIN(ps.visits) as min_visits,
            COUNT(DISTINCT ps.version) as different_versions,
            MAX(ps.last_visit) as most_recent_visit,
            MIN(ps.created_at) as first_tracked_page
            FROM {$this->table} ps
            JOIN pages p ON ps.page_id = p.id
            WHERE p.status = 'published'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting summary statistics: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza versión de página.
     */
    public function updatePageVersion(int $page_id, string $new_version): bool
    {
        if ($page_id <= 0) {
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9\.\-]+$/', $new_version)) {
            return false;
        }

        try {
            $query = "UPDATE {$this->table}
            SET version = :version, updated_at = NOW()
            WHERE page_id = :page_id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':version' => $new_version,
                ':page_id' => $page_id,
            ]);
        } catch (PDOException $e) {
            error_log('Error updating page version: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene páginas más visitadas.
     */
    public function getMostVisitedPages(int $limit = 10): array|false
    {
        // ✅ Límite seguro
        $limit = max(1, min($limit, 100));

        try {
            $query = "SELECT ps.*, p.title, p.slug, p.status,
            p.created_at as page_created, p.updated_at as page_updated
            FROM {$this->table} ps
            JOIN pages p ON ps.page_id = p.id
            WHERE p.status = 'published'
            ORDER BY ps.visits DESC
            LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting most visited pages: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene estadísticas por período de tiempo.
     */
    public function getVisitsByDateRange(string $start_date, string $end_date): array|false
    {
        // ✅ Validar fechas
        if (!$this->isValidDate($start_date) || !$this->isValidDate($end_date)) {
            return false;
        }

        try {
            $query = "SELECT DATE(ps.created_at) as visit_date,
            COUNT(*) as pages_created,
            SUM(ps.visits) as total_visits
            FROM {$this->table} ps
            JOIN pages p ON ps.page_id = p.id
            WHERE DATE(ps.created_at) BETWEEN :start_date AND :end_date
            GROUP BY DATE(ps.created_at)
            ORDER BY visit_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':start_date' => $start_date,
                ':end_date'   => $end_date,
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting visits by date range: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Resetear contador de visitas.
     */
    public function resetVisits(int $page_id): bool
    {
        if ($page_id <= 0) {
            return false;
        }

        try {
            $query = "UPDATE {$this->table}
            SET visits = 0, updated_at = NOW()
            WHERE page_id = :page_id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':page_id' => $page_id]);
        } catch (PDOException $e) {
            error_log('Error resetting visits: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida formato de fecha.
     */
    private function isValidDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
