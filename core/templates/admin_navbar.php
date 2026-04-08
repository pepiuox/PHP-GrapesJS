<?php
// templates/navbar.php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/RoleManager.php';
require_once __DIR__ . '/../classes/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>dashboard.php">
            <i class="bi bi-layout-text-window-reverse"></i> PePiuoX Web Editor
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainNavbar">
<?php if ($auth->isLoggedIn()): ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>users/dashboard.php">
                                <i class="bi bi-grid"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'users-manager.php') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>users/users-manager.php">
                                <i class="bi bi-person"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'editor.php') ? 'active' : ''; ?>" 
                               href="#" data-bs-toggle="modal" data-bs-target="#createPageModal">
                                <i class="bi bi-plus-circle"></i> Nueva Página
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-collection"></i> Plantillas
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="templates.php">Mis Plantillas</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#saveAsTemplateModal">
                                    Guardar como Plantilla
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>settings.php">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><span class="dropdown-item-text">
                                    <small><?php echo htmlspecialchars($_SESSION['email']); ?></small>
                                </span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile.php">
                                    <i class="bi bi-person"></i> Mi Perfil
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>settings.php">
                                    <i class="bi bi-sliders"></i> Configuración
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                    </ul>
<?php else: ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>index.php">
                                Iniciar Sesión
                            </a>
                        </li>
                    </ul>
<?php endif; ?>
        </div>
    </div>
</nav>

    <!-- Modal para crear página (se usa en múltiples páginas) -->
    <div class="modal fade" id="createPageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Página</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createPageForm" method="POST" action="<?php echo BASE_URL; ?>dashboard.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newPageTitle" class="form-label">Título de la Página</label>
                            <input type="text" class="form-control" id="newPageTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPageTemplate" class="form-label">Plantilla (opcional)</label>
                            <select class="form-select" id="newPageTemplate" name="template_id">
                                <option value="">Crear desde cero</option>
    <?php
    if ($auth->isLoggedIn()) {
        require_once __DIR__ . '/../classes/Page.php';
        $pageManager = new Page($db);
        $templates = $pageManager->getTemplates();
        foreach ($templates as $template):
            ?>
                                                <option value="<?php echo $template['id']; ?>">
            <?php echo htmlspecialchars($template['name']); ?>
                                                </option>
        <?php
        endforeach;
    }
    ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="create_page" class="btn btn-primary">Crear Página</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

