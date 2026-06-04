<?php
class CacheManager {
    private $cacheDir;
    private $ttl;

    public function __construct($cacheDir = '/tmp/page_cache', $ttl = 3600) {
        $this->cacheDir = $cacheDir;
        $this->ttl = $ttl;

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function get($key) {
        $file = $this->getCacheFile($key);

        if (!file_exists($file)) return null;

        $data = unserialize(file_get_contents($file));

        if (time() - $data['timestamp'] > $this->ttl) {
            unlink($file);
            return null;
        }

        return $data['content'];
    }

    public function set($key, $content) {
        $file = $this->getCacheFile($key);
        $data = [
            'timestamp' => time(),
            'content' => $content
        ];

        file_put_contents($file, serialize($data), LOCK_EX);
    }

    private function getCacheFile($key) {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
}
