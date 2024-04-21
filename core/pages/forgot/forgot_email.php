<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$login = new UsersClass();
$forgotmail = new SendData();
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
    include_once 'views/forgotEmail.php';
    ?>
                        </div>
    <?php
}
    ?>    
