<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

if (!isset($_SESSION)) {
    session_start();
}

$currentDate = new DateTime();
$connfile = 'config/dbconnection.php';
if (!file_exists($connfile)) {

    $_SESSION['PathInstall'] = "http://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];
    header('Location: installer/install.php');
    exit();
} else {
    $page = $_SERVER['PHP_SELF'];

    require 'config/dbconnection.php';
    require 'classes/UserClass.php';
    require 'classes/GetVisitor.php';

    $login = new UserClass();
    $timestamp = $currentDate->format('Y-m-d H:i:s');
    $visitor = new GetVisitor($timestamp);

    require 'start.php';
}
?>
