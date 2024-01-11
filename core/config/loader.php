<?php
$connfile = "../core/config/dbconnection.php";
if (!file_exists($connfile)) {
    $_SESSION["PathInstall"] = "http://{$_SERVER["HTTP_HOST"]}/";
    header("Location: ../core/application/installer/install.php");
    exit;
}
include_once $connfile;
include_once "Autoload.php";
?>
