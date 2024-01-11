<?php

//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION)) {
    session_start();
}
$path_app = dirname(__DIR__, 2);
$source = str_replace('\\', '/', $path_app);
 
require_once $source.'/core/managers/view.php';
?>
