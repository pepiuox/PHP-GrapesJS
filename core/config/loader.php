<?php

$connserv = "../core/config/server.php";
$connfile = "../core/config/dbconnection.php";
if (!file_exists($connserv)) {
    $_SESSION["PathInstall"] = "http://{$_SERVER["HTTP_HOST"]}/";
    header("Location: ../core/application/installer/install.php");
    die();
}
include_once $connfile;
include_once "Autoload.php";
?>
