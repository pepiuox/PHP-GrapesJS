<div class="hold-transition login-page">
    <?php
     $login = new UsersClass();
     $level = new AccessLevel();
     $forgotuser = new SendData();
    $menu = 1;
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit;
    } else {
        include_once 'views/forgotUsername.php';
    }
    ?>
    </div>
   
