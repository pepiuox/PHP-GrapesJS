<?php
// classes/Page.php
// Migrated to PDO & Secured by AI Assistant
class Page {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /** Escapa HTML para prevenir XSS */
    private function e(?string $str): string {
        return htmlspecialchars((string)($str ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Crea una nueva página
     */
    public function create(
        int $user_id,
        string $title,
        ?int $template_id = null,
        string $php_content = '',
        string $js_content = ''
    ): int|false {
        $slug = $this->generateSlug($title);

        $html_content = '';
        $css_content = '';

        // Si hay template, cargar su contenido
        if ($template_id) {
            $template = $this->getTemplate($template_id);
            if ($template) {
                $html_content = $template['content_html'] ?? '';
                $css_content = $template['content_css'] ?? '';
            }
        }

        $query = "INSERT INTO pages (user_id, title, slug, content_html, content_css, content_php, content_js)
        VALUES (:user_id, :title, :slug, :html, :css, :php, :js)";
        $stmt = $this->conn->prepare($query);

        $success = $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':slug' => $slug,
            ':html' => $html_content,
            ':css' => $css_content,
            ':php' => $php_content,
            ':js' => $js_content
        ]);

        if ($success) {
            $page_id = (int)$this->conn->lastInsertId();
            $this->createVersion($page_id, $html_content, $css_content, $php_content, $user_id);
            return $page_id;
        }

        return false;
    }

    /**
     * Actualiza una página existente
     */
    public function update(int $page_id, int $user_id, array $data): bool {
        $query = "UPDATE pages SET
        title = :title,
        content_html = :html,
        content_css = :css,
        content_php = :php,
        content_js = :js,
        updated_at = NOW()
        WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $success = $stmt->execute([
            ':id' => $page_id,
            ':user_id' => $user_id,
            ':title' => $data['title'] ?? '',
            ':html' => $data['html'] ?? '',
            ':css' => $data['css'] ?? '',
            ':php' => $data['php'] ?? '',
            ':js' => $data['js'] ?? ''
        ]);

        if ($success) {
            $this->createVersion(
                $page_id,
                $data['html'] ?? '',
                $data['css'] ?? '',
                $data['php'] ?? '',
                $user_id
            );
            return true;
        }

        return false;
    }

    /**
     * Obtiene todas las páginas de un usuario
     */
    public function getPagesByUser(int $user_id): array {
        $query = "SELECT * FROM pages WHERE user_id = :user_id ORDER BY updated_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una página específica
     */
    public function getPage(int $page_id, ?int $user_id = null): array|false {
        $query = "SELECT * FROM pages WHERE id = :id";
        $params = [':id' => $page_id];

        if ($user_id !== null) {
            $query .= " AND user_id = :user_id";
            $params[':user_id'] = $user_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina una página
     */
    public function delete(int $page_id, int $user_id): bool {
        $query = "DELETE FROM pages WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $page_id,
            ':user_id' => $user_id
        ]);
    }

    /**
     * Publica una página
     */
    public function publish(int $page_id, int $user_id): bool {
        $query = "UPDATE pages SET is_published = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $page_id,
            ':user_id' => $user_id
        ]);
    }

    /**
     * Despublica una página
     */
    public function unpublish(int $page_id, int $user_id): bool {
        $query = "UPDATE pages SET is_published = 0 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $page_id,
            ':user_id' => $user_id
        ]);
    }

    /**
     * Genera un slug único y seguro
     */
    private function generateSlug(string $title): string {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Usar random_bytes en lugar de uniqid() (criptográficamente seguro)
        $slug = $slug . '-' . bin2hex(random_bytes(4));

        return $slug;
    }

    /**
     * Crea una nueva versión de la página
     */
    private function createVersion(
        int $page_id,
        string $html,
        string $css,
        string $php,
        int $user_id
    ): bool {
        $version_number = $this->getNextVersionNumber($page_id);

        $query = "INSERT INTO page_versions (page_id, version_number, content_html, content_css, content_php, created_by)
        VALUES (:page_id, :version, :html, :css, :php, :user_id)";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':page_id' => $page_id,
            ':version' => $version_number,
            ':html' => $html,
            ':css' => $css,
            ':php' => $php,
            ':user_id' => $user_id
        ]);
    }

    /**
     * Obtiene el siguiente número de versión
     */
    private function getNextVersionNumber(int $page_id): int {
        $query = "SELECT MAX(version_number) as max_version FROM page_versions WHERE page_id = :page_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':page_id' => $page_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['max_version'] ? (int)$result['max_version'] + 1 : 1);
    }

    /**
     * Obtiene todas las plantillas
     */
    public function getTemplates(): array {
        $query = "SELECT * FROM templates ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una plantilla específica
     */
    public function getTemplate(int $template_id): array|false {
        $query = "SELECT * FROM templates WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $template_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todas las versiones de una página
     */
    public function getVersions(int $page_id): array {
        $query = "SELECT * FROM page_versions WHERE page_id = :page_id ORDER BY version_number DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':page_id' => $page_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Restaura una versión anterior
     */
    public function restoreVersion(int $page_id, int $version_id, int $user_id): bool {
        // Obtener la versión
        $query = "SELECT * FROM page_versions WHERE id = :id AND page_id = :page_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':id' => $version_id,
            ':page_id' => $page_id
        ]);
        $version = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$version) {
            return false;
        }

        // Actualizar la página con la versión
        $update_query = "UPDATE pages SET
        content_html = :html,
        content_css = :css,
        content_php = :php,
        updated_at = NOW()
        WHERE id = :id AND user_id = :user_id";

        $update_stmt = $this->conn->prepare($update_query);
        $success = $update_stmt->execute([
            ':id' => $page_id,
            ':user_id' => $user_id,
            ':html' => $version['content_html'],
            ':css' => $version['content_css'],
            ':php' => $version['content_php']
        ]);

        if ($success) {
            // Crear nueva versión con el contenido restaurado
            return $this->createVersion(
                $page_id,
                $version['content_html'],
                $version['content_css'],
                $version['content_php'],
                $user_id
            );
        }

        return false;
    }
}
