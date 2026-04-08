<?php
// edit-user.php

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireLogin();

$user = new User($db);
$message = '';
$error = '';

// Obtener ID del usuario
$user->id = isset($_GET['id']) ? $_GET['id'] : die('ID de usuario no especificado.');
$user->readOne();

// Solo administradores pueden editar otros usuarios
if(!$auth->isAdmin() && $user->id != $_SESSION['user_id']) {
    header("Location: dashboard.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asignar valores
    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->full_name = $_POST['full_name'];
    $user->bio = $_POST['bio'];
    $user->role = $_POST['role'];
    $user->permissions = $_POST['permissions'];
    $user->is_active = isset($_POST['is_active']) ? 1 : 0;
    $user->is_banned = isset($_POST['is_banned']) ? 1 : 0;
    $user->banned_reason = $_POST['banned_reason'];
    
    // Si se proporciona nueva contraseña
    if(!empty($_POST['new_password'])) {
        $user->password_hash = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        // Actualizar contraseña
        $query = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$user->password_hash, $user->id]);
    }
    
    // Actualizar usuario
    if($user->update()) {
        $message = '<div class="alert alert-success">Usuario actualizado correctamente.</div>';
        // Releer datos actualizados
        $user->readOne();
    } else {
        $error = "Error al actualizar el usuario.";
    }
}
$page_title = 'Editar usuarios';
?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-user-edit"></i> Editar Usuario: <?php echo htmlspecialchars($user->username); ?>
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
                                           value="<?php echo htmlspecialchars($user->username); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> Email *
                                    </label>
                                    <input type="email" class="form-control" id="email" 
                                           name="email" required
                                           value="<?php echo htmlspecialchars($user->email); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">
                                        <i class="fas fa-id-card"></i> Nombre Completo
                                    </label>
                                    <input type="text" class="form-control" id="full_name" 
                                           name="full_name"
                                           value="<?php echo htmlspecialchars($user->full_name); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">
                                        <i class="fas fa-user-tag"></i> Rol *
                                    </label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="guest" <?php echo ($user->role == 'guest') ? 'selected' : ''; ?>>Invitado</option>
                                        <option value="editor" <?php echo ($user->role == 'editor') ? 'selected' : ''; ?>>Editor</option>
                                        <option value="manager" <?php echo ($user->role == 'manager') ? 'selected' : ''; ?>>Manager</option>
                                        <option value="admin" <?php echo ($user->role == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="permissions" class="form-label">
                                        <i class="fas fa-key"></i> Permisos Especiales
                                    </label>
                                    <textarea class="form-control" id="permissions" 
                                              name="permissions" rows="2"><?php echo htmlspecialchars($user->permissions); ?></textarea>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="form-label">
                                        <i class="fas fa-key"></i> Nueva Contraseña
                                    </label>
                                    <input type="password" class="form-control" id="new_password" 
                                           name="new_password">
                                    <div class="form-text">Dejar en blanco para no cambiar.</div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="bio" class="form-label">
                                        <i class="fas fa-file-alt"></i> Biografía
                                    </label>
                                    <textarea class="form-control" id="bio" name="bio" 
                                              rows="3"><?php echo htmlspecialchars($user->bio); ?></textarea>
                                    <div class="form-text">Máximo 100 caracteres.</div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_active" name="is_active" value="1" 
                                               <?php echo $user->is_active ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_active">
                                            Usuario Activo
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_banned" name="is_banned" value="1" 
                                               <?php echo $user->is_banned ? 'checked' : ''; ?>
                                               onchange="toggleBanReason()">
                                        <label class="form-check-label" for="is_banned">
                                            Usuario Suspendido
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 mb-3" id="banReasonDiv" 
                                     style="display: <?php echo $user->is_banned ? 'block' : 'none'; ?>">
                                    <label for="banned_reason" class="form-label">
                                        <i class="fas fa-exclamation-triangle"></i> Razón de Suspensión
                                    </label>
                                    <textarea class="form-control" id="banned_reason" 
                                              name="banned_reason" rows="2"><?php echo htmlspecialchars($user->banned_reason); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Guardar Cambios
                                    </button>
                                    <a href="users.php" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</main>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleBanReason() {
            const banReasonDiv = document.getElementById('banReasonDiv');
            const isBanned = document.getElementById('is_banned').checked;
            banReasonDiv.style.display = isBanned ? 'block' : 'none';
        }
        
        // Contador para biografía
        document.getElementById('bio').addEventListener('input', function() {
            const current = this.value.length;
            const max = 100;
            if(current > max) {
                this.value = this.value.substring(0, max);
            }
        });
    </script>
