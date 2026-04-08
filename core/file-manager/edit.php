<?php
// modules/file-manager/edit.php
require_once '../config/constants.php';
require_once '../config/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/FileManager.php';
require_once '../includes/file-functions.php';

// Inicializar
$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$fileManager = new FileManager($db);

// Verificar acceso
checkFileManagerAccess($auth);

// Obtener ID del archivo
$file_id = $_GET['id'] ?? 0;

if (!$file_id) {
    header("Location: " . getFileManagerUrl("index.php"));
    exit();
}

// Obtener información del archivo
$file = $fileManager->getFileById($file_id, $_SESSION['user_id']);

if (!$file || $file['user_id'] != $_SESSION['user_id']) {
    set_flash_message('error', 'No tienes permisos para editar este archivo');
    header("Location: " . getFileManagerUrl("index.php"));
    exit();
}

$message = '';
$error = '';

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = sanitize_input($_POST['description'] ?? '');
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    
    $data = [
        'description' => $description,
        'is_public' => $is_public
    ];
    
    $result = $fileManager->update($file_id, $_SESSION['user_id'], $data);
    
    if ($result['success']) {
        set_flash_message('success', $result['message']);
        header("Location: " . getFileManagerUrl("index.php"));
        exit();
    } else {
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Archivo - Administrador de Archivos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    
</head>
<body>
    <?php 
    $is_file_manager = true;
    //require_once '../includes/header.php'; 
    ?>
    <style>
        .edit-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .file-preview-edit {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <?php require_once '../includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-edit"></i> Editar Archivo
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?php echo getFileManagerUrl('index.php'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="edit-container">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-file"></i> <?php echo htmlspecialchars($file['original_name']); ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-3 text-center">
                                    <?php if ($file['category'] == 'image'): ?>
                                    <img src="<?php echo getFileManagerUrl('preview.php?id=' . $file['id'] . '&thumb=true'); ?>" 
                                         alt="<?php echo htmlspecialchars($file['original_name']); ?>"
                                         class="file-preview-edit rounded shadow">
                                    <?php else: ?>
                                    <div class="file-icon-large mb-3">
                                        <i class="<?php echo FileManager::getFileIcon($file['file_type']); ?> fa-4x"></i>
                                    </div>
                                    <?php endif; ?>
                                    <p class="small text-muted mt-2">
                                        <i class="fas fa-hdd"></i> <?php echo FileManager::formatFileSize($file['file_size']); ?><br>
                                        <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($file['created_at'])); ?>
                                    </p>
                                </div>
                                
                                <div class="col-md-9">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="original_name" class="form-label">Nombre del Archivo</label>
                                            <input type="text" class="form-control" id="original_name" 
                                                   value="<?php echo htmlspecialchars($file['original_name']); ?>" readonly>
                                            <div class="form-text">El nombre del archivo no se puede cambiar.</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="description" name="description" 
                                                      rows="4"><?php echo htmlspecialchars($file['description']); ?></textarea>
                                            <div class="form-text">Describe el contenido o propósito del archivo.</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="is_public" name="is_public" value="1"
                                                       <?php echo $file['is_public'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_public">
                                                    Hacer archivo público
                                                </label>
                                            </div>
                                            <div class="form-text">
                                                Los archivos públicos pueden ser vistos por otros usuarios del sistema.
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Información del Archivo</label>
                                            <div class="border rounded p-3 bg-light">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Tipo:</small>
                                                        <div><?php echo htmlspecialchars($file['mime_type']); ?></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Categoría:</small>
                                                        <div>
                                                            <span class="badge bg-<?php echo FileManager::getCategoryColor($file['category']); ?>">
                                                                <?php echo $file['category']; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Descargas:</small>
                                                        <div><?php echo $file['download_count']; ?></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Última modificación:</small>
                                                        <div><?php echo date('d/m/Y H:i', strtotime($file['updated_at'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save"></i> Guardar Cambios
                                            </button>
                                            <a href="<?php echo getFileManagerUrl('index.php'); ?>" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> Cancelar
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de seguridad -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Advertencias de Seguridad</h6>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li>No compartas archivos que contengan información sensible o personal</li>
                                <li>Verifica que el archivo no infrinja derechos de autor</li>
                                <li>Los archivos públicos son accesibles para todos los usuarios del sistema</li>
                                <li>Considera usar enlaces temporales en lugar de hacer el archivo permanente público</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>