<?php
// file-manager/includes/toggle-public.php
require_once '../../config/constants.php';
require_once '../../config/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/FileManager.php';

// Solo para peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit();
}

// Inicializar
$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$fileManager = new FileManager($db);

// Verificar acceso
session_start();
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

// Obtener datos
$data = json_decode(file_get_contents('php://input'), true);
$file_id = $data['file_id'] ?? 0;
$is_public = $data['is_public'] ?? false;

if (!$file_id) {
    echo json_encode(['success' => false, 'error' => 'ID de archivo no especificado']);
    exit();
}

// Verificar que el usuario es dueño del archivo
$file = $fileManager->getFileById($file_id, $_SESSION['user_id']);
if (!$file || $file['user_id'] != $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'error' => 'No tienes permisos para este archivo']);
    exit();
}

// Actualizar visibilidad
try {
    $query = "UPDATE files SET is_public = :is_public WHERE id = :file_id";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':is_public' => $is_public ? 1 : 0,
        ':file_id' => $file_id
    ]);
    
    // Log de auditoría
    log_audit_action($db, $_SESSION['user_id'], 'file_visibility_changed', 
        ($is_public ? 'Hizo público' : 'Hizo privado') . ' el archivo: ' . $file['original_name']);
    
    echo json_encode(['success' => true, 'is_public' => $is_public]);
    
} catch (PDOException $e) {
    error_log("Error actualizando visibilidad: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
}
?>