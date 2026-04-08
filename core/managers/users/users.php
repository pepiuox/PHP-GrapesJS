<?php
// users.php

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireLogin();

$user = new User($db);
$message = '';

// Procesar búsqueda
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search_keyword)) {
    $stmt = $user->search($search_keyword);
} else {
    $stmt = $user->readAll();
}

// Procesar eliminación
if (isset($_GET['delete_id'])) {
    $user->id = $_GET['delete_id'];
    if ($user->delete()) {
        $message = '<div class="alert alert-success">Usuario eliminado correctamente.</div>';
    } else {
        $message = '<div class="alert alert-danger">Error al eliminar el usuario.</div>';
    }
}

$page_title = 'Usuarios';
?>


            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-users"></i> Gestión de Usuarios
                    </h1>
                    <a href="create-user.php" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Nuevo Usuario
                    </a>
                </div>

<?php echo $message; ?>

                <!-- Filtros y Búsqueda -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Buscar por nombre, usuario o email..." 
                                           value="<?php echo htmlspecialchars($search_keyword); ?>">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
<?php if (!empty($search_keyword)): ?>
                                        <a href="users.php" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Limpiar
                                        </a>
<?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" onchange="filterTable(this.value)">
                                    <option value="">Todos los estados</option>
                                    <option value="active">Activos</option>
                                    <option value="inactive">Inactivos</option>
                                    <option value="banned">Suspendidos</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Usuarios -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="usersTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Nombre Completo</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Registro</th>
                                        <th>Último Login</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                        <tr data-status="<?php
    echo $row['is_banned'] ? 'banned' :
            ($row['is_active'] ? 'active' : 'inactive');
?>">
                                            <td><?php echo $row['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($row['username']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php
    echo $row['role'] == 'admin' ? 'danger' :
            ($row['role'] == 'manager' ? 'warning' :
                    ($row['role'] == 'editor' ? 'info' : 'secondary'));
?>">
    <?php echo $row['role']; ?>
                                                </span>
                                            </td>
                                            <td>
    <?php if ($row['is_banned']): ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-ban"></i> Suspendido
                                                        </span>
        <?php if ($row['banned_reason']): ?>
                                                                <br><small><?php echo htmlspecialchars($row['banned_reason']); ?></small>
        <?php endif; ?>
    <?php elseif ($row['is_active']): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle"></i> Activo
                                                        </span>
    <?php else: ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-times-circle"></i> Inactivo
                                                        </span>
    <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                            <td>
    <?php if ($row['last_login']): ?>
        <?php echo date('d/m/Y H:i', strtotime($row['last_login'])); ?>
    <?php else: ?>
                                                        <span class="text-muted">Nunca</span>
    <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="edit-user.php?id=<?php echo $row['id']; ?>" 
                                                       class="btn btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="view-user.php?id=<?php echo $row['id']; ?>" 
                                                       class="btn btn-outline-info" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
    <?php if ($auth->isAdmin() && $row['id'] != $_SESSION['user_id']): ?>
                                                        <button type="button" class="btn btn-outline-danger" 
                                                                onclick="confirmDelete(<?php echo $row['id']; ?>)" 
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
<?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar este usuario? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="deleteLink" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>
 