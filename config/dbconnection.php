
    <?php

require 'error_report.php';
/* Credentials for login system */

define('DBHOST', 'localhost'); // Add your host
define('DBUSER', 'root'); // Add your username
define('DBPASS', 'truelove'); // Add your password
define('DBNAME', 'projectloginsytem'); // Add your database name

/* Credentials for app use another database */
/*
  define('LNKHOST', 'localhost'); // Add your host
  define('LNKUSER', 'root'); // Add your username
  define('LNKDBPASS', 'truelove'); // Add your password
  define('LNKDBNAME', 'projectloginsytem'); // Add your database name
 */
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

/* If connection fails for some reason */
if ($conn->connect_error) {
    die('Error, Database connection failed: (' . $conn->connect_errno . ') ' . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$base = "http://" . $_SERVER['HTTP_HOST'] . "/PHP-GrapesJS/";

$path = "http://" . $_SERVER['SCRIPT_FILENAME'];

$fname = basename($path, ".php");

include 'define.php';
include 'function.php';
?>
