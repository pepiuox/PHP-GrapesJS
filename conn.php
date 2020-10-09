<?php

define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'truelove');
define('DBNAME', 'demosite');
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

/* If connection fails for some reason */
if ($conn->connect_error) {
    die('Error, Database connection failed: (' . $conn->connect_errno . ') ' . $conn->connect_error);
}
$base = 'http://' . $_SERVER['HTTP_HOST'] . '/PHP-GrapesJS/';

require 'function.php';
$define = $conn->query("SELECT * FROM config");
while ($def = $define->fetch_array()) {
    $type_name = $def['type_name'];
    $value = $def['value'];
    $vars[] = "define('" . $type_name . "', '" . $value . "');" . "\n";
}
$definefiles = 'define.php';
if (!file_exists($definefiles)) {
    $ndef = '<?php' . "\n";
    $ndef .= implode(" ", $vars);
    $ndef .= '?>' . "\n";
    file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
}
require 'define.php';
?>
    
