<?php
// file-manager/includes/file-functions.php

/**
 * Verificar si el usuario tiene acceso al módulo de archivos
 */
function checkFileManagerAccess($auth) {
    if (!$auth->isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
    
    // Puedes agregar restricciones por rol si lo deseas
    // Ej: Solo admin y manager pueden acceder
    // if (!in_array($_SESSION['role'], ['admin', 'manager'])) {
    //     header("Location: ../../dashboard.php");
    //     exit();
    // }
}

/**
 * Obtener URL del módulo de archivos
 */
function getFileManagerUrl($path = '') {
    $base_url = dirname($_SERVER['SCRIPT_NAME']);
    return rtrim($base_url, '/') . '/' . ltrim($path, '/');
}

/**
 * Obtener ruta física del módulo
 */
function getFileManagerPath($path = '') {
    return dirname(__DIR__) . '/' . ltrim($path, '/');
}

/**
 * Sanitizar nombre de archivo
 */
function sanitizeFilename($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    $filename = preg_replace('/_+/', '_', $filename);
    return $filename;
}

/**
 * Verificar si una extensión está permitida
 */
function isExtensionAllowed($filename, $allowed_extensions = null) {
    if ($allowed_extensions === null) {
        $allowed_extensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt',
            'zip', 'rar', 'mp3', 'mp4'
        ];
    }
    
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $allowed_extensions);
}

/**
 * Generar vista previa de imagen
 */
function generateImagePreview($file_path, $max_width = 200, $max_height = 200) {
    if (!file_exists($file_path)) {
        return false;
    }
    
    $mime_type = mime_content_type($file_path);
    if (strpos($mime_type, 'image/') !== 0) {
        return false;
    }
    
    // En una implementación real, podrías generar thumbnails
    return true;
}

/**
 * Obtener archivos eliminados (para admin)
 */
function getDeletedFiles($conn) {
    $query = "SELECT f.*, u.username as deleted_by 
              FROM files f
              JOIN users u ON f.user_id = u.id
              WHERE f.deleted_at IS NOT NULL
              ORDER BY f.deleted_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Restaurar archivo eliminado
 */
function restoreFile($conn, $file_id) {
    $query = "UPDATE files 
              SET deleted_at = NULL 
              WHERE id = :file_id";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":file_id", $file_id);
    
    return $stmt->execute();
}

// Agregar al final del archivo file-functions.php

/**
 * Obtener enlace de compartir con token
 */
function getFileShareLink($conn, $file_id, $user_id, $expires_days = 7) {
    try {
        // Verificar que el usuario es dueño del archivo
        $query = "SELECT id FROM files WHERE id = :file_id AND user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':file_id' => $file_id, ':user_id' => $user_id]);
        
        if (!$stmt->fetch()) {
            return false;
        }
        
        // Generar token
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime("+{$expires_days} days"));
        
        // Guardar token
        $query = "INSERT INTO file_shares (file_id, shared_by, share_token, expires_at) 
                  VALUES (:file_id, :shared_by, :share_token, :expires_at)";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':file_id' => $file_id,
            ':shared_by' => $user_id,
            ':share_token' => $token,
            ':expires_at' => $expires_at
        ]);
        
        return get_base_url() . '/file-manager/share.php?id=' . $file_id . '&token=' . $token;
        
    } catch (PDOException $e) {
        error_log("Error generando enlace de compartir: " . $e->getMessage());
        return false;
    }
}

/**
 * Verificar si un archivo puede ser compartido
 */
function canShareFile($conn, $file_id, $user_id) {
    try {
        $query = "SELECT id FROM files WHERE id = :file_id AND user_id = :user_id AND deleted_at IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->execute([':file_id' => $file_id, ':user_id' => $user_id]);
        
        return (bool)$stmt->fetch();
        
    } catch (PDOException $e) {
        error_log("Error verificando permisos de compartir: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtener estadísticas de uso de almacenamiento
 */
function getStorageUsage($conn, $user_id = null) {
    try {
        $query = "SELECT 
                    COUNT(*) as file_count,
                    SUM(file_size) as total_size,
                    MAX(file_size) as largest_file,
                    AVG(file_size) as avg_file_size
                  FROM files 
                  WHERE deleted_at IS NULL";
        
        $params = [];
        
        if ($user_id) {
            $query .= " AND user_id = :user_id";
            $params[':user_id'] = $user_id;
        }
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calcular porcentaje de uso (suponiendo límite de 1GB)
        $limit = 1024 * 1024 * 1024; // 1GB
        $used = $stats['total_size'] ?? 0;
        $percent = $limit > 0 ? ($used / $limit) * 100 : 0;
        
        $stats['storage_limit'] = $limit;
        $stats['storage_used_percent'] = min($percent, 100);
        $stats['storage_available'] = max($limit - $used, 0);
        
        return $stats;
        
    } catch (PDOException $e) {
        error_log("Error obteniendo estadísticas de almacenamiento: " . $e->getMessage());
        return [];
    }
}

/**
 * Obtener archivos recientes
 */
function getRecentFiles($conn, $user_id = null, $limit = 10) {
    try {
        $query = "SELECT f.*, u.username as owner_name 
                  FROM files f
                  LEFT JOIN users u ON f.user_id = u.id
                  WHERE f.deleted_at IS NULL";
        
        $params = [];
        
        if ($user_id) {
            $query .= " AND (f.user_id = :user_id OR f.is_public = 1)";
            $params[':user_id'] = $user_id;
        }
        
        $query .= " ORDER BY f.created_at DESC LIMIT :limit";
        $params[':limit'] = $limit;
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Error obteniendo archivos recientes: " . $e->getMessage());
        return [];
    }
}

/**
 * Limpiar archivos temporales expirados
 */
function cleanupExpiredFiles($conn) {
    try {
        // Archivos eliminados hace más de 30 días (hard delete)
        $query = "SELECT id, file_path FROM files 
                  WHERE deleted_at IS NOT NULL 
                  AND deleted_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        
        $stmt = $conn->query($query);
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($files as $file) {
            // Eliminar archivo físico
            if (file_exists($file['file_path'])) {
                unlink($file['file_path']);
            }
            
            // Eliminar de la base de datos
            $deleteStmt = $conn->prepare("DELETE FROM files WHERE id = :id");
            $deleteStmt->execute([':id' => $file['id']]);
        }
        
        // Limpiar tokens de compartir expirados
        $query = "DELETE FROM file_shares WHERE expires_at < NOW()";
        $conn->exec($query);
        
        return count($files);
        
    } catch (PDOException $e) {
        error_log("Error limpiando archivos expirados: " . $e->getMessage());
        return 0;
    }
}
?>