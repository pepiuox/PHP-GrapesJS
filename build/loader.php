<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION)) {
    session_start();
}
$pathInRoot = '../../core/';
$connfile = $pathInRoot.'config/dbconnection.php';
if (!file_exists($connfile)) {
     header("Location: $pathInRoot/installer/install.php");
    exit;
}
include_once $connfile;
include_once 'Autoload.php';
?>
