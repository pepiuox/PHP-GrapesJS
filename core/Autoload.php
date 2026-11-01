<?php
declare(strict_types=1);

/**
 * Autoloader único del sistema — PHP-GrapesJS.
 *
 * Reemplaza a los antiguos cargadores (`Autoload::loader`) que estaban duplicados
 * en 5 archivos, usaban rutas relativas al CWD y lanzaban excepciones por clases
 * que no eran suyas (p. ej. las de PHPMailer).
 *
 * Resuelve, en este orden:
 *
 *   1. **Código nuevo con namespace (PSR-4)**
 *      `PepiuoX\`  →  `core/src/`
 *      Ej.: `PepiuoX\Database\Connection`  →  `core/src/Database/Connection.php`
 *
 *   2. **Puente de compatibilidad** (`COMPAT_MAP`) para las pocas clases globales
 *      que no viven en `core/controller/` (shims de transición).
 *
 *   3. **Clases legadas con nombre global** (sin namespace) de `core/controller/`,
 *      resueltas con un índice **insensible a mayúsculas** para que funcione igual
 *      en Windows y en Linux (`new userForgot()` sigue encontrando `UserForgot.php`).
 *
 * Reglas de diseño:
 * - **Nunca lanza excepciones**: si no es una clase del proyecto, devuelve el control
 *   a los demás autoloaders (Composer, PHPMailer, elFinder…). Nunca hace `require` de
 *   un archivo que no existe.
 * - **Idempotente**: `register()` puede llamarse muchas veces.
 * - No depende de Composer ni del directorio de trabajo actual.
 *
 * @see docs/PDO-MIGRATION.md §10 (arquitectura de carga)
 */

namespace PepiuoX\Autoloader;

// Guarda de idempotencia: el archivo figura en `composer.json > autoload.files` y ademas se
// incluye con `require_once` desde el bootstrap. Si Composer lo vuelve a cargar (porque su
// propio guard no registro la inclusion manual), la clase se declaraba dos veces y el proceso
// moria con "Cannot redeclare class".
//
// Se usa una constante y no `class_exists()`: PHP enlaza las clases declaradas de forma
// incondicional en tiempo de compilacion, asi que `class_exists(ClassLoader::class, false)`
// ya devolvia true antes de ejecutar esta linea y el autoloader no llegaba a registrarse.
if (defined('PEPIUOX_CLASS_LOADER_REGISTERED')) {
    return;
}

define('PEPIUOX_CLASS_LOADER_REGISTERED', true);

final class ClassLoader
{
    /** Prefijo de namespace del código nuevo. */
    private const PSR4_PREFIX = 'PepiuoX\\';

    /** Raíz del código nuevo con namespace (PSR-4): PepiuoX\ → core/src/. */
    private const PSR4_DIR = __DIR__ . '/src/';

    /** Directorio de las clases LEGADAS con nombre global. */
    private const LEGACY_DIR = __DIR__ . '/controller/';

    /**
     * Puente de compatibilidad: clase global  =>  ruta relativa a core/.
     *
     * Se usa sólo cuando el archivo NO está en `core/controller/` o cuando hay que
     * resolver un nombre de forma **determinista**.
     *
     * `Database` (clase global) debe ser SIEMPRE la conexión PDO canónica de
     * `core/config/Database.php` (alias de `PepiuoX\Database\Connection`), porque es
     * la que expone `getConnection()` / `PdoConnection()` que usa la aplicación
     * (28 puntos de uso). Antes esto sólo funcionaba por **orden de carga**: si el
     * autoloader atendía `Database` primero, cargaba `core/controller/Database.php`
     * (que es en realidad `App\Core\Database` y NO tiene `getConnection()`) y el
     * proceso moría con "Call to undefined method". Este mapa lo hace explícito.
     */
    private const COMPAT_MAP = [
        'Database' => '/config/Database.php',

        // Clases cuyo NOMBRE no coincide con el de su archivo en core/controller/.
        // Sin esta entrada serían inalcanzables por autoload (el resolvedor busca
        // `<Clase>.php` y no existe), aunque el archivo esté ahí. Hoy sólo se usan
        // dentro de su propio archivo, así que es un riesgo latente; mapearlas las
        // hace cargables también por nombre.
        'SecurityAccess' => '/controller/SecurityAccessClass.php',
        'SessionClass' => '/controller/SetSession.php',
        'RemoteAddress' => '/controller/statsPages.php',
        'PageStatsRecorder' => '/controller/statsPages.php',
    ];

