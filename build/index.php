<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
// La depuracion visible solo se activa con APP_DEBUG=1.
// Fijar display_errors=1 en produccion filtra rutas absolutas, SQL y credenciales (CWE-209).
$debug = (getenv('APP_DEBUG') === '1');
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
if (!isset($_SESSION)) {
    session_start();
}

$source = str_replace('\\', '/', dirname(__DIR__));
define("URL", $source);

require_once URL.'/core/dispatcher.php';
?>
