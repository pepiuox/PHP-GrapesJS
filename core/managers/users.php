<?php
session_start();
// Verificar si el usuario está logueado y tiene permisos de admin
// if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'Admin') {
//     header('Location: ../login.php');
//     exit;
// }

require_once '../classes/UserManager.php';

$userManager = new UserManager();

// Obtener parámetros
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_level = isset($_GET['level']) ? $_GET['level'] : '';
$records_per_page = 10;

// Procesar acciones
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'update':
            $iduv = $_POST['iduv'];
            $data = array(
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'level' => $_POST['level'],
                'banned' => isset($_POST['banned']) ? 1 : 0,
                'is_activate' => isset($_POST['is_activate']) ? 1 : 0,
                'verified' => isset($_POST['verified']) ? 1 : 0
            );
            $userManager->updateUser($iduv, $data);
            break;
            
        case 'delete':
            $iduv = $_POST['iduv'];
            $userManager->deleteUser($iduv);
            break;
    }
}

// Obtener datos
$users = $userManager->getUsers($page, $records_per_page, $search, $filter_level);
$total_users = $userManager->countUsers($search, $filter_level);
$total_pages = ceil($total_users / $records_per_page);
$levels = $userManager->getLevels();
$stats = $userManager->getStatistics();

// Generar token en el formulario
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;
echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
 
// Verificar token en el servidor
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    // Token inválido, manejar el error
    die("Ataque CSRF detectado");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .stat-card {
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .user-row {
            transition: background-color 0.2s;
        }
        .user-row:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <h5 class="px-3">Administración</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="bi bi-people"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#stats" data-bs-toggle="collapse">
                                <i class="bi bi-graph-up"></i> Estadísticas
                            </a>
                            <div class="collapse" id="stats">
                                <ul class="nav flex-column ms-3">
                                    <li><a class="nav-link" href="#overview">Resumen</a></li>
                                    <li><a class="nav-link" href="#activity">Actividad</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Administrador de Usuarios</h1>
                </div>

                <!-- Filtros y búsqueda -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Buscar usuario o email..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" name="level">
                                    <option value="">Todos los niveles</option>
                                    <?php foreach ($levels as $level): ?>
                                        <option value="<?php echo $level; ?>" 
                                                <?php echo ($filter_level == $level) ? 'selected' : ''; ?>>
                                            <?php echo $level; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-secondary">Total: <?php echo $total_users; ?> usuarios</span>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="row mb-4" id="overview">
                    <div class="col-md-3">
                        <div class="card stat-card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Total Usuarios</h5>
                                <h2 class="card-text"><?php echo $stats['total_users']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card border-success">
                            <div class="card-body">
                                <h5 class="card-title">Activados</h5>
                                <h2 class="card-text"><?php echo $stats['activated_users']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card border-danger">
                            <div class="card-body">
                                <h5 class="card-title">Baneados</h5>
                                <h2 class="card-text"><?php echo $stats['banned_users']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card border-warning">
                            <div class="card-body">
                                <h5 class="card-title">Niveles</h5>
                                <h2 class="card-text"><?php echo count($stats['users_by_level']); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Nivel</th>
                                <th>Estado</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="user-row">
                                <td><small class="text-muted"><?php echo substr($user['iduv'], 0, 8); ?>...</small></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo $user['level']; ?></span>
                                </td>
                                <td>
                                    <?php if ($user['banned']): ?>
                                        <span class="badge bg-danger">Baneado</span>
                                    <?php elseif (!$user['is_activate']): ?>
                                        <span class="badge bg-warning">No activado</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?php echo date('d/m/Y H:i', strtotime($user['timestamp'])); ?></small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?php echo $user['iduv']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal<?php echo $user['iduv']; ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal de edición -->
                            <div class="modal fade" id="editModal<?php echo $user['iduv']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="iduv" value="<?php echo $user['iduv']; ?>">
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Usuario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Usuario</label>
                                                    <input type="text" class="form-control" name="username" 
                                                           value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email" 
                                                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nivel</label>
                                                    <select class="form-select" name="level">
                                                        <?php foreach ($levels as $level): ?>
                                                            <option value="<?php echo $level; ?>" 
                                                                    <?php echo ($user['level'] == $level) ? 'selected' : ''; ?>>
                                                                <?php echo $level; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="banned" 
                                                                   id="banned<?php echo $user['iduv']; ?>" 
                                                                   <?php echo $user['banned'] ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="banned<?php echo $user['iduv']; ?>">
                                                                Baneado
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_activate" 
                                                                   id="activated<?php echo $user['iduv']; ?>" 
                                                                   <?php echo $user['is_activate'] ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="activated<?php echo $user['iduv']; ?>">
                                                                Activado
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="verified" 
                                                                   id="verified<?php echo $user['iduv']; ?>" 
                                                                   <?php echo $user['verified'] ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="verified<?php echo $user['iduv']; ?>">
                                                                Verificado
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de eliminación -->
                            <div class="modal fade" id="deleteModal<?php echo $user['iduv']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="iduv" value="<?php echo $user['iduv']; ?>">
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de eliminar al usuario <strong><?php echo htmlspecialchars($user['username']); ?></strong>?</p>
                                                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&level=<?php echo urlencode($filter_level); ?>">
                                    Anterior
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&level=<?php echo urlencode($filter_level); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&level=<?php echo urlencode($filter_level); ?>">
                                    Siguiente
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <!-- Gráfico de estadísticas -->
                <div class="row mt-5" id="activity">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Registros últimos 30 días</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="statsChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribución por niveles -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Distribución por Niveles</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($stats['users_by_level'] as $level_stat): ?>
                                    <div class="col-md-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                            <span><?php echo $level_stat['level']; ?></span>
                                            <span class="badge bg-primary rounded-pill"><?php echo $level_stat['count']; ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para el gráfico
        const statsData = <?php echo json_encode($stats['last_30_days']); ?>;
        
        // Preparar datos para Chart.js
        const labels = statsData.map(item => item.date);
        const data = statsData.map(item => item.count);
        
        // Crear gráfico
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registros por día',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>