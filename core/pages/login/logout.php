<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

$login = new UsersClass();
if ($login->isLoggedIn() === true) {
    $login->logout();
} else {
   ?>
<script>
    window.location.href = "<?php echo SITE_PATH; ?>"; 
</script>
<?php
}
?>
