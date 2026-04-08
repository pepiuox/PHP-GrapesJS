<?php
// file-manager/upload.php
require_once '../config/constants.php';
require_once '../config/Database.php';
require_once '../classes/Auth.php';
require_once '../includes/functions.php';
require_once '../classes/FileManager.php';
require_once '../includes/file-functions.php';

// Inicializar
$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$fileManager = new FileManager($db);

// Verificar acceso
checkFileManagerAccess($auth);

$message = '';
$error = '';

// Procesar upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Subida simple (un archivo)
    if (isset($_FILES['file'])) {
        $description = sanitize_input($_POST['description'] ?? '');
        $is_public = isset($_POST['is_public']) ? 1 : 0;
        
        $result = $fileManager->upload($_FILES['file'], $_SESSION['user_id'], $description, $is_public);
        
        if ($result['success']) {
            set_flash_message('success', 'Archivo subido correctamente');
            header("Location: " . getFileManagerUrl("index.php"));
            exit();
        } else {
            $error = $result['error'];
        }
    }
    
    // Subida múltiple
    if (isset($_FILES['files'])) {
        $uploaded_count = 0;
        $error_count = 0;
        $errors = [];
        
        foreach ($_FILES['files']['error'] as $key => $error) {
            if ($error === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $_FILES['files']['name'][$key],
                    'type' => $_FILES['files']['type'][$key],
                    'tmp_name' => $_FILES['files']['tmp_name'][$key],
                    'error' => $_FILES['files']['error'][$key],
                    'size' => $_FILES['files']['size'][$key]
                ];
                
                $result = $fileManager->upload($file, $_SESSION['user_id']);
                
                if ($result['success']) {
                    $uploaded_count++;
                } else {
                    $error_count++;
                    $errors[] = $file['name'] . ': ' . $result['error'];
                }
            }
        }
        
        if ($uploaded_count > 0) {
            $message = '<div class="alert alert-success">' . $uploaded_count . ' archivo(s) subido(s) correctamente.</div>';
        }
        
        if ($error_count > 0) {
            $error = '<div class="alert alert-danger">' . $error_count . ' error(es):<br>' . implode('<br>', $errors) . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos - Administrador de Archivos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .upload-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .upload-zone {
            border: 3px dashed #dee2e6;
            border-radius: 15px;
            padding: 60px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        .upload-zone:hover, .upload-zone.dragover {
            border-color: #4361ee;
            background: #e9ecef;
        }
        .upload-zone i {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .file-list {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 20px;
        }
        .file-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .file-item:last-child {
            border-bottom: none;
        }
        .file-icon {
            margin-right: 10px;
            font-size: 1.5rem;
        }
        .file-info {
            flex-grow: 1;
        }
        .file-size {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .file-progress {
            height: 5px;
            background: #e9ecef;
            border-radius: 3px;
            margin-top: 5px;
            overflow: hidden;
        }
        .file-progress-bar {
            height: 100%;
            background: #4361ee;
            width: 0%;
            transition: width 0.3s;
        }
        .file-status {
            margin-left: 10px;
            font-size: 0.9rem;
        }
        .file-status.success {
            color: #28a745;
        }
        .file-status.error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php 
    $is_file_manager = true;
    require_once '../includes/header.php'; 
    ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php require_once '../includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-cloud-upload-alt"></i> Subir Archivos
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?php echo getFileManagerUrl('index.php'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                
                <?php echo $message; ?>
                <?php echo $error; ?>
                
                <div class="upload-container">
                    <!-- Formulario de upload múltiple -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-upload"></i> Subida Múltiple
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="uploadZone" class="upload-zone">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <h4>Arrastra y suelta archivos aquí</h4>
                                <p class="text-muted">O haz clic para seleccionar</p>
                                <p class="small text-muted">Tamaño máximo por archivo: 10MB</p>
                                
                                <input type="file" id="fileInput" multiple style="display: none;">
                                <button type="button" id="selectFilesBtn" class="btn btn-primary mt-3">
                                    <i class="fas fa-folder-open"></i> Seleccionar Archivos
                                </button>
                            </div>
                            
                            <div id="fileList" class="file-list"></div>
                            
                            <form id="uploadForm" method="POST" enctype="multipart/form-data" style="display: none;">
                                <input type="file" name="files[]" id="filesInput" multiple>
                                <button type="submit" id="submitBtn" class="btn btn-success mt-3">
                                    <i class="fas fa-upload"></i> Subir Archivos Seleccionados
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Formulario de upload simple -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-file-upload"></i> Subida Simple
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="singleFile" class="form-label">Seleccionar Archivo</label>
                                    <input class="form-control" type="file" id="singleFile" name="file" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="singleDescription" class="form-label">Descripción (opcional)</label>
                                    <textarea class="form-control" id="singleDescription" name="description" rows="2"></textarea>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="singleIsPublic" name="is_public" value="1">
                                    <label class="form-check-label" for="singleIsPublic">
                                        Hacer archivo público
                                    </label>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-upload"></i> Subir Archivo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadZone = document.getElementById('uploadZone');
            const fileInput = document.getElementById('fileInput');
            const selectFilesBtn = document.getElementById('selectFilesBtn');
            const fileList = document.getElementById('fileList');
            const filesInput = document.getElementById('filesInput');
            const uploadForm = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');
            
            let selectedFiles = [];
            
            // Seleccionar archivos
            selectFilesBtn.addEventListener('click', () => fileInput.click());
            uploadZone.addEventListener('click', () => fileInput.click());
            
            fileInput.addEventListener('change', handleFileSelect);
            
            // Drag & Drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadZone.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadZone.addEventListener(eventName, () => {
                    uploadZone.classList.add('dragover');
                });
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadZone.addEventListener(eventName, () => {
                    uploadZone.classList.remove('dragover');
                });
            });
            
            uploadZone.addEventListener('drop', handleDrop);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }
            
            function handleFileSelect(e) {
                const files = e.target.files;
                handleFiles(files);
            }
            
            function handleFiles(files) {
                selectedFiles = Array.from(files);
                updateFileList();
            }
            
            function updateFileList() {
                fileList.innerHTML = '';
                
                if (selectedFiles.length === 0) {
                    uploadForm.style.display = 'none';
                    return;
                }
                
                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    
                    const fileIcon = getFileIcon(file.name);
                    const fileSize = formatFileSize(file.size);
                    
                    fileItem.innerHTML = `
                        <div class="file-icon text-primary">
                            <i class="${fileIcon}"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${fileSize}</div>
                            <div class="file-progress">
                                <div class="file-progress-bar" id="progress-${index}"></div>
                            </div>
                        </div>
                        <div class="file-status" id="status-${index}">
                            <i class="fas fa-clock text-muted"></i>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    fileList.appendChild(fileItem);
                });
                
                // Actualizar input del formulario
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                filesInput.files = dataTransfer.files;
                
                uploadForm.style.display = 'block';
            }
            
            // Simular upload (en producción sería con AJAX)
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                selectedFiles.forEach((file, index) => {
                    // Simular progreso
                    let progress = 0;
                    const progressBar = document.getElementById(`progress-${index}`);
                    const statusIcon = document.getElementById(`status-${index}`);
                    
                    const interval = setInterval(() => {
                        progress += Math.random() * 20;
                        if (progress >= 100) {
                            progress = 100;
                            clearInterval(interval);
                            
                            // Simular éxito/error
                            const success = Math.random() > 0.2; // 80% éxito
                            
                            if (success) {
                                statusIcon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
                                statusIcon.className = 'file-status success';
                            } else {
                                statusIcon.innerHTML = '<i class="fas fa-times-circle text-danger"></i>';
                                statusIcon.className = 'file-status error';
                            }
                        }
                        
                        progressBar.style.width = progress + '%';
                    }, 200);
                });
                
                // Después de simular, enviar el formulario
                setTimeout(() => {
                    uploadForm.submit();
                }, 3000);
            });
            
            window.removeFile = function(index) {
                selectedFiles.splice(index, 1);
                updateFileList();
            };
            
            function getFileIcon(filename) {
                const extension = filename.split('.').pop().toLowerCase();
                const icons = {
                    'pdf': 'fas fa-file-pdf',
                    'doc': 'fas fa-file-word',
                    'docx': 'fas fa-file-word',
                    'xls': 'fas fa-file-excel',
                    'xlsx': 'fas fa-file-excel',
                    'txt': 'fas fa-file-alt',
                    'jpg': 'fas fa-file-image',
                    'jpeg': 'fas fa-file-image',
                    'png': 'fas fa-file-image',
                    'gif': 'fas fa-file-image',
                    'zip': 'fas fa-file-archive',
                    'rar': 'fas fa-file-archive',
                    'mp3': 'fas fa-file-audio',
                    'mp4': 'fas fa-file-video'
                };
                
                return icons[extension] || 'fas fa-file';
            }
            
            function formatFileSize(bytes) {
                if (bytes >= 1073741824) {
                    return (bytes / 1073741824).toFixed(2) + ' GB';
                } else if (bytes >= 1048576) {
                    return (bytes / 1048576).toFixed(2) + ' MB';
                } else if (bytes >= 1024) {
                    return (bytes / 1024).toFixed(2) + ' KB';
                } else {
                    return bytes + ' bytes';
                }
            }
        });
    </script>
</body>
</html>