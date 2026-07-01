<?php
// index.php - Integración con tu router existente

// Incluir la clase
require_once 'includes/PageCacheManager.php';

// Conexión a BD (tu conexión existente)
global $conn;

// Configuración del caché
$cacheConfig = [
    'cache_dir' => __DIR__ . '/cache/pages',
'cache_ttl' => 7200,  // 2 horas
'enable_compression' => true
];

// Inicializar cache manager
$pageCache = new PageCacheManager($conn, $cacheConfig);

// Función para limpiar caché cuando se actualiza una página
function onPageUpdate($pageId) {
    global $pageCache;

    // Obtener slug de la página actualizada
    $stmt = $conn->prepare("SELECT slug FROM pages WHERE id = ?");
    $stmt->bind_param("i", $pageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();

    if ($page) {
        // Invalidar caché de esta página
        $pageCache->invalidateCache($page['slug']);

        // Opcional: Invalidar páginas hijas
        // $pageCache->invalidateChildren($pageId);
    }
}

// Uso en tu router existente
class Routers {
    private $pageCache;

    public function __construct($conn) {
        global $pageCache;
        $this->pageCache = $pageCache;
        // ... resto de tu código
    }

    public function loadPage() {
        // Obtener slug actual
        $slug = $this->getCurrentSlug();

        // Intentar cargar desde caché
        $pageData = $this->pageCache->getPage($slug);

        if (!$pageData) {
            // Si no está en caché, cargar normalmente
            $pageData = $this->loadPageFromDatabase($slug);

            if (!$pageData) {
                // Página 404
                header("HTTP/1.0 404 Not Found");
                $pageData = $this->get404Page();
            }
        }

        return $pageData;
    }
}

// Precalentar caché para páginas populares
// Ejecutar esto en un cron job o después de actualizaciones masivas
function warmupPopularPages() {
    global $pageCache;

    $popularSlugs = ['home', 'about', 'contact', 'services'];
    $result = $pageCache->warmupCache($popularSlugs);

    echo "Caché precalentado: {$result['success']} páginas\n";
}

// Script de mantenimiento (cron diario)
function cacheMaintenance() {
    global $pageCache;

    // Limpiar caché expirado
    $cleaned = $pageCache->cleanExpiredCache();
    echo "Limpiadas $cleaned entradas de caché expiradas\n";

    // Mostrar estadísticas
    $stats = $pageCache->getCacheStats();
    print_r($stats);
}

// Ejemplo de uso en página dinámica
function renderPage($slug) {
    global $pageCache;

    // Obtener página con caché automático
    $page = $pageCache->getPage($slug);

    if (!$page) {
        return "Página no encontrada";
    }

    // Forzar actualización si es admin
    if (isAdmin()) {
        $page = $pageCache->getPage($slug, true); // forceRefresh = true
    }

    // Renderizar contenido
    ob_start();
    eval('?>' . $page['php_content']);
    $dynamicContent = ob_get_clean();

    return [
        'title' => $page['title'],
        'html' => $page['html_content'],
        'css' => $page['css_content'],
        'js' => $page['js_content'],
        'dynamic' => $dynamicContent
    ];
}
