<?php
$rout = str_replace('\\', '/', dirname(__DIR__));
include_once $rout.'/config/dbconnection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $sql = "UPDATE pages SET title=:title, content=:content WHERE id=:id";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([':title' => protect($title), ':content' => protect($content), ':id' => $id]) === TRUE) {
        echo "Page Updated";
    } else {
        echo "Failed";
    }
}
?>
