<?php
declare(strict_types=1);

/**
 * Gestor de archivos multimedia con validación robusta.
 */
class MediaManager
{
    private PDO $db;
    private string $uploadDir;
    private int $maxFileSize;
    private array $allowedExtensions;
    private array $allowedMimeTypes;

    public function __construct(PDO $db, string $uploadDir = '', int $maxSizeMB = 10)
    {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->maxFileSize = $maxSizeMB * 1024 * 1024;

        $this->uploadDir = $uploadDir !== ''
        ? rtrim($uploadDir, '/') . '/media/'
        : (defined('UPLOAD_DIR') ? rtrim(UPLOAD_DIR, '/') . '/media/' : dirname(__DIR__) . '/uploads/media/');

        // Extensiones permitidas
        $this->allowedExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'txt', 'csv', 'rtf',
            'zip', 'rar', '7z', 'tar', 'gz',
            'mp3', 'mp4', 'avi', 'mov', 'webm',
        ];

        // MIME types permitidos
        $this->allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain', 'text/csv', 'application/rtf',
            'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
            'application/x-tar', 'application/gzip',
            'audio/mpeg', 'video/mp4', 'video/x-msvideo', 'video/quicktime', 'video/webm',
        ];

        $this->initUploadDir();
    }

    /**
     * Crea el directorio de uploads si no existe.
     */
    private function initUploadDir(): void
    {
        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true) && !is_dir($this->uploadDir)) {
                throw new RuntimeException("Failed to create upload directory: {$this->uploadDir}");
            }
            // Prevenir ejecución de PHP
            file_put_contents(
                $this->uploadDir . '.htaccess',
                "Options -Indexes\nphp_flag engine off\n"
            );
        }
    }

    /**
     * Sube un archivo con validación completa.
     */
    public function uploadFile(array $file, int $userId): array
    {
        // Validar errores de upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . $this->getUploadErrorMessage($file['error']));
        }

        // Validar tamaño
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('File is too large (max ' . ($this->maxFileSize / 1024 / 1024) . 'MB)');
        }

        if ($file['size'] === 0) {
            throw new Exception('Empty file');
        }

        // 🔒 Validación doble: extensión + MIME real
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mimeType = $this->getRealMimeType($file['tmp_name']);

        if (!in_array($extension, $this->allowedExtensions, true)) {
            throw new Exception('File extension not allowed');
        }

        if (!in_array($mimeType, $this->allowedMimeTypes, true)) {
            throw new Exception('File type not allowed (MIME)');
        }

        // 🔒 Sanitizar nombre original
        $originalName = $this->sanitizeFilename($file['name']);

        // 🔒 Nombre único criptográficamente seguro
        $filename = bin2hex(random_bytes(16)) . '_' . time() . '.' . $extension;
        $uploadPath = $this->uploadDir . $filename;

        // 🔒 Verificar path traversal
        $realUploadDir = realpath($this->uploadDir);
        $realUploadPath = realpath(dirname($uploadPath));
        if ($realUploadDir === false || $realUploadPath === false
            || strpos($realUploadPath, $realUploadDir) !== 0) {
            throw new Exception('Invalid upload path');
            }

            // Mover archivo
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Failed to move uploaded file');
            }

            // Permisos seguros
            chmod($uploadPath, 0644);

            // Guardar en BD
            try {
                $sql = "INSERT INTO media
                (user_id, filename, original_name, file_path, file_type, mime_type, file_size, created_at)
                VALUES (:user_id, :filename, :original_name, :file_path, :file_type, :mime_type, :file_size, NOW())";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':user_id'       => $userId,
                    ':filename'      => $filename,
                    ':original_name' => $originalName,
                    ':file_path'     => $uploadPath,
                    ':file_type'     => $file['type'] ?? $extension,
                    ':mime_type'     => $mimeType,
                    ':file_size'     => $file['size'],
                ]);

                $id = (int) $this->db->lastInsertId();
                $baseUrl = defined('APP_URL') ? APP_URL : '';

                return [
                    'id'            => $id,
                    'filename'      => $filename,
                    'original_name' => $originalName,
                    'file_path'     => $uploadPath,
                    'url'           => $baseUrl . '/uploads/media/' . $filename,
                ];
            } catch (PDOException $e) {
                // Rollback: eliminar archivo si falla la BD
                @unlink($uploadPath);
                error_log('MediaManager upload DB error: ' . $e->getMessage());
                throw new Exception('Database error while saving file');
            }
    }

    /**
     * Obtiene el MIME type real usando finfo.
     */
    private function getRealMimeType(string $filepath): string
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $filepath);
            finfo_close($finfo);
            return $mime !== false ? $mime : 'application/octet-stream';
        }
        return mime_content_type($filepath) ?: 'application/octet-stream';
    }

    /**
     * Sanitiza el nombre del archivo.
     */
    private function sanitizeFilename(string $filename): string
    {
        $filename = preg_replace('/[^\w\s\.\-]/u', '', $filename);
        $filename = str_replace(' ', '_', $filename);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        return substr($name, 0, 100) . '.' . $ext;
    }

    /**
     * Obtiene los archivos multimedia de un usuario.
     */
    public function getUserMedia(int $userId, ?string $type = null, int $limit = 50): array
    {
        $where = ['user_id = :user_id', 'deleted_at IS NULL'];
        $params = [':user_id' => $userId];

        if ($type !== null && $type !== '') {
            $where[] = 'file_type LIKE :type';
            $params[':type'] = $type . '%';
        }

        $sql = "SELECT * FROM media
        WHERE " . implode(' AND ', $where) . "
        ORDER BY created_at DESC
        LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un archivo específico por ID.
     */
    public function getMediaById(int $mediaId, ?int $userId = null): ?array
    {
        $sql = "SELECT * FROM media WHERE id = :id AND deleted_at IS NULL";
        $params = [':id' => $mediaId];

        if ($userId !== null) {
            $sql .= " AND user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * Elimina un archivo (soft delete).
     */
    public function deleteMedia(int $mediaId, int $userId): bool
    {
        $media = $this->getMediaById($mediaId, $userId);
        if (!$media) {
            return false;
        }

        // Verificar permisos
        if ((int) $media['user_id'] !== $userId) {
            return false;
        }

        try {
            $stmt = $this->db->prepare(
                "UPDATE media SET deleted_at = NOW() WHERE id = :id AND user_id = :user_id"
            );
            return $stmt->execute([':id' => $mediaId, ':user_id' => $userId]);
        } catch (PDOException $e) {
            error_log('MediaManager delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina físicamente un archivo (solo admin).
     */
    public function hardDeleteMedia(int $mediaId): bool
    {
        $media = $this->getMediaById($mediaId);
        if (!$media) {
            return false;
        }

        // 🔒 Validar path antes de eliminar
        $realPath = realpath($media['file_path']);
        $realBase = realpath($this->uploadDir);
        if ($realPath === false || $realBase === false
            || strpos($realPath, $realBase) !== 0) {
            return false;
            }

            try {
                // Eliminar archivo físico
                if (file_exists($realPath)) {
                    @unlink($realPath);
                }

                // Eliminar de BD
                $stmt = $this->db->prepare("DELETE FROM media WHERE id = :id");
                return $stmt->execute([':id' => $mediaId]);
            } catch (PDOException $e) {
                error_log('MediaManager hardDelete error: ' . $e->getMessage());
                return false;
            }
    }

    /**
     * Obtiene estadísticas de uso.
     */
    public function getStats(?int $userId = null): array
    {
        $sql = "SELECT
        COUNT(*) as total_files,
        COALESCE(SUM(file_size), 0) as total_size
        FROM media
        WHERE deleted_at IS NULL";
        $params = [];

        if ($userId !== null) {
            $sql .= " AND user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : ['total_files' => 0, 'total_size' => 0];
    }

    /**
     * Obtiene mensaje de error de upload.
     */
    private function getUploadErrorMessage(int $code): string
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server max size',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds form max size',
            UPLOAD_ERR_PARTIAL    => 'File partially uploaded',
            UPLOAD_ERR_NO_FILE    => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'No temp directory',
            UPLOAD_ERR_CANT_WRITE => 'Disk write error',
            UPLOAD_ERR_EXTENSION  => 'Upload blocked by extension',
        ];
        return $errors[$code] ?? 'Unknown upload error';
    }
}
