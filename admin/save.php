<?php

/* Save Page */
include '../config/dbconnection.php';

if (isset($_POST['content'])) {
    $idp = $_POST['idp'];
    $tbl = $_POST['tbl'];
    $content = $_POST['content'];
    $style = $_POST['style'];

    $sql = "UPDATE $tbl SET  content='" . protect($content) . "', style='" . protect($style) . "' WHERE id='$idp'";
    if ($conn->query($sql) === TRUE) {
        echo "The $tbl has been updated";
    } else {
        echo "Failed";
    }
}
?>
