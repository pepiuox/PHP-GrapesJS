<?php
declare(strict_types=1);

/**
 * Gestión de plantillas con PDO.
 *
 * ✅ CORRECCIONES CRÍTICAS:
 * - eval() ELIMINADO (previene RCE)
 * - Sandboxing seguro para contenido PHP
 * - Validación y sanitización de HTML/CSS/JS
 * - CSRF protection en operaciones POST
 * - Lista blanca de tags HTML permitidos
 */
class Templates
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getAll(?int $user_id = null): array
    {
        $query = "SELECT * FROM templates WHERE is_system_template = 1";
        $params = [];

        if ($user_id !== null && $user_id > 0) {
            $query .= " OR created_by = :user_id";
            $params[':user_id'] = $user_id;
        }

        $query .= " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, ?int $user_id = null): array|false
    {
        if ($id <= 0) {
            return false;
        }

        $query = "SELECT * FROM templates WHERE id = :id";
        $params = [':id' => $id];

        if ($user_id !== null && $user_id > 0) {
            $query .= " AND (is_system_template = 1 OR created_by = :user_id)";
            $params[':user_id'] = $user_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('Token CSRF inválido');
        }

        // ✅ Validar campos requeridos
        if (empty($data['name']) || strlen($data['name']) < 3) {
            throw new InvalidArgumentException('Nombre de plantilla inválido');
        }

        // ✅ Sanitizar contenido
        $data['html'] = $this->sanitizeHtml($data['html'] ?? '');
        $data['css'] = $this->sanitizeCss($data['css'] ?? '');
        $data['js'] = $this->sanitizeJs($data['js'] ?? '');

        // ✅ PHP content: NO se ejecuta, solo se almacena como texto
        $data['php'] = $this->sanitizePhp($data['php'] ?? '');

        $query = "INSERT INTO templates
        (name, description, content_html, content_css, content_php, content_js,
        is_system_template, created_by, created_at)
        VALUES (:name, :description, :html, :css, :php, :js, :is_system, :user_id, NOW())";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name'        => htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'),
                              ':description' => htmlspecialchars($data['description'] ?? '', ENT_QUOTES, 'UTF-8'),
                              ':html'        => $data['html'],
                              ':css'         => $data['css'],
                              ':php'         => $data['php'],
                              ':js'          => $data['js'],
                              ':is_system'   => (int) ($data['is_system_template'] ?? 0),
                              ':user_id'     => $data['created_by'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        if ($id <= 0) {
            return false;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('Token CSRF inválido');
        }

        // ✅ Sanitizar contenido
        $data['html'] = $this->sanitizeHtml($data['html'] ?? '');
        $data['css'] = $this->sanitizeCss($data['css'] ?? '');
        $data['js'] = $this->sanitizeJs($data['js'] ?? '');
        $data['php'] = $this->sanitizePhp($data['php'] ?? '');

        $query = "UPDATE templates
        SET name = :name, description = :description,
        content_html = :html, content_css = :css,
        content_php = :php, content_js = :js,
        updated_at = NOW()
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name'        => htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8'),
                              ':description' => htmlspecialchars($data['description'] ?? '', ENT_QUOTES, 'UTF-8'),
                              ':html'        => $data['html'],
                              ':css'         => $data['css'],
                              ':php'         => $data['php'],
                              ':js'          => $data['js'],
                              ':id'          => $id,
        ]);
    }

    public function delete(int $id, int $user_id): bool
    {
        if ($id <= 0 || $user_id <= 0) {
            return false;
        }

        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('Token CSRF inválido');
        }

        $query = "DELETE FROM templates
        WHERE id = :id AND created_by = :user_id AND is_system_template = 0";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id'       => $id,
            ':user_id'  => $user_id,
        ]);
    }

    /**
     * Renderiza una plantilla de forma SEGURA.
     *
     * ✅ CORREGIDO: eval() ELIMINADO
     * ✅ El código PHP NO se ejecuta, solo se muestra como comentario o se ignora
     */
    public function render(int $template_id, ?int $user_id = null): string|false
    {
        $template = $this->getById($template_id, $user_id);
        if (!$template) {
            return false;
        }

        // ✅ Construir salida HTML segura
        $output = '<!DOCTYPE html>';
        $output .= '<html lang="es">';
        $output .= '<head>';
        $output .= '<meta charset="UTF-8">';
        $output .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $output .= '<title>' . htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') . '</title>';
        $output .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        $output .= '<style>' . $template['content_css'] . '</style>';
        $output .= '</head>';
        $output .= '<body>';
        $output .= $template['content_html'];

        // ✅ PHP content: NO se ejecuta, solo se muestra como comentario para debugging
        if (!empty($template['content_php'])) {
            $output .= '<!-- PHP content (not executed for security): '
            . htmlspecialchars($template['content_php'], ENT_QUOTES, 'UTF-8')
            . ' -->';
        }

        $output .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';

        if (!empty($template['content_js'])) {
            $output .= '<script>' . $template['content_js'] . '</script>';
        }

        $output .= '</body>';
        $output .= '</html>';

        return $output;
    }

    /**
     * Vista previa segura.
     * ✅ CORREGIDO: eval() ELIMINADO
     */
    public function renderPreview(string $html, string $css, string $php = '', string $js = ''): string
    {
        // ✅ Sanitizar todo el contenido
        $html = $this->sanitizeHtml($html);
        $css = $this->sanitizeCss($css);
        $js = $this->sanitizeJs($js);

        $output = '<!DOCTYPE html>';
        $output .= '<html>';
        $output .= '<head>';
        $output .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        $output .= '<style>' . $css . '</style>';
        $output .= '</head>';
        $output .= '<body>';
        $output .= $html;

        // ✅ PHP content: NO se ejecuta
        if (!empty($php)) {
            $output .= '<!-- PHP preview (not executed): '
            . htmlspecialchars($php, ENT_QUOTES, 'UTF-8')
            . ' -->';
        }

        $output .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';

        if (!empty($js)) {
            $output .= '<script>' . $js . '</script>';
        }

        $output .= '</body>';
        $output .= '</html>';

        return $output;
    }

    /**
     * Sanitiza HTML permitiendo tags seguros.
     */
    private function sanitizeHtml(string $html): string
    {
        $allowed = '<p><br><b><i><u><strong><em><h1><h2><h3><h4><h5><h6>'
        . '<ul><ol><li><a><img><div><span><table><tr><td><th>'
        . '<thead><tbody><blockquote><pre><code><section><article>'
        . '<header><footer><nav><main><form><input><button><label>'
        . '<select><option><textarea>';

        $html = strip_tags($html, $allowed);

        // ✅ Eliminar atributos peligrosos
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/javascript\s*:/i', '', $html);
        $html = preg_replace('/data\s*:\s*text\/html/i', '', $html);

        return $html;
    }

    /**
     * Sanitiza CSS eliminando expresiones peligrosas.
     */
    private function sanitizeCss(string $css): string
    {
        // ✅ Eliminar expresiones peligrosas
        $css = preg_replace('/expression\s*\(/i', '', $css);
        $css = preg_replace('/url\s*\(\s*["\']?javascript:/i', '', $css);
        $css = preg_replace('/behavior\s*:/i', '', $css);
        $css = preg_replace('/-moz-binding\s*:/i', '', $css);
        $css = preg_replace('/@import/i', '', $css);

        return $css;
    }

    /**
     * Sanitiza JavaScript.
     */
    private function sanitizeJs(string $js): string
    {
        // ✅ Eliminar funciones peligrosas
        $js = preg_replace('/eval\s*\(/i', '', $js);
        $js = preg_replace('/document\.write/i', '', $js);
        $js = preg_replace('/innerHTML\s*=/i', '', $js);
        $js = preg_replace('/window\.location/i', '', $js);

        return $js;
    }

    /**
     * Sanitiza contenido PHP (solo lo almacena, no lo ejecuta).
     */
    private function sanitizePhp(string $php): string
    {
        // ✅ Eliminar tags PHP peligrosos
        $php = preg_replace('/<\?php/i', '', $php);
        $php = preg_replace('/\?>/i', '', $php);

        // ✅ Eliminar funciones peligrosas
        $dangerous = [
            'exec', 'shell_exec', 'system', 'passthru', 'popen', 'proc_open',
            'eval', 'assert', 'preg_replace', 'create_function',
            'include', 'include_once', 'require', 'require_once',
            'file_get_contents', 'file_put_contents', 'fopen', 'fwrite',
        ];

        foreach ($dangerous as $func) {
            $php = preg_replace('/\b' . $func . '\s*\(/i', '', $php);
        }

        return $php;
    }
}
