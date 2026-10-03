<?php
declare(strict_types=1);

/**
 * Gestor de archivos con validación de seguridad robusta.
 */
class FileManager
{
    private PDO $conn;
    private string $table_name = "files";
    private string $uploads_dir;
    private int $max_file_size;
    private array $allowed_types;
    private array $allowed_extensions;

    public int $id = 0;
    public int $user_id = 0;
    public string $filename = '';
    public string $original_name = '';
    public string $file_path = '';
    public int $file_size = 0;
    public string $file_type = '';
    public string $mime_type = '';
    public string $category = '';
    public string $description = '';
    public int $is_public = 0;

    public function __construct(PDO $db, int $maxSizeMB = 10)
    {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->max_file_size = $maxSizeMB * 1024 * 1024;

        // Tipos MIME permitidos
        $this->allowed_types = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'application/zip', 'application/x-rar-compressed',
        ];

        // Extensiones permitidas (validación doble)
        $this->allowed_extensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx',
            'txt', 'zip', 'rar',
        ];

        $this->initUploadDirs();
    }

    /**
     * Inicializa los directorios de uploads.
     */
    private function initUploadDirs(): void
    {
        $this->uploads_dir = dirname(__DIR__) . '/uploads/';

        $dirs = ['', 'documents/', 'images/', 'audio/', 'video/', 'archives/', 'others/'];
        foreach ($dirs as $dir) {
            $path = $this->uploads_dir . $dir;
            if (!is_dir($path)) {
                if (!mkdir($path, 0755, true) && !is_dir($path)) {
                    throw new RuntimeException("Failed to create directory: {$path}");
                }
                // Crear .htaccess para prevenir ejecución de PHP
                file_put_contents($path . '.htaccess', "Options -Indexes\nphp_flag engine off\n");
            }
        }
    }

    /**
     * Sube un archivo con validación completa.
     */
    public function upload(array $file, int $user_id, string $description = '', int $is_public = 0): array
    {
        // Validar errores de upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => $this->getUploadError($file['error'])];
        }

        // Validar tamaño
        if ($file['size'] > $this->max_file_size) {
            return [
                'success' => false,
                'error' => 'File too large (max ' . ($this->max_file_size / 1024 / 1024) . 'MB)',
            ];
        }

        if ($file['size'] === 0) {
            return ['success' => false, 'error' => 'Empty file'];
        }

        // 🔒 Validación doble: MIME + extensión
        $mime_type = $this->getRealMimeType($file['tmp_name']);
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mime_type, $this->allowed_types, true)) {
            return ['success' => false, 'error' => 'File type not allowed (MIME)'];
        }

        if (!in_array($extension, $this->allowed_extensions, true)) {
            return ['success' => false, 'error' => 'File extension not allowed'];
        }

        // 🔒 Verificar que MIME y extensión coincidan
        if (!$this->validateMimeExtension($mime_type, $extension)) {
            return ['success' => false, 'error' => 'MIME type does not match extension'];
        }

        // 🔒 Sanitizar nombre de archivo
        $original_name = $this->sanitizeFilename($file['name']);

        // Determinar categoría
        $category = $this->getFileCategory($mime_type);

        // Generar nombre único seguro
        $unique_name = bin2hex(random_bytes(16)) . '_' . time() . '.' . $extension;
        $upload_path = $this->uploads_dir . $category . 's/' . $unique_name;

        // 🔒 Verificar que el path final esté dentro del directorio permitido
        $realUploadPath = realpath(dirname($upload_path));
        $realBasePath = realpath($this->uploads_dir);
        if ($realUploadPath === false || strpos($realUploadPath, $realBasePath) !== 0) {
            return ['success' => false, 'error' => 'Invalid upload path'];
        }

        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            return ['success' => false, 'error' => 'Failed to save file'];
        }

        // Establecer permisos seguros
        chmod($upload_path, 0644);

        // Guardar en BD
        $query = "INSERT INTO {$this->table_name}
        (user_id, filename, original_name, file_path, file_size,
        file_type, mime_type, category, description, is_public)
        VALUES (:user_id, :filename, :original_name, :file_path, :file_size,
        :file_type, :mime_type, :category, :description, :is_public)";

        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ':user_id'       => $user_id,
                ':filename'      => $unique_name,
                ':original_name' => $original_name,
                ':file_path'     => $upload_path,
                ':file_size'     => $file['size'],
                ':file_type'     => $extension,
                ':mime_type'     => $mime_type,
                ':category'      => $category,
                ':description'   => $description,
                ':is_public'     => $is_public,
            ]);

            $this->id = (int) $this->conn->lastInsertId();

            return [
                'success'  => true,
                'id'       => $this->id,
                'filename' => $unique_name,
                'path'     => $upload_path,
            ];
        } catch (PDOException $e) {
            // Eliminar archivo si falla la BD
            @unlink($upload_path);
            error_log('FileManager upload DB error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Database error'];
        }
    }

    /**
     * Obtiene el MIME type real usando finfo (más seguro que mime_content_type).
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
     * Valida que el MIME y la extensión sean compatibles.
     */
    private function validateMimeExtension(string $mime, string $ext): bool
    {
        $map = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png'  => ['png'],
            'image/gif'  => ['gif'],
            'image/webp' => ['webp'],
            'application/pdf' => ['pdf'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
            'application/vnd.ms-excel' => ['xls'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
            'text/plain' => ['txt'],
            'application/zip' => ['zip'],
            'application/x-rar-compressed' => ['rar'],
        ];

        if (!isset($map[$mime])) {
            return false;
        }

        return in_array($ext, $map[$mime], true);
    }

    /**
     * Sanitiza el nombre del archivo.
     */
    private function sanitizeFilename(string $filename): string
    {
        // Eliminar caracteres peligrosos
        $filename = preg_replace('/[^\w\s\.\-]/u', '', $filename);
        // Reemplazar espacios
        $filename = str_replace(' ', '_', $filename);
        // Limitar longitud
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = substr($name, 0, 100);

        return $name . '.' . $ext;
    }

    /**
     * Obtiene archivos del usuario con filtros.
     */
    public function getUserFiles(int $user_id, ?string $category = null, ?string $search = null): array
    {
        $query = "SELECT * FROM {$this->table_name}
        WHERE user_id = :user_id AND deleted_at IS NULL";
        $params = [':user_id' => $user_id];

        if ($category !== null && $category !== '') {
            $query .= " AND category = :category";
            $params[':category'] = $category;
        }

        if ($search !== null && $search !== '') {
            $query .= " AND (original_name LIKE :search OR description LIKE :search2)";
            $params[':search'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
        }

        $query .= " ORDER BY created_at DESC LIMIT 100";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un archivo por ID con validación de permisos.
     */
    public function getFileById(int $file_id, ?int $user_id = null): ?array
    {
        $query = "SELECT f.*, u.username as owner_name
        FROM {$this->table_name} f
        LEFT JOIN users u ON f.user_id = u.id
        WHERE f.id = :file_id AND f.deleted_at IS NULL";
        $params = [':file_id' => $file_id];

        if ($user_id !== null) {
            $query .= " AND (f.user_id = :user_id OR f.is_public = 1)";
            $params[':user_id'] = $user_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    /**
     * Soft delete de archivo.
     */
    public function delete(int $file_id, int $user_id): array
    {
        $file = $this->getFileById($file_id, $user_id);
        if (!$file) {
            return ['success' => false, 'error' => 'File not found'];
        }

        if ((int) $file['user_id'] !== $user_id) {
            return ['success' => false, 'error' => 'Permission denied'];
        }

        $stmt = $this->conn->prepare(
            "UPDATE {$this->table_name} SET deleted_at = NOW() WHERE id = :file_id AND user_id = :user_id"
        );

        if ($stmt->execute([':file_id' => $file_id, ':user_id' => $user_id])) {
            return ['success' => true, 'message' => 'File deleted'];
        }

        return ['success' => false, 'error' => 'Delete failed'];
    }

    /**
     * Hard delete (solo admin).
     */
    public function hardDelete(int $file_id): array
    {
        $file = $this->getFileById($file_id);
        if (!$file) {
            return ['success' => false, 'error' => 'File not found'];
        }

        // 🔒 Validar path antes de eliminar
        $realPath = realpath($file['file_path']);
        $realBase = realpath($this->uploads_dir);
        if ($realPath === false || strpos($realPath, $realBase) !== 0) {
            return ['success' => false, 'error' => 'Invalid file path'];
        }

        if (file_exists($realPath)) {
            @unlink($realPath);
        }

        $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id = :file_id");
        if ($stmt->execute([':file_id' => $file_id])) {
            return ['success' => true, 'message' => 'File permanently deleted'];
        }

        return ['success' => false, 'error' => 'Delete failed'];
    }

    /**
     * Actualiza información del archivo.
     */
    public function update(int $file_id, int $user_id, array $data): array
    {
        $allowed_fields = ['description', 'is_public'];
        $updates = [];
        $params = [':file_id' => $file_id, ':user_id' => $user_id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed_fields, true)) {
                $updates[] = "`{$key}` = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        if (empty($updates)) {
            return ['success' => false, 'error' => 'No fields to update'];
        }

        $query = "UPDATE {$this->table_name}
        SET " . implode(', ', $updates) . ", updated_at = NOW()
        WHERE id = :file_id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        if ($stmt->execute($params)) {
            return ['success' => true, 'message' => 'File updated'];
        }

        return ['success' => false, 'error' => 'Update failed'];
    }

    /**
     * Incrementa contador de descargas.
     */
    public function incrementDownloadCount(int $file_id): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table_name} SET download_count = download_count + 1 WHERE id = :file_id"
        );
        return $stmt->execute([':file_id' => $file_id]);
    }

    /**
     * Obtiene estadísticas.
     */
    public function getStats(?int $user_id = null): array
    {
        $query = "SELECT
        COUNT(*) as total_files,
        COALESCE(SUM(file_size), 0) as total_size,
        COUNT(CASE WHEN category = 'image' THEN 1 END) as images,
        COUNT(CASE WHEN category = 'document' THEN 1 END) as documents,
        COUNT(CASE WHEN is_public = 1 THEN 1 END) as public_files
        FROM {$this->table_name}
        WHERE deleted_at IS NULL";
        $params = [];

        if ($user_id !== null) {
            $query .= " AND user_id = :user_id";
            $params[':user_id'] = $user_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : [];
    }

    /**
     * Determina la categoría del archivo.
     */
    private function getFileCategory(string $mime_type): string
    {
        if (str_starts_with($mime_type, 'image/')) return 'image';
        if (str_starts_with($mime_type, 'audio/')) return 'audio';
        if (str_starts_with($mime_type, 'video/')) return 'video';

        $documentTypes = [
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
        ];
        if (in_array($mime_type, $documentTypes, true)) return 'document';

        $archiveTypes = [
            'application/zip', 'application/x-rar-compressed',
            'application/x-tar', 'application/x-7z-compressed',
        ];
        if (in_array($mime_type, $archiveTypes, true)) return 'archive';

        return 'other';
    }

    /**
     * Obtiene mensaje de error de upload.
     */
    private function getUploadError(int $code): string
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

    /**
     * Formatea tamaño de archivo.
     */
    public static function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
        if ($bytes > 1)           return $bytes . ' bytes';
        if ($bytes === 1)         return '1 byte';
        return '0 bytes';
    }

    /**
     * Obtiene icono según tipo.
     */
    public static function getFileIcon(string $file_type): string
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf',
            'doc' => 'fas fa-file-word', 'docx' => 'fas fa-file-word',
            'xls' => 'fas fa-file-excel', 'xlsx' => 'fas fa-file-excel',
            'txt' => 'fas fa-file-alt',
            'jpg' => 'fas fa-file-image', 'jpeg' => 'fas fa-file-image',
            'png' => 'fas fa-file-image', 'gif' => 'fas fa-file-image',
            'zip' => 'fas fa-file-archive', 'rar' => 'fas fa-file-archive',
            'mp3' => 'fas fa-file-audio', 'mp4' => 'fas fa-file-video',
        ];
        return $icons[strtolower($file_type)] ?? 'fas fa-file';
    }

    /**
     * Obtiene color según categoría.
     */
    public static function getCategoryColor(string $category): string
    {
        $colors = [
            'document' => 'primary', 'image' => 'success',
            'audio' => 'info', 'video' => 'warning',
            'archive' => 'secondary', 'other' => 'dark',
        ];
        return $colors[$category] ?? 'secondary';
    }
}
