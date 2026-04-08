<?php
// includes/sidebar.php
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}
?>
<!-- Sidebar -->
<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center p-3">
            <h4><i class="fas fa-users"></i> Admin Usuarios</h4>
            <small>Bienvenido, <?php echo $_SESSION['username']; ?></small>
        </div>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                   href="<?php echo PathUrl().'/users/dashboard.php'; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>" 
                   href="<?php echo PathUrl().'/users/users.php'; ?>">
                    <i class="fas fa-users"></i> Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'create-user.php' ? 'active' : ''; ?>" 
                   href="<?php echo PathUrl().'/users/create-user.php'; ?>">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </a>
            </li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>" 
                   href="<?php echo PathUrl().'/settings.php'; ?>">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                    </li>
                <?php endif; ?>
           
            <!-- En includes/sidebar.php, agregar después de los enlaces existentes -->
<li class="nav-item">
    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && isset($is_file_manager) ? 'active' : ''; ?>" 
       href="<?php echo PathUrl().'/users/file-manager/index.php'; ?>">
        <i class="fas fa-folder-open"></i> Administrador de Archivos
    </a>
</li>
 <li class="nav-item">
                <a class="nav-link text-danger" href="../signin/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</nav>