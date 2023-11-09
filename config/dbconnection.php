<?php

include 'error_report.php';
include 'Database.php';
$link = new Database();
$conn = $link->MysqliConnection();
require_once 'Routers.php';
require_once 'function.php';
include_once 'define.php';

$protocol =
        (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
        $_SERVER["SERVER_PORT"] == 443
            ? "https://"
            : "http://";

if (!empty(SITE_PATH)) {
    $siteinstall = SITE_PATH;
} else {
    $base = $protocol . $_SERVER['HTTP_HOST'] . '/';
}
$fname = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'];
?>
    
