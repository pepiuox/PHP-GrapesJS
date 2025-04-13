<?php
include_once URL . '/core/config/dbconnection.php';
/* Save Page */

if (isset($_POST['content'])) {
    $idp = $_POST['idp'];
    $tbl = $_POST['tbl'];
    $content = $_POST['content'];
    $style = $_POST['style'];

    $sql = "UPDATE $tbl SET  content = ?, style = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", protect($content), protect($style), $idp);
    $stmt->execute();
    $save = $stmt->affected_rows;
    $stmt->close();

    if ($save === 1) {
        echo "Save Data";
    } else {
        echo "Failed";
    }
}

?>

