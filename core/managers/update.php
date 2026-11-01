<?php

/* Update pages */
include '../config/dbconnection.php';

if (isset($_POST['content'])) {
    $idp = $_POST['idp'];
    $content = $_POST['content'];
    $style = $_POST['style'];

    $sql = "UPDATE pages SET  content=:content, style=:style WHERE id=:id";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([':content' => protect($content), ':style' => protect($style), ':id' => $idp]) === TRUE) {
        echo "The page has been updated";
    } else {
        echo "Failed";
    }
}
?>
