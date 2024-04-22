<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$login = new UsersClass();
$level = new AccessLevel();
if ($login->isLoggedIn() === true) {
?>
        <script>
        window.location.replace("<?php echo SITE_PATH; ?>profile/user-profile");
        </script>
    <?php
} else {
    ?>
        <div class="hold-transition login-page">
    <?php
    /* login-box */
    if (isset($_SESSION["attempt"]) || isset($_SESSION["attempt_again"])) {
        if ($_SESSION["attempt"] === 3 || $_SESSION["attempt_again"] >= 3) {
            include_once "views/attempts.php";
        } else {
            include_once "views/login.php";
        }
    } else {
        include_once "views/login.php";
    }
    ?>
        </div>
    <?php
}
    ?>

