<div class="hold-transition login-page">
    <?php
        $login = new UsersClass();
    $forgotpass = new UsersForgot();
    $menu = 1;
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit;
    } else {

        include_once '../../elements/footer.php';
    }
    ?>
    </div>
    
