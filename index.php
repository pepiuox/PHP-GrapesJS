<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

session_start();

$currentDate = new DateTime();
$file = 'config/dbconnection.php';
if (file_exists($connfile)) {
    include 'config/dbconnection.php';
    include 'classes/GetVisitor.php';
    $mypage = $_SERVER['PHP_SELF'];
    $page = $mypage;

    $timestamp = $currentDate->format('Y-m-d H:i:s');
    $visitor = new GetVisitor($timestamp);

    require_once 'start.php';
} else {
    $_SESSION['PathInstall'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header('Location: installer/install.php');
    exit();
}
?>
