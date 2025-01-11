<?php

if ($login->isLoggedIn() === true) {
    $login->logout();
} else {
    header('Location: index.php');
    exit();
}
