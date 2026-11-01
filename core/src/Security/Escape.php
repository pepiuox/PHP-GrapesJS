<?php
declare(strict_types=1);

/**
 * Escapado de SALIDA (contexto-aware) para prevenir XSS.
 *
 * Regla: **escapar en la salida**, no en la entrada. Un mismo dato puede acabar en
 * HTML, en un atributo, en una URL o en JavaScript, y cada contexto necesita un
 * escapado distinto.
 *
 * Uso:
 *
 *     <p><?= Escape::html($row['title']) ?></p>
 *     <input value="<?= Escape::attr($row['name']) ?>">
 *     <a href="<?= Escape::url($row['link']) ?>">…</a>
 *     <script>const n = <?= Escape::json($row['name']) ?>;</script>
 *
 * `protect()` (`core/config/function.php`) hace algo parecido pero **doble-escapando**
 * (`htmlentities` + `htmlspecialchars`) y sin distinguir contexto; usa estas funciones
 * en código nuevo.
 *
 * @see docs/SECURITY.md §7 (S5, S6)
 */

namespace PepiuoX\Security;

final class Escape
{
    private const CHARSET = 'UTF-8';

    /**
     * Texto para el cuerpo de un documento HTML.
     */
    public static function html(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, self::CHARSET);
    }

    /**
     * Valor para un atributo HTML (incluye las comillas simples).
     *
     * Es el mismo escapado que `html()`; se expone con nombre propio para que la
     * intención quede explícita en el punto de uso.
     */
    public static function attr(?string $value): string
    {
        return self::html($value);
    }

    /**
     * URL segura para `href`/`src`.
     *
     * Además de escapar, bloquea esquemas peligrosos (`javascript:`, `data:`, `vbscript:`)
     * que permitirían XSS aunque el valor esté escapado.
     */
    public static function url(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        // Normaliza entidades y espacios que se usan para evadir el filtro.
        $probe = strtolower(preg_replace('/[\x00-\x20]+/', '', html_entity_decode($value, ENT_QUOTES, self::CHARSET)) ?? '');

        foreach (['javascript:', 'vbscript:', 'data:', 'file:'] as $scheme) {
            if (str_starts_with($probe, $scheme)) {
                return '';
            }
        }

        return self::html($value);
    }

    /**
     * Valor PHP → literal JSON seguro para incrustar en un `<script>`.
     *
     * `JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT` evita que `</script>`
     * (o `<!--`) cierren el bloque y permitan inyectar HTML.
     */
    public static function json(mixed $value): string
    {
        $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
        $json = json_encode($value, $flags);

        return $json === false ? 'null' : $json;
    }

    /**
     * Cadena segura para incluir dentro de una literal de JavaScript.
     */
    public static function js(?string $value): string
    {
        return substr(self::json((string) $value), 1, -1);
    }

    /**
     * Escapa un array completo (útil para listados).
     *
     * @param array<array-key,mixed> $data
     * @return array<array-key,mixed>
     */
    public static function htmlArray(array $data): array
    {
        $clean = [];

        foreach ($data as $key => $value) {
            $clean[$key] = is_array($value) ? self::htmlArray($value) : self::html(is_scalar($value) || $value === null ? (string) $value : '');
        }

        return $clean;
    }
}
