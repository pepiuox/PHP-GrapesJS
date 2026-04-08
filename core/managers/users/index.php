<?php
// index.php - Página principal que redirige al dashboard o login

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

// Verificar si el usuario está logueado
if ($auth->isLoggedIn()) {
    // Redirigir al dashboard
    redirect('dashboard.php');
} else {
    // Redirigir al login
    redirect('login.php');
}
?>