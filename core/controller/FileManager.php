<?php
// file-manager/classes/FileManager.php

class FileManager {
    private $conn;
    private $table_name = "files";
    private $uploads_dir;
    
    public $id;
    public $user_id;
    public $filename;
    public $original_name;
    public $file_path;
    public $file_size;
    public $file_type;
    public $mime_type;
    public $category;
    public $description;
    public $is_public;
    
    public function __construct($db) {
        $this->conn = $db;
        // Definir directorio de uploads
        $this->uploads_dir = dirname(__DIR__) . '/uploads/';
        
        // Crear directorio si no existe
        if (!file_exists($this->uploads_dir)) {
            mkdir($this->uploads_dir, 0755, true);
            mkdir($this->uploads_dir . 'documents/', 0755, true);
            mkdir($this->uploads_dir . 'images/', 0755, true);
            mkdir($this->uploads_dir . 'audio/', 0755, true);
            mkdir($this->uploads_dir . 'video/', 0755, true);
            mkdir($this->uploads_dir . 'archives/', 0755, true);
            mkdir($this->uploads_dir . 'others/', 0755, true);
        }
    }
    
    /**
     * Subir un archivo
     */
    public function upload($file, $user_id, $description = '', $is_public = 0) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => $this->getUploadError($file['error'])];
        }
        
        // Validar tamaño máximo (10MB por defecto)
        $max_size = 10 * 1024 * 1024; // 10MB
        if ($file['size'] > $max_size) {
            return ['success' => false, 'error' => 'El archivo es demasiado grande (máximo 10MB)'];
        }
        
        // Validar tipo de archivo
        $allowed_types = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain', 'application/zip', 'application/x-rar-compressed'
        ];
        
        $mime_type = mime_content_type($file['tmp_name']);
        if (!in_array($mime_type, $allowed_types)) {
            return ['success' => false, 'error' => 'Tipo de archivo no permitido'];
        }
        
        // Determinar categoría
        $category = $this->getFileCategory($mime_type);
        
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_name = uniqid() . '_' . time() . '.' . $extension;
        $upload_path = $this->uploads_dir . $category . 's/' . $unique_name;
        
        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            return ['success' => false, 'error' => 'Error al guardar el archivo'];
        }
        
        // Guardar en base de datos
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, filename, original_name, file_path, file_size, 
                   file_type, mime_type, category, description, is_public) 
                  VALUES (:user_id, :filename, :original_name, :file_path, :file_size, 
                          :file_type, :mime_type, :category, :description, :is_public)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":filename", $unique_name);
        $stmt->bindParam(":original_name", $file['name']);
        $stmt->bindParam(":file_path", $upload_path);
        $stmt->bindParam(":file_size", $file['size']);
        $stmt->bindParam(":file_type", $extension);
        $stmt->bindParam(":mime_type", $mime_type);
        $stmt->bindParam(":category", $category);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":is_public", $is_public);
        
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return [
                'success' => true,
                'id' => $this->id,
                'filename' => $unique_name,
                'path' => $upload_path
            ];
        }
        
        return ['success' => false, 'error' => 'Error al guardar en la base de datos'];
    }
    
    /**
     * Obtener todos los archivos del usuario
     */
    public function getUserFiles($user_id, $category = null, $search = null) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE user_id = :user_id 
                  AND deleted_at IS NULL";
        
        $params = [':user_id' => $user_id];
        
        if ($category) {
            $query .= " AND category = :category";
            $params[':category'] = $category;
        }
        
        if ($search) {
            $query .= " AND (original_name LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt;
    }
    
    /**
     * Obtener archivo por ID
     */
    public function getFileById($file_id, $user_id = null) {
        $query = "SELECT f.*, u.username as owner_name 
                  FROM " . $this->table_name . " f
                  LEFT JOIN users u ON f.user_id = u.id
                  WHERE f.id = :file_id";
        
        if ($user_id) {
            $query .= " AND (f.user_id = :user_id OR f.is_public = 1)";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":file_id", $file_id);
        
        if ($user_id) {
            $stmt->bindParam(":user_id", $user_id);
        }
        
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Eliminar archivo
     */
    public function delete($file_id, $user_id) {
        // Obtener información del archivo
        $file = $this->getFileById($file_id, $user_id);
        
        if (!$file) {
            return ['success' => false, 'error' => 'Archivo no encontrado'];
        }
        
        // Verificar permisos
        if ($file['user_id'] != $user_id) {
            return ['success' => false, 'error' => 'No tienes permisos para eliminar este archivo'];
        }
        
        // Soft delete (marcar como eliminado)
        $query = "UPDATE " . $this->table_name . " 
                  SET deleted_at = NOW() 
                  WHERE id = :file_id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":file_id", $file_id);
        $stmt->bindParam(":user_id", $user_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Archivo eliminado correctamente'];
        }
        
        return ['success' => false, 'error' => 'Error al eliminar el archivo'];
    }
    
    /**
     * Eliminar archivo físicamente (solo admin)
     */
    public function hardDelete($file_id) {
        $file = $this->getFileById($file_id);
        
        if (!$file) {
            return ['success' => false, 'error' => 'Archivo no encontrado'];
        }
        
        // Eliminar archivo físico
        if (file_exists($file['file_path'])) {
            unlink($file['file_path']);
        }
        
        // Eliminar de la base de datos
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :file_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":file_id", $file_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Archivo eliminado permanentemente'];
        }
        
        return ['success' => false, 'error' => 'Error al eliminar el archivo'];
    }
    
    /**
     * Actualizar información del archivo
     */
    public function update($file_id, $user_id, $data) {
        $allowed_fields = ['description', 'is_public'];
        $updates = [];
        $params = [':file_id' => $file_id, ':user_id' => $user_id];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                $updates[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        
        if (empty($updates)) {
            return ['success' => false, 'error' => 'No hay campos para actualizar'];
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET " . implode(', ', $updates) . ", updated_at = NOW()
                  WHERE id = :file_id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute($params)) {
            return ['success' => true, 'message' => 'Archivo actualizado correctamente'];
        }
        
        return ['success' => false, 'error' => 'Error al actualizar el archivo'];
    }
    
    /**
     * Incrementar contador de descargas
     */
    public function incrementDownloadCount($file_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET download_count = download_count + 1 
                  WHERE id = :file_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":file_id", $file_id);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener estadísticas de archivos
     */
    public function getStats($user_id = null) {
        $query = "SELECT 
                    COUNT(*) as total_files,
                    SUM(file_size) as total_size,
                    COUNT(CASE WHEN category = 'image' THEN 1 END) as images,
                    COUNT(CASE WHEN category = 'document' THEN 1 END) as documents,
                    COUNT(CASE WHEN is_public = 1 THEN 1 END) as public_files
                  FROM " . $this->table_name . " 
                  WHERE deleted_at IS NULL";
        
        $params = [];
        
        if ($user_id) {
            $query .= " AND user_id = :user_id";
            $params[':user_id'] = $user_id;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Determinar categoría del archivo
     */
    private function getFileCategory($mime_type) {
        if (strpos($mime_type, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mime_type, 'audio/') === 0) {
            return 'audio';
        } elseif (strpos($mime_type, 'video/') === 0) {
            return 'video';
        } elseif (in_array($mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain'
        ])) {
            return 'document';
        } elseif (in_array($mime_type, [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-tar',
            'application/x-7z-compressed'
        ])) {
            return 'archive';
        } else {
            return 'other';
        }
    }
    
    /**
     * Obtener mensaje de error de upload
     */
    private function getUploadError($error_code) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por el servidor',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo fue subido parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'No hay directorio temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir en el disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida'
        ];
        
        return $errors[$error_code] ?? 'Error desconocido al subir el archivo';
    }
    
    /**
     * Formatear tamaño de archivo
     */
    public static function formatFileSize($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
    
    /**
     * Obtener icono según tipo de archivo
     */
    public static function getFileIcon($file_type) {
        $icons = [
            'pdf' => 'fas fa-file-pdf',
            'doc' => 'fas fa-file-word',
            'docx' => 'fas fa-file-word',
            'xls' => 'fas fa-file-excel',
            'xlsx' => 'fas fa-file-excel',
            'txt' => 'fas fa-file-alt',
            'jpg' => 'fas fa-file-image',
            'jpeg' => 'fas fa-file-image',
            'png' => 'fas fa-file-image',
            'gif' => 'fas fa-file-image',
            'zip' => 'fas fa-file-archive',
            'rar' => 'fas fa-file-archive',
            'mp3' => 'fas fa-file-audio',
            'mp4' => 'fas fa-file-video',
            'default' => 'fas fa-file'
        ];
        
        return $icons[strtolower($file_type)] ?? $icons['default'];
    }
    
    /**
     * Obtener color según categoría
     */
    public static function getCategoryColor($category) {
        $colors = [
            'document' => 'primary',
            'image' => 'success',
            'audio' => 'info',
            'video' => 'warning',
            'archive' => 'secondary',
            'other' => 'dark'
        ];
        
        return $colors[$category] ?? 'secondary';
    }
}
?>