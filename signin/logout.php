<?php

if (!isset($_SESSION)) {
    session_start();
}
require '../config/dbconnection.php';
require 'Autoload.php';

$login = new UserClass();

if ($login->isLoggedIn() === true) {
    $login->logout();
} else {
    header('Location: index.php');
}
