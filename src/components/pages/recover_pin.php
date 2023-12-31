<div class="hold-transition login-page">
    <?php
        $login = new UsersClass();
    $forgotpass = new userForgot();
    $menu = 1;
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit;
    } else {       
        include_once 'views/recoverPin.php';
    }
    ?>
</div>
     
