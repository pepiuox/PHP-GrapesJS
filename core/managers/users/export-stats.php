<?php
// export-stats.php - Exportar estadísticas a PDF/Excel

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Verificar autenticación y permisos
$auth->requireLogin();
if (!$auth->isAdmin()) {
    redirect('dashboard.php');
}

// Obtener parámetros
$format = $_GET['format'] ?? 'pdf';
$type = $_GET['type'] ?? 'all';

// Validar formato
$valid_formats = ['pdf', 'excel', 'csv'];
if (!in_array($format, $valid_formats)) {
    $format = 'pdf';
}

try {
    // Obtener datos según el tipo
    switch ($type) {
        case 'users':
            $data = export_users_data($db);
            $filename = "usuarios_" . date('Y-m-d');
            break;
            
        case 'stats':
            $data = export_stats_data($db);
            $filename = "estadisticas_" . date('Y-m-d');
            break;
            
        case 'activity':
            $data = export_activity_data($db);
            $filename = "actividad_" . date('Y-m-d');
            break;
            
        default:
            $data = export_full_report($db);
            $filename = "reporte_completo_" . date('Y-m-d');
    }
    
    // Exportar según formato
    switch ($format) {
        case 'excel':
            export_to_excel($data, $filename);
            break;
            
        case 'csv':
            export_to_csv($data, $filename);
            break;
            
        case 'pdf':
        default:
            export_to_pdf($data, $filename);
    }
    
} catch (PDOException $e) {
    error_log("Error exportando estadísticas: " . $e->getMessage());
    set_flash_message('error', 'Error al exportar los datos.');
    redirect('dashboard.php');
}

/**
 * Exportar datos de usuarios
 */
function export_users_data($db) {
    $query = "
        SELECT 
            id,
            username,
            email,
            full_name,
            role,
            CASE 
                WHEN is_banned = 1 THEN 'Suspendido'
                WHEN is_active = 1 THEN 'Activo'
                ELSE 'Inactivo'
            END as status,
            DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as created_at,
            DATE_FORMAT(last_login, '%d/%m/%Y %H:%i') as last_login,
            login_count
        FROM users 
        ORDER BY created_at DESC
    ";
    
    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Exportar datos estadísticos
 */
function export_stats_data($db) {
    $stats = [];
    
    // Total por rol
    $query = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
    $stmt = $db->query($query);
    $stats['roles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Total por estado
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
    $stats['status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Registros últimos 30 días
    $query = "
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as count
        FROM users 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ";
    $stmt = $db->query($query);
    $stats['daily'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Métricas generales
    $query = "
        SELECT 
            COUNT(*) as total_users,
            COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_users,
            COUNT(CASE WHEN is_banned = 1 THEN 1 END) as banned_users,
            COUNT(CASE WHEN last_login IS NULL THEN 1 END) as never_logged_in,
            AVG(login_count) as avg_logins,
            MAX(created_at) as last_registration
        FROM users
    ";
    $stmt = $db->query($query);
    $stats['metrics'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $stats;
}

/**
 * Exportar datos de actividad
 */
function export_activity_data($db) {
    $query = "
        SELECT 
            DATE(last_login) as date,
            COUNT(*) as logins,
            GROUP_CONCAT(username SEPARATOR ', ') as users
        FROM users 
        WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            AND last_login IS NOT NULL
        GROUP BY DATE(last_login)
        ORDER BY date DESC
    ";
    
    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Exportar reporte completo
 */
function export_full_report($db) {
    return [
        'users' => export_users_data($db),
        'stats' => export_stats_data($db),
        'activity' => export_activity_data($db),
        'generated_at' => date('Y-m-d H:i:s'),
        'generated_by' => $_SESSION['username'] ?? 'Sistema'
    ];
}

/**
 * Exportar a Excel
 */
function export_to_excel($data, $filename) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
    
    echo '<html>';
    echo '<head><meta charset="UTF-8"></head>';
    echo '<body>';
    
    if (isset($data['users'])) {
        echo '<h2>Usuarios del Sistema</h2>';
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>ID</th><th>Usuario</th><th>Email</th><th>Nombre</th><th>Rol</th>';
        echo '<th>Estado</th><th>Registro</th><th>Último Login</th><th>Logins</th>';
        echo '</tr>';
        
        foreach ($data['users'] as $user) {
            echo '<tr>';
            echo '<td>' . $user['id'] . '</td>';
            echo '<td>' . $user['username'] . '</td>';
            echo '<td>' . $user['email'] . '</td>';
            echo '<td>' . $user['full_name'] . '</td>';
            echo '<td>' . $user['role'] . '</td>';
            echo '<td>' . $user['status'] . '</td>';
            echo '<td>' . $user['created_at'] . '</td>';
            echo '<td>' . $user['last_login'] . '</td>';
            echo '<td>' . $user['login_count'] . '</td>';
            echo '</tr>';
        }
        echo '</table><br><br>';
    }
    
    if (isset($data['stats'])) {
        echo '<h2>Estadísticas Generales</h2>';
        
        // Métricas
        if (isset($data['stats']['metrics'])) {
            echo '<h3>Métricas</h3>';
            echo '<table border="1">';
            foreach ($data['stats']['metrics'] as $key => $value) {
                echo '<tr>';
                echo '<td><strong>' . ucfirst(str_replace('_', ' ', $key)) . '</strong></td>';
                echo '<td>' . $value . '</td>';
                echo '</tr>';
            }
            echo '</table><br>';
        }
        
        // Por rol
        if (isset($data['stats']['roles'])) {
            echo '<h3>Distribución por Rol</h3>';
            echo '<table border="1">';
            echo '<tr><th>Rol</th><th>Cantidad</th></tr>';
            foreach ($data['stats']['roles'] as $role) {
                echo '<tr>';
                echo '<td>' . $role['role'] . '</td>';
                echo '<td>' . $role['count'] . '</td>';
                echo '</tr>';
            }
            echo '</table><br>';
        }
    }
    
    echo '</body></html>';
    exit();
}

/**
 * Exportar a CSV
 */
function export_to_csv($data, $filename) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    if (isset($data['users'])) {
        // Cabecera
        fputcsv($output, ['ID', 'Usuario', 'Email', 'Nombre', 'Rol', 'Estado', 'Registro', 'Último Login', 'Logins']);
        
        // Datos
        foreach ($data['users'] as $user) {
            fputcsv($output, [
                $user['id'],
                $user['username'],
                $user['email'],
                $user['full_name'],
                $user['role'],
                $user['status'],
                $user['created_at'],
                $user['last_login'],
                $user['login_count']
            ]);
        }
    }
    
    fclose($output);
    exit();
}

/**
 * Exportar a PDF (requiere librería TCPDF o similar)
 */
function export_to_pdf($data, $filename) {
    // En una implementación real, usarías TCPDF, FPDF o Dompdf
    // Esta es una versión simplificada que genera HTML
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '.pdf"');
    
    // En producción, usarías:
    // require_once('tcpdf/tcpdf.php');
    // $pdf = new TCPDF();
    // $pdf->AddPage();
    // $pdf->writeHTML($html);
    // $pdf->Output($filename . '.pdf', 'D');
    
    // Por ahora, redirigir a una versión HTML imprimible
    $_SESSION['export_data'] = $data;
    $_SESSION['export_filename'] = $filename;
    
    redirect('print-report.php');
}
?>