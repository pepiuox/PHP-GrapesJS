<?php

if (!isset($_SESSION)) {
    session_start();
}

$redirect = '../signin/login.php';
header('Location: ' . $redirect);
exit;
?>
