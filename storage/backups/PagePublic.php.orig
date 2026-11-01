<?php
// classes/PagePublic.php
// Migrated to PDO & Secured by AI Assistant
class PagePublic {
    protected PDO $conn;
    private string $allowedBasePath;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
        $this->allowedBasePath = realpath(__DIR__ . '/../core/elements');
    }

    /**
     * Muestra una página pública con validación de seguridad
     */
    public function viewPagePublic(array $rp): void {
        global $fname, $title;

        // Validar que $rp tenga las claves necesarias
        if (!isset($rp['type']) || !isset($rp['url'])) {
            http_response_code(400);
            echo '<h1>Error: Invalid page configuration</h1>';
            return;
        }

        include_once URL . "/core/elements/top.php";

        // Renderizar estilos si es tipo Design
        if ($rp['type'] === 'Design' && !empty($rp['css_content'])) {
            echo '<style>' . "\n";
            // decodeContent() debe estar definida en otro archivo
            echo $this->sanitizeCSS($rp['css_content']);
            echo "\n" . '</style>' . "\n";
        }
        ?>
        </head>
        <body>
        <div id="wrapper">
        <div class='container-fluid min-h-screen' id="content-page">
        <?php
        include_once URL . "/core/elements/menu.php";

        if ($rp['type'] === 'File') {
            include_once URL . "/core/elements/alerts.php";

            // VALIDACIÓN CRÍTICA: Prevenir LFI y Path Traversal
            if ($_SERVER["REQUEST_URI"] === $rp['url']) {
                $this->safeInclude($rp['path_file'] ?? '');
            }
        } else if ($rp['type'] === 'Design') {
            if (!empty($rp['content'])) {
                $string = $this->sanitizeHTML($rp['content']);
                // Eliminar tags body para evitar conflictos
                $string = str_replace(['<body>', '</body>'], '', $string);
                echo $string . "\n";
            }
        } else {
            echo '<p>Tipo de página no soportado.</p>';
        }
        ?>
        </div>
        <?php include_once URL . "/core/elements/footer.php"; ?>
        </div>
        </body>
        </html>
        <?php
    }

    /**
     * Incluye un archivo de forma segura (previene LFI)
     */
    private function safeInclude(string $path_file): void {
        if (empty($path_file)) {
            return;
        }

        // Resolver ruta absoluta
        $full_path = realpath($path_file . '.php');

        // Verificar que la ruta existe
        if ($full_path === false || !file_exists($full_path)) {
            error_log("PagePublic: File not found: {$path_file}");
            echo '<p>Página no encontrada.</p>';
            return;
        }

        // Verificar que está dentro del directorio permitido
        if (strpos($full_path, $this->allowedBasePath) !== 0) {
            error_log("PagePublic: Path traversal attempt blocked: {$path_file}");
            echo '<p>Acceso denegado.</p>';
            return;
        }

        // Verificar que es un archivo PHP
        if (pathinfo($full_path, PATHINFO_EXTENSION) !== 'php') {
            error_log("PagePublic: Non-PHP file attempt: {$path_file}");
            echo '<p>Tipo de archivo no permitido.</p>';
            return;
        }

        // Incluir archivo seguro
        include $full_path;
    }

    /**
     * Sanitiza CSS para prevenir inyección
     */
    private function sanitizeCSS(string $css): string {
        // Eliminar posibles intentos de inyección de JavaScript
        $css = preg_replace('/expression\s*\(/i', '', $css);
        $css = preg_replace('/javascript\s*:/i', '', $css);
        $css = preg_replace('/url\s*\([^)]*\)/i', '', $css);

        // Eliminar comentarios que puedan ocultar código malicioso
        $css = preg_replace('/\/\*.*?\*\//s', '', $css);

        return $css;
    }

    /**
     * Sanitiza HTML para prevenir XSS
     */
    private function sanitizeHTML(string $html): string {
        // Permitir tags HTML básicos pero eliminar scripts
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

        // Eliminar eventos JavaScript inline
        $html = preg_replace('/\bon\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\bon\w+\s*=\s*[^\s>]*/i', '', $html);

        // Eliminar javascript: URLs
        $html = preg_replace('/href\s*=\s*["\']javascript:[^"\']*["\']/i', 'href="#"', $html);

        return $html;
    }
}
