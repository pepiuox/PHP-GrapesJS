<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

session_start();
$file = 'conn.php';
if (file_exists($file)) {
    include 'conn.php';
    $mypage = $_SERVER['PHP_SELF'];
    $page = $mypage;

    require_once 'start.php';
} else {
    header('Location: config.php');
}
?>
