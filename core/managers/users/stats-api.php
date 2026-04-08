<?php
// stats-api.php - API para obtener datos estadísticos

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Verificar autenticación
if (!$auth->isLoggedIn()) {
    json_response(['error' => 'No autorizado'], 401);
    exit();
}

// Obtener parámetros
$period = $_GET['period'] ?? '30'; // días
$chart_type = $_GET['chart'] ?? 'registrations';

// Validar período
$valid_periods = ['7', '30', '90', '365'];
if (!in_array($period, $valid_periods)) {
    $period = '30';
}

try {
    switch ($chart_type) {
        case 'registrations':
            $data = get_registrations_data($db, $period);
            break;
            
        case 'roles':
            $data = get_roles_data($db);
            break;
            
        case 'status':
            $data = get_status_data($db);
            break;
            
        case 'activity':
            $data = get_activity_data($db, $period);
            break;
            
        default:
            $data = get_registrations_data($db, $period);
    }
    
    json_response([
        'success' => true,
        'data' => $data,
        'period' => $period,
        'generated_at' => date('Y-m-d H:i:s')
    ]);
    
} catch (PDOException $e) {
    error_log("Error en stats API: " . $e->getMessage());
    json_response(['error' => 'Error interno del servidor'], 500);
}

/**
 * Obtener datos de registros por período
 */
function get_registrations_data($db, $period) {
    $query = "
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as total,
            COUNT(CASE WHEN role = 'admin' THEN 1 END) as admins,
            COUNT(CASE WHEN role = 'manager' THEN 1 END) as managers,
            COUNT(CASE WHEN role = 'editor' THEN 1 END) as editors,
            COUNT(CASE WHEN role = 'guest' THEN 1 END) as guests
        FROM users 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$period]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $data = [
        'labels' => [],
        'datasets' => [
            'total' => [],
            'admins' => [],
            'managers' => [],
            'editors' => [],
            'guests' => []
        ]
    ];
    
    foreach ($results as $row) {
        $data['labels'][] = date('d/m', strtotime($row['date']));
        $data['datasets']['total'][] = $row['total'];
        $data['datasets']['admins'][] = $row['admins'];
        $data['datasets']['managers'][] = $row['managers'];
        $data['datasets']['editors'][] = $row['editors'];
        $data['datasets']['guests'][] = $row['guests'];
    }
    
    return $data;
}

/**
 * Obtener distribución por roles
 */
function get_roles_data($db) {
    $query = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $data = [
        'labels' => [],
        'data' => [],
        'colors' => []
    ];
    
    $color_map = [
        'admin' => '#ef4444',
        'manager' => '#f59e0b',
        'editor' => '#3b82f6',
        'guest' => '#7209b7'
    ];
    
    $role_names = [
        'admin' => 'Administrador',
        'manager' => 'Manager',
        'editor' => 'Editor',
        'guest' => 'Invitado'
    ];
    
    foreach ($results as $row) {
        $data['labels'][] = $role_names[$row['role']] ?? $row['role'];
        $data['data'][] = $row['count'];
        $data['colors'][] = $color_map[$row['role']] ?? '#94a3b8';
    }
    
    return $data;
}

/**
 * Obtener datos de estado de usuarios
 */
function get_status_data($db) {
    $query = "
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
    
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $data = [
        'labels' => [],
        'data' => [],
        'colors' => []
    ];
    
    $color_map = [
        'Activos' => '#10b981',
        'Inactivos' => '#94a3b8',
        'Suspendidos' => '#ef4444'
    ];
    
    foreach ($results as $row) {
        $data['labels'][] = $row['status'];
        $data['data'][] = $row['count'];
        $data['colors'][] = $color_map[$row['status']] ?? '#f59e0b';
    }
    
    return $data;
}

/**
 * Obtener datos de actividad de login
 */
function get_activity_data($db, $period) {
    $query = "
        SELECT 
            DATE(last_login) as date,
            COUNT(*) as logins
        FROM users 
        WHERE last_login >= DATE_SUB(NOW(), INTERVAL ? DAY)
            AND last_login IS NOT NULL
        GROUP BY DATE(last_login)
        ORDER BY date ASC
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$period]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $data = [
        'labels' => [],
        'data' => []
    ];
    
    foreach ($results as $row) {
        $data['labels'][] = date('d/m', strtotime($row['date']));
        $data['data'][] = $row['logins'];
    }
    
    return $data;
}
?>