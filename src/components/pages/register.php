<div class="hold-transition register-page">
    <?php
    $login = new UsersClass(); 
    if ($login->isLoggedIn() === true) {
       // header('Location: ../users/profile.php');
		?>
		<script>
			window.location.replace("<?php echo SITE_PATH;?>/users/profile.php");
		</script>
		<?php
    } else {
        include_once 'views/register.php';
    }
    ?>
      </div>
