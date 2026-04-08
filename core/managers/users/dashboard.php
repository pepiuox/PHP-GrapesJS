<?php
// dashboard.php

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireLogin();

$user = new User($db);
$total_users = $user->readAll()->rowCount();

// Obtener estadísticas
$query = "SELECT 
            COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_users,
            COUNT(CASE WHEN is_banned = 1 THEN 1 END) as banned_users,
            COUNT(CASE WHEN level = 'admin' THEN 1 END) as admin_users,
            MAX(created_at) as last_registration
          FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// chart
// Obtener datos para gráficos
$stats_query = "
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as count,
        COUNT(CASE WHEN level = 'admin' THEN 1 END) as admins,
        COUNT(CASE WHEN level = 'guest' THEN 1 END) as guests,
        COUNT(CASE WHEN level = 'editor' THEN 1 END) as editors,
        COUNT(CASE WHEN level = 'manager' THEN 1 END) as managers
    FROM users 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
";

$daily_stats = $db->query($stats_query)->fetchAll(PDO::FETCH_ASSOC);

// Preparar datos para JavaScript
$chart_data = [
    'dates' => [],
    'counts' => [],
    'admins' => [],
    'guests' => [],
    'editors' => [],
    'managers' => []
];

foreach ($daily_stats as $stat) {
    $chart_data['dates'][] = date('d/m', strtotime($stat['date']));
    $chart_data['counts'][] = $stat['count'];
    $chart_data['admins'][] = $stat['admins'];
    $chart_data['guests'][] = $stat['guests'];
    $chart_data['editors'][] = $stat['editors'];
    $chart_data['managers'][] = $stat['managers'];
}

// Datos para gráfico de levels
$levels_query = "SELECT level, COUNT(*) as count FROM users GROUP BY level";
$levels_data = $db->query($levels_query)->fetchAll(PDO::FETCH_ASSOC);

// Datos para gráfico de estados
$status_query = "
    SELECT 
        CASE 
            WHEN is_banned = 1 THEN 'Suspendidos'
            WHEN is_active = 1 THEN 'Activos'
            ELSE 'Inactivos'
        END as status,
        COUNT(*) as count
    FROM users 
    GROUP BY is_banned, is_active
";
$status_data = $db->query($status_query)->fetchAll(PDO::FETCH_ASSOC);

// Datos para gráfico de actividad de login
$activity_query = "
    SELECT 
        DATE(last_login) as date,
        COUNT(*) as logins
    FROM users 
    WHERE last_login >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(last_login)
    ORDER BY date ASC
";
$activity_data = $db->query($activity_query)->fetchAll(PDO::FETCH_ASSOC);
$page_title = 'Dashboard - Sistema de Usuarios';
?>
<?php include '../includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center p-3">
                        <h4><i class="fas fa-users"></i> Admin Usuarios</h4>
                        <small>Bienvenido, <?php echo $_SESSION['username']; ?></small>
                    </div>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="users.php">
                                <i class="fas fa-users"></i> Usuarios
                            </a>
                        </li>
                        
<?php if ($auth->isAdmin()): ?>
                              <li class="nav-item">
                                    <a class="nav-link" href="create-user.php">
                                        <i class="fas fa-user-plus"></i> Nuevo Usuario
                                    </a>
                              </li>
                              <li class="nav-item">
                                    <a class="nav-link" href="../settings.php">
                                        <i class="fas fa-cog"></i> Configuración
                                    </a>
                              </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && isset($is_file_manager) ? 'active' : ''; ?>" 
                               href="<?php echo PathUrl() . '/file-manager/index.php'; ?>">
                                <i class="fas fa-folder-open"></i> Administrador de Archivos
                            </a>
                        </li>
<?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="../signin/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
       
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="badge bg-primary">
                                Rol: <?php echo $_SESSION['level']; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white stat-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Total Usuarios</h5>
                                        <h2><?php echo $total_users; ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-users stat-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white stat-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Activos</h5>
                                        <h2><?php echo $stats['active_users']; ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-user-check stat-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-danger text-white stat-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Suspendidos</h5>
                                        <h2><?php echo $stats['banned_users']; ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-user-slash stat-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-white stat-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Administradores</h5>
                                        <h2><?php echo $stats['admin_users']; ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-user-shield stat-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history"></i> Usuarios Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Nombre Completo</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$stmt = $user->readAll();
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($count++ >= 5)
        break;
?>
                                                                    <tr>
                                                                        <td><?php echo $row['id']; ?></td>
                                                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                                        <td>
                                                                            <span class="badge bg-<?php
    echo $row['level'] == 'admin' ? 'danger' :
            ($row['level'] == 'manager' ? 'warning' :
                    ($row['level'] == 'editor' ? 'info' : 'secondary'));
?>">
    <?php echo $row['level']; ?>
                                                                            </span>
                                                                        </td>
                                                                        <td>
    <?php if ($row['is_banned']): ?>
                                                                                                            <span class="badge bg-danger">Suspendido</span>
    <?php elseif ($row['is_active']): ?>
                                                                                                            <span class="badge bg-success">Activo</span>
    <?php else: ?>
                                                                                                            <span class="badge bg-secondary">Inactivo</span>
    <?php endif; ?>
                                                                        </td>
                                                                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                                                    </tr>
