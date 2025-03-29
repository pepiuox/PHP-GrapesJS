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

$source = str_replace('\\', '/', dirname(__DIR__));
define("URL", $source);

require_once URL.'/core/view.php';
?>
