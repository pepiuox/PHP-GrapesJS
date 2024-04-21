<?php
      $login = new UsersClass();
    $forgotpass = new userForgot();
    if ($login->isLoggedIn() === true) {
 ?>
 <script>
    window.location.replace("<?php echo SITE_PATH; ?>profile/user-profile");
        </script>
<?php
    } else {
        include 'views/forgotPin.php';
       
    }
    ?>
