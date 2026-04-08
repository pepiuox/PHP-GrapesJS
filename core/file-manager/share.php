<?php
// modules/file-manager/share.php
require_once '../config/constants.php';
require_once '../config/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/FileManager.php';
require_once '../includes/functions.php';
require_once '../includes/file-functions.php';

// Inicializar
$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$fileManager = new FileManager($db);

// Obtener ID del archivo
$file_id = $_GET['id'] ?? 0;
$token = $_GET['token'] ?? '';

if (!$file_id) {
    header("Location: ../index.php");
    exit();
}

// Si hay token, verificar acceso por token
if ($token) {
    $file = verifyShareToken($db, $file_id, $token);
    $access_by_token = true;
} else {
    // Verificar si el usuario está logueado
    session_start();
    if (!$auth->isLoggedIn()) {
        // Redirigir a login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: ../login.php");
        exit();
    }
    
    // Obtener archivo con verificación de acceso
    $file = $fileManager->getFileById($file_id, $_SESSION['user_id']);
    $access_by_token = false;
}

// Verificar si se encontró el archivo
if (!$file) {
    showErrorPage('Archivo no encontrado o no tienes acceso a este archivo.');
    exit();
}

// Verificar si el archivo ha sido eliminado
if ($file['deleted_at']) {
    showErrorPage('Este archivo ha sido eliminado.');
    exit();
}

// Incrementar contador de visualizaciones si es acceso por token
if ($access_by_token) {
    incrementViewCount($db, $file_id);
}

// Determinar tipo de contenido
$is_image = strpos($file['mime_type'], 'image/') === 0;
$is_pdf = $file['mime_type'] === 'application/pdf';
$is_text = in_array($file['mime_type'], ['text/plain', 'text/html', 'text/css', 'text/javascript']);
$is_audio = strpos($file['mime_type'], 'audio/') === 0;
$is_video = strpos($file['mime_type'], 'video/') === 0;

// Preparar URLs
$download_url = getFileManagerUrl('download.php?id=' . $file_id . ($token ? '&token=' . $token : ''));
$preview_url = getFileManagerUrl('preview.php?id=' . $file_id);
$back_url = $access_by_token ? '#' : getFileManagerUrl('index.php');

