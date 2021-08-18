<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

session_start();
/*
  echo 'http://' . $_SERVER['HTTP_HOST'] . '/';
  echo '<br>';
  echo basename(__DIR__);
 */
$currentDate = new DateTime();
$file = 'config/dbconnection.php';
if (file_exists($file)) {
    include 'config/dbconnection.php';
    include 'classes/GetVisitor.php';
    $mypage = $_SERVER['PHP_SELF'];

    $timestamp = $currentDate->format('Y-m-d H:i:s');
    $visitor = new GetVisitor($timestamp);
    $page = $mypage;
    require_once 'start.php';
} else {
    $_SESSION['PathInstall'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: admin/install/install.php');
}
?>
