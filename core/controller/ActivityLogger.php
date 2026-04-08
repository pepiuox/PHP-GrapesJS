<?php
// classes/ActivityLogger.php

class ActivityLogger {

    private $table = 'user_activities';
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function logActivity($user_id, $activity_type, $description) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, activity_type, description, ip_address, user_agent) 
                  VALUES (:user_id, :activity_type, :description, :ip_address, :user_agent)";
        
        $stmt = $this->conn->prepare($query);
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':activity_type', $activity_type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ip_address', $ip_address);
        $stmt->bindParam(':user_agent', $user_agent);
        
        return $stmt->execute();
    }

    public function getUserActivities($user_id = null, $limit = 50) {
        $query = "SELECT 
                    ua.*, 
                    u.username, 
                    u.role,
                    DATE_FORMAT(ua.created_at, '%d/%m/%Y %H:%i:%s') as formatted_date
                  FROM " . $this->table . " ua
                  LEFT JOIN users u ON ua.user_id = u.id";
        
        if ($user_id) {
            $query .= " WHERE ua.user_id = :user_id";
        }
        
        $query .= " ORDER BY ua.created_at DESC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivitiesByType($activity_type, $limit = 50) {
        $query = "SELECT 
                    ua.*, 
                    u.username, 
                    u.role,
                    DATE_FORMAT(ua.created_at, '%d/%m/%Y %H:%i:%s') as formatted_date
                  FROM " . $this->table . " ua
                  LEFT JOIN users u ON ua.user_id = u.id
                  WHERE ua.activity_type = :activity_type
                  ORDER BY ua.created_at DESC LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':activity_type', $activity_type);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivitiesByDateRange($start_date, $end_date) {
        $query = "SELECT 
                    ua.*, 
                    u.username, 
                    u.role,
                    DATE_FORMAT(ua.created_at, '%d/%m/%Y %H:%i:%s') as formatted_date
                  FROM " . $this->table . " ua
                  LEFT JOIN users u ON ua.user_id = u.id
                  WHERE DATE(ua.created_at) BETWEEN :start_date AND :end_date
                  ORDER BY ua.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivityStatistics($days = 30) {
        $query = "SELECT 
                    activity_type,
                    COUNT(*) as total,
                    DATE(created_at) as date
                  FROM " . $this->table . "
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                  GROUP BY activity_type, DATE(created_at)
                  ORDER BY date DESC, total DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>