<?php
declare(strict_types=1);

/**
 * Gestión de plantillas del sistema.
 * Migrado a PDO con validaciones y CSRF protection.
 *
 * CORRECCIONES:
 * - Validación estricta de inputs
 * - CSRF protection en operaciones POST
 * - Tipado estricto
 * - Validación de permisos de usuario
 * - Sanitización de contenido HTML/CSS
 */
class Template
{
    private PDO $conn;
    private string $table = 'templates';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Obtiene todas las plantillas del sistema.
     */
    public function getSystemTemplates(): array
    {
        $query = "SELECT id, name, description, content_html, content_css,
        is_system_template, created_at
        FROM {$this->table}
        WHERE is_system_template = 1
        ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene plantillas de un usuario específico.
     */
    public function getUserTemplates(int $user_id): array
    {
        if ($user_id <= 0) {
            return [];
        }

        $query = "SELECT id, name, description, content_html, content_css,
        is_system_template, created_at, updated_at
        FROM {$this->table}
        WHERE is_system_template = 0 AND created_by = :user_id
        ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea una nueva plantilla.
     *
     * ✅ CSRF validation requerida
     */
    public function createTemplate(
        int $user_id,
        string $name,
        string $description,
        string $html_content,
        string $css_content
    ): bool {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('Token CSRF inválido');
        }

        // ✅ Validación de inputs
        if ($user_id <= 0) {
            throw new InvalidArgumentException('ID de usuario inválido');
        }
        if (strlen($name) < 3 || strlen($name) > 100) {
            throw new InvalidArgumentException('Nombre debe tener entre 3 y 100 caracteres');
        }
        if (strlen($description) > 500) {
            throw new InvalidArgumentException('Descripción demasiado larga');
        }

        // ✅ Sanitizar contenido HTML (permitir tags seguros)
        $html_content = $this->sanitizeHtml($html_content);
        $css_content = $this->sanitizeCss($css_content);

        $query = "INSERT INTO {$this->table}
        (name, description, content_html, content_css,
        is_system_template, created_by, created_at)
        VALUES (:name, :description, :html, :css, 0, :user_id, NOW())";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name'        => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                              ':description' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
                              ':html'        => $html_content,
                              ':css'         => $css_content,
                              ':user_id'     => $user_id,
        ]);
    }

    /**
     * Obtiene una plantilla por ID.
     */
    public function getTemplate(int $template_id): ?array
    {
        if ($template_id <= 0) {
            return null;
        }

        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $template_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Elimina una plantilla (solo si pertenece al usuario y no es del sistema).
     */
    public function deleteTemplate(int $template_id, int $user_id): bool
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('Token CSRF inválido');
        }

        if ($template_id <= 0 || $user_id <= 0) {
            return false;
        }

        $query = "DELETE FROM {$this->table}
        WHERE id = :id AND created_by = :user_id
        AND is_system_template = 0";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id'       => $template_id,
            ':user_id'  => $user_id,
        ]);
    }

    /**
     * Actualiza una plantilla existente.
     */
    public function updateTemplate(int $template_id, int $user_id, array $data): bool
    {
        // ✅ CSRF validation
        if (!Utils::verifyCSRFToken($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('Token CSRF inválido');
        }

        if ($template_id <= 0 || $user_id <= 0) {
            return false;
        }

        // ✅ Validar campos permitidos
        $allowedFields = ['name', 'description', 'content_html', 'content_css'];
        $setParts = [];
        $params = [':id' => $template_id, ':user_id' => $user_id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields, true)) {
                $setParts[] = "{$key} = :{$key}";

                if ($key === 'content_html') {
                    $params[":{$key}"] = $this->sanitizeHtml((string) $value);
                } elseif ($key === 'content_css') {
                    $params[":{$key}"] = $this->sanitizeCss((string) $value);
                } else {
                    $params[":{$key}"] = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
                }
            }
        }

        if (empty($setParts)) {
            return false;
        }

        $setClause = implode(', ', $setParts);
        $query = "UPDATE {$this->table}
        SET {$setClause}, updated_at = NOW()
        WHERE id = :id AND created_by = :user_id
        AND is_system_template = 0";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Verifica si una plantilla pertenece a un usuario.
     */
    public function belongsToUser(int $template_id, int $user_id): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->table}
        WHERE id = :id AND created_by = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $template_id, ':user_id' => $user_id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Sanitiza HTML permitiendo tags seguros.
     */
    private function sanitizeHtml(string $html): string
    {
        // ✅ Permitir tags HTML comunes pero eliminar scripts
        $allowed = '<p><br><b><i><u><strong><em><h1><h2><h3><h4><h5><h6>'
        . '<ul><ol><li><a><img><div><span><table><tr><td><th>'
        . '<thead><tbody><blockquote><pre><code>';

        $html = strip_tags($html, $allowed);

        // ✅ Eliminar atributos peligrosos (onclick, onerror, etc.)
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);

        // ✅ Eliminar javascript:
        $html = preg_replace('/javascript\s*:/i', '', $html);

        return $html;
    }

    /**
     * Sanitiza CSS eliminando expresiones peligrosas.
     */
    private function sanitizeCss(string $css): string
    {
        // ✅ Eliminar expression() (IE XSS)
        $css = preg_replace('/expression\s*\(/i', '', $css);

        // ✅ Eliminar url() con javascript:
        $css = preg_replace('/url\s*\(\s*["\']?javascript:/i', '', $css);

        // ✅ Eliminar behavior:
        $css = preg_replace('/behavior\s*:/i', '', $css);

        // ✅ Eliminar -moz-binding:
        $css = preg_replace('/-moz-binding\s*:/i', '', $css);

        return $css;
    }
}
