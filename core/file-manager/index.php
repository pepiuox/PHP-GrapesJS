<?php
// file-manager/index.php
require_once '../config/constants.php';
require_once '../config/Database.php';
require_once '../classes/RoleManager.php';
require_once '../classes/Auth.php';
require_once '../classes/FileManager.php';
require_once '../includes/functions.php';
require_once '../includes/file-functions.php';

// Inicializar
$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$fileManager = new FileManager($db);

// Verificar acceso
checkFileManagerAccess($auth);

// Obtener parámetros
$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Procesar eliminación
if (isset($_GET['delete_id'])) {
    $result = $fileManager->delete($_GET['delete_id'], $_SESSION['user_id']);
    if ($result['success']) {
        set_flash_message('success', $result['message']);
    } else {
        set_flash_message('error', $result['error']);
    }
    header("Location: " . getFileManagerUrl("index.php"));
    exit();
}

// Obtener archivos del usuario
$files_result = $fileManager->getUserFiles($_SESSION['user_id'], $category, $search);
$all_files = $files_result->fetchAll(PDO::FETCH_ASSOC);

// Paginación
$total_files = count($all_files);
$total_pages = ceil($total_files / $limit);
$files = array_slice($all_files, $offset, $limit);

// Obtener estadísticas
$stats = $fileManager->getStats($_SESSION['user_id']);

