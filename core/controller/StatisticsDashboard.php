<?php

class StatisticsDashboard {
    private $pageStats;
    
    public function __construct() {
        $this->pageStats = new PageStatistics();
    }
    
    public function displayDashboard() {
        echo "<h1>Estadísticas de Páginas</h1>";
        
        // Resumen general
        $summary = $this->pageStats->getSummaryStatistics();
        echo "<div class='summary'>
                <h2>Resumen General</h2>
                <p>Total de páginas rastreadas: {$summary['total_pages_tracked']}</p>
                <p>Total de visitas: {$summary['total_visits']}</p>
                <p>Promedio de visitas por página: " . round($summary['average_visits_per_page'], 2) . "</p>
                <p>Última visita: {$summary['most_recent_visit']}</p>
              </div>";
        
        // Páginas más visitadas
        $mostVisited = $this->pageStats->getMostVisitedPages(5);
        echo "<div class='most-visited'>
                <h2>Páginas Más Visitadas</h2>
                <table border='1'>
                    <tr>
                        <th>Título</th>
                        <th>Visitas</th>
                        <th>Versión</th>
                        <th>Última visita</th>
                        <th>Creada</th>
                    </tr>";
        
        foreach ($mostVisited as $page) {
            echo "<tr>
                    <td>{$page['title']}</td>
                    <td>{$page['visits']}</td>
                    <td>{$page['version']}</td>
                    <td>{$page['last_visit']}</td>
                    <td>{$page['page_created']}</td>
                  </tr>";
        }
        
        echo "</table></div>";
        
        // Todas las estadísticas
        $allStats = $this->pageStats->getAllStatistics();
        echo "<div class='all-stats'>
                <h2>Todas las Estadísticas</h2>
                <table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Slug</th>
                        <th>Visitas</th>
                        <th>Versión</th>
                        <th>Estado</th>
                        <th>Creada</th>
                        <th>Actualizada</th>
                        <th>Última visita</th>
                    </tr>";
        
        foreach ($allStats as $stat) {
            echo "<tr>
                    <td>{$stat['page_id']}</td>
                    <td>{$stat['title']}</td>
                    <td>{$stat['slug']}</td>
                    <td>{$stat['visits']}</td>
                    <td>{$stat['version']}</td>
                    <td>{$stat['status']}</td>
                    <td>{$stat['page_created']}</td>
                    <td>{$stat['page_updated']}</td>
                    <td>{$stat['last_visit']}</td>
                  </tr>";
        }
        
        echo "</table></div>";
    }
    
    public function displayDateRangeStats($start_date, $end_date) {
        $statsByDate = $this->pageStats->getVisitsByDateRange($start_date, $end_date);
        
        echo "<h2>Estadísticas por Rango de Fechas: {$start_date} - {$end_date}</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Fecha</th>
                    <th>Páginas Creadas</th>
                    <th>Total Visitas</th>
                    <th>Versiones Usadas</th>
                </tr>";
        
        foreach ($statsByDate as $stat) {
            echo "<tr>
                    <td>{$stat['visit_date']}</td>
                    <td>{$stat['pages_created']}</td>
                    <td>{$stat['total_visits']}</td>
                    <td>{$stat['versions_used']}</td>
                  </tr>";
        }
        
        echo "</table>";
    }
}

