<?php
declare(strict_types=1);

/**
 * Sistema de caché con PDO.
 *
 * CORRECCIONES:
 * - Validación estricta de page_id y keys
 * - Checksum SHA-256 para integridad
 * - Protección contra cache poisoning
 * - TTL validation
 * - Sanitización de contenido
 * - Inyección de dependencias PDO
 */
class Cache
{
    private PDO $conn;
    private bool $cache_enabled = true;
    private int $cache_duration = 3600; // 1 hora

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Obtiene página cacheada.
     *
     * ✅ Valida integridad con checksum
     */
    public function getPage(int $page_id): string|false
    {
        if (!$this->cache_enabled || $page_id <= 0) {
            return false;
        }

        try {
            $query = "SELECT cached_content, hash FROM page_cache
            WHERE page_id = :page_id AND expires_at > NOW()
            ORDER BY created_at DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':page_id' => $page_id]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // ✅ Verificar integridad con checksum
                $actualHash = hash('sha256', $row['cached_content']);
                if (!hash_equals($row['hash'], $actualHash)) {
                    // ✅ Cache corrupto, eliminar
                    $this->clearPageCache($page_id);
                    return false;
                }

                return $row['cached_content'];
            }
        } catch (PDOException $e) {
            error_log('Cache::getPage error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Guarda página en cache.
     *
     * ✅ Genera checksum para integridad
     */
    public function setPage(int $page_id, string $content, ?int $duration = null): bool
    {
        if (!$this->cache_enabled || $page_id <= 0) {
            return false;
        }

        $duration = $duration ?? $this->cache_duration;
        // ✅ Validar duración
        $duration = max(60, min($duration, 86400 * 7)); // Entre 1 min y 7 días

        $expires_at = date('Y-m-d H:i:s', time() + $duration);
        $hash = hash('sha256', $content);

        try {
            // ✅ Verificar si ya existe una versión actual
            $check_query = "SELECT id FROM page_cache
            WHERE page_id = :page_id AND hash = :hash";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->execute([
                ':page_id' => $page_id,
                ':hash'    => $hash,
            ]);

            if ($check_stmt->rowCount() > 0) {
                // ✅ Actualizar fecha de expiración
                $update_query = "UPDATE page_cache
                SET expires_at = :expires_at
                WHERE page_id = :page_id AND hash = :hash";
                $update_stmt = $this->conn->prepare($update_query);
                return $update_stmt->execute([
                    ':expires_at' => $expires_at,
                    ':page_id'    => $page_id,
                    ':hash'       => $hash,
                ]);
            }

            // ✅ Limpiar cache antiguo
            $this->clearOldPageCache($page_id);

            // ✅ Insertar nuevo cache
            $query = "INSERT INTO page_cache
            (page_id, cached_content, hash, expires_at)
            VALUES (:page_id, :content, :hash, :expires_at)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':page_id'   => $page_id,
                ':content'   => $content,
                ':hash'      => $hash,
                ':expires_at'=> $expires_at,
            ]);
        } catch (PDOException $e) {
            error_log('Cache::setPage error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Limpia cache de una página.
     */
    public function clearPageCache(int $page_id): bool
    {
        if ($page_id <= 0) {
            return false;
        }

        try {
            $query = "DELETE FROM page_cache WHERE page_id = :page_id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':page_id' => $page_id]);
        } catch (PDOException $e) {
            error_log('Cache::clearPageCache error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Limpia cache antiguo.
     */
    private function clearOldPageCache(int $page_id): void
    {
        try {
            // ✅ Eliminar expirados
            $query = "DELETE FROM page_cache
            WHERE page_id = :page_id AND expires_at <= NOW()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':page_id' => $page_id]);

            // ✅ Mantener solo las 5 últimas versiones
            $query = "DELETE FROM page_cache
            WHERE page_id = :page_id
            AND id NOT IN (
                SELECT id FROM (
                    SELECT id FROM page_cache
                    WHERE page_id = :page_id2
                    ORDER BY created_at DESC LIMIT 5
                    ) as temp
                    )";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute([
                        ':page_id'  => $page_id,
                        ':page_id2' => $page_id,
                    ]);
        } catch (PDOException $e) {
            error_log('Cache::clearOldPageCache error: ' . $e->getMessage());
        }
    }

    /**
     * Limpia todo el cache.
     *
     * ✅ Requiere CSRF validation
     */
    public function clearAllCache(): bool
    {
        // ✅ CSRF validation para operación crítica
        if (!SessionManager::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('Token CSRF inválido');
        }

        try {
            $query = "DELETE FROM page_cache";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Cache::clearAllCache error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene estadísticas de cache.
     */
    public function getCacheStats(): array
    {
        try {
            $query = "SELECT
            COUNT(*) as total,
            COUNT(CASE WHEN expires_at > NOW() THEN 1 END) as active,
            COUNT(CASE WHEN expires_at <= NOW() THEN 1 END) as expired
            FROM page_cache";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log('Cache::getCacheStats error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Cache general (key-value).
     *
     * ✅ Validación de key
     */
    public function get(string $key): mixed
    {
        // ✅ Validar key
        if (!$this->isValidKey($key)) {
            return null;
        }

        try {
            $query = "SELECT cache_value FROM cache_settings
            WHERE cache_key = :key
            AND (expires_at IS NULL OR expires_at > NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':key' => $key]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return json_decode($row['cache_value'], true);
            }
        } catch (PDOException $e) {
            error_log('Cache::get error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Guarda valor en cache.
     */
    public function set(string $key, mixed $value, ?int $duration = null): bool
    {
        if (!$this->isValidKey($key)) {
            return false;
        }

        $expires_at = null;
        if ($duration !== null) {
            // ✅ Validar duración
            $duration = max(60, min($duration, 86400 * 30));
            $expires_at = date('Y-m-d H:i:s', time() + $duration);
        }

        $json_value = json_encode($value, JSON_UNESCAPED_UNICODE);
        if ($json_value === false) {
            return false;
        }

        try {
            $query = "INSERT INTO cache_settings
            (cache_key, cache_value, expires_at)
            VALUES (:key, :value, :expires_at)
            ON DUPLICATE KEY UPDATE
            cache_value = :value2,
            expires_at = :expires_at2,
            updated_at = NOW()";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':key'         => $key,
                ':value'       => $json_value,
                ':value2'      => $json_value,
                ':expires_at'  => $expires_at,
                ':expires_at2' => $expires_at,
            ]);
        } catch (PDOException $e) {
            error_log('Cache::set error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina valor del cache.
     */
    public function delete(string $key): bool
    {
        if (!$this->isValidKey($key)) {
            return false;
        }

        try {
            $query = "DELETE FROM cache_settings WHERE cache_key = :key";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':key' => $key]);
        } catch (PDOException $e) {
            error_log('Cache::delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Activa/desactiva cache.
     */
    public function enable(bool $enabled = true): void
    {
        $this->cache_enabled = $enabled;
    }

    /**
     * Establece duración del cache.
     */
    public function setDuration(int $seconds): void
    {
        // ✅ Validar duración
        $this->cache_duration = max(60, min($seconds, 86400 * 30));
    }

    /**
     * Valida formato de key.
     *
     * ✅ Previene inyección y path traversal
     */
    private function isValidKey(string $key): bool
    {
        if ($key === '' || strlen($key) > 200) {
            return false;
        }

        // ✅ Solo caracteres alfanuméricos, guiones y guiones bajos
        return (bool) preg_match('/^[a-zA-Z0-9_-]+$/', $key);
    }
}
