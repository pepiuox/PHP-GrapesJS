<?php
// classes/Cache.php
class Cache {
    private $conn;
    private $cache_enabled = true;
    private $cache_duration = 3600; // 1 hora en segundos
    
    public function __construct($db) {
        $this->conn = $db;
        // Puedes desactivar cache en desarrollo
        $this->cache_enabled = true;
    }
    
    /**
     * Obtener página cacheada
     */
    public function getPage($page_id) {
        if (!$this->cache_enabled) {
            return false;
        }
        
        $query = "SELECT cached_content FROM page_cache 
                  WHERE page_id = :page_id AND expires_at > NOW() 
                  ORDER BY created_at DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['cached_content'];
        }
        
        return false;
    }
    
    /**
     * Guardar página en cache
     */
    public function setPage($page_id, $content, $duration = null) {
        if (!$this->cache_enabled) {
            return false;
        }
        
        $duration = $duration ?: $this->cache_duration;
        $expires_at = date('Y-m-d H:i:s', time() + $duration);
        $hash = hash('sha256', $content);
        
        // Verificar si ya existe una versión actual
        $check_query = "SELECT id FROM page_cache WHERE page_id = :page_id AND hash = :hash";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(':page_id', $page_id);
        $check_stmt->bindParam(':hash', $hash);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            // Actualizar fecha de expiración
            $update_query = "UPDATE page_cache SET expires_at = :expires_at 
                            WHERE page_id = :page_id AND hash = :hash";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(':expires_at', $expires_at);
            $update_stmt->bindParam(':page_id', $page_id);
            $update_stmt->bindParam(':hash', $hash);
            return $update_stmt->execute();
        }
        
        // Limpiar cache antiguo
        $this->clearOldPageCache($page_id);
        
        // Insertar nuevo cache
        $query = "INSERT INTO page_cache (page_id, cached_content, hash, expires_at) 
                  VALUES (:page_id, :content, :hash, :expires_at)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':expires_at', $expires_at);
        
        return $stmt->execute();
    }
    
    /**
     * Limpiar cache de una página
     */
    public function clearPageCache($page_id) {
        $query = "DELETE FROM page_cache WHERE page_id = :page_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        return $stmt->execute();
    }
    
    /**
     * Limpiar cache antiguo
     */
    private function clearOldPageCache($page_id) {
        $query = "DELETE FROM page_cache WHERE page_id = :page_id AND expires_at <= NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->execute();
        
        // Mantener solo las 5 últimas versiones cacheadas
        $query = "DELETE FROM page_cache WHERE page_id = :page_id AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM page_cache 
                        WHERE page_id = :page_id2 
                        ORDER BY created_at DESC LIMIT 5
                    ) as temp
                  )";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->bindParam(':page_id2', $page_id);
        return $stmt->execute();
    }
    
    /**
     * Limpiar todo el cache
     */
    public function clearAllCache() {
        $query = "DELETE FROM page_cache";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
    
    /**
     * Obtener estadísticas de cache
     */
    public function getCacheStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN expires_at > NOW() THEN 1 END) as active,
                    COUNT(CASE WHEN expires_at <= NOW() THEN 1 END) as expired
                  FROM page_cache";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Cache general (key-value)
     */
    public function get($key) {
        $query = "SELECT cache_value FROM cache_settings 
                  WHERE cache_key = :key AND (expires_at IS NULL OR expires_at > NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_decode($row['cache_value'], true);
        }
        
        return null;
    }
    
    public function set($key, $value, $duration = null) {
        $expires_at = $duration ? date('Y-m-d H:i:s', time() + $duration) : null;
        $json_value = json_encode($value);
        
        $query = "INSERT INTO cache_settings (cache_key, cache_value, expires_at) 
                  VALUES (:key, :value, :expires_at)
                  ON DUPLICATE KEY UPDATE 
                  cache_value = :value2, 
                  expires_at = :expires_at2, 
                  updated_at = NOW()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $key);
        $stmt->bindParam(':value', $json_value);
        $stmt->bindParam(':value2', $json_value);
        $stmt->bindParam(':expires_at', $expires_at);
        $stmt->bindParam(':expires_at2', $expires_at);
        
        return $stmt->execute();
    }
    
    public function delete($key) {
        $query = "DELETE FROM cache_settings WHERE cache_key = :key";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $key);
        return $stmt->execute();
    }
    
    /**
     * Activar/desactivar cache
     */
    public function enable($enabled = true) {
        $this->cache_enabled = $enabled;
    }
    
    public function setDuration($seconds) {
        $this->cache_duration = $seconds;
    }
}
?>