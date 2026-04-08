<?php
// classes/view.php

require_once 'classes/PageStatistics.php';

class PageView {
    private $pageStats;
    
    public function __construct() {
        $this->pageStats = new PageStatistics();
    }
    
    public function displayPage($page_id) {
        // Registrar visita cuando se visualiza la página
        $this->pageStats->registerVisit($page_id, '1.0');
        
        // Obtener información de la página de la base de datos
        // ... tu código para mostrar la página ...
        
        // Puedes mostrar estadísticas si es necesario
        $stats = $this->pageStats->getPageStatistics($page_id);
        if ($stats) {
            echo "<div class='page-stats'>
                    <small>Esta página ha sido visitada {$stats['visits']} veces</small>
                  </div>";
        }
    }
}