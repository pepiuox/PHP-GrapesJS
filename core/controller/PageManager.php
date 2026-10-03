<?php
// classes/PageManager.php
// Migrated to PDO & Secured by AI Assistant
class PageManager {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /** Escapa HTML para prevenir XSS */
    private function e(?string $str): string {
        return htmlspecialchars((string)($str ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Crea una nueva página
     */
    public function createPage(array $data): int|false {
        // Validar campos requeridos
        if (empty($data['user_id']) || empty($data['title']) || empty($data['slug'])) {
            return false;
        }

        $sql = "INSERT INTO pages (
            user_id, title, slug, content, styles, components,
            is_published, is_template, category
            ) VALUES (
                :user_id, :title, :slug, :html_content, :style_content,
                :components, :is_published, :is_template, :category
                )";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':user_id' => (int)$data['user_id'],
                                  ':title' => $data['title'],
                                  ':slug' => $data['slug'],
                                  ':html_content' => $data['html_content'] ?? '',
                                  ':style_content' => $data['style_content'] ?? '',
                                  ':components' => $data['components'] ?? '',
                                  ':is_published' => (int)($data['is_published'] ?? 0),
                                  ':is_template' => (int)($data['is_template'] ?? 0),
                                  ':category' => $data['category'] ?? null
        ]);

        if ($success) {
            return (int)$this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualiza una página
     */
    public function updatePage(int $page_id, array $data): bool {
        $fields = [];
        $values = [];

        // Whitelist de campos permitidos (previene inyección de columnas)
        $allowed_fields = [
            'title', 'slug', 'content', 'styles', 'components',
            'is_published', 'is_template', 'category', 'thumbnail_url'
        ];

        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = :{$field}";
                $values[":{$field}"] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[':id'] = $page_id;

        // CORRECCIÓN: actualizar tabla 'pages' en lugar de 'templates'
        $sql = "UPDATE pages SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($values);
    }

    /**
     * Elimina una página
     */
    public function deletePage(int $page_id): bool {
        $stmt = $this->db->prepare("DELETE FROM pages WHERE id = :id");
        return $stmt->execute([':id' => $page_id]);
    }

    /**
     * Obtiene una página específica
     */
    public function getPage(int $page_id): array|false {
        $stmt = $this->db->prepare("
        SELECT p.*, u.username
        FROM pages p
        LEFT JOIN users u ON p.user_id = u.id
        WHERE p.id = :id
        ");
        $stmt->execute([':id' => $page_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene múltiples páginas con filtros
     */
    public function getPages(?int $user_id = null, array $filters = []): array {
        $where = [];
        $params = [];

        if ($user_id !== null) {
            $where[] = "p.user_id = :user_id";
            $params[':user_id'] = $user_id;
        }

        if (isset($filters['is_template'])) {
            $where[] = "p.is_template = :is_template";
            $params[':is_template'] = (int)$filters['is_template'];
        }

        if (isset($filters['is_published'])) {
            $where[] = "p.is_published = :is_published";
            $params[':is_published'] = (int)$filters['is_published'];
        }

        if (isset($filters['category'])) {
            $where[] = "p.category = :category";
            $params[':category'] = $filters['category'];
        }

        $where_clause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);

        $sql = "
        SELECT p.*, u.username,
        (SELECT COUNT(*) FROM pages WHERE user_id = p.user_id) as total_pages
        FROM pages p
        LEFT JOIN users u ON p.user_id = u.id
        {$where_clause}
        ORDER BY p.updated_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todas las categorías
     */
    public function getCategories(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca páginas por título o slug
     */
    public function searchPages(string $query, ?int $user_id = null): array {
        $where = ["(p.title LIKE :query1 OR p.slug LIKE :query2)"];
        $params = [
            ':query1' => "%{$query}%",
            ':query2' => "%{$query}%"
        ];

        if ($user_id !== null) {
            $where[] = "p.user_id = :user_id";
            $params[':user_id'] = $user_id;
        }

        $sql = "
        SELECT p.*, u.username
        FROM pages p
        LEFT JOIN users u ON p.user_id = u.id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY p.updated_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una página por slug (solo publicadas)
     */
    public function getPageBySlug(string $slug): array|false {
        $stmt = $this->db->prepare("
        SELECT p.*, u.username
        FROM pages p
        LEFT JOIN users u ON p.user_id = u.id
        WHERE p.slug = :slug AND p.is_published = 1
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
