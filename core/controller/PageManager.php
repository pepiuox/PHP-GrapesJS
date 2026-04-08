<?php

class PageManager {

    private $db;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createPage($data) {
        $sql = "INSERT INTO pages (
            user_id, title, slug, content, styles, components,
            is_published, is_template, category
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['slug'],
            $data['html_content'] ?? '',
            $data['style_content'] ?? '',
            $data['components'] ?? '',
            $data['is_published'] ?? false,
            $data['is_template'] ?? false,
            $data['category'] ?? null
        ]);

        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updatePage($page_id, $data) {
        $fields = [];
        $values = [];

        $allowed_fields = ['title', 'slug', 'html_content', 'is_published', 'category', 'thumbnail_url'];

        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $page_id;
        $sql = "UPDATE templates SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($values);
    }

    public function deletePage($page_id) {
        $stmt = $this->db->prepare("DELETE FROM pages WHERE id = ?");
        return $stmt->execute([$page_id]);
    }

    public function getPage($page_id) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username 
            FROM pages p 
            LEFT JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$page_id]);
        return $stmt->fetch();
    }

    public function getPages($user_id = null, $filters = []) {
        $where = [];
        $params = [];

        if ($user_id !== null) {
            $where[] = "p.user_id = ?";
            $params[] = $user_id;
        }

        if (isset($filters['is_template'])) {
            $where[] = "p.is_template = ?";
            $params[] = $filters['is_template'];
        }

        if (isset($filters['is_published'])) {
            $where[] = "p.is_published = ?";
            $params[] = $filters['is_published'];
        }

        if (isset($filters['category'])) {
            $where[] = "p.category = ?";
            $params[] = $filters['category'];
        }

        $where_clause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);

        $sql = "
            SELECT p.*, u.username, 
                   (SELECT COUNT(*) FROM pages WHERE user_id = p.user_id) as total_pages
            FROM pages p 
            LEFT JOIN users u ON p.user_id = u.id 
            $where_clause
            ORDER BY p.updated_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll();
    }

    public function searchPages($query, $user_id = null) {
        $where = ["(p.title LIKE ? OR p.slug LIKE ?)"];
        $params = ["%$query%", "%$query%"];

        if ($user_id !== null) {
            $where[] = "p.user_id = ?";
            $params[] = $user_id;
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
        return $stmt->fetchAll();
    }

    public function getPageBySlug($slug) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username 
            FROM pages p 
            LEFT JOIN users u ON p.user_id = u.id 
            WHERE p.slug = ? AND p.is_published = 1
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
