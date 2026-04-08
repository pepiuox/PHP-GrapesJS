<?php
// file-manager/includes/get-file-info.php
require_once '../../config/constants.php';
require_once '../../config/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/FileManager.php';
require_once '../includes/file-functions.php';

// Inicializar
$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$fileManager = new FileManager($db);

// Verificar acceso
session_start();
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo '<div class="alert alert-danger">No autorizado</div>';
    exit();
}

// Obtener ID del archivo
$file_id = $_GET['id'] ?? 0;

if (!$file_id) {
    echo '<div class="alert alert-danger">ID de archivo no especificado</div>';
    exit();
}

// Obtener información del archivo
$file = $fileManager->getFileById($file_id, $_SESSION['user_id']);

if (!$file) {
    echo '<div class="alert alert-danger">Archivo no encontrado o no tienes acceso</div>';
    exit();
}

// Formatear información
$file_size = FileManager::formatFileSize($file['file_size']);
$created_date = date('d/m/Y H:i:s', strtotime($file['created_at']));
$updated_date = date('d/m/Y H:i:s', strtotime($file['updated_at']));
$file_icon = FileManager::getFileIcon($file['file_type']);
$category_color = FileManager::getCategoryColor($file['category']);
$category_names = [
    'document' => 'Documento',
    'image' => 'Imagen',
    'audio' => 'Audio',
    'video' => 'Video',
    'archive' => 'Archivo Comprimido',
    'other' => 'Otro'
];
$category_name = $category_names[$file['category']] ?? $file['category'];

// Determinar si es una imagen para mostrar vista previa
$is_image = $file['category'] == 'image';
$preview_url = getFileManagerUrl('preview.php?id=' . $file['id']);
$download_url = getFileManagerUrl('download.php?id=' . $file['id']);
$share_url = get_base_url() . '/file-manager/share.php?id=' . $file['id'];
?>

