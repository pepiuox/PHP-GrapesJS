<?php
// users-manager.php


$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$roleManager = new RoleManager($db);

// Verificar permisos de administración
if (!$auth->isLoggedIn() || !$auth->hasPermission('manage_users')) {
    header('Location: ../index.php');
    exit;
}

$user_id = $auth->getCurrentUserId();
$current_role = $auth->getCurrentUserRole();

// Procesar acciones
$message = '';
$message_type = '';

// Cambiar rol de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_role'])) {
    $target_user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    
    // Verificar que no sea auto-modificación para admin
    if ($target_user_id == $user_id && $new_role != 'admin') {
        $message = 'No puedes cambiar tu propio rol de administrador';
        $message_type = 'danger';
    } else {
        if ($roleManager->updateUserRole($target_user_id, $new_role)) {
            $message = 'Rol actualizado correctamente';
            $message_type = 'success';
            $roleManager->logActivity($user_id, 'change_role', 
                "Cambió rol de usuario ID: $target_user_id a $new_role");
        } else {
            $message = 'Error al actualizar el rol';
            $message_type = 'danger';
        }
    }
}

// Banear/desbanear usuario
if (isset($_GET['toggle_ban'])) {
    $target_user_id = $_GET['toggle_ban'];
    $ban = isset($_GET['ban']) ? (bool)$_GET['ban'] : true;
    $reason = $_GET['reason'] ?? '';
    
    // Verificar que no sea auto-ban
    if ($target_user_id == $user_id) {
        $message = 'No puedes banear tu propia cuenta';
        $message_type = 'danger';
    } else {
        if ($roleManager->toggleBanUser($target_user_id, $ban, $reason)) {
            $action = $ban ? 'baneado' : 'desbaneado';
            $message = "Usuario $action correctamente";
            $message_type = 'success';
            $roleManager->logActivity($user_id, $ban ? 'ban_user' : 'unban_user', 
                "Usuario ID: $target_user_id - Razón: $reason");
        } else {
            $message = 'Error al actualizar el estado del usuario';
            $message_type = 'danger';
        }
    }
}

// Obtener todos los usuarios
$query = "SELECT id, username, email, full_name, role, is_banned, 
                 created_at, last_login, banned_reason
          FROM users 
          ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas
$user_stats = $roleManager->getUserStatsByRole();

