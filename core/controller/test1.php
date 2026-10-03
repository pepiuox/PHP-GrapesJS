<?php
// Conexión PDO centralizada
$pdo = new PDO(
    "mysql:host=localhost;dbname=ecommerce;charset=utf8mb4",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

// Inicializar componentes
$siteDefs = new SiteDefinitions($pdo);
$siteDefs->loadDefinitions(); // Cargar defines en el entorno

// Registrar visita
$statistic = new Statistic($pdo);
$statsRecorder = new PageStatsRecorder($pdo, false);
$statsRecorder->recordVisit(123, 'Página de inicio');

// Obtener estadísticas
$pageStats = $statsRecorder->getPageStats(123, 30);
$mostVisited = $statsRecorder->getMostVisited(10, 30);

// Controlador del sistema (solo admin)
if (SessionManager::isAdmin()) {
    $systemController = new SystemController($pdo);
    $systemController->config();
}

// Actualizar configuración (requiere CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteDefs->updateConfig('SITE_NAME', 'Mi Nuevo Sitio');
}

// Inicializar componentes
$userController = new UserController($pdo);
$typeFields = new TypeFields($pdo, 'products');
$tableSettings = new TableSettings($pdo);
$testClass = new TestClass($pdo);
$userChange = new UserChange($pdo);

// Mostrar perfil del usuario
$userController->profile();

// Generar campos dinámicos para productos
echo '<form method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="csrf_token" value="' . SessionManager::getCSRFToken() . '">';
$typeFields->renderFields('id');
echo '<button type="submit">Guardar</button>';
echo '</form>';

// Obtener configuración de tabla
$config = $tableSettings->tblSettings('products');
if ($config !== null) {
    echo '<pre>' . htmlspecialchars($config) . '</pre>';
}

// Verificar permisos del usuario
if ($testClass->hasPermission('edit_products')) {
    echo "Puede editar productos";
}

$level = $testClass->levels();
echo "Nivel de acceso: " . ($level ?? 'Ninguno');

// Inicializar componentes
$roleManager = new RoleManager($pdo);
$cache = new Cache($pdo);
$logger = new ActivityLogger($pdo);
$forgot = new UsersForgot($pdo);

// Login y gestión de sesión
if (isset($_POST['login'])) {
    $user = [...]; // datos del usuario
    SessionManager::startSession($user);
    $logger->logActivity($user['id'], 'login', 'Inicio de sesión exitoso');
}

// Verificar permisos
if (SessionManager::isLoggedIn()) {
    $userId = SessionManager::getUserId();

    if ($roleManager->hasPermission($userId, 'manage_pages')) {
        echo "Puede gestionar páginas";
    }

    if ($roleManager->canEditPage($userId, 123)) {
        echo "Puede editar la página 123";
    }
}

// Uso de cache
$pageId = 42;
$cached = $cache->getPage($pageId);
if ($cached === false) {
    $content = "..."; // generar contenido
    $cache->setPage($pageId, $content, 3600);
}

// Recuperación de contraseña
if (isset($_POST['forgotPassword'])) {
    // CSRF token en el formulario
    // <input type="hidden" name="csrf_token" value="<?= SessionManager::getCSRFToken() ?>">
}

// Inicializar componentes
$pageStats = new PageStatistics($pdo);
$pageView = new PageView($pdo);
$dashboard = new StatisticsDashboard($pdo);
$templates = new Templates($pdo);
$forgot = new UsersForgot($pdo);

// Mostrar página con estadísticas
$pageView->displayPage(123, '1.0');

// Mostrar dashboard de estadísticas
$dashboard->displayDashboard();
$dashboard->displayDateRangeStats('2024-01-01', '2024-12-31');

// Gestionar plantillas
$templateId = 5;
$html = $templates->render($templateId, $_SESSION['user_id'] ?? null);
echo $html;

// Recuperación de contraseña
if (isset($_POST['forgotPassword'])) {
    // CSRF token debe estar en el formulario
    // <input type="hidden" name="csrf_token" value="<?= Utils::generateCSRFToken() ?>">
}

// Inicializar componentes
$template = new Template($pdo);
$paths = new Paths($pdo);
$pageSystem = new PageSystem($pdo, __DIR__ . '/../../..');

// Renderizar página
$pageSystem->viewPageSystem();

// Obtener path de página
$pagePath = $paths->PagesPath('about-us');
echo '<a href="' . htmlspecialchars($pagePath) . '">About Us</a>';

// Gestionar plantillas
$templates = $template->getUserTemplates($_SESSION['user_id'] ?? 0);
foreach ($templates as $tpl) {
    echo '<div>' . htmlspecialchars($tpl['name']) . '</div>';
}

// Inicializar componentes
$cache = new CacheManager(__DIR__ . '/../cache/pages', 3600);
$pageRepo = new PageRepository($pdo);
$router = new RouterOptimized($pdo, $cache);
$urls = new UrlGenerator($pdo);

// Cargar página actual
$page = $router->loadPage();

if ($page === null || ($page['status'] ?? 200) === 404) {
    http_response_code(404);
    echo '<h1>404 - Página no encontrada</h1>';
    exit;
}

// Generar URLs
$canonical = $urls->getCanonicalUrl($page);
$homeUrl = $urls->route('home');
$profileUrl = $urls->route('user.profile', ['id' => 123]);
$assetUrl = $urls->asset('css/style.css');

// Usar en HTML
echo '<link rel="canonical" href="' . htmlspecialchars($canonical) . '">';
echo '<a href="' . htmlspecialchars($homeUrl) . '">Inicio</a>';
echo '<link rel="stylesheet" href="' . htmlspecialchars($assetUrl) . '">';