// Determinar categorías disponibles
$categories = [
    'all' => 'Todos los Archivos',
    'document' => 'Documentos',
    'image' => 'Imágenes',
    'audio' => 'Audio',
    'video' => 'Videos',
    'archive' => 'Archivos Comprimidos',
    'other' => 'Otros'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Archivos - Sistema de Usuarios</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?php echo getFileManagerUrl('css/file-manager.css'); ?>">
    
    <style>
        .file-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e9ecef;
        }
        .file-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .file-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .file-actions {
            opacity: 0;
            transition: opacity 0.2s;
        }
        .file-card:hover .file-actions {
            opacity: 1;
        }
        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            border-color: #4361ee;
            background-color: #f8f9fa;
        }
        .upload-area.dragover {
            border-color: #4361ee;
            background-color: #e9ecef;
        }
        .file-preview-img {
            max-width: 100%;
            max-height: 150px;
            object-fit: contain;
        }
        .storage-progress {
            height: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php 
    // Incluir header de la aplicación principal
    $is_file_manager = true;
    //require_once '../includes/header.php'; 
    ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php 
            // Incluir sidebar de la aplicación principal
            require_once '../includes/sidebar.php'; 
            ?>
            
            <!-- Contenido Principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-folder-open"></i> Administrador de Archivos
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="fas fa-upload"></i> Subir Archivo
                            </button>
                            <a href="<?php echo getFileManagerUrl('upload.php'); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Subir Múltiples
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Mensajes Flash -->
                <?php echo display_flash_messages(); ?>
                
                <!-- Estadísticas Rápidas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Total Archivos</h5>
                                        <h2><?php echo $stats['total_files'] ?? 0; ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-file fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Espacio Usado</h5>
                                        <h2><?php echo FileManager::formatFileSize($stats['total_size'] ?? 0); ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-hdd fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Documentos</h5>
                                        <h2><?php echo $stats['documents'] ?? 0; ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-file-pdf fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <h5 class="card-title">Imágenes</h5>
                                        <h2><?php echo $stats['images'] ?? 0; ?></h2>
                                    </div>
                                    <div class="col-4 text-end">
                                        <i class="fas fa-file-image fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filtros y Búsqueda -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Filtro por categoría -->
                                <div class="btn-group" role="group">
                                    <?php foreach ($categories as $cat_key => $cat_name): ?>
                                    <a href="<?php echo getFileManagerUrl('index.php') . '?category=' . $cat_key . ($search ? '&search=' . urlencode($search) : ''); ?>" 
                                       class="btn btn-outline-<?php echo $cat_key == ($category ?: 'all') ? 'primary' : 'secondary'; ?>">
                                        <?php if ($cat_key != 'all'): ?>
                                        <i class="fas fa-<?php echo $cat_key == 'document' ? 'file-alt' : ($cat_key == 'image' ? 'image' : ($cat_key == 'audio' ? 'music' : ($cat_key == 'video' ? 'video' : 'archive'))); ?>"></i>
                                        <?php endif; ?>
                                        <?php echo $cat_name; ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Búsqueda -->
                                <form method="GET" class="d-flex">
                                    <input type="text" class="form-control me-2" name="search" 
                                           placeholder="Buscar archivos..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <?php if ($search): ?>
                                    <a href="<?php echo getFileManagerUrl('index.php'); ?>" class="btn btn-secondary ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de Archivos -->
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($files)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h4>No hay archivos</h4>
                            <p class="text-muted">
                                <?php if ($search): ?>
                                No se encontraron archivos que coincidan con "<?php echo htmlspecialchars($search); ?>"
                                <?php elseif ($category && $category != 'all'): ?>
                                No hay archivos en la categoría "<?php echo $categories[$category] ?? $category; ?>"
                                <?php else: ?>
                                Comienza subiendo tu primer archivo
                                <?php endif; ?>
                            </p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="fas fa-upload"></i> Subir Primer Archivo
                            </button>
                        </div>
                        <?php else: ?>
                        <div class="row">
                            <?php foreach ($files as $file): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card file-card h-100">
                                    <span class="badge bg-<?php echo FileManager::getCategoryColor($file['category']); ?> category-badge">
                                        <?php echo $file['category']; ?>
                                    </span>
                                    
                                    <div class="card-body text-center">
                                        <!-- Icono o vista previa -->
                                        <?php if ($file['category'] == 'image'): ?>
                                        <div class="mb-3">
                                            <img src="<?php echo getFileManagerUrl('preview.php?id=' . $file['id'] . '&thumb=true'); ?>" 
                                                 alt="<?php echo htmlspecialchars($file['original_name']); ?>"
                                                 class="file-preview-img rounded">
                                        </div>
                                        <?php else: ?>
                                        <div class="file-icon text-<?php echo FileManager::getCategoryColor($file['category']); ?>">
                                            <i class="<?php echo FileManager::getFileIcon($file['file_type']); ?>"></i>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Información del archivo -->
                                        <h6 class="card-title" title="<?php echo htmlspecialchars($file['original_name']); ?>">
                                            <?php echo strlen($file['original_name']) > 20 ? substr($file['original_name'], 0, 20) . '...' : $file['original_name']; ?>
                                        </h6>
                                        
                                        <p class="card-text small text-muted">
                                            <i class="fas fa-hdd"></i> <?php echo FileManager::formatFileSize($file['file_size']); ?><br>
                                            <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($file['created_at'])); ?>
                                        </p>
                                        
                                        <?php if ($file['description']): ?>
                                        <p class="card-text small">
                                            <?php echo strlen($file['description']) > 50 ? substr($file['description'], 0, 50) . '...' : $file['description']; ?>
                                        </p>
                                        <?php endif; ?>
                                        
                                        <!-- Acciones -->
                                        <div class="file-actions mt-3">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo getFileManagerUrl('download.php?id=' . $file['id']); ?>" 
                                                   class="btn btn-outline-primary" title="Descargar">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-info" 
                                                        data-bs-toggle="modal" data-bs-target="#fileInfoModal"
                                                        data-file-id="<?php echo $file['id']; ?>"
                                                        title="Información">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                                <a href="#" class="btn btn-outline-warning" 
                                                   onclick="copyShareLink(<?php echo $file['id']; ?>)" 
                                                   title="Compartir">
                                                    <i class="fas fa-share-alt"></i>
                                                </a>
                                                <a href="<?php echo getFileManagerUrl('index.php?delete_id=' . $file['id']); ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('¿Eliminar este archivo?')"
                                                   title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent border-top-0">
                                        <small class="text-muted">
                                            <i class="fas fa-download"></i> <?php echo $file['download_count']; ?> descargas
                                            <?php if ($file['is_public']): ?>
                                            <span class="badge bg-success float-end">Público</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Paginación -->
                        <?php if ($total_pages > 1): ?>
                        <nav aria-label="Paginación de archivos">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo getFileManagerUrl('index.php') . '?page=' . ($page - 1) . ($category ? '&category=' . $category : '') . ($search ? '&search=' . urlencode($search) : ''); ?>">
                                        Anterior
                                    </a>
                                </li>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo getFileManagerUrl('index.php') . '?page=' . $i . ($category ? '&category=' . $category : '') . ($search ? '&search=' . urlencode($search) : ''); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo getFileManagerUrl('index.php') . '?page=' . ($page + 1) . ($category ? '&category=' . $category : '') . ($search ? '&search=' . urlencode($search) : ''); ?>">
                                        Siguiente
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Modal para Subir Archivo -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-upload"></i> Subir Archivo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo getFileManagerUrl('upload.php'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Seleccionar Archivo</label>
                            <input class="form-control" type="file" id="file" name="file" required>
                            <div class="form-text">
                                Tamaño máximo: 10MB. Formatos permitidos: JPG, PNG, GIF, PDF, DOC, XLS, TXT, ZIP, etc.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción (opcional)</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1">
                            <label class="form-check-label" for="is_public">
                                Hacer archivo público (visible para otros usuarios)
                            </label>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Información</h6>
                            <ul class="mb-0 small">
                                <li>Los archivos se almacenan de forma segura</li>
                                <li>Puedes editar la información después de subir</li>
                                <li>Los archivos públicos pueden ser vistos por otros usuarios</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Subir Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal para Información del Archivo -->
    <div class="modal fade" id="fileInfoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> Información del Archivo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="fileInfoContent">
                    <!-- Cargado dinámicamente -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script src="<?php echo getFileManagerUrl('js/file-manager.js'); ?>"></script>
    
    <script>
        // Cargar información del archivo en el modal
        $('#fileInfoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var fileId = button.data('file-id');
            var modal = $(this);
            
            // Mostrar cargando
            modal.find('#fileInfoContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando información del archivo...</p>
                </div>
            `);
            
            // Cargar información via AJAX
            $.ajax({
                url: '<?php echo getFileManagerUrl("includes/get-file-info.php"); ?>',
                method: 'GET',
                data: { id: fileId },
                success: function(response) {
                    modal.find('#fileInfoContent').html(response);
                },
                error: function() {
                    modal.find('#fileInfoContent').html(`
                        <div class="alert alert-danger">
                            Error al cargar la información del archivo.
                        </div>
                    `);
                }
            });
        });
        
        // Copiar enlace para compartir
        function copyShareLink(fileId) {
            var shareUrl = window.location.origin + '<?php echo getFileManagerUrl("share.php?id="); ?>' + fileId;
            
            navigator.clipboard.writeText(shareUrl).then(function() {
                alert('Enlace copiado al portapapeles:\n' + shareUrl);
            }, function(err) {
                alert('Error al copiar el enlace');
            });
            
            return false;
        }
        
        // Drag & Drop para upload
        document.addEventListener('DOMContentLoaded', function() {
            var uploadArea = document.createElement('div');
            uploadArea.className = 'upload-area mt-3';
            uploadArea.innerHTML = `
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <h5>Arrastra y suelta archivos aquí</h5>
                <p class="text-muted">O haz clic para seleccionar</p>
                <input type="file" id="dropzoneFile" style="display: none;" multiple>
            `;
            
            var fileInput = uploadArea.querySelector('#dropzoneFile');
            var form = document.querySelector('form[enctype="multipart/form-data"]');
            
            if (form) {
                form.parentNode.insertBefore(uploadArea, form.nextSibling);
                
                uploadArea.addEventListener('click', function() {
                    fileInput.click();
                });
                
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        // Actualizar el input de archivo del formulario
                        var mainFileInput = form.querySelector('input[type="file"]');
                        if (mainFileInput) {
                            // Para múltiples archivos, necesitarías un formulario diferente
                            if (this.files.length === 1) {
                                // Transferir el archivo al input principal
                                var dataTransfer = new DataTransfer();
                                dataTransfer.items.add(this.files[0]);
                                mainFileInput.files = dataTransfer.files;
                                
                                // Mostrar nombre del archivo
                                uploadArea.innerHTML = `
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5>${this.files[0].name}</h5>
                                    <p class="text-muted">Listo para subir</p>
                                `;
                            } else {
                                // Redirigir a página de upload múltiple
                                window.location.href = '<?php echo getFileManagerUrl("upload.php"); ?>';
                            }
                        }
                    }
                });
                
                // Drag & Drop events
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, preventDefaults, false);
                });
                
                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, highlight, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, unhighlight, false);
                });
                
                function highlight() {
                    uploadArea.classList.add('dragover');
                }
                
                function unhighlight() {
                    uploadArea.classList.remove('dragover');
                }
                
                uploadArea.addEventListener('drop', handleDrop, false);
                
                function handleDrop(e) {
                    var dt = e.dataTransfer;
                    var files = dt.files;
                    
                    if (files.length > 0) {
                        if (files.length === 1) {
                            // Para un solo archivo
                            fileInput.files = files;
                            fileInput.dispatchEvent(new Event('change'));
                        } else {
                            // Para múltiples archivos
                            window.location.href = '<?php echo getFileManagerUrl("upload.php"); ?>';
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>