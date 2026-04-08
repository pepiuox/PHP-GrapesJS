<?php
// classes/Page.php
class Page {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($user_id, $title, $template_id = null) {
        $slug = $this->generateSlug($title);
        
        // Si hay template, cargar su contenido
        $html_content = '';
        $css_content = '';
        
        if ($template_id) {
            $template = $this->getTemplate($template_id);
            if ($template) {
                $html_content = $template['content_html'];
                $css_content = $template['content_css'];
            }
        }
        
        $query = "INSERT INTO pages (user_id, title, slug, content_html, content_css) 
                  VALUES (:user_id, :title, :slug, :html, :css)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':html', $html_content);
        $stmt->bindParam(':css', $css_content);
        $stmt->bindParam(':php', $php_content);
        $stmt->bindParam(':js', $js_content);
        
        if ($stmt->execute()) {
            $page_id = $this->conn->lastInsertId();
            $this->createVersion($page_id, $html_content, $css_content, '', $user_id);
            return $page_id;
        }
        
        return false;
    }
    
    public function update($page_id, $user_id, $data) {
        $query = "UPDATE pages SET 
                  title = :title,
                  html_content = :html,
                  css_content = :css,
                  php_content = :php,
                  js_content = :js,
                  updated_at = NOW()
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $page_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':html', $data['html']);
        $stmt->bindParam(':css', $data['css']);
        $stmt->bindParam(':php', $data['php']);
        $stmt->bindParam(':js', $data['js']);
        
        if ($stmt->execute()) {
            // Crear nueva versión
            $version_number = $this->getNextVersionNumber($page_id);
            $this->createVersion($page_id, $data['html'], $data['css'], $data['php'], $user_id);
            return true;
        }
        
        return false;
    }
    
    public function getPagesByUser($user_id) {
        $query = "SELECT * FROM pages WHERE user_id = :user_id ORDER BY updated_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPage($page_id, $user_id = null) {
        $query = "SELECT * FROM pages WHERE id = :id";
        if ($user_id) {
            $query .= " AND user_id = :user_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $page_id);
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id);
        }
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function delete($page_id, $user_id) {
        $query = "DELETE FROM pages WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $page_id);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    
    public function publish($page_id, $user_id) {
        $query = "UPDATE pages SET is_published = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $page_id);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    
    public function unpublish($page_id, $user_id) {
        $query = "UPDATE pages SET is_published = 0 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $page_id);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    
    private function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = $slug . '-' . uniqid();
        return $slug;
    }
    
    private function createVersion($page_id, $html, $css, $php, $user_id) {
        $version_number = $this->getNextVersionNumber($page_id);
        
        $query = "INSERT INTO page_versions (page_id, version_number, content_html, content_css, content_php, created_by) 
                  VALUES (:page_id, :version, :html, :css, :php, :user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->bindParam(':version', $version_number);
        $stmt->bindParam(':html', $html);
        $stmt->bindParam(':css', $css);
        $stmt->bindParam(':php', $php);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    
    private function getNextVersionNumber($page_id) {
        $query = "SELECT MAX(version_number) as max_version FROM page_versions WHERE page_id = :page_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($result['max_version'] ? $result['max_version'] + 1 : 1);
    }
    
    public function getTemplates() {
        $query = "SELECT * FROM templates ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTemplate($template_id) {
        $query = "SELECT * FROM templates WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $template_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getVersions($page_id) {
        $query = "SELECT * FROM page_versions WHERE page_id = :page_id ORDER BY version_number DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function restoreVersion($page_id, $version_id, $user_id) {
        // Obtener la versión
        $query = "SELECT * FROM page_versions WHERE id = :id AND page_id = :page_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $version_id);
        $stmt->bindParam(':page_id', $page_id);
        $stmt->execute();
        $version = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($version) {
            // Actualizar la página con la versión
            $update_query = "UPDATE pages SET 
                            content_html = :html,
                            content_css = :css,
                            content_php = :php,
                            updated_at = NOW()
                            WHERE id = :id AND user_id = :user_id";
            
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(':id', $page_id);
            $update_stmt->bindParam(':user_id', $user_id);
            $update_stmt->bindParam(':html', $version['content_html']);
            $update_stmt->bindParam(':css', $version['content_css']);
            $update_stmt->bindParam(':php', $version['content_php']);
            
            if ($update_stmt->execute()) {
                // Crear nueva versión con el contenido restaurado
                $this->createVersion($page_id, $version['content_html'], $version['content_css'], $version['content_php'], $user_id);
                return true;
            }
        }
        
        return false;
    }
}
?>
