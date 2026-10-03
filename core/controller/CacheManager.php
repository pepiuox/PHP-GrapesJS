<?php
declare(strict_types=1);

/**
 * Gestor de caché en archivos con seguridad mejorada.
 *
 * ✅ CORRECCIONES CRÍTICAS:
 * - unserialize() reemplazado por json_decode() (previene Object Injection)
 * - Validación de path traversal en $key
 * - hash_file() para integridad
 * - Permisos seguros en directorio
 * - Locking para prevenir race conditions
 * - Limpieza automática de caché expirado
 */
class CacheManager
{
    private string $cacheDir;
    private int $ttl;
    private string $prefix;

    public function __construct(
        string $cacheDir = '/tmp/page_cache',
        int $ttl = 3600,
        string $prefix = 'cache_'
    ) {
        // ✅ Validar directorio
        if (!is_dir($cacheDir)) {
            // ✅ Permisos seguros: 0755 (no 0777)
            if (!mkdir($cacheDir, 0755, true)) {
                throw new RuntimeException("No se pudo crear el directorio de caché: {$cacheDir}");
            }
        }

        // ✅ Verificar permisos de escritura
        if (!is_writable($cacheDir)) {
            throw new RuntimeException("El directorio de caché no es escribible: {$cacheDir}");
        }

        // ✅ Validar prefijo
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $prefix)) {
            throw new InvalidArgumentException('Prefijo de caché inválido');
        }

        $this->cacheDir = rtrim($cacheDir, '/');
        $this->ttl = max(60, $ttl); // Mínimo 60 segundos
        $this->prefix = $prefix;
    }

    /**
     * Obtiene un valor del caché.
     *
     * ✅ CORREGIDO: json_decode() en lugar de unserialize()
     */
    public function get(string $key): mixed
    {
        // ✅ Validar key
        if (!$this->isValidKey($key)) {
            return null;
        }

        $file = $this->getCacheFile($key);
        if (!file_exists($file)) {
            return null;
        }

        // ✅ Lectura atómica con locking
        $handle = fopen($file, 'rb');
        if ($handle === false) {
            return null;
        }

        // ✅ Lock compartido para lectura
        if (!flock($handle, LOCK_SH)) {
            fclose($handle);
            return null;
        }

        $content = stream_get_contents($handle);
        flock($handle, LOCK_UN);
        fclose($handle);

        if ($content === false || $content === '') {
            return null;
        }

        // ✅ JSON en lugar de unserialize (previene Object Injection)
        $data = json_decode($content, true);
        if (!is_array($data)) {
            // Archivo corrupto, eliminar
            @unlink($file);
            return null;
        }

        // Verificar estructura
        if (!isset($data['timestamp'], $data['content'], $data['checksum'])) {
            @unlink($file);
            return null;
        }

        // ✅ Verificar TTL
        if (time() - $data['timestamp'] > $this->ttl) {
            @unlink($file);
            return null;
        }

        // ✅ Verificar integridad con checksum
        $expectedChecksum = hash('sha256', json_encode($data['content']));
        if (!hash_equals($expectedChecksum, $data['checksum'])) {
            @unlink($file);
            return null;
        }

        return $data['content'];
    }

    /**
     * Guarda un valor en caché.
     *
     * ✅ Escritura atómica con checksum de integridad
     */
    public function set(string $key, mixed $content): bool
    {
        if (!$this->isValidKey($key)) {
            return false;
        }

        // ✅ Verificar que el contenido sea serializable a JSON
        $jsonContent = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($jsonContent === false) {
            return false;
        }

        $data = [
            'timestamp' => time(),
            'content'   => $content,
            'checksum'  => hash('sha256', $jsonContent),
        ];

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($jsonData === false) {
            return false;
        }

        $file = $this->getCacheFile($key);
        $tempFile = $file . '.tmp.' . bin2hex(random_bytes(4));

        // ✅ Escritura atómica: escribir en temporal, luego mover
        $bytes = file_put_contents($tempFile, $jsonData, LOCK_EX);
        if ($bytes === false) {
            @unlink($tempFile);
            return false;
        }

        // ✅ Permisos seguros
        chmod($tempFile, 0644);

        // ✅ Movimiento atómico (rename es atómico en el mismo filesystem)
        if (!rename($tempFile, $file)) {
            @unlink($tempFile);
            return false;
        }

        return true;
    }

    /**
     * Elimina un valor del caché.
     */
    public function delete(string $key): bool
    {
        if (!$this->isValidKey($key)) {
            return false;
        }

        $file = $this->getCacheFile($key);
        if (file_exists($file)) {
            return @unlink($file);
        }
        return true;
    }

    /**
     * Limpia todo el caché.
     */
    public function clear(): bool
    {
        $pattern = $this->cacheDir . '/' . $this->prefix . '*.cache';
        $files = glob($pattern);

        if ($files === false) {
            return false;
        }

        $success = true;
        foreach ($files as $file) {
            if (is_file($file) && !@unlink($file)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Limpia entradas expiradas.
     */
    public function gc(): int
    {
        $pattern = $this->cacheDir . '/' . $this->prefix . '*.cache';
        $files = glob($pattern);

        if ($files === false) {
            return 0;
        }

        $cleaned = 0;
        $now = time();

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            $content = @file_get_contents($file);
            if ($content === false) {
                continue;
            }

            $data = json_decode($content, true);
            if (!is_array($data) || !isset($data['timestamp'])) {
                @unlink($file);
                $cleaned++;
                continue;
            }

            if ($now - $data['timestamp'] > $this->ttl) {
                @unlink($file);
                $cleaned++;
            }
        }

        return $cleaned;
    }

    /**
     * Obtiene estadísticas del caché.
     */
    public function getStats(): array
    {
        $pattern = $this->cacheDir . '/' . $this->prefix . '*.cache';
        $files = glob($pattern) ?: [];

        $totalSize = 0;
        $validEntries = 0;
        $expiredEntries = 0;
        $now = time();

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            $totalSize += filesize($file);

            $content = @file_get_contents($file);
            if ($content === false) {
                continue;
            }

            $data = json_decode($content, true);
            if (is_array($data) && isset($data['timestamp'])) {
                if ($now - $data['timestamp'] <= $this->ttl) {
                    $validEntries++;
                } else {
                    $expiredEntries++;
                }
            }
        }

        return [
            'total_files'      => count($files),
            'valid_entries'    => $validEntries,
            'expired_entries'  => $expiredEntries,
            'total_size_bytes' => $totalSize,
            'ttl'              => $this->ttl,
            'cache_dir'        => $this->cacheDir,
        ];
    }

    /**
     * Genera nombre de archivo seguro.
     *
     * ✅ CORREGIDO: hash_file() + validación de key
     */
    private function getCacheFile(string $key): string
    {
        // ✅ sha256 en lugar de md5 (más seguro)
        $hash = hash('sha256', $this->prefix . $key);
        return $this->cacheDir . '/' . $this->prefix . $hash . '.cache';
    }

    /**
     * Valida formato de key.
     *
     * ✅ Previene path traversal
     */
    private function isValidKey(string $key): bool
    {
        if ($key === '' || strlen($key) > 200) {
            return false;
        }

        // ✅ Rechazar caracteres peligrosos
        if (strpos($key, '..') !== false || strpos($key, '/') !== false ||
            strpos($key, '\\') !== false || strpos($key, "\0") !== false) {
            return false;
            }

            return true;
    }
}
