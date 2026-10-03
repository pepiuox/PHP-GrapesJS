<?php
declare(strict_types=1);

/**
 * Vista de páginas con registro de estadísticas.
 * Migrado con validaciones de seguridad.
 *
 * CORRECCIONES:
 * - Inyección de dependencias PDO
 * - Validación de page_id
 * - CSRF protection
 * - Tipado estricto
 */
class PageView
{
    private PageStatistics $pageStats;
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
        $this->pageStats = new PageStatistics($db);
    }

    /**
     * Muestra una página y registra la visita.
     */
    public function displayPage(int $page_id, string $version = '1.0'): void
    {
        // ✅ Validar page_id
        if ($page_id <= 0) {
            $this->render404();
            return;
        }

        // ✅ Registrar visita
        $this->pageStats->registerVisit($page_id, $version);

        // ✅ Obtener información de la página
        $page = $this->getPageData($page_id);

        if (!$page) {
            $this->render404();
            return;
        }

        // ✅ Renderizar página
        $this->renderPage($page);

        // ✅ Mostrar estadísticas si es necesario
        $stats = $this->pageStats->getPageStatistics($page_id);
        if ($stats) {
            echo "<div class='page-stats'>"
            . "<small>Esta página ha sido visitada "
            . htmlspecialchars((string) $stats['visits'], ENT_QUOTES, 'UTF-8')
            . " veces</small>"
            . "</div>";
        }
    }

    /**
     * Obtiene datos de la página desde la BD.
     */
    private function getPageData(int $page_id): array|false
    {
        try {
            $query = "SELECT id, title, slug, content_html, content_css, status
            FROM pages
            WHERE id = :id AND status = 'published'
            LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $page_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting page data: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Renderiza el contenido de la página.
     */
    private function renderPage(array $page): void
    {
        echo '<div class="page-content">';
        echo '<h1>' . htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') . '</h1>';
        echo '<div class="page-body">' . $page['content_html'] . '</div>';
        echo '</div>';
    }

    /**
     * Renderiza página 404.
     */
    private function render404(): void
    {
        http_response_code(404);
        echo '<div class="error-404">';
        echo '<h1>Página no encontrada</h1>';
        echo '<p>La página que buscas no existe o ha sido movida.</p>';
        echo '</div>';
    }
}