<?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="users.php" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Ver Todos los Usuarios
                            </a>
                        </div>
                    </div>
                </div>
                         <!-- Sección de Gráficos -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line"></i> Estadísticas y Gráficos
                        </h5>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary active" data-chart-period="7">
                                7 días
                            </button>
                            <button class="btn btn-sm btn-outline-primary" data-chart-period="30">
                                30 días
                            </button>
                            <button class="btn btn-sm btn-outline-primary" data-chart-period="90">
                                90 días
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Contlevels de gráficos -->
                        <div class="chart-controls">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Filtrar por rol:</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input chart-level-filter" type="checkbox" value="all" id="filterAll" checked>
                                        <label class="form-check-label" for="filterAll">Todos</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input chart-level-filter" type="checkbox" value="admin" id="filterAdmin" checked>
                                        <label class="form-check-label" for="filterAdmin">Admins</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input chart-level-filter" type="checkbox" value="manager" id="filterManager" checked>
                                        <label class="form-check-label" for="filterManager">Managers</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input chart-level-filter" type="checkbox" value="editor" id="filterEditor" checked>
                                        <label class="form-check-label" for="filterEditor">Editores</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input chart-level-filter" type="checkbox" value="guest" id="filterGuest" checked>
                                        <label class="form-check-label" for="filterGuest">Invitados</label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button class="btn btn-outline-secondary btn-sm" id="exportChart">
                                        <i class="fas fa-download"></i> Exportar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fila 1: Gráficos principales -->
                        <div class="row mb-4">
                            <div class="col-lg-8 mb-4">
                                <div class="card chart-card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Registros Diarios (Últimos 30 días)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="registrationsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-4">
                                <div class="card chart-card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Distribución por Rol</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="levelsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fila 2: Gráficos secundarios -->
                        <div class="row">
                            <div class="col-lg-4 mb-4">
                                <div class="card chart-card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Estado de Usuarios</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="statusChart"></canvas>
                                        </div>
                                        <div class="row text-center mt-3">
<?php foreach ($status_data as $status): ?>
                                                                        <div class="col-4">
                                                                            <h5><?php echo $status['count']; ?></h5>
                                                                            <small class="text-muted"><?php echo $status['status']; ?></small>
                                                                        </div>
<?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-4">
                                <div class="card chart-card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Actividad de Login (7 días)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="activityChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-4">
                                <div class="card chart-card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Métricas Clave</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6 mb-3">
                                                <div class="bg-light p-3 rounded">
                                                    <i class="fas fa-sign-in-alt fa-2x text-primary mb-2"></i>
                                                    <h5><?php echo $stats['avg_logins'] ?? '0'; ?></h5>
                                                    <small class="text-muted">Logins promedio</small>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <div class="bg-light p-3 rounded">
                                                    <i class="fas fa-user-clock fa-2x text-warning mb-2"></i>
                                                    <h5><?php echo $stats['never_logged_in'] ?? '0'; ?></h5>
                                                    <small class="text-muted">Nunca logueados</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-3 rounded">
                                                    <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                                    <h5><?php echo $stats['twofa_users'] ?? '0'; ?></h5>
                                                    <small class="text-muted">Con 2FA</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-3 rounded">
                                                    <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                                                    <h5><?php echo count($daily_stats); ?></h5>
                                                    <small class="text-muted">Días con registros</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
            </div>
        </div>
    </div>
    
