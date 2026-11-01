<?php
require_once dirname(__DIR__) . '/Autoload.php';

// 1. Forzar siempre la respuesta a ser JSON
header('Content-Type: application/json');

// 2. Iniciar sesión con la configuración central: `httponly`, `samesite=Lax` y
// `secure` sólo cuando la petición es realmente HTTPS (forzarlo en HTTP impide
// que el navegador envíe la cookie de sesión).
\PepiuoX\Security\SessionConfig::start();

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

        // 6.1 Guardar con un nombre generado por el servidor (mitiga path traversal
        // y sobrescritura). `Upload::storeImage()` valida el código de error, el
        // tamaño, la extensión y el MIME real del contenido (finfo), no
        // `$_FILES[...]['type']`, que lo controla el cliente.
        $upload_error = null;
        $secureFileName = \PepiuoX\Security\Upload::storeImage($file, $targetDir, $MAX_FILE_SIZE, $upload_error);

        if ($secureFileName === null) {
            http_response_code(400);
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                error_log("Error de subida en '$inputName': Código " . $file['error']);
                echo json_encode(['error' => 'Error al procesar la subida del archivo.']);
            } elseif (($file['size'] ?? 0) > $MAX_FILE_SIZE) {
                echo json_encode(['error' => 'El archivo excede el tamaño máximo permitido (5 MB).']);
            } elseif (!in_array(\PepiuoX\Security\Upload::extension((string) $file['name']), $ALLOWED_EXTENSIONS, true)) {
                echo json_encode(['error' => 'Tipo de archivo no permitido.']);
            } elseif (in_array($upload_error, [
                'El directorio de destino no existe o no es escribible.',
                'No se pudo resolver el directorio de destino.',
                'Ruta de destino no permitida.',
                'No se pudo guardar el archivo.',
            ], true)) {
                // Fallo de almacenamiento (no de validación del archivo)
                error_log("Fallo al guardar el archivo subido en '$inputName': " . $upload_error);
                http_response_code(500);
                echo json_encode(['error' => 'Error interno del servidor al guardar el archivo.']);
            } else {
                echo json_encode(['error' => 'El contenido del archivo no coincide con su extensión.']);
            }
            exit;
        }

        // 6.2 Comprobar que es una imagen real y obtener sus dimensiones
        $imageInfo = getimagesize($targetDir . DIRECTORY_SEPARATOR . $secureFileName);

        if ($imageInfo === false) {
            @unlink($targetDir . DIRECTORY_SEPARATOR . $secureFileName);
            http_response_code(400);
            echo json_encode(['error' => 'El archivo no es una imagen válida o está corrupto.']);
            exit;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        $originalName = $file['name'];

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
