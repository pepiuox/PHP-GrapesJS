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

define("URL", dirname(__DIR__));
$source = str_replace('\\', '/', URL);

require_once $source.'/core/view.php';
?>
