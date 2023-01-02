<?php

if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';

    $login = new UserClass();

    if ($login->isLoggedIn() === true) {
        if($level->levels() === 5 || $level->levels() === 9){
        header('Location: ../admin/dashboard.php');
        exit();
        }else{
          header('Location: ../users/profile.php');
        exit();  
        }
    } else {

        $redirect = '../signin/login.php';
        header('Location: ' . $redirect);
        exit();
    }
} else {
    header('Location: ../installer/install.php');
    exit();
}
?>