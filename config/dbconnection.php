<?php

include 'error_report.php';
include 'Database.php';

$link = new Database();
$conn = $link->MysqliConnection();

require 'function.php';
include 'define.php';

if (!empty(SITE_PATH)) {
    $_SESSION['base'] =  SITE_PATH;
} else {
    $_SESSION['base'] =  'http://localhost:130/';
}

$fname = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'];
?>
    
