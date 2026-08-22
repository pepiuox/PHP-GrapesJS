<?php
// 1. Forzar respuesta JSON consistente en todo el script
header('Content-Type: application/json');

// 2. Configuración segura de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        // Se elimina 'domain' para evitar Host Header Injection. PHP lo maneja seguro por defecto.
        'secure' => true,      // Solo enviar por HTTPS
        'httponly' => true,    // Inaccesible desde JavaScript (previene robo de sesión por XSS)
    'samesite' => 'Strict' // Protección máxima contra CSRF
    ]);
    session_start();
}

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

        // 6.1. Verificar errores de subida de PHP
        $uploadError = $_FILES['file']['error'][$key];
        if ($uploadError !== UPLOAD_ERR_OK) {
            $uploadResults[] = ['original_name' => $_FILES['file']['name'][$key], 'status' => 'error', 'message' => 'Error en la subida (Código: ' . $uploadError . ')'];
            $hasErrors = true;
            continue;
        }

        $fileSize = $_FILES['file']['size'][$key];
        $originalName = basename($_FILES['file']['name'][$key]);
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // 6.2. Validar tamaño
        if ($fileSize > MAX_FILE_SIZE) {
            $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'El archivo excede el límite de 5 MB.'];
            $hasErrors = true;
            continue;
        }

        // 6.3. Validar extensión (Whitelist)
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'Extensión de archivo no permitida.'];
            $hasErrors = true;
            continue;
        }

        // 6.4. Validar tipo MIME real (NO confiar en $_FILES['type'])
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tmpName);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, ALLOWED_MIME_TYPES) || ALLOWED_MIME_TYPES[$mimeType] !== $extension) {
            $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'El contenido del archivo no coincide con su extensión.'];
            $hasErrors = true;
            continue;
        }

        // 6.5. Validar que sea una imagen real (previene imágenes con código PHP incrustado)
        if (getimagesize($tmpName) === false) {
            $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'El archivo no es una imagen válida o está corrupto.'];
            $hasErrors = true;
            continue;
        }

        // 6.6. Generar nombre de archivo seguro y único (Mitiga Path Traversal y sobrescritura)
        $secureFileName = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetFilePath = $targetDir . DIRECTORY_SEPARATOR . $secureFileName;

        // 6.7. Mover el archivo
        if (move_uploaded_file($tmpName, $targetFilePath)) {
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
        } else {
            $uploadResults[] = ['original_name' => $originalName, 'status' => 'error', 'message' => 'Error del servidor al guardar el archivo.'];
            $hasErrors = true;
        }
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
