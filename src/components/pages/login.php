<?php
    $login = new UsersClass();
    $level = new AccessLevel();
?>

	<div class="hold-transition login-page">
    <?php if ($login->isLoggedIn() === true) {
        /* header("Location: ../admin/dashboard.php");
        exit;
		  */
		 ?>
		<script>
			window.location.replace("<?php echo SITE_PATH;?>admin/dashboard.php");
		</script>
		<?php
    } else {
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
    } ?>
    </div>
    
