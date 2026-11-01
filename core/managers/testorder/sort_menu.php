<?php

// ✅ PDO: conexión canónica global (antes $database, inexistente).
if (!isset($conn)) {
    require_once __DIR__ . '/../../config/dbconnection.php';
}

$menu = $_POST['menu'];
for ($i = 0; $i < count($menu); $i++) {
    // ✅ PDO lanza PDOException en error: "or die($database->error)" es innecesario.
    $stmt = $conn->prepare("UPDATE `menu` SET `sort`=:sort WHERE `id`=:id");
    $stmt->execute([':sort' => $i, ':id' => $menu[$i]]);
}
?>