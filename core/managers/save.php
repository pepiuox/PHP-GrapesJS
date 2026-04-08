<?php
$rout = str_replace('\\', '/', dirname(__DIR__));
include_once $rout.'/config/dbconnection.php';
/* Save Page */

if (isset($_POST['idp'])) {
    $idp = $_POST['idp'];
    $tbl = $_POST['tbl'];
    $content = $_POST['content'];
    $style = $_POST['style'];

    $sql = "UPDATE $tbl SET  html_content = ?, css_content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", protect($content), protect($style), $idp);
    $stmt->execute();
    $save = $stmt->affected_rows;
    $stmt->close();

    if ($save === 1) {
        echo "Saved Page";
    } else {
        echo "Failed";
    }
}

?>

