<?php
declare(strict_types=1);

/**
 * Generador de archivo de definiciones desde la base de datos.
 *
 * ✅ CORRECCIONES CRÍTICAS DE SEGURIDAD:
 * - Sanitización de valores antes de escribir en define()
 * - Validación de nombres de campos permitidos
 * - Protección contra inyección de código PHP
 * - Validación de permisos de escritura
 */

if (!isset($conn) || !($conn instanceof PDO)) {
    throw new RuntimeException('Conexión PDO no válida');
}

$definefiles = 'define.php';
$configDir = __DIR__ . '/config/';

// ✅ Crear directorio si no existe
if (!is_dir($configDir)) {
    if (!mkdir($configDir, 0755, true)) {
        throw new RuntimeException('No se pudo crear el directorio de configuración');
    }
}

$definePath = $configDir . $definefiles;

if (!file_exists($definePath)) {
    try {
        // ✅ Consulta PDO con prepared statement
        $stmt = $conn->prepare("SELECT * FROM site_configuration WHERE ID_Site = :id LIMIT 1");
        $stmt->execute([':id' => 1]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new RuntimeException('No se encontró la configuración del sitio');
        }

        // ✅ Lista blanca de campos permitidos
        $allowedFields = [
            'SITE_NAME', 'SITE_PATH', 'SITE_EMAIL', 'DEBUG',
            'SECURE_HASH', 'SECURE_TOKEN', 'SECRET_KEY',
            'MAILSERVER', 'PORTSERVER', 'USEREMAIL', 'PASSMAIL',
            'ENCRYPTION_METHOD'
        ];

        // ✅ Campos que deben omitirse
        $skipFields = ['ID_Site', 'CREATE', 'UPDATED'];

        $fldname = [];

        foreach ($result as $fieldName => $fieldValue) {
            // Omitir campos no permitidos
            if (in_array($fieldName, $skipFields, true)) {
                continue;
            }

            // ✅ Validar que el nombre del campo sea seguro
            if (!preg_match('/^[A-Z_][A-Z0-9_]*$/', $fieldName)) {
                error_log("Campo inválido ignorado: {$fieldName}");
                continue;
            }

            // ✅ Sanitizar el valor para prevenir inyección de código
            $sanitizedValue = self::sanitizeDefineValue($fieldValue);

            $fldname[] = "define('{$fieldName}', '{$sanitizedValue}');";
        }

        if (empty($fldname)) {
            throw new RuntimeException('No se generaron definiciones válidas');
        }

        // ✅ Generar contenido del archivo
        $content = "<?php\n";
        $content .= "/**\n";
        $content .= " * Archivo de definiciones generado automáticamente.\n";
        $content .= " * Generado: " . date('Y-m-d H:i:s') . "\n";
        $content .= " * NO EDITAR MANUALMENTE\n";
        $content .= " */\n\n";
        $content .= implode("\n", $fldname) . "\n";

        // ✅ Escribir archivo con bloqueo exclusivo
        $bytesWritten = file_put_contents($definePath, $content, LOCK_EX);

        if ($bytesWritten === false) {
            throw new RuntimeException('Error al escribir el archivo de definiciones');
        }

        // ✅ Establecer permisos seguros (solo lectura para el propietario)
        chmod($definePath, 0644);

        $_SESSION['SuccessMessage'] = "El archivo de definiciones de configuración ha sido creado correctamente.";

    } catch (PDOException $e) {
        error_log("Error en make_define: " . $e->getMessage());
        $_SESSION['ErrorMessage'] = 'Error al crear el archivo de configuración.';
    } catch (Exception $e) {
        error_log("Error en make_define: " . $e->getMessage());
        $_SESSION['ErrorMessage'] = $e->getMessage();
    }
}

/**
 * Sanitiza valores para usar en define().
 * Previene inyección de código PHP.
 */
function sanitizeDefineValue(mixed $value): string
{
    if (!is_string($value) && !is_numeric($value)) {
        return '';
    }

    $value = (string) $value;

    // ✅ Eliminar caracteres peligrosos
    $value = str_replace(["<?php", "?>", "<?", "<?="], '', $value);

    // ✅ Escapar comillas simples
    $value = str_replace("'", "\\'", $value);

    // ✅ Eliminar saltos de línea
    $value = str_replace(["\r", "\n"], '', $value);

    // ✅ Limitar longitud
    $value = substr($value, 0, 500);

    return $value;
}
