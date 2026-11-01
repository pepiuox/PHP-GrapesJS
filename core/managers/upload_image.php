<?php
require_once dirname(__DIR__) . '/Autoload.php';

// 1. Forzar respuesta JSON consistente en todo el script
header('Content-Type: application/json');

// 2. Sesión con la configuración central: `httponly`, `samesite=Lax` y `secure`
// sólo cuando la petición es realmente HTTPS (forzarlo en HTTP rompe el login).
\PepiuoX\Security\SessionConfig::start();

// 3. Verificar autorización (DESBLOQUEAR EN PRODUCCIÓN)
// if (!isset($_SESSION['user_id'])) {
//     http_response_code(401);
//     echo json_encode(['success' => false, 'error' => 'No autorizado.']);
//     exit;
// }

// 4. Constantes de seguridad estrictas
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB máximo
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_MIME_TYPES', [
    'image/jpeg' => 'jpg', // Permite jpg y jpeg
    'image/png'  => 'png',
    'image/gif'  => 'gif'
]);

// 5. Validar directorio de destino de forma segura
$targetDir = realpath($_SERVER['DOCUMENT_ROOT'] . '/build/uploads');
if (!$targetDir || !is_dir($targetDir) || !is_writable($targetDir)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error de configuración: directorio no válido o sin permisos.']);
    exit;
}

$uploadResults = [];
$hasErrors = false;

// 6. Procesar múltiples archivos subidos
if (isset($_FILES['file']) && is_array($_FILES['file']['tmp_name'])) {
    foreach ($_FILES['file']['tmp_name'] as $key => $tmpName) {

        $fileSize = $_FILES['file']['size'][$key];
        $originalName = basename($_FILES['file']['name'][$key]);

        // 6.1. Guardar con un nombre generado por el servidor (mitiga path traversal
        // y sobrescritura). `Upload::storeImage()` valida el código de error, el
        // tamaño, la extensión y el MIME real del contenido (finfo), no
        // `$_FILES[...]['type']`, que lo controla el cliente.
        $file = [
            'name'     => $originalName,
            'tmp_name' => $tmpName,
            'size'     => $fileSize,
            'error'    => $_FILES['file']['error'][$key],
        ];

        $upload_error = null;
        $secureFileName = \PepiuoX\Security\Upload::storeImage($file, $targetDir, MAX_FILE_SIZE, $upload_error);

        if ($secureFileName === null) {
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'Error en la subida (Código: ' . $file['error'] . ')'];
            } elseif ($fileSize > MAX_FILE_SIZE) {
                $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'El archivo excede el límite de 5 MB.'];
            } elseif (!in_array(\PepiuoX\Security\Upload::extension($originalName), ALLOWED_EXTENSIONS, true)) {
                $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'Extensión de archivo no permitida.'];
            } elseif (in_array($upload_error, [
                'El directorio de destino no existe o no es escribible.',
                'No se pudo resolver el directorio de destino.',
                'Ruta de destino no permitida.',
                'No se pudo guardar el archivo.',
            ], true)) {
                // Fallo de almacenamiento (no de validación del archivo)
                $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'Error del servidor al guardar el archivo.'];
            } else {
                $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'El contenido del archivo no coincide con su extensión.'];
            }
            $hasErrors = true;
            continue;
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $publicUrl = $protocol . $host . '/build/uploads/' . $secureFileName;

        $uploadResults[] = [
            'original_name' => $originalName,
            'saved_as'      => $secureFileName,
            'url'           => $publicUrl,
            'status'        => 'success'
        ];

        // AQUÍ: Insertar $secureFileName en la base de datos usando Prepared Statements (PDO)
    }
}

// 7. Función corregida y segura para listar imágenes
function GetImagesToFolder(string $targetDir, string $imgDir): array {
    $ImagesArray = [];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!is_dir($targetDir) || !is_readable($targetDir)) {
        return []; // Retornar array vacío en lugar de un mensaje de error mal formado
    }

    $dirContents = scandir($targetDir);
    foreach ($dirContents as $file) {
        // Ignorar directorios actuales y padres
        if ($file === '.' || $file === '..') {
            continue;
        }

        $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($fileType, $allowedExtensions, true)) {
            // Asegurar que el nombre del archivo no tenga caracteres raros al servirlo
            $safeFile = urlencode($file);
            $ImagesArray[] = rtrim($imgDir, '/') . '/' . $safeFile;
        }
    }
    return $ImagesArray;
}

// 8. Respuesta JSON final unificada y limpia
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
$imgDirUrl = $protocol . $_SERVER['HTTP_HOST'] . '/build/uploads';

$galleryImages = GetImagesToFolder($targetDir, $imgDirUrl);

http_response_code($hasErrors ? 400 : 200);
echo json_encode([
    'success' => !$hasErrors,
    'uploaded' => $uploadResults,
    'gallery' => $galleryImages
], JSON_UNESCAPED_SLASHES);
