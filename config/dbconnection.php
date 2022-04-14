<?php

include 'error_report.php';
include 'Database.php';

$link = new Database();
$conn = $link-> MysqliConnection();

require 'function.php';
require 'define.php';

if (!empty(SITE_PATH)) {
    $base = SITE_PATH;
} else {
    $base = 'http://localhost:130/';
}

$fname = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'];
?>
    