    /** @var array<string,string>|null Índice lowercase => ruta real. */
    private static ?array $legacyIndex = null;

    private static bool $registered = false;

    /** Evita registrar el mismo autoloader dos veces. */
    public static function register(): void
    {
        if (self::$registered) {
            return;
        }

        self::$registered = true;
        // prepend = true: el código del proyecto tiene prioridad.
        spl_autoload_register([self::class, 'load'], true, true);
    }

    /**
     * Callback de `spl_autoload_register()`.
     */
    public static function load(string $class): void
    {
        if ($class === '' || self::isLoaded($class)) {
            return;
        }

        // 1) Código nuevo con namespace (PSR-4). No cae a la resolución legada.
        if (str_starts_with($class, self::PSR4_PREFIX)) {
            $relative = substr($class, strlen(self::PSR4_PREFIX));
            $file = self::PSR4_DIR . str_replace('\\', '/', $relative) . '.php';

            if (is_file($file)) {
                require_once $file;
            }

            return;
        }

        // 2) Puente de compatibilidad explícito.
        if (isset(self::COMPAT_MAP[$class])) {
            $file = __DIR__ . self::COMPAT_MAP[$class];

            if (is_file($file)) {
                require_once $file;
            }

            return;
        }

        // 3) Clases legadas globales de core/controller/.
        $file = self::legacyFile($class);

        if ($file !== null) {
            require_once $file;
        }
    }

    /**
     * Devuelve la ruta del archivo legado para una clase global, o null.
     *
     * El índice se construye una sola vez (scandir de ~120 archivos) y se resuelve
     * comparando en minúsculas, para que dé igual cómo se escriba la clase.
     */
    private static function legacyFile(string $class): ?string
    {
        if (self::$legacyIndex === null) {
            self::$legacyIndex = [];

            $entries = @scandir(self::LEGACY_DIR);

            if ($entries !== false) {
                foreach ($entries as $entry) {
                    if (!str_ends_with($entry, '.php')) {
                        continue;
                    }

                    self::$legacyIndex[strtolower(substr($entry, 0, -4))] = self::LEGACY_DIR . $entry;
                }
            }
        }

        // El nombre de clase puede venir con namespace interno (Foo\Bar): se usa el último segmento.
        $short = str_contains($class, '\\') ? substr($class, (int) strrpos($class, '\\') + 1) : $class;

        return self::$legacyIndex[strtolower($short)] ?? null;
    }

    private static function isLoaded(string $class): bool
    {
        return class_exists($class, false)
            || interface_exists($class, false)
            || trait_exists($class, false)
            || enum_exists($class, false);
    }

    /**
     * Registra en un solo paso el autoloader y, si existe, el de Composer.
     *
     * Punto de entrada recomendado para el bootstrap de la aplicación.
     */
    public static function bootstrap(): void
    {
        self::register();

        // __DIR__ es <raiz>/core: la raiz del proyecto esta un nivel arriba.
        $composer = dirname(__DIR__) . '/vendor/autoload.php';

        if (is_file($composer)) {
            require_once $composer;
        }
    }
}

// Registro + carga del autoloader de Composer en un solo paso. Antes solo se llamaba a
// `register()`, asi que `vendor/autoload.php` nunca se cargaba: PHPMailer (y cualquier
// dependencia instalada por Composer) fallaba con "Class not found" en el envío de correos.
ClassLoader::bootstrap();