<?php

$connfile = 'http://' . $_SERVER['HTTP_HOST'] . 'config/dbconnection.php';
$host = 'http://' . $_SERVER['HTTP_HOST'];

if (!file_exists($connfile)) {
    header('Location: ' . $host);
    exit();
}
?>