<?php

include 'error_report.php';
include 'Database.php';
$link = new Database();
$conn = $link->MysqliConnection();
require 'function.php';
include 'define.php';

if (!empty(SITE_PATH)) {
    $siteinstall = SITE_PATH;
} else {
    $base = 'http://localhost:130/';
}
$fname = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'];
?>
    
