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
$base = 'http://'.$_SERVER['HTTP_HOST'].'/PHP-GrapesJS/';
require 'function.php';
    ?>
    