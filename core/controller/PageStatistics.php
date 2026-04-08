<?php
// classes/PageStatistics.php
class PageStatistics {
    private $conn;
    private $table = 'page_statistics';
    
    public function __construct($db) {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Registrar una visita a una página
     */
    public function registerVisit($page_id, $page_version = '1.0') {
        try {
            // Primero verificamos si ya existen estadísticas para esta página
            $query = "SELECT id, visits FROM " . $this->table . " WHERE page_id = :page_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                // Actualizar estadísticas existentes
                $row = $stmt->fetch();
                $new_visits = $row['visits'] + 1;
                
                $updateQuery = "UPDATE " . $this->table . " 
                               SET visits = :visits, 
                                   last_visit = NOW(),
                                   version = :version,
                                   updated_at = NOW()
                               WHERE page_id = :page_id";
                
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':visits', $new_visits, PDO::PARAM_INT);
                $updateStmt->bindParam(':version', $page_version);
                $updateStmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
                
                return $updateStmt->execute();
            } else {
                // Crear nuevas estadísticas
                $insertQuery = "INSERT INTO " . $this->table . " 
                               (page_id, visits, version, last_visit, created_at, updated_at) 
                               VALUES (:page_id, 1, :version, NOW(), NOW(), NOW())";
                
                $insertStmt = $this->conn->prepare($insertQuery);
                $insertStmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
                $insertStmt->bindParam(':version', $page_version);
                
                return $insertStmt->execute();
            }
        } catch(PDOException $exception) {
            error_log("Error registering visit: " . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener estadísticas de una página específica
     */
    public function getPageStatistics($page_id) {
        try {
            $query = "SELECT ps.*, p.title, p.slug, p.created_at as page_created, 
                             p.updated_at as page_updated, p.status
                      FROM " . $this->table . " ps
                      JOIN pages p ON ps.page_id = p.id
                      WHERE ps.page_id = :page_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $exception) {
            error_log("Error getting page statistics: " . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todas las estadísticas con información de páginas
     */
    public function getAllStatistics($filters = []) {
        try {
            $whereConditions = [];
            $params = [];
            
            $query = "SELECT ps.*, p.title, p.slug, p.created_at as page_created, 
                             p.updated_at as page_updated, p.status,
                             (SELECT COUNT(*) FROM page_statistics WHERE visits > 0) as total_pages_with_visits,
                             (SELECT SUM(visits) FROM page_statistics) as total_visits_all
                      FROM " . $this->table . " ps
                      JOIN pages p ON ps.page_id = p.id";
            
            // Aplicar filtros
            if (!empty($filters['start_date'])) {
                $whereConditions[] = "ps.created_at >= :start_date";
                $params[':start_date'] = $filters['start_date'];
            }
            
            if (!empty($filters['end_date'])) {
                $whereConditions[] = "ps.created_at <= :end_date";
                $params[':end_date'] = $filters['end_date'];
            }
            
            if (!empty($filters['version'])) {
                $whereConditions[] = "ps.version = :version";
                $params[':version'] = $filters['version'];
            }
            
            if (!empty($filters['status'])) {
                $whereConditions[] = "p.status = :status";
                $params[':status'] = $filters['status'];
            }
            
            if (!empty($whereConditions)) {
                $query .= " WHERE " . implode(" AND ", $whereConditions);
            }
            
            // Ordenar
            $query .= " ORDER BY ps.visits DESC, ps.last_visit DESC";
            
            // Límite para paginación
            if (!empty($filters['limit'])) {
                $query .= " LIMIT :limit";
                if (!empty($filters['offset'])) {
                    $query .= " OFFSET :offset";
                }
            }
            
            $stmt = $this->conn->prepare($query);
            
            // Vincular parámetros
            foreach ($params as $key => $value) {
                $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $paramType);
            }
            
            // Vincular límite y offset si existen
            if (!empty($filters['limit'])) {
                $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
                if (!empty($filters['offset'])) {
                    $stmt->bindValue(':offset', (int)$filters['offset'], PDO::PARAM_INT);
                }
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $exception) {
            error_log("Error getting all statistics: " . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener estadísticas resumidas
     */
    public function getSummaryStatistics() {
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
            FROM " . $this->table . " ps
            JOIN pages p ON ps.page_id = p.id
            WHERE p.status = 'published'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $exception) {
            error_log("Error getting summary statistics: " . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar versión de página
     */
    public function updatePageVersion($page_id, $new_version) {
        try {
            $query = "UPDATE " . $this->table . " 
                     SET version = :version, updated_at = NOW()
                     WHERE page_id = :page_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':version', $new_version);
            $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $exception) {
            error_log("Error updating page version: " . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener páginas más visitadas
     */
    public function getMostVisitedPages($limit = 10) {
        try {
            $query = "SELECT ps.*, p.title, p.slug, p.status,
                             p.created_at as page_created, p.updated_at as page_updated
                      FROM " . $this->table . " ps
                      JOIN pages p ON ps.page_id = p.id
                      WHERE p.status = 'published'
                      ORDER BY ps.visits DESC
                      LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $exception) {
            error_log("Error getting most visited pages: " . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener estadísticas por período de tiempo
     */
    public function getVisitsByDateRange($start_date, $end_date) {
        try {
            $query = "SELECT 
                DATE(ps.created_at) as visit_date,
                COUNT(*) as pages_created,
                SUM(ps.visits) as total_visits,
                GROUP_CONCAT(DISTINCT ps.version) as versions_used
            FROM " . $this->table . " ps
            JOIN pages p ON ps.page_id = p.id
            WHERE DATE(ps.created_at) BETWEEN :start_date AND :end_date
            GROUP BY DATE(ps.created_at)
            ORDER BY visit_date DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $exception) {
            error_log("Error getting visits by date range: " . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Resetear contador de visitas (para testing o cambios mayores)
     */
    public function resetVisits($page_id) {
        try {
            $query = "UPDATE " . $this->table . " 
                     SET visits = 0, updated_at = NOW()
                     WHERE page_id = :page_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $exception) {
            error_log("Error resetting visits: " . $exception->getMessage());
            return false;
        }
    }
}