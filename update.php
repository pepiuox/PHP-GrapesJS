<?php

/* Update Page */
include 'conn.php';

function protect($str) {
    global $conn;
    $str = trim($str);
    $str = stripslashes($str);
    $str = htmlentities($str, ENT_QUOTES);
    $str = mysqli_real_escape_string($conn, $str);
    return $str;
}

if (isset($_POST['content'])) {
    $idp = $_POST['idp'];
    $content = $_POST['content'];
    $style = $_POST['style'];

    $sql = "UPDATE page SET  content='" . protect($content) . "', style='" . protect($style) . "' WHERE id='$idp'";
    if ($conn->query($sql) === TRUE) {
        echo "The page has been updated";
    } else {
        echo "Failed";
    }
}
?>
