<?php
    $login = new UserClass();
    $forgotpass = new userForgot();
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit;
    } else {
        include 'views/forgotPassword.php'; 
    }
    ?>