<!-- Chart.js -->
<script>
    // Datos PHP a JavaScript
    const chartData = <?php echo json_encode($chart_data); ?>;
    const levelsData = <?php echo json_encode($levels_data); ?>;
    const statusData = <?php echo json_encode($status_data); ?>;
    const activityData = <?php echo json_encode($activity_data); ?>;
    
    // Colores para gráficos
    const chartColors = {
        primary: '#4361ee',
        secondary: '#7209b7',
        success: '#10b981',
        danger: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6',
        light: '#94a3b8',
        dark: '#334155'
    };
    
    // 1. Gráfico de Registros Diarios
    const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsChart = new Chart(registrationsCtx, {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [
                {
                    label: 'Total Registros',
                    data: chartData.counts,
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Administradores',
                    data: chartData.admins,
                    borderColor: chartColors.danger,
                    backgroundColor: 'transparent',
                    borderWidth: 1,
                    borderDash: [5, 5],
                    tension: 0.4
                },
                {
                    label: 'Managers',
                    data: chartData.managers,
                    borderColor: chartColors.warning,
                    backgroundColor: 'transparent',
                    borderWidth: 1,
                    borderDash: [5, 5],
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: chartColors.primary,
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'nearest'
            }
        }
    });
    
    // 2. Gráfico de Distribución por Rol
    const levelsCtx = document.getElementById('levelsChart').getContext('2d');
    
    // Preparar datos para gráfico de levels
    const levelLabels = [];
    const levelCounts = [];
    const levelColors = [];
    
    levelsData.forEach(level => {
        levelLabels.push(getRoleText(level.level));
        levelCounts.push(level.count);
        
        switch(level.level) {
            case 'admin': levelColors.push(chartColors.danger); break;
            case 'manager': levelColors.push(chartColors.warning); break;
            case 'editor': levelColors.push(chartColors.info); break;
            case 'guest': levelColors.push(chartColors.secondary); break;
            default: levelColors.push(chartColors.light);
        }
    });
    
    const levelsChart = new Chart(levelsCtx, {
        type: 'doughnut',
        data: {
            labels: levelLabels,
            datasets: [{
                data: levelCounts,
                backgroundColor: levelColors,
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
    
    // 3. Gráfico de Estado de Usuarios
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    
    const statusLabels = [];
    const statusCounts = [];
    const statusColors = [];
    
    statusData.forEach(status => {
        statusLabels.push(status.status);
        statusCounts.push(status.count);
        
        switch(status.status) {
            case 'Activos': statusColors.push(chartColors.success); break;
            case 'Inactivos': statusColors.push(chartColors.light); break;
            case 'Suspendidos': statusColors.push(chartColors.danger); break;
            default: statusColors.push(chartColors.warning);
        }
    });
    
    const statusChart = new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: statusColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
    
    // 4. Gráfico de Actividad de Login
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    
    // Preparar datos de actividad
    const activityLabels = [];
    const activityCounts = [];
    
    // Si no hay datos de actividad, mostrar mensaje
    if (activityData.length > 0) {
        activityData.forEach(day => {
            activityLabels.push(new Date(day.date).toLocaleDateString('es-ES', { weekday: 'short' }));
            activityCounts.push(day.logins);
        });
    } else {
        activityLabels.push('Sin datos');
        activityCounts.push(0);
    }
    
    const activityChart = new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: activityLabels,
            datasets: [{
                label: 'Logins',
                data: activityCounts,
                backgroundColor: chartColors.info,
                borderColor: chartColors.primary,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Funciones auxiliares
    function getRoleText(level) {
        const levels = {
            'admin': 'Administrador',
            'manager': 'Manager',
            'editor': 'Editor',
            'guest': 'Invitado'
        };
        return levels[level] || level;
    }
    
    // Controladores de eventos
    document.querySelectorAll('[data-chart-period]').forEach(button => {
        button.addEventListener('click', function() {
            const period = this.dataset.chartPeriod;
            
            // Actualizar botón activo
            document.querySelectorAll('[data-chart-period]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Aquí podrías cargar nuevos datos según el período
            console.log('Cambiar período a:', period, 'días');
            // En una implementación real, harías una petición AJAX
        });
    });
    
    // Filtrar por rol
    document.querySelectorAll('.chart-level-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.value === 'all') {
                // Si se marca "Todos", marcar todos
                if (this.checked) {
                    document.querySelectorAll('.chart-level-filter').forEach(cb => {
                        cb.checked = true;
                    });
                }
            } else {
                // Si se desmarca un rol específico, desmarcar "Todos"
                if (!this.checked) {
                    document.getElementById('filterAll').checked = false;
                }
                
                // Verificar si todos los levels están marcados
                const allChecked = Array.from(document.querySelectorAll('.chart-level-filter:not([value="all"])'))
                    .every(cb => cb.checked);
                
                if (allChecked) {
                    document.getElementById('filterAll').checked = true;
                }
            }
            
            // Aquí podrías actualizar el gráfico según los filtros
            console.log('Filtros actualizados');
        });
    });
    
    // Exportar gráfico
    document.getElementById('exportChart').addEventListener('click', function() {
        // Crear un canvas combinado
        const exportCanvas = document.createElement('canvas');
        exportCanvas.width = 1200;
        exportCanvas.height = 800;
        const ctx = exportCanvas.getContext('2d');
        
        // Fondo blanco
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
        
        // Título
        ctx.fillStyle = '#333';
        ctx.font = 'bold 24px Arial';
        ctx.fillText('Reporte de Estadísticas - Sistema de Usuarios', 50, 50);
        ctx.font = '14px Arial';
        ctx.fillText('Generado el ' + new Date().toLocaleDateString('es-ES'), 50, 80);
        
        // Aquí podrías dibujar los gráficos en el canvas exportado
        // Esto es más complejo y requeriría una librería adicional o hacerlo en el servidor
        
        // Por ahora, simplemente descargar cada gráfico individualmente
        const link = document.createElement('a');
        link.download = 'grafico-registros.png';
        link.href = registrationsChart.toBase64Image();
        link.click();
        
        // Mostrar mensaje
        alert('Los gráficos se están descargando. En una implementación completa, se generaría un PDF con todos los gráficos.');
    });
    
    // Actualizar gráficos al cambiar tamaño de ventana
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            registrationsChart.resize();
            levelsChart.resize();
            statusChart.resize();
            activityChart.resize();
        }, 250);
    });
</script>
<?php include '../includes/footer.php'; ?>
