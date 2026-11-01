<?php
declare(strict_types=1);

/**
 * Validación y almacenamiento seguro de archivos subidos.
 *
 * Motivo: los puntos de subida del proyecto repetían (o omitían) las comprobaciones y,
 * lo más grave, construían el destino con **el nombre que envía el cliente**:
 *
 *     $file = 'uploads/' . $_FILES['files']['name'][0];   // ❌
 *     move_uploaded_file($tmp, $file);
 *
 * Un nombre como `../../core/config/server.php` escribía **fuera** del directorio de
 * subidas (path traversal → escritura arbitraria de archivos). Además se validaba el
 * tipo con `$_FILES[...]['type']`, que **lo controla el cliente**, y la extensión se
 * comparaba sin normalizar a minúsculas.
 *
 * Reglas que aplica esta clase:
 *  1. El nombre que envía el cliente se usa **sólo** para leer la extensión; nunca forma
 *     parte de la ruta de destino.
 *  2. El nombre final es **aleatorio** (bin2hex(random_bytes(...))) + la extensión validada.
 *  3. El tipo se comprueba con `finfo` sobre el archivo temporal (contenido real),
 *     no con la cabecera del cliente.
 *  4. Se comprueba `is_uploaded_file()`, el código de error y el tamaño máximo.
 *  5. El directorio de destino debe existir y ser escribible; se normaliza y se comprueba
 *     que la ruta final sigue estando dentro de él.
 *
 * @see docs/SECURITY.md §11
 */

namespace PepiuoX\Security;

use RuntimeException;

