<?php
//manager/users create-user.php

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireAdmin(); // Solo administradores pueden crear usuarios

$user = new User($db);
$message = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asignar valores
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->password_hash = $_POST['password'];
    $user->full_name = $_POST['full_name'];
    $user->bio = $_POST['bio'];
    $user->role = $_POST['role'];
    $user->permissions = $_POST['permissions'];
    $user->is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validar email único
    if($user->emailExists()) {
        $error = "El email ya está registrado.";
    } else {
        // Crear usuario
        if($user->create()) {
            $message = '<div class="alert alert-success">Usuario creado correctamente.</div>';
            // Limpiar formulario
            $_POST = array();
        } else {
            $error = "Error al crear el usuario.";
        }
    }
}
$page_title = 'Crear usuario';
?>

 <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-user-plus"></i> Crear Nuevo Usuario
                    </h1>
                    <a href="users.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <?php 
                echo $message;
                if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-user"></i> Nombre de Usuario *
                                    </label>
                                    <input type="text" class="form-control" id="username" 
                                           name="username" required
                                           value="<?php echo $_POST['username'] ?? ''; ?>">
                                    <div class="form-text">Mínimo 3 caracteres, sin espacios.</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> Email *
                                    </label>
                                    <input type="email" class="form-control" id="email" 
                                           name="email" required
                                           value="<?php echo $_POST['email'] ?? ''; ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock"></i> Contraseña *
                                    </label>
                                    <input type="password" class="form-control" id="password" 
                                           name="password" required minlength="6">
                                    <div class="form-text">Mínimo 6 caracteres.</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">
                                        <i class="fas fa-id-card"></i> Nombre Completo
                                    </label>
                                    <input type="text" class="form-control" id="full_name" 
                                           name="full_name"
                                           value="<?php echo $_POST['full_name'] ?? ''; ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">
                                        <i class="fas fa-user-tag"></i> Rol *
                                    </label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="guest" <?php echo (($_POST['role'] ?? '') == 'guest') ? 'selected' : ''; ?>>Invitado</option>
                                        <option value="editor" <?php echo (($_POST['role'] ?? '') == 'editor') ? 'selected' : ''; ?>>Editor</option>
                                        <option value="manager" <?php echo (($_POST['role'] ?? '') == 'manager') ? 'selected' : ''; ?>>Manager</option>
                                        <option value="admin" <?php echo (($_POST['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="permissions" class="form-label">
                                        <i class="fas fa-key"></i> Permisos Especiales
                                    </label>
                                    <textarea class="form-control" id="permissions" 
                                              name="permissions" rows="2"><?php echo $_POST['permissions'] ?? ''; ?></textarea>
                                    <div class="form-text">JSON o lista separada por comas.</div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="bio" class="form-label">
                                        <i class="fas fa-file-alt"></i> Biografía
                                    </label>
                                    <textarea class="form-control" id="bio" name="bio" 
                                              rows="3"><?php echo $_POST['bio'] ?? ''; ?></textarea>
                                    <div class="form-text">Máximo 100 caracteres.</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_active" name="is_active" value="1" 
                                               <?php echo isset($_POST['is_active']) ? 'checked' : 'checked'; ?>>
                                        <label class="form-check-label" for="is_active">
                                            Usuario Activo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Crear Usuario
                                    </button>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="fas fa-undo"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validar formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            if(password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres.');
                return false;
            }
            
            const username = document.getElementById('username').value;
            if(username.length < 3 || username.includes(' ')) {
                e.preventDefault();
                alert('El nombre de usuario debe tener al menos 3 caracteres y no contener espacios.');
                return false;
            }
        });
        
        // Contador para biografía
        document.getElementById('bio').addEventListener('input', function() {
            const counter = document.getElementById('bioCounter') || 
                           (function() {
                               const div = document.createElement('div');
                               div.className = 'form-text';
                               div.id = 'bioCounter';
                               this.parentNode.appendChild(div);
                               return div;
                           }).call(this);
            
            const current = this.value.length;
            const max = 100;
            counter.textContent = `${current}/${max} caracteres`;
            
            if(current > max) {
                this.value = this.value.substring(0, max);
                counter.textContent = `${max}/${max} caracteres (se excedió el límite)`;
                counter.className = 'form-text text-danger';
            } else {
                counter.className = 'form-text';
            }
        });
    </script>
