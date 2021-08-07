<?php

// Display errors on screen
ini_set("display_errors", 1);

// Report all errors
error_reporting(E_ALL);

// Report critical errors only
error_reporting(E_ERROR);

// Report critical errors and warnings
error_reporting(E_ERROR | E_WARNING);

// Report all errors, except notices
error_reporting(E_ALL & ~E_NOTICE);

/* (B) TO TURN OFF ALL ERROR REPORTING */
ini_set("display_errors", 0);
error_reporting(0);
?>