// Si es descarga directa (parámetro download=1)
if (isset($_GET['download'])) {
    header('Location: ' . $download_url);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir: <?php echo htmlspecialchars($file['original_name']); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .share-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .file-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
        }
        .file-preview-container {
            background: #f8f9fa;
            padding: 40px;
            text-align: center;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .file-info-sidebar {
            background: white;
            padding: 30px;
            border-left: 1px solid #e9ecef;
        }
        .preview-image {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .preview-pdf {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .share-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        .share-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .access-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Badge de compartido -->
    <?php if ($access_by_token): ?>
    <div class="share-badge">
        <span class="badge bg-warning">
            <i class="fas fa-share-alt"></i> Compartido
        </span>
    </div>
    <?php endif; ?>
    
    <div class="share-container">
        <!-- Header del archivo -->
        <div class="file-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2">
                        <i class="fas fa-file"></i> 
                        <?php echo htmlspecialchars($file['original_name']); ?>
                    </h1>
                    <p class="mb-0">
                        <i class="fas fa-user"></i> 
                        <?php echo htmlspecialchars($file['owner_name'] ?? 'Usuario'); ?> 
                        • 
                        <i class="fas fa-calendar"></i> 
                        <?php echo date('d/m/Y', strtotime($file['created_at'])); ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?php echo $download_url; ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-download"></i> Descargar
                    </a>
                    <?php if (!$access_by_token): ?>
                    <a href="<?php echo $back_url; ?>" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Área de previsualización -->
            <div class="col-md-8">
                <div class="file-preview-container">
                    <?php if ($is_image): ?>
                    <!-- Vista previa de imagen -->
                    <img src="<?php echo $preview_url; ?>" 
                         alt="<?php echo htmlspecialchars($file['original_name']); ?>"
                         class="preview-image">
                    
                    <?php elseif ($is_pdf && !$access_by_token): ?>
                    <!-- Vista previa de PDF (solo para usuarios logueados) -->
                    <iframe src="<?php echo $download_url; ?>" 
                            class="preview-pdf"
                            title="<?php echo htmlspecialchars($file['original_name']); ?>">
                    </iframe>
                    
                    <?php elseif ($is_text && filesize($file['file_path']) < 1024 * 100): ?>
                    <!-- Vista previa de texto (solo archivos pequeños) -->
                    <div class="text-start w-100">
                        <pre class="bg-white p-3 rounded" style="max-height: 500px; overflow: auto;">
<?php echo htmlspecialchars(file_get_contents($file['file_path'])); ?>
                        </pre>
                    </div>
                    
                    <?php elseif ($is_audio): ?>
                    <!-- Reproductor de audio -->
                    <div class="w-100">
                        <div class="bg-white p-4 rounded shadow">
                            <i class="fas fa-file-audio fa-5x text-primary mb-3"></i>
                            <h4><?php echo htmlspecialchars($file['original_name']); ?></h4>
                            <audio controls class="w-100 mt-3">
                                <source src="<?php echo $download_url; ?>" type="<?php echo $file['mime_type']; ?>">
                                Tu navegador no soporta el elemento de audio.
                            </audio>
                        </div>
                    </div>
                    
                    <?php elseif ($is_video): ?>
                    <!-- Reproductor de video -->
                    <div class="w-100">
                        <div class="bg-white p-4 rounded shadow">
                            <i class="fas fa-file-video fa-5x text-danger mb-3"></i>
                            <h4><?php echo htmlspecialchars($file['original_name']); ?></h4>
                            <video controls class="w-100 mt-3" style="max-height: 400px;">
                                <source src="<?php echo $download_url; ?>" type="<?php echo $file['mime_type']; ?>">
                                Tu navegador no soporta el elemento de video.
                            </video>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    <!-- Vista por defecto -->
                    <div class="text-center">
                        <i class="fas fa-file fa-5x text-secondary mb-3"></i>
                        <h3><?php echo htmlspecialchars($file['original_name']); ?></h3>
                        <p class="text-muted">Este tipo de archivo no se puede previsualizar</p>
                        <p>Usa el botón de descarga para obtener el archivo</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Sidebar de información -->
            <div class="col-md-4">
                <div class="file-info-sidebar">
                    <?php if ($access_by_token): ?>
                    <div class="access-info">
                        <h6><i class="fas fa-shield-alt"></i> Acceso por Enlace Compartido</h6>
                        <p class="small mb-0">Estás viendo este archivo a través de un enlace compartido.</p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Información del archivo -->
                    <div class="mb-4">
                        <h5><i class="fas fa-info-circle"></i> Información del Archivo</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Tamaño:</strong> 
                                <?php echo FileManager::formatFileSize($file['file_size']); ?>
                            </li>
                            <li class="mb-2">
                                <strong>Tipo:</strong> 
                                <?php echo htmlspecialchars($file['mime_type']); ?>
                            </li>
                            <li class="mb-2">
                                <strong>Subido:</strong> 
                                <?php echo date('d/m/Y H:i', strtotime($file['created_at'])); ?>
                            </li>
                            <li class="mb-2">
                                <strong>Descargas:</strong> 
                                <?php echo $file['download_count']; ?>
                            </li>
                            <?php if ($file['description']): ?>
                            <li class="mb-2">
                                <strong>Descripción:</strong><br>
                                <small class="text-muted"><?php echo nl2br(htmlspecialchars($file['description'])); ?></small>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="mb-4">
                        <h5><i class="fas fa-cogs"></i> Acciones</h5>
                        <div class="d-grid gap-2">
                            <a href="<?php echo $download_url; ?>" class="btn btn-primary">
                                <i class="fas fa-download"></i> Descargar Archivo
                            </a>
                            
                            <?php if (!$access_by_token && $auth->isLoggedIn() && $file['user_id'] == $_SESSION['user_id']): ?>
                            <button type="button" class="btn btn-outline-primary" onclick="showShareOptions()">
                                <i class="fas fa-share-alt"></i> Compartir Archivo
                            </button>
                            
                            <a href="<?php echo getFileManagerUrl('edit.php?id=' . $file_id); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-edit"></i> Editar Información
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Información de seguridad -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lock"></i> Información de Seguridad</h6>
                        <ul class="mb-0 small">
                            <li>Este archivo es <?php echo $file['is_public'] ? 'público' : 'privado'; ?></li>
                            <li>Descarga solo si confías en la fuente</li>
                            <li>Verifica el tipo de archivo antes de abrirlo</li>
                        </ul>
                    </div>
                    
                    <!-- Código QR para compartir (solo para archivos públicos) -->
                    <?php if ($file['is_public'] || $access_by_token): ?>
                    <div class="text-center mt-4">
                        <h6><i class="fas fa-qrcode"></i> Compartir Rápido</h6>
                        <div id="qrcode" class="mt-2"></div>
                        <small class="text-muted">Escanear para acceder al archivo</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botones flotantes de acción -->
    <div class="share-actions">
        <div class="btn-group dropup">
            <button type="button" class="btn btn-primary btn-lg rounded-circle shadow" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-share-alt"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#" onclick="copyLinkToClipboard()">
                        <i class="fas fa-link"></i> Copiar Enlace
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="mailto:?subject=Compartir archivo&body=Te comparto este archivo: <?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
                        <i class="fas fa-envelope"></i> Compartir por Email
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?php echo $download_url; ?>">
                        <i class="fas fa-download"></i> Descargar Directamente
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="#" onclick="reportFile()">
                        <i class="fas fa-flag"></i> Reportar Archivo
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Modal para opciones de compartir -->
    <div class="modal fade" id="shareModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-share-alt"></i> Compartir Archivo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Enlace Público:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="publicLink" 
                                   value="<?php echo get_base_url() . '/share.php?id=' . $file_id; ?>" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyPublicLink()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <?php if ($file['is_public']): ?>
                            <span class="text-success">
                                <i class="fas fa-check-circle"></i> Este archivo ya es público
                            </span>
                            <?php else: ?>
                            <span class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i> Solo usuarios logueados pueden ver este archivo
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Enlace Temporal con Token:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="tokenLink" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="generateTokenLink()">
                                <i class="fas fa-key"></i> Generar
                            </button>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyTokenLink()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-clock"></i> Válido por 7 días
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Compartir con Usuario Específico:</label>
                        <select class="form-select" id="shareWithUser">
                            <option value="">Seleccionar usuario...</option>
                            <!-- Se cargarían los usuarios via AJAX -->
                        </select>
                        <div class="mt-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="shareWithUser()">
                                <i class="fas fa-user-plus"></i> Compartir
                            </button>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Configuración de Acceso</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="makePublic" 
                                   <?php echo $file['is_public'] ? 'checked' : ''; ?> 
                                   onchange="togglePublicAccess()">
                            <label class="form-check-label" for="makePublic">
                                Hacer archivo público
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para reportar archivo -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-flag"></i> Reportar Archivo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motivo del Reporte:</label>
                        <select class="form-select" id="reportReason">
                            <option value="">Seleccionar motivo...</option>
                            <option value="malware">Contiene malware o virus</option>
                            <option value="copyright">Infringe derechos de autor</option>
                            <option value="inappropriate">Contenido inapropiado</option>
                            <option value="personal">Información personal expuesta</option>
                            <option value="other">Otro motivo</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción (opcional):</label>
                        <textarea class="form-control" id="reportDescription" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Los reportes falsos pueden resultar en la suspensión de tu cuenta.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="submitReport()">Enviar Reporte</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.0/build/qrcode.min.js"></script>
    
    <script>
        // Generar QR Code
        <?php if ($file['is_public'] || $access_by_token): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const shareUrl = window.location.href;
            QRCode.toCanvas(document.getElementById('qrcode'), shareUrl, {
                width: 128,
                margin: 1,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                }
            }, function(error) {
                if (error) {
                    console.error('Error generando QR:', error);
                    document.getElementById('qrcode').innerHTML = 
                        '<div class="alert alert-warning">Error generando código QR</div>';
                }
            });
        });
        <?php endif; ?>
        
        // Funciones para compartir
        function showShareOptions() {
            const modal = new bootstrap.Modal(document.getElementById('shareModal'));
            modal.show();
        }
        
        function copyLinkToClipboard() {
            const link = window.location.href;
            navigator.clipboard.writeText(link).then(() => {
                alert('Enlace copiado al portapapeles');
            });
        }
        
        function copyPublicLink() {
            const input = document.getElementById('publicLink');
            input.select();
            document.execCommand('copy');
            alert('Enlace público copiado');
        }
        
        function generateTokenLink() {
            // En producción, esto haría una llamada AJAX para generar un token
            const token = Math.random().toString(36).substring(2) + 
                         Math.random().toString(36).substring(2);
            const tokenLink = '<?php echo get_base_url() . "/share.php?id=" . $file_id . "&token="; ?>' + token;
            document.getElementById('tokenLink').value = tokenLink;
            
            // Guardar token en la base de datos (simulado)
            console.log('Token generado:', token);
        }
        
        function copyTokenLink() {
            const input = document.getElementById('tokenLink');
            if (!input.value) {
                alert('Primero genera un enlace con token');
                return;
            }
            input.select();
            document.execCommand('copy');
            alert('Enlace con token copiado');
        }
        
        function togglePublicAccess() {
            const isPublic = document.getElementById('makePublic').checked;
            
            // En producción, harías una llamada AJAX
            fetch('<?php echo getFileManagerUrl("includes/toggle-public.php"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    file_id: <?php echo $file_id; ?>,
                    is_public: isPublic
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(isPublic ? 'Archivo hecho público' : 'Archivo hecho privado');
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar la visibilidad');
            });
        }
        
        function shareWithUser() {
            const userId = document.getElementById('shareWithUser').value;
            if (!userId) {
                alert('Selecciona un usuario');
                return;
            }
            
            // En producción, harías una llamada AJAX
            alert('Compartiendo con usuario ID: ' + userId);
        }
        
        // Funciones para reportar
        function reportFile() {
            const modal = new bootstrap.Modal(document.getElementById('reportModal'));
            modal.show();
        }
        
        function submitReport() {
            const reason = document.getElementById('reportReason').value;
            const description = document.getElementById('reportDescription').value;
            
            if (!reason) {
                alert('Selecciona un motivo para el reporte');
                return;
            }
            
            // En producción, harías una llamada AJAX
            console.log('Reporte enviado:', { reason, description });
            alert('Reporte enviado. Gracias por tu colaboración.');
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            modal.hide();
        }
        
        // Funciones auxiliares
        function showError(message) {
            alert('Error: ' + message);
        }
        
        function showSuccess(message) {
            alert('Éxito: ' + message);
        }
    </script>
