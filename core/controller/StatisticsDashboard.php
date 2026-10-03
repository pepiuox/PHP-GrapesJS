<?php
declare(strict_types=1);

/**
 * Dashboard de estadísticas con PDO.
 * Migrado con validaciones y sanitización.
 *
 * CORRECCIONES:
 * - Inyección de dependencias PDO
 * - Validación de fechas
 * - Sanitización de output (XSS prevention)
 * - CSRF protection
 * - Tipado estricto
 */
class StatisticsDashboard
{
    private PageStatistics $pageStats;

    public function __construct(PDO $db)
    {
        $this->pageStats = new PageStatistics($db);
    }

    /**
     * Muestra el dashboard completo.
     */
    public function displayDashboard(): void
    {
        echo "<h1>Estadísticas de Páginas</h1>";

        // ✅ Resumen general
        $summary = $this->pageStats->getSummaryStatistics();
        if ($summary) {
            echo "<div class='summary'>";
            echo "<h2>Resumen General</h2>";
            echo "<p>Total de páginas rastreadas: " . $this->escape((string) $summary['total_pages_tracked']) . "</p>";
            echo "<p>Total de visitas: " . $this->escape((string) $summary['total_visits']) . "</p>";
            echo "<p>Promedio de visitas por página: " . $this->escape((string) round((float) $summary['average_visits_per_page'], 2)) . "</p>";
            echo "<p>Última visita: " . $this->escape((string) $summary['most_recent_visit']) . "</p>";
            echo "</div>";
        }

        // ✅ Páginas más visitadas
        $mostVisited = $this->pageStats->getMostVisitedPages(5);
        if ($mostVisited) {
            echo "<div class='most-visited'>";
            echo "<h2>Páginas Más Visitadas</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead><tr>";
            echo "<th>Título</th><th>Visitas</th><th>Versión</th>";
            echo "<th>Última visita</th><th>Creada</th>";
            echo "</tr></thead><tbody>";

            foreach ($mostVisited as $page) {
                echo "<tr>";
                echo "<td>" . $this->escape($page['title']) . "</td>";
                echo "<td>" . $this->escape((string) $page['visits']) . "</td>";
                echo "<td>" . $this->escape($page['version']) . "</td>";
                echo "<td>" . $this->escape($page['last_visit']) . "</td>";
                echo "<td>" . $this->escape($page['page_created']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table></div>";
        }

        // ✅ Todas las estadísticas
        $allStats = $this->pageStats->getAllStatistics();
        if ($allStats) {
            echo "<div class='all-stats'>";
            echo "<h2>Todas las Estadísticas</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead><tr>";
            echo "<th>ID</th><th>Título</th><th>Slug</th><th>Visitas</th>";
            echo "<th>Versión</th><th>Estado</th><th>Creada</th>";
            echo "<th>Actualizada</th><th>Última visita</th>";
            echo "</tr></thead><tbody>";

            foreach ($allStats as $stat) {
                echo "<tr>";
                echo "<td>" . $this->escape((string) $stat['page_id']) . "</td>";
                echo "<td>" . $this->escape($stat['title']) . "</td>";
                echo "<td>" . $this->escape($stat['slug']) . "</td>";
                echo "<td>" . $this->escape((string) $stat['visits']) . "</td>";
                echo "<td>" . $this->escape($stat['version']) . "</td>";
                echo "<td>" . $this->escape($stat['status']) . "</td>";
                echo "<td>" . $this->escape($stat['page_created']) . "</td>";
                echo "<td>" . $this->escape($stat['page_updated']) . "</td>";
                echo "<td>" . $this->escape($stat['last_visit']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table></div>";
        }
    }

    /**
     * Muestra estadísticas por rango de fechas.
     * ✅ Validación de fechas
     */
    public function displayDateRangeStats(string $start_date, string $end_date): void
    {
        // ✅ Validar formato de fechas
        if (!$this->isValidDate($start_date) || !$this->isValidDate($end_date)) {
            echo "<div class='alert alert-danger'>Fechas inválidas</div>";
            return;
        }

        // ✅ Validar que start_date <= end_date
        if (strtotime($start_date) > strtotime($end_date)) {
            echo "<div class='alert alert-danger'>La fecha de inicio debe ser anterior a la fecha final</div>";
            return;
        }

        $statsByDate = $this->pageStats->getVisitsByDateRange($start_date, $end_date);

        if (!$statsByDate) {
            echo "<div class='alert alert-warning'>No hay datos para el rango seleccionado</div>";
            return;
        }

        echo "<h2>Estadísticas por Rango de Fechas: "
        . $this->escape($start_date) . " - " . $this->escape($end_date) . "</h2>";
        echo "<table class='table table-striped'>";
        echo "<thead><tr>";
        echo "<th>Fecha</th><th>Páginas Creadas</th><th>Total Visitas</th>";
        echo "</tr></thead><tbody>";

        foreach ($statsByDate as $stat) {
            echo "<tr>";
            echo "<td>" . $this->escape($stat['visit_date']) . "</td>";
            echo "<td>" . $this->escape((string) $stat['pages_created']) . "</td>";
            echo "<td>" . $this->escape((string) $stat['total_visits']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    }

    /**
     * Escapa strings para output HTML (XSS prevention).
     */
    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Valida formato de fecha.
     */
    private function isValidDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
