<?php

require_once 'error_report.php';
require_once 'Database.php';
$db = new Database();
$dbdata = $db->PdoConnection();
$conn = $db->MysqliConnection();

require_once 'Routers.php';
require_once 'function.php';
require_once 'define.php';
require_once 'emails.php';

$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
    $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";

if (!empty(SITE_PATH)) {
    $siteinstall = SITE_PATH;
} else {
    $base = $protocol . $_SERVER['HTTP_HOST'] . '/';
}

$fname = basename($_SERVER["REQUEST_URI"]);

$rname = $fname . '.php';
$alertpg = $_SERVER['REQUEST_URI'];

$lang = '';
$lg = '';
if (!isset($_SESSION['translation'])) {
    $lg = 'es';
}
if (!empty($lg)) {
    $_SESSION['translation'] = $lg;
}
if (isset($_SESSION['translation'])) {
    $lg = $_SESSION['translation'];
    require_once "language/lang/" . $lg . ".php";
} else {
    require_once "language/lang/es.php";
}
?>
   
