
	<div class="hold-transition register-page">
    <?php
    $login = new UsersClass();
    $forgotpass = new UsersForgot();
    $menu = 1;
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit;
    } else {
        include_once 'views/forgotPassword.php';
    }
    ?>
       </div>
      
