<?php
// file-manager/delete.php
require_once '../config/constants.php';
require_once '../config/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/FileManager.php';
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

// Eliminar archivo
$result = $fileManager->delete($file_id, $_SESSION['user_id']);

if ($result['success']) {
    set_flash_message('success', $result['message']);
} else {
    set_flash_message('error', $result['error']);
}

// Redirigir
header("Location: " . getFileManagerUrl("index.php"));
exit();
?>