$page_title = 'Administración de Usuarios';
$additional_css = ['../assets/css/admin.css'];
?>
<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Administración de Usuarios</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Administración de Usuarios</h2>
        <a href="activity_logs.php" class="btn btn-outline-info">
            <i class="bi bi-clock-history"></i> Ver Logs de Actividad
        </a>
    </div>
    
    <!-- Mensajes -->
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estadísticas de Usuarios</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($user_stats as $stat): 
                            $role_info = $roleManager->getRoleInfo($stat['role']);
                            $role_name = $role_info ? $role_info['name'] : $stat['role'];
                        ?>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><?php echo htmlspecialchars($role_name); ?></h6>
                                    <h2 class="text-primary"><?php echo $stat['total']; ?></h2>
                                    <div class="small">
                                        <span class="badge bg-success">Activos: <?php echo $stat['active']; ?></span>
                                        <span class="badge bg-danger">Baneados: <?php echo $stat['banned']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Usuarios -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list"></i> Lista de Usuarios</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Último Login</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): 
                            $role_info = $roleManager->getRoleInfo($user['role']);
                            $role_name = $role_info ? $role_info['name'] : $user['role'];
                        ?>
                        <tr class="<?php echo $user['is_banned'] ? 'table-danger' : ''; ?>">
                            <td><?php echo $user['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                <?php if ($user['full_name']): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($user['full_name']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo getRoleBadgeColor($user['role']); ?>">
                                    <?php echo htmlspecialchars($role_name); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['is_banned']): ?>
                                    <span class="badge bg-danger">Baneado</span>
                                    <?php if ($user['banned_reason']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($user['banned_reason']); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></small>
                            </td>
                            <td>
                                <?php if ($user['last_login']): ?>
                                    <small><?php echo date('d/m/Y H:i', strtotime($user['last_login'])); ?></small>
                                <?php else: ?>
                                    <small class="text-muted">Nunca</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Cambiar Rol -->
                                    <button type="button" class="btn btn-outline-primary" 
                                            data-bs-toggle="modal" data-bs-target="#changeRoleModal"
                                            data-user-id="<?php echo $user['id']; ?>"
                                            data-current-role="<?php echo $user['role']; ?>"
                                            data-user-name="<?php echo htmlspecialchars($user['username']); ?>">
                                        <i class="bi bi-person-badge"></i>
                                    </button>
                                    
                                    <!-- Banear/Desbanear -->
                                    <?php if ($user['id'] != $user_id): ?>
                                        <?php if ($user['is_banned']): ?>
                                            <a href="?toggle_ban=<?php echo $user['id']; ?>&ban=0" 
                                               class="btn btn-outline-success"
                                               onclick="return confirm('¿Desbanear a <?php echo htmlspecialchars($user['username']); ?>?')">
                                                <i class="bi bi-person-check"></i>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-outline-warning" 
                                                    data-bs-toggle="modal" data-bs-target="#banUserModal"
                                                    data-user-id="<?php echo $user['id']; ?>"
                                                    data-user-name="<?php echo htmlspecialchars($user['username']); ?>">
                                                <i class="bi bi-person-x"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <!-- Ver Actividad -->
                                    <a href="user_activity.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-outline-info">
                                        <i class="bi bi-activity"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar rol -->
<div class="modal fade" id="changeRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Rol de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="modalUserId" name="user_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Usuario: <strong id="modalUserName"></strong></label>
                    </div>
                    
                    <div class="mb-3">
                        <label for="newRole" class="form-label">Nuevo Rol</label>
                        <select class="form-select" id="newRole" name="new_role" required>
                            <?php 
                            $roles = $roleManager->getAllRoles();
                            foreach ($roles as $role_key => $role_info): 
                            ?>
                                <option value="<?php echo $role_key; ?>">
                                    <?php echo htmlspecialchars($role_info['name']); ?>
                                    (Nivel: <?php echo $role_info['level']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Los permisos se actualizarán automáticamente según el nuevo rol.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="change_role" class="btn btn-primary">Cambiar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para banear usuario -->
<div class="modal fade" id="banUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Banear Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="">
                <div class="modal-body">
                    <input type="hidden" id="banUserId" name="toggle_ban">
                    <input type="hidden" name="ban" value="1">
                    
                    <div class="mb-3">
                        <p>¿Estás seguro de banear a <strong id="banUserName"></strong>?</p>
                        <p class="text-danger">El usuario no podrá iniciar sesión hasta que sea desbaneado.</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="banReason" class="form-label">Razón (opcional)</label>
                        <textarea class="form-control" id="banReason" name="reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Banear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Función para color de badges según rol
function getRoleBadgeColor($role) {
    $colors = [
        'admin' => 'danger',
        'manager' => 'warning',
        'editor' => 'primary',
        'guest' => 'secondary'
    ];
    return $colors[$role] ?? 'secondary';
}

$additional_js = ['../assets/js/admin.js'];
$custom_js = <<<JS
// Configurar modales
document.addEventListener('DOMContentLoaded', function() {
    // Modal cambiar rol
    const changeRoleModal = document.getElementById('changeRoleModal');
    if (changeRoleModal) {
        changeRoleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            const currentRole = button.getAttribute('data-current-role');
            
            document.getElementById('modalUserId').value = userId;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('newRole').value = currentRole;
        });
    }
    
    // Modal banear usuario
    const banUserModal = document.getElementById('banUserModal');
    if (banUserModal) {
        banUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            
            document.getElementById('banUserId').value = userId;
            document.getElementById('banUserName').textContent = userName;
        });
    }
});
JS;

?>