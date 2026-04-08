<?php
// modules/file-manager/includes/create-share-token.php
require_once '../../config/constants.php';
require_once '../../config/Database.php';
require_once '../classes/Auth.php';

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
$expires_days = $data['expires_days'] ?? 7;
$access_level = $data['access_level'] ?? 'view';

if (!$file_id) {
    echo json_encode(['success' => false, 'error' => 'ID de archivo no especificado']);
    exit();
}

// Verificar que el usuario es dueño del archivo
try {
    $query = "SELECT id, user_id FROM files WHERE id = :file_id AND deleted_at IS NULL";
    $stmt = $db->prepare($query);
    $stmt->execute([':file_id' => $file_id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$file) {
        echo json_encode(['success' => false, 'error' => 'Archivo no encontrado']);
        exit();
    }
    
    if ($file['user_id'] != $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'error' => 'No tienes permisos para compartir este archivo']);
        exit();
    }
    
} catch (PDOException $e) {
    error_log("Error verificando archivo: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
    exit();
}

// Generar token
$token = bin2hex(random_bytes(32));
$expires_at = date('Y-m-d H:i:s', strtotime("+{$expires_days} days"));

// Guardar token
try {
    $query = "INSERT INTO file_shares (file_id, shared_by, share_token, access_level, expires_at) 
              VALUES (:file_id, :shared_by, :share_token, :access_level, :expires_at)";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':file_id' => $file_id,
        ':shared_by' => $_SESSION['user_id'],
        ':share_token' => $token,
        ':access_level' => $access_level,
        ':expires_at' => $expires_at
    ]);
    
    // Log de auditoría
    log_audit_action($db, $_SESSION['user_id'], 'file_shared', 
        'Compartió archivo con token temporal (expira: ' . $expires_at . ')');
    
    // Construir URL
    $share_url = get_base_url() . '/file-manager/share.php?id=' . $file_id . '&token=' . $token;
    
    echo json_encode([
        'success' => true,
        'token' => $token,
        'share_url' => $share_url,
        'expires_at' => $expires_at
    ]);
    
} catch (PDOException $e) {
    error_log("Error creando token: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error al crear token de compartir']);
}
?>