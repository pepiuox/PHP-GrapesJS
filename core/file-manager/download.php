<?php
// file-manager/download.php
require_once '../config/constants.php';
require_once '../config/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/FileManager.php';
require_once '../includes/functions.php';
require_once '../includes/file-functions.php';

// Inicializar
$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$fileManager = new FileManager($db);

// Verificar acceso
checkFileManagerAccess($auth);

// Obtener ID del archivo
$file_id = $_GET['id'] ?? 0;

if (!$file_id) {
    header("Location: " . getFileManagerUrl("index.php"));
    exit();
}

// Obtener información del archivo
$file = $fileManager->getFileById($file_id, $_SESSION['user_id']);

if (!$file) {
    header("Location: " . getFileManagerUrl("index.php") . "?error=Archivo no encontrado");
    exit();
}

// Verificar que el archivo existe físicamente
if (!file_exists($file['file_path'])) {
    header("Location: " . getFileManagerUrl("index.php") . "?error=El archivo no existe en el servidor");
    exit();
}

// Incrementar contador de descargas
$fileManager->incrementDownloadCount($file_id);

// Log de auditoría
log_audit_action($db, $_SESSION['user_id'], 'file_download', 'Descargó: ' . $file['original_name']);

// Configurar headers para descarga
header('Content-Description: File Transfer');
header('Content-Type: ' . $file['mime_type']);
header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file['file_path']));

// Limpiar buffer de salida
ob_clean();
flush();

// Leer y enviar archivo
readfile($file['file_path']);
exit();
?>