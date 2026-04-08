<?php
// delete-user.php

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->requireAdmin();

if(isset($_GET['id'])) {
    $user = new User($db);
    $user->id = $_GET['id'];
    
    // No permitir eliminarse a sí mismo
    if($user->id == $_SESSION['user_id']) {
        $_SESSION['error'] = "No puedes eliminarte a ti mismo.";
        header("Location: users.php");
        exit();
    }
    
    if($user->delete()) {
        $_SESSION['success'] = "Usuario eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el usuario.";
    }
}

header("Location: users.php");
exit();
?>