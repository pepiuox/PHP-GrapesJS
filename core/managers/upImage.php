<?php
// 1. Forzar siempre la respuesta a ser JSON
header('Content-Type: application/json');

// 2. Iniciar sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    // Configurar cookies de sesión seguras (requerido para producción)
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,      // Solo enviar por HTTPS
        'httponly' => true,    // Inaccesible desde JavaScript
        'samesite' => 'Strict' // Protección contra CSRF
    ]);
    session_start();
}

// 3. Verificar autenticación/autorización (DESBLOQUEAR EN PRODUCCIÓN)
// if (!isset($_SESSION['user_id'])) {
//     http_response_code(401);
//     echo json_encode(['error' => 'No autorizado. Debe iniciar sesión.']);
//     exit;
// }

// 4. Configuración estricta de seguridad
$MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB máximo
$ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$ALLOWED_MIME_TYPES = [
    'image/jpeg' => ['jpg', 'jpeg'],
'image/png'  => ['png'],
'image/gif'  => ['gif'],
'image/webp' => ['webp']
];

// 5. Validar directorio de destino (ruta absoluta en el servidor)
$targetDir = realpath($_SERVER['DOCUMENT_ROOT'] . '/build/uploads');

if (!$targetDir || !is_dir($targetDir) || !is_writable($targetDir)) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de configuración: directorio de uploads no válido o sin permisos de escritura.']);
    exit;
}

$resultArray = [];

// 6. Procesar archivos subidos
if (!empty($_FILES)) {
    foreach ($_FILES as $inputName => $file) {
        // Nota: Si usas <input type="file" name="archivos[]" multiple>,
        // $file['name'] será un array y necesitarás un bucle anidado.
        // Esta versión asume un archivo por input para simplificar.
        if (is_array($file['name'])) {
            continue;
        }

        // 6.1 Verificar errores de subida de PHP PRIMERO
        if ($file['error'] !== UPLOAD_ERR_OK) {
            error_log("Error de subida en '$inputName': Código " . $file['error']);
            http_response_code(400);
            echo json_encode(['error' => 'Error al procesar la subida del archivo.']);
            exit;
        }

        // 6.2 Validar tamaño del archivo
        if ($file['size'] > $MAX_FILE_SIZE) {
            http_response_code(400);
            echo json_encode(['error' => 'El archivo excede el tamaño máximo permitido (5 MB).']);
            exit;
        }

        // 6.3 Validar extensión (Whitelist estricta)
        $originalName = $file['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $ALLOWED_EXTENSIONS)) {
            http_response_code(400);
            echo json_encode(['error' => 'Tipo de archivo no permitido.']);
            exit;
        }

        // 6.4 Validar tipo MIME real del archivo (NO confiar en $_FILES['type'])
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, $ALLOWED_MIME_TYPES) || !in_array($extension, $ALLOWED_MIME_TYPES[$mimeType])) {
            http_response_code(400);
            echo json_encode(['error' => 'El contenido del archivo no coincide con su extensión.']);
            exit;
        }

        // 6.5 Validar que sea una imagen real ANTES de moverla (previene ejecución)
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            http_response_code(400);
            echo json_encode(['error' => 'El archivo no es una imagen válida o está corrupto.']);
            exit;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // 6.6 Generar un nombre de archivo seguro y único (Mitiga Path Traversal y sobrescritura)
        $secureFileName = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetFilePath = $targetDir . DIRECTORY_SEPARATOR . $secureFileName;

        // 6.7 Mover el archivo subido de forma segura
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            // Construir la URL pública de forma segura
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            $targetFileSrc = $protocol . $host . '/build/uploads/' . $secureFileName;

            $resultArray[] = [
                'name'       => $originalName,      // Nombre original solo para referencia visual del usuario
                'type'       => 'image',
                'src'        => $targetFileSrc,
                'height'     => $height,
                'width'      => $width,
                'saved_as'   => $secureFileName     // Útil para guardar en base de datos
            ];

            // AQUÍ: Código para guardar en base de datos usando Prepared Statements (PDO/MySQLi)
        } else {
            error_log("Fallo crítico al mover el archivo a: $targetFilePath");
            http_response_code(500);
            echo json_encode(['error' => 'Error interno del servidor al guardar el archivo.']);
            exit;
        }
    }

    // 7. Respuesta exitosa consistente
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $resultArray
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se enviaron archivos en la solicitud.']);
}
