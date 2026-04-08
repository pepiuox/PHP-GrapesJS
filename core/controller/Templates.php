<?php

class Templates {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($user_id = null) {
        $query = "SELECT * FROM templates WHERE is_system_template = 1";

        if ($user_id) {
            $query .= " OR created_by = :user_id";
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);

        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id, $user_id = null) {
        $query = "SELECT * FROM templates WHERE id = :id";

        if ($user_id) {
            $query .= " AND (is_system_template = 1 OR created_by = :user_id)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO templates (name, description, content_html, content_css, content_php, content_js, is_system_template, created_by) 
                  VALUES (:name, :description, :html, :css, :php, :js, :is_system, :user_id)";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
                    ':name' => $data['name'],
                    ':description' => $data['description'],
                    ':html' => $data['html'],
                    ':css' => $data['css'],
                    ':php' => $data['php'] ?? '',
                    ':js' => $data['js'] ?? '',
                    ':is_system' => $data['is_system_template'] ?? 0,
                    ':user_id' => $data['created_by'] ?? null
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE templates SET 
                  name = :name,
                  description = :description,
                  content_html = :html,
                  content_css = :css,
                  content_php = :php,
                  content_js = :js
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
                    ':name' => $data['name'],
                    ':description' => $data['description'],
                    ':html' => $data['html'],
                    ':css' => $data['css'],
                    ':php' => $data['php'] ?? '',
                    ':js' => $data['js'] ?? '',
                    ':id' => $id
        ]);
    }

    public function delete($id, $user_id) {
        $query = "DELETE FROM templates WHERE id = :id AND created_by = :user_id AND is_system_template = 0";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
                    ':id' => $id,
                    ':user_id' => $user_id
        ]);
    }

    public function render($template_id, $user_id = null) {
        $template = $this->getById($template_id, $user_id);

        if (!$template) {
            return false;
        }

        // Iniciar el buffer de salida para capturar el PHP
        ob_start();

        // Evaluar el código PHP primero
        if (!empty($template['content_php'])) {
            try {
                // Crear un entorno seguro para ejecutar PHP
                $php_code = $template['content_php'];

                // Definir variables disponibles en el contexto PHP de la plantilla
                $template_vars = [
                    'template_name' => $template['name'],
                    'template_id' => $template['id'],
                    'created_at' => $template['created_at']
                ];

                extract($template_vars);

                // Evaluar el código PHP
                eval('?>' . $php_code);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error en código PHP: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }

        $php_output = ob_get_clean();

        // Construir la salida HTML completa
        $output = '<!DOCTYPE html>';
        $output .= '<html lang="es">';
        $output .= '<head>';
        $output .= '<meta charset="UTF-8">';
        $output .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $output .= '<title>' . htmlspecialchars($template['name']) . '</title>';
        $output .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        $output .= '<style>' . $template['content_css'] . '</style>';
        $output .= '</head>';
        $output .= '<body>';
        $output .= $template['content_html'];
        $output .= $php_output; // Insertar la salida del PHP
        $output .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';

        // JavaScript de la plantilla
        if (!empty($template['content_js'])) {
            $output .= '<script>' . $template['content_js'] . '</script>';
        }

        $output .= '</body>';
        $output .= '</html>';

        return $output;
    }

    public function renderPreview($html, $css, $php = '', $js = '') {
        ob_start();

        // Evaluar el código PHP
        if (!empty($php)) {
            try {
                eval('?>' . $php);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error en código PHP: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }

        $php_output = ob_get_clean();

        $output = '<!DOCTYPE html>';
        $output .= '<html>';
        $output .= '<head>';
        $output .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        $output .= '<style>' . $css . '</style>';
        $output .= '</head>';
        $output .= '<body>';
        $output .= $html;
        $output .= $php_output;
        $output .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';

        if (!empty($js)) {
            $output .= '<script>' . $js . '</script>';
        }

        $output .= '</body>';
        $output .= '</html>';

        return $output;
    }
}
