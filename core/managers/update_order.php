<?php
// ✅ PDO: se usa la conexión canónica global ($conn) en lugar de un connect.php inexistente.
if (!isset($conn)) {
    require_once __DIR__ . '/../config/dbconnection.php';
}
if (isset($_POST["order"])) {
    $order = explode(",", $_POST["order"]);
    for ($i = 0; $i < count($order); $i++) {
        $sql = "UPDATE items SET priority=:priority WHERE id=:id";
        // ✅ PDO lanza PDOException en error: el antiguo "or die(mysqli_error())" es innecesario.
        $stmt = $conn->prepare($sql);
        $stmt->execute([':priority' => $i, ':id' => $order[$i]]);
    }
}
?>
