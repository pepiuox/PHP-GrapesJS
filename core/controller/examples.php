<?php
// Conexión PDO centralizada
$pdo = new PDO(
    "mysql:host=localhost;dbname=miapp;charset=utf8mb4",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

try {
    // Planes
    $plans = new UsersPlans($pdo);
    if ($plans->hasActivePlan()) {
        $duration = $plans->getPlanDuration('month');
        echo "Plan activo por {$duration} días";
    }

    // Ratings
    $ratings = new UsersRatings($pdo);
    if ($ratings->hasRated('user123')) {
        echo "Ya diste like a este usuario";
    }
    $totalLikes = $ratings->getTotalRatings('user123');
    echo "Total de likes: {$totalLikes}";

    // Roles
    $roles = new UsersRoles($pdo);
    if ($roles->hasPermission('edit_posts')) {
        echo "Puede editar posts";
    }

    // Perfiles
    $profiles = new UsersProfiles($pdo);
    $fullName = $profiles->getFullName();
    echo "Nombre: {$fullName}";

    if (!$profiles->isProfileComplete()) {
        echo "Complete su perfil";
    }

    // Privacidad
    $privacy = new UsersPrivacy($pdo);
    $settings = $privacy->getPrivacySettings();
    if (!$settings['profile_public']) {
        echo "Perfil privado";
    }

} catch (RuntimeException $e) {
    error_log('Error: ' . $e->getMessage());
    $_SESSION['ErrorMessage'] = 'Error de sesión';
}
