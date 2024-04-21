<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$login = new UsersClass();
$forgotpass = new UsersForgot();
$menu = 1;
if ($login->isLoggedIn() === true) {
    ?>
    <script>
                    window.location.replace("<?php echo SITE_PATH; ?>profile/user-profile");
                        </script>
        <?php
    } else {
        ?>
        <div class="hold-transition register-page">
    <?php
    include_once 'views/forgotPassword.php';
    ?>
        </div>
    <?php
}
?>
       
      
