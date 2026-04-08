<?php
// print-report.php - Versión imprimible del reporte

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Verificar autenticación
$auth->requireLogin();

// Verificar si hay datos de exportación
if (!isset($_SESSION['export_data'])) {
    redirect('dashboard.php');
}

$data = $_SESSION['export_data'];
$filename = $_SESSION['export_filename'] ?? 'reporte';

// Limpiar datos de sesión después de usarlos
unset($_SESSION['export_data']);
unset($_SESSION['export_filename']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte - <?php echo $filename; ?></title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12pt; }
            .page-break { page-break-after: always; }
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header .subtitle {
            color: #666;
            margin-top: 10px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            background: #f5f5f5;
            padding: 10px;
            border-left: 4px solid #4361ee;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .metric-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .metric-value {
            font-size: 2em;
            font-weight: bold;
            color: #4361ee;
            margin: 10px 0;
        }
        .metric-label {
            color: #666;
            font-size: 0.9em;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #666;
            font-size: 0.9em;
        }
        .print-controls {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cerrar
        </button>
        <button onclick="saveAsPDF()" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Guardar como PDF
        </button>
    </div>
    
    <div class="header">
        <h1>Reporte del Sistema de Usuarios</h1>
        <div class="subtitle">
            Generado el <?php echo date('d/m/Y H:i:s'); ?> por <?php echo $_SESSION['username'] ?? 'Sistema'; ?>
        </div>
    </div>
    
    <?php if (isset($data['stats']['metrics'])): ?>
    <div class="section">
        <h2>Métricas Generales</h2>
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value"><?php echo $data['stats']['metrics']['total_users'] ?? 0; ?></div>
                <div class="metric-label">Total Usuarios</div>
            </div>
            <div class="metric-card">
                <div class="metric-value"><?php echo $data['stats']['metrics']['active_users'] ?? 0; ?></div>
                <div class="metric-label">Usuarios Activos</div>
            </div>
            <div class="metric-card">
                <div class="metric-value"><?php echo $data['stats']['metrics']['banned_users'] ?? 0; ?></div>
                <div class="metric-label">Usuarios Suspendidos</div>
            </div>
            <div class="metric-card">
                <div class="metric-value"><?php echo round($data['stats']['metrics']['avg_logins'] ?? 0, 1); ?></div>
                <div class="metric-label">Logins Promedio</div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($data['users'])): ?>
    <div class="section page-break">
        <h2>Listado de Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Último Login</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td><?php echo $user['status']; ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td><?php echo $user['last_login'] ?: 'Nunca'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <?php if (isset($data['stats']['roles'])): ?>
    <div class="section">
        <h2>Distribución por Rol</h2>
        <table>
            <thead>
                <tr>
                    <th>Rol</th>
                    <th>Cantidad</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = array_sum(array_column($data['stats']['roles'], 'count'));
                foreach ($data['stats']['roles'] as $role): 
                    $percentage = $total > 0 ? round(($role['count'] / $total) * 100, 1) : 0;
                ?>
                <tr>
                    <td><?php echo $role['role']; ?></td>
                    <td><?php echo $role['count']; ?></td>
                    <td><?php echo $percentage; ?>%</td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong><?php echo $total; ?></strong></td>
                    <td><strong>100%</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <?php if (isset($data['stats']['daily'])): ?>
    <div class="section page-break">
        <h2>Registros Últimos 30 Días</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Registros</th>
                    <th>Acumulado</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $accumulated = 0;
                foreach ($data['stats']['daily'] as $day): 
                    $accumulated += $day['count'];
                ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($day['date'])); ?></td>
                    <td><?php echo $day['count']; ?></td>
                    <td><?php echo $accumulated; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <div class="footer">
        <p><strong>Sistema de Administración de Usuarios</strong></p>
        <p>Reporte generado automáticamente. Los datos son confidenciales.</p>
        <p>Página <?php echo '<span id="pageNumber"></span> de <span id="totalPages"></span>'; ?></p>
    </div>

    <script>
        // Script para números de página
        window.onload = function() {
            var totalPages = Math.ceil(document.body.scrollHeight / window.innerHeight);
            document.getElementById('totalPages').textContent = totalPages;
            
            window.onafterprint = function() {
                // Redirigir después de imprimir
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        };
        
        // Actualizar número de página al imprimir
        window.onbeforeprint = function() {
            var pageNum = 1;
            var elements = document.querySelectorAll('.section');
            elements.forEach(function(element, index) {
                var pageElement = document.createElement('div');
                pageElement.className = 'page-number';
                pageElement.textContent = 'Página ' + pageNum;
                pageElement.style.position = 'absolute';
                pageElement.style.top = '0';
                pageElement.style.right = '0';
                pageElement.style.fontSize = '10pt';
                pageElement.style.color = '#666';
                element.style.position = 'relative';
                element.appendChild(pageElement);
                pageNum++;
            });
        };
        
        // Función para guardar como PDF (simplificada)
        function saveAsPDF() {
            alert('En una implementación completa, esto generaría un archivo PDF.');
            // En producción usarías: window.print() con configuración de PDF
        }
        
        // Configurar impresión
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar clase para control de saltos de página
            var sections = document.querySelectorAll('.section');
            sections.forEach(function(section, index) {
                if (index > 0) {
                    section.classList.add('page-break-before');
                }
            });
        });
    </script>
</body>
</html>