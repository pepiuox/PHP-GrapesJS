<?php
/* Add you DB Connection*/
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'truelove');
define('DBNAME', 'empresas');

/* MySQLi Procedural */

$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

/* If connection fails for some reason */
if ($conn->connect_error) {
    die('Error, Database connection failed: ('. $conn->connect_errno .') '. $conn->connect_error);
}
?>
