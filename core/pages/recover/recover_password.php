<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$login = new UsersClass();
$forgotpass = new userForgot();
$menu = 1;
if ($login->isLoggedIn() === true) {
    ?>
         <script>
            window.location.replace("<?php echo SITE_PATH; ?>profile/userprofile");
                </script>
    <?php
} else {
    ?>
                <div class="hold-transition login-page">
                    <?php
                    include_once 'views/recoverPassword.php';
                    ?>
                </div>
    <?php
}
?>
    
      
