<?php

class MediaManager {
    private $db;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function uploadFile($file, $user_id) {
        // Validar archivo
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error en la subida del archivo: " . $file['error']);
        }
        
        if ($file['size'] > MAX_FILE_SIZE) {
            throw new Exception("El archivo es demasiado grande");
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            throw new Exception("Tipo de archivo no permitido");
        }
        
        // Generar nombre único
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $upload_path = UPLOAD_DIR . '/media/' . $filename;
        
        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception("Error al mover el archivo");
        }
        
        // Guardar en base de datos
        $sql = "INSERT INTO media (user_id, filename, original_name, file_path, file_type, file_size) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $user_id,
            $filename,
            $file['name'],
            $upload_path,
            $file['type'],
            $file['size']
        ]);
        
        return [
            'id' => $this->db->lastInsertId(),
            'filename' => $filename,
            'original_name' => $file['name'],
            'file_path' => $upload_path,
            'url' => APP_URL . '/uploads/media/' . $filename
        ];
    }
    
    public function getUserMedia($user_id, $type = null) {
        $where = ["user_id = ?"];
        $params = [$user_id];
        
        if ($type) {
            $where[] = "file_type LIKE ?";
            $params[] = "$type%";
        }
        
        $sql = "SELECT * FROM media WHERE " . implode(' AND ', $where) . " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function deleteMedia($media_id, $user_id) {
        // Obtener información del archivo
        $stmt = $this->db->prepare("SELECT * FROM media WHERE id = ? AND user_id = ?");
        $stmt->execute([$media_id, $user_id]);
        $media = $stmt->fetch();
        
        if (!$media) {
            return false;
        }
        
        // Eliminar archivo físico
        if (file_exists($media['file_path'])) {
            unlink($media['file_path']);
        }
        
        // Eliminar de la base de datos
        $stmt = $this->db->prepare("DELETE FROM media WHERE id = ?");
        return $stmt->execute([$media_id]);
    }
}


