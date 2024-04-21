<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

$login = new UsersClass();
$level = new AccessLevel();
?>
<div class="hold-transition register-page">
<?php
if ($login->isLoggedIn() === true) {
?>
<script>
window.location.replace("<?php echo SITE_PATH; ?>profile/user-profile");
</script>
<?php
} else {
    include_once 'views/register.php';
}
?>
</div>

