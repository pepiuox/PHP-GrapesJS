<?php
declare(strict_types=1);

namespace App\Core\Cache;

use PDO;
use PDOStatement;

/**
 * Driver de caché basado en base de datos usando PDO.
 */
class DatabaseCacheDriver implements CacheInterface
{
    private PDO $db;
    private string $table;

    public function __construct(PDO $db, string $table = 'cache')
    {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->table = $table;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $stmt = $this->db->prepare(
            "SELECT value, expiration FROM `{$this->table}` WHERE key_hash = ? LIMIT 1"
        );
        $stmt->execute([$this->hashKey($key)]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$row) {
            return $default;
        }

        if ($row->expiration > 0 && $row->expiration < time()) {
            $this->delete($key);
            return $default;
        }

        $value = @unserialize($row->value);
        return $value !== false ? $value : $default;
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        $keyHash = $this->hashKey($key);
        $serialized = serialize($value);
        $expiration = $ttl > 0 ? time() + $ttl : 0;

        // Intentar UPDATE primero
        $stmt = $this->db->prepare(
            "UPDATE `{$this->table}` SET value = ?, expiration = ? WHERE key_hash = ?"
        );
        $stmt->execute([$serialized, $expiration, $keyHash]);
        $affected = $stmt->rowCount();

        if ($affected === 0) {
            try {
                $stmt = $this->db->prepare(
                    "INSERT INTO `{$this->table}` (key_hash, value, expiration) VALUES (?, ?, ?)"
                );
                $stmt->execute([$keyHash, $serialized, $expiration]);
            } catch (\Throwable $e) {
                // Race condition: intentar UPDATE de nuevo
                $stmt = $this->db->prepare(
                    "UPDATE `{$this->table}` SET value = ?, expiration = ? WHERE key_hash = ?"
                );
                $stmt->execute([$serialized, $expiration, $keyHash]);
            }
        }

        return true;
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function delete(string $key): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM `{$this->table}` WHERE key_hash = ?"
        );
        $stmt->execute([$this->hashKey($key)]);
        return true;
    }

    public function clear(): bool
    {
        $this->db->exec("DELETE FROM `{$this->table}`");
        return true;
    }

    public function getMultiple(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }
        return $result;
    }

    public function setMultiple(array $data, int $ttl = 3600): bool
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        $value = $this->get($key);
        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }

    /**
     * Limpia entradas expiradas.
     */
    public function gc(): int
    {
        $stmt = $this->db->prepare(
            "DELETE FROM `{$this->table}` WHERE expiration > 0 AND expiration < ?"
        );
        $stmt->execute([time()]);
        return $stmt->rowCount();
    }

    private function hashKey(string $key): string
    {
        return hash('sha256', $key);
    }
}
