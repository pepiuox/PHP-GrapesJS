<?php

require_once 'error_report.php';
require_once 'Database.php';
$link = new Database();
$conn = $link->MysqliConnection();
require_once 'function.php';
require_once 'define.php';

if (!empty(SITE_PATH)) {
    $siteinstall = SITE_PATH;
} else {
    $base = 'http://localhost:130/';
}
$fname = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'];
?>
    
