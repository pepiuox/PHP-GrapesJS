<?php

if (isset($_POST["order"])) {
    include("connect.php");
    $order = explode(",", $_POST["order"]);
    for ($i = 0; $i < count($order); $i++) {
        $sql = "UPDATE items SET priority='" . $i . "' WHERE id=" . $order[$i];
        mysqli_query($conn, $sql) or die("database error:" . mysqli_error($conn));
    }
}
?>