final class Upload
{
    /** Extensiones y tipos MIME de imagen admitidos por defecto. */
    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    public const IMAGE_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/avif',
    ];

    /** 2 MiB, el límite que ya usaba `core/users/upload.php`. */
    public const DEFAULT_MAX_BYTES = 2097152;

    /**
     * Extensión en minúsculas, sin el punto. Devuelve '' si no hay.
     *
     * Se usa `pathinfo()` y no `explode('.', …)` para no depender del último punto
     * (un nombre como `x.php.jpg` da `jpg`, que es lo correcto).
     */
    public static function extension(string $filename): string
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Tipo MIME real, leído del contenido con finfo.
     *
     * Devuelve '' si no se puede determinar (p. ej. la extensión `fileinfo` no está
     * disponible); en ese caso la validación por MIME se omite y manda la de extensión.
     */
    public static function mime(string $path): string
    {
        if (!function_exists('finfo_open') || !is_file($path)) {
            return '';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            return '';
        }

        try {
            $mime = finfo_file($finfo, $path);
        } finally {
            finfo_close($finfo);
        }

        return is_string($mime) ? strtolower($mime) : '';
    }

    /**
     * Nombre de archivo aleatorio y seguro, conservando la extensión indicada.
     */
    public static function safeName(string $extension, string $prefix = ''): string
    {
        $extension = self::extension('x.' . $extension);

        return ($prefix !== '' ? preg_replace('/[^A-Za-z0-9_-]/', '', $prefix) . '-' : '')
            . bin2hex(random_bytes(8))
            . ($extension !== '' ? '.' . $extension : '');
    }

    /**
     * Valida una entrada de `$_FILES`.
     *
     * @param array{name?:string,tmp_name?:string,size?:int,error?:int,type?:string} $file
     * @param list<string> $allowedExtensions
     * @param list<string> $allowedMimes
     * @param int $maxBytes 0 = sin límite
     * @param string|null $error Se rellena con el motivo del rechazo
     */
    public static function validate(
        array $file,
        array $allowedExtensions,
        array $allowedMimes = [],
        int $maxBytes = self::DEFAULT_MAX_BYTES,
        ?string &$error = null
    ): bool {
        $error = null;
        $name = (string) ($file['name'] ?? '');
        $tmp = (string) ($file['tmp_name'] ?? '');
        $size = (int) ($file['size'] ?? 0);
        $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($code === UPLOAD_ERR_NO_FILE || $name === '') {
            $error = 'No se seleccionó ningún archivo.';

            return false;
        }

        if ($code !== UPLOAD_ERR_OK) {
            $error = 'La subida falló (código ' . (int) $code . ').';

            return false;
        }

        if ($tmp === '' || !is_uploaded_file($tmp)) {
            $error = 'El archivo temporal no es válido.';

            return false;
        }

        if ($maxBytes > 0 && $size > $maxBytes) {
            $error = 'El archivo supera el tamaño máximo permitido.';

            return false;
        }

        $extension = self::extension($name);

        if ($extension === '' || !in_array($extension, array_map('strtolower', $allowedExtensions), true)) {
            $error = 'Extensión no permitida.';

            return false;
        }

        // El MIME se comprueba sobre el contenido; `$_FILES[...]['type']` lo controla el cliente.
        if ($allowedMimes !== []) {
            $mime = self::mime($tmp);

            if ($mime !== '' && !in_array($mime, array_map('strtolower', $allowedMimes), true)) {
                $error = 'El contenido del archivo no corresponde al tipo permitido.';

                return false;
            }
        }

        return true;
    }

    /**
     * Guarda una imagen subida con un nombre generado por el servidor.
     *
     * @return string|null Nombre final del archivo (sin ruta), o null si se rechazó.
     */
    public static function storeImage(
        array $file,
        string $directory,
        int $maxBytes = self::DEFAULT_MAX_BYTES,
        ?string &$error = null
    ): ?string {
        return self::store($file, $directory, self::IMAGE_EXTENSIONS, self::IMAGE_MIMES, $maxBytes, $error);
    }

    /**
     * Guarda un archivo subido con un nombre generado por el servidor.
     *
     * @param list<string> $allowedExtensions
     * @param list<string> $allowedMimes
     * @return string|null Nombre final del archivo (sin ruta), o null si se rechazó.
     */
    public static function store(
        array $file,
        string $directory,
        array $allowedExtensions,
        array $allowedMimes = [],
        int $maxBytes = self::DEFAULT_MAX_BYTES,
        ?string &$error = null
    ): ?string {
        if (!self::validate($file, $allowedExtensions, $allowedMimes, $maxBytes, $error)) {
            return null;
        }

        $directory = rtrim($directory, "/\\");

        // Sólo se exige que el directorio EXISTA. No se usa `is_writable()` como puerta:
        // en algunos entornos (sandboxes, ACL de Windows, sistemas de archivos en red)
        // devuelve falsos negativos y bloquearía todas las subidas. La comprobación
        // fiable es el propio resultado de `move_uploaded_file()`, que se evalúa abajo.
        if (!is_dir($directory)) {
            $error = 'El directorio de destino no existe.';

            return null;
        }

        // El nombre del cliente NO participa en la ruta: sólo la extensión ya validada.
        $filename = self::safeName(self::extension((string) $file['name']));
        $target = $directory . DIRECTORY_SEPARATOR . $filename;

        // Defensa en profundidad: la ruta final debe seguir dentro del directorio.
        $realDir = realpath($directory);
        $realTarget = realpath($target);

        if ($realDir === false) {
            $error = 'No se pudo resolver el directorio de destino.';

            return null;
        }

        if ($realTarget !== false && !str_starts_with($realTarget, $realDir . DIRECTORY_SEPARATOR)) {
            $error = 'Ruta de destino no permitida.';

            return null;
        }

        if (!move_uploaded_file((string) $file['tmp_name'], $target)) {
            // La causa real (permisos, cuota, directorio no escribible) se registra;
            // al usuario se le da un mensaje genérico.
            error_log('Upload: no se pudo mover el archivo a ' . $target);
            $error = 'No se pudo guardar el archivo (permisos del directorio de destino).';

            return null;
        }

        @chmod($target, 0644);

        return $filename;
    }

    /**
     * Rechaza nombres de archivo peligrosos (por si algún punto del código sigue
     * guardando el nombre original). Lanza excepción si no es seguro.
     *
     * @throws RuntimeException
     */
    public static function assertSafeFilename(string $filename): string
    {
        if ($filename === ''
            || $filename !== basename($filename)
            || str_contains($filename, '..')
            || preg_match('/[\x00-\x1f\\\\\/:*?"<>|]/', $filename) === 1
        ) {
            throw new RuntimeException('Nombre de archivo no permitido.');
        }

        return $filename;
    }
}
