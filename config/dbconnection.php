<?php

include 'error_report.php';
include 'Database.php';
$link = new Database();
$conn = $link->MysqliConnection();
require_once 'function.php';
include_once 'define.php';

if (!empty(SITE_PATH)) {
    $siteinstall = SITE_PATH;
} else {
    $base = 'http://' . $_SERVER['HTTP_HOST'] . '/';
}
$fname = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'];
?>
    
