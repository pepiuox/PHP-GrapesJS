<?php
// file-manager/preview.php
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
$thumb = isset($_GET['thumb']);

if (!$file_id) {
    http_response_code(404);
    exit();
}

// Obtener información del archivo
$file = $fileManager->getFileById($file_id, $_SESSION['user_id']);

if (!$file || !file_exists($file['file_path'])) {
    http_response_code(404);
    exit();
}

// Solo imágenes para preview
if (strpos($file['mime_type'], 'image/') !== 0) {
    // Mostrar icono por defecto
    header('Content-Type: image/png');
    readfile(dirname(__DIR__) . '/assets/default-file.png');
    exit();
}

// Para thumbnails, generar imagen reducida
if ($thumb) {
    $width = 200;
    $height = 200;
    
    // Crear thumbnail si no existe
    $thumb_path = dirname($file['file_path']) . '/thumbs/' . $file['filename'];
    $thumb_dir = dirname($thumb_path);
    
    if (!file_exists($thumb_dir)) {
        mkdir($thumb_dir, 0755, true);
    }
    
    if (!file_exists($thumb_path)) {
        createThumbnail($file['file_path'], $thumb_path, $width, $height);
    }
    
    $preview_path = $thumb_path;
} else {
    $preview_path = $file['file_path'];
}

// Mostrar imagen
header('Content-Type: ' . $file['mime_type']);
header('Content-Length: ' . filesize($preview_path));
readfile($preview_path);
exit();

/**
 * Crear thumbnail
 */
function createThumbnail($source_path, $dest_path, $max_width, $max_height) {
    list($orig_width, $orig_height, $type) = getimagesize($source_path);
    
    // Calcular nuevas dimensiones manteniendo proporción
    $ratio = $orig_width / $orig_height;
    
    if ($max_width / $max_height > $ratio) {
        $new_width = $max_height * $ratio;
        $new_height = $max_height;
    } else {
        $new_width = $max_width;
        $new_height = $max_width / $ratio;
    }
    
    // Crear imagen
    $image = imagecreatetruecolor($new_width, $new_height);
    
    // Cargar imagen original según tipo
    switch ($type) {
        case IMAGETYPE_JPEG:
            $original = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $original = imagecreatefrompng($source_path);
            // Mantener transparencia
            imagealphablending($image, false);
            imagesavealpha($image, true);
            break;
        case IMAGETYPE_GIF:
            $original = imagecreatefromgif($source_path);
            break;
        case IMAGETYPE_WEBP:
            $original = imagecreatefromwebp($source_path);
            break;
        default:
            return false;
    }
    
    // Redimensionar
    imagecopyresampled($image, $original, 0, 0, 0, 0, 
                       $new_width, $new_height, $orig_width, $orig_height);
    
    // Guardar según tipo
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($image, $dest_path, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($image, $dest_path, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($image, $dest_path);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($image, $dest_path, 90);
            break;
    }
    
    // Liberar memoria
    imagedestroy($image);
    imagedestroy($original);
    
    return true;
}
?>