<div class="row">
    <!-- Columna izquierda: Vista previa/información básica -->
    <div class="col-md-5">
        <?php if ($is_image): ?>
        <div class="text-center mb-4">
            <img src="<?php echo $preview_url; ?>" 
                 alt="<?php echo htmlspecialchars($file['original_name']); ?>"
                 class="img-fluid rounded shadow">
            <p class="text-muted mt-2">Vista previa de la imagen</p>
        </div>
        <?php else: ?>
        <div class="text-center mb-4">
            <div class="file-icon-large mb-3">
                <i class="<?php echo $file_icon; ?> fa-5x text-<?php echo $category_color; ?>"></i>
            </div>
            <h5><?php echo htmlspecialchars($file['original_name']); ?></h5>
            <span class="badge bg-<?php echo $category_color; ?>">
                <?php echo $category_name; ?>
            </span>
        </div>
        <?php endif; ?>
        
        <div class="d-grid gap-2 mb-4">
            <a href="<?php echo $download_url; ?>" class="btn btn-primary">
                <i class="fas fa-download"></i> Descargar Archivo
            </a>
            <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('<?php echo $share_url; ?>')">
                <i class="fas fa-share-alt"></i> Copiar Enlace para Compartir
            </button>
        </div>
        
        <!-- Acciones rápidas -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt"></i> Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="btn-group w-100" role="group">
                    <a href="<?php echo $download_url; ?>" class="btn btn-outline-primary" title="Descargar">
                        <i class="fas fa-download"></i>
                    </a>
                    <button type="button" class="btn btn-outline-info" 
                            onclick="editFileInfo(<?php echo $file['id']; ?>)" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-outline-warning" 
                            onclick="shareFile(<?php echo $file['id']; ?>)" title="Compartir">
                        <i class="fas fa-share"></i>
                    </button>
                    <a href="<?php echo getFileManagerUrl('index.php?delete_id=' . $file['id']); ?>" 
                       class="btn btn-outline-danger" 
                       onclick="return confirm('¿Eliminar este archivo?')" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Columna derecha: Información detallada -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información Detallada</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th width="30%">Nombre Original:</th>
                                <td><?php echo htmlspecialchars($file['original_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Nombre en Servidor:</th>
                                <td><code><?php echo htmlspecialchars($file['filename']); ?></code></td>
                            </tr>
                            <tr>
                                <th>Tamaño:</th>
                                <td><?php echo $file_size; ?></td>
                            </tr>
                            <tr>
                                <th>Tipo MIME:</th>
                                <td><code><?php echo htmlspecialchars($file['mime_type']); ?></code></td>
                            </tr>
                            <tr>
                                <th>Extensión:</th>
                                <td><span class="badge bg-secondary">.<?php echo htmlspecialchars($file['file_type']); ?></span></td>
                            </tr>
                            <tr>
                                <th>Categoría:</th>
                                <td>
                                    <span class="badge bg-<?php echo $category_color; ?>">
                                        <i class="fas fa-<?php echo $file['category'] == 'document' ? 'file-alt' : 
                                                           ($file['category'] == 'image' ? 'image' : 
                                                           ($file['category'] == 'audio' ? 'music' : 
                                                           ($file['category'] == 'video' ? 'video' : 'archive'))); ?>"></i>
                                        <?php echo $category_name; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Propietario:</th>
                                <td>
                                    <i class="fas fa-user"></i> 
                                    <?php echo htmlspecialchars($file['owner_name'] ?? 'Desconocido'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <?php if ($file['is_public']): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-globe"></i> Público
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-lock"></i> Privado
                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Descargas:</th>
                                <td>
                                    <i class="fas fa-download"></i> 
                                    <?php echo $file['download_count']; ?> veces
                                </td>
                            </tr>
                            <tr>
                                <th>Fecha de Subida:</th>
                                <td>
                                    <i class="fas fa-calendar-plus"></i> 
                                    <?php echo $created_date; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Última Modificación:</th>
                                <td>
                                    <i class="fas fa-calendar-check"></i> 
                                    <?php echo $updated_date; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Ruta en Servidor:</th>
                                <td>
                                    <code class="small"><?php echo htmlspecialchars($file['file_path']); ?></code>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($file['description']): ?>
                <div class="mt-4">
                    <h6><i class="fas fa-file-alt"></i> Descripción:</h6>
                    <div class="border rounded p-3 bg-light">
                        <?php echo nl2br(htmlspecialchars($file['description'])); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Información técnica -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs"></i> Información Técnica</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <small class="text-muted">ID del Archivo:</small>
                            <div><code><?php echo $file['id']; ?></code></div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">ID del Propietario:</small>
                            <div><code><?php echo $file['user_id']; ?></code></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <small class="text-muted">Hash del Archivo:</small>
                            <div>
                                <code class="small">
                                    <?php 
                                    if (file_exists($file['file_path'])) {
                                        echo md5_file($file['file_path']);
                                    } else {
                                        echo 'No disponible';
                                    }
                                    ?>
                                </code>
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Permisos del Archivo:</small>
                            <div>
                                <code class="small">
                                    <?php 
                                    if (file_exists($file['file_path'])) {
                                        echo substr(sprintf('%o', fileperms($file['file_path'])), -4);
                                    } else {
                                        echo 'No disponible';
                                    }
                                    ?>
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para las acciones -->
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Enlace copiado al portapapeles:\n' + text);
    }, function(err) {
        alert('Error al copiar el enlace: ' + err);
    });
}

function editFileInfo(fileId) {
    // Redirigir a página de edición (podría ser un modal en el futuro)
    window.location.href = '<?php echo getFileManagerUrl("edit.php?id="); ?>' + fileId;
}

function shareFile(fileId) {
    // Mostrar modal para compartir
    $('#shareModal').modal('show');
    $('#shareFileId').val(fileId);
    
    // Generar enlace de compartir
    const shareUrl = window.location.origin + '<?php echo getFileManagerUrl("share.php?id="); ?>' + fileId;
    $('#shareLink').val(shareUrl);
}

// Actualizar vista previa si es imagen
<?php if ($is_image): ?>
function refreshPreview() {
    const img = document.querySelector('.img-fluid');
    if (img) {
        img.src = '<?php echo $preview_url; ?>?t=' + new Date().getTime();
    }
}
<?php endif; ?>
</script>

<style>
.file-icon-large {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    display: inline-block;
}
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}
.code-block {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    font-family: monospace;
    font-size: 0.9em;
    overflow-x: auto;
}
</style>