<?php

// classes/Template.php
class Template {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getSystemTemplates() {
        $query = "SELECT * FROM templates WHERE is_system_template = 1 ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserTemplates($user_id) {
        $query = "SELECT * FROM templates 
                  WHERE is_system_template = 0 AND created_by = :user_id 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTemplate($user_id, $name, $description, $html_content, $css_content) {
        $query = "INSERT INTO templates (name, description, content_html, content_css, 
                  is_system_template, created_by) 
                  VALUES (:name, :description, :html, :css, 0, :user_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':html', $html_content);
        $stmt->bindParam(':css', $css_content);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }

    public function getTemplate($template_id) {
        $query = "SELECT * FROM templates WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $template_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteTemplate($template_id, $user_id) {
        $query = "DELETE FROM templates WHERE id = :id AND created_by = :user_id 
                  AND is_system_template = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $template_id);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }

    public function updateTemplate($template_id, $user_id, $data) {
        $query = "UPDATE templates SET 
                  name = :name,
                  description = :description,
                  content_html = :html,
                  content_css = :css
                  WHERE id = :id AND created_by = :user_id AND is_system_template = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $template_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':html', $data['html']);
        $stmt->bindParam(':css', $data['css']);

        return $stmt->execute();
    }
}

?>