<?php
declare(strict_types=1);

/**
 * Protección CSRF (Cross-Site Request Forgery).
 *
 * Un atacante puede hacer que el navegador de un usuario autenticado envíe un POST
 * a la aplicación (desde otro sitio). El token CSRF, ligado a la sesión y comparado
 * en **tiempo constante**, demuestra que el envío se originó en nuestras propias vistas.
 *
 * Uso en el formulario:
 *
 *     <form method="post">
 *         <?= \PepiuoX\Security\Csrf::field() ?>
 *         …
 *     </form>
 *
 * Uso al procesar (antes de cualquier efecto):
 *
 *     \PepiuoX\Security\Csrf::check();   // aborta con 403 si no es válido
 *
 * Para peticiones AJAX, enviar el token en la cabecera `X-CSRF-Token`
 * (`Csrf::fromRequest()` lo lee también de ahí).
 *
 * @see docs/SECURITY.md §6
 */

namespace PepiuoX\Security;

final class Csrf
{
    public const FIELD_NAME = 'csrf_token';
    public const HEADER_NAME = 'HTTP_X_CSRF_TOKEN';

    private const SESSION_KEY = 'csrf_token';
    private const SESSION_TIME_KEY = 'csrf_token_time';

    /** Caducidad del token en segundos (2 h). */
    private const TOKEN_TTL = 7200;

    /**
     * Devuelve el token de la sesión, creándolo si no existe o si caducó.
     *
     * @throws \Random\RandomException
     */
    public static function token(): string
    {
        self::ensureSession();

        $current = $_SESSION[self::SESSION_KEY] ?? null;
        $created = (int) ($_SESSION[self::SESSION_TIME_KEY] ?? 0);

        if (!is_string($current) || $current === '' || (time() - $created) > self::TOKEN_TTL) {
            return self::rotate();
        }

        return $current;
    }

    /**
     * Fuerza la creación de un token nuevo (p. ej. tras iniciar sesión).
     *
     * @throws \Random\RandomException
     */
    public static function rotate(): string
    {
        self::ensureSession();

        $token = Random::token(32);

        $_SESSION[self::SESSION_KEY] = $token;
        $_SESSION[self::SESSION_TIME_KEY] = time();

        return $token;
    }

    /**
     * Campo oculto listo para insertar en un formulario.
     *
     * @throws \Random\RandomException
     */
    public static function field(): string
    {
        return '<input type="hidden" name="' . self::FIELD_NAME . '" value="'
            . Escape::attr(self::token()) . '">';
    }

    /**
     * Token recibido en la petición (POST o cabecera `X-CSRF-Token`).
     */
    public static function fromRequest(): ?string
    {
        $posted = $_POST[self::FIELD_NAME] ?? null;

        if (is_string($posted) && $posted !== '') {
            return $posted;
        }

        $header = $_SERVER[self::HEADER_NAME] ?? null;

        return is_string($header) && $header !== '' ? $header : null;
    }

    /**
     * ¿Es válido el token recibido?
     */
    public static function isValid(?string $token = null): bool
    {
        self::ensureSession();

        $expected = $_SESSION[self::SESSION_KEY] ?? null;

        if (!is_string($expected) || $expected === '') {
            return false;
        }

        $token ??= self::fromRequest();

        if (!is_string($token) || $token === '') {
            return false;
        }

        return Random::equals($expected, $token);
    }

    /**
     * Valida o aborta la petición con 403.
     *
     * Pensado para llamarse al principio del procesamiento de un POST.
     *
     * @throws \Random\RandomException
     */
    public static function check(bool $rotateAfter = false): void
    {
        if (!self::isValid()) {
            if (!headers_sent()) {
                http_response_code(403);
                header('Content-Type: text/plain; charset=UTF-8');
            }

            exit('Solicitud rechazada: token CSRF inválido o caducado.');
        }

        if ($rotateAfter) {
            self::rotate();
        }
    }

    /**
     * Política CSRF para los puntos de entrada de los paneles (dashboard y área de usuario).
     *
     * Se aplica una sola vez, al principio del despachador, y cubre así TODOS los
     * handlers de las vistas que se incluyan después (que en este proyecto no
     * comprobaban nada por sí mismos).
     *
     * Reglas:
     *  - Sólo se aplica a peticiones POST (GET no modifica estado).
     *  - Se exige un token válido siempre, **salvo** en peticiones AJAX del propio panel
     *    sin token: jQuery añade `X-Requested-With: XMLHttpRequest` automáticamente y un
     *    sitio externo **no puede** poner esa cabecera (una cabecera personalizada dispara
     *    un preflight CORS que el atacante no puede superar). Un POST cross-site normal no
     *    la lleva, así que sí se valida.
     *  - Si la petición AJAX sí trae token (campo o cabecera `X-CSRF-Token`), se valida:
     *    así el endurecimiento es compatible con migrar los AJAX a enviarlo.
     */
    public static function guardPost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }

        $esAjaxPanel = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
        $traeToken = self::fromRequest() !== null;

        if ((!$esAjaxPanel || $traeToken) && !self::isValid()) {
            if (!headers_sent()) {
                http_response_code(403);
                header('Content-Type: text/plain; charset=UTF-8');
            }

            exit('Solicitud rechazada: token CSRF inválido o ausente.');
        }
    }

    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }
}