</body>
</html>

<?php
/**
 * Mostrar página de error
 */
function showErrorPage($message) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - Archivo no disponible</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                height: 100vh;
                display: flex;
                align-items: center;
            }
            .error-card {
                background: white;
                border-radius: 15px;
                padding: 40px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="error-card text-center">
                        <i class="fas fa-exclamation-triangle fa-5x text-danger mb-4"></i>
                        <h2>Archivo no disponible</h2>
                        <p class="lead"><?php echo htmlspecialchars($message); ?></p>
                        <hr>
                        <div class="mt-4">
                            <a href="../../index.php" class="btn btn-primary">
                                <i class="fas fa-home"></i> Ir al Inicio
                            </a>
                            <a href="../../login.php" class="btn btn-outline-secondary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

/**
 * Verificar token de compartir
 */
function verifyShareToken($db, $file_id, $token) {
    try {
        // Verificar en tabla file_shares
        $query = "SELECT fs.*, f.*, u.username as owner_name
                  FROM file_shares fs
                  JOIN files f ON fs.file_id = f.id
                  JOIN users u ON f.user_id = u.id
                  WHERE fs.file_id = :file_id 
                  AND fs.share_token = :token
                  AND (fs.expires_at IS NULL OR fs.expires_at > NOW())
                  AND f.deleted_at IS NULL";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':file_id' => $file_id,
            ':token' => $token
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Error verificando token: " . $e->getMessage());
        return false;
    }
}

/**
 * Incrementar contador de visualizaciones
 */
function incrementViewCount($db, $file_id) {
    try {
        $query = "UPDATE files SET download_count = download_count + 1 WHERE id = :file_id";
        $stmt = $db->prepare($query);
        $stmt->execute([':file_id' => $file_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error incrementando contador: " . $e->getMessage());
        return false;
    }
}
?>