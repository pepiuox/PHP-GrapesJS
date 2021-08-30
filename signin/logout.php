<?php

if (!isset($_SESSION)) {
    session_start();
}
include '../config/checkfile.php';
require '../config/dbconnection.php';
require 'Autoload.php';

$login = new UserClass();

if ($login->isLoggedIn() === true) {
    $login->logout();
} else {
    header('Location: index.php');
}
