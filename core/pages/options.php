<?php
if ($fname != "login") { ?>
<p class="mt-3 mb-1">
                    <a href="<?php echo SITE_PATH; ?>signin/login"><?php echo $lang[
    "ihuser"
]; ?></a>
            </p>
			<?php }
if ($fname != "forgot-username") { ?>
	    <p class="mb-1">
                <a href="<?php echo SITE_PATH; ?>signin/forgot-username"><?php echo $lang[
    "ifguser"
]; ?></a>
            </p>
			<?php }
if ($fname != "forgot-email") { ?>
	    <p class="mb-1">
                <a href="<?php echo SITE_PATH; ?>signin/forgot-email"><?php echo $lang[
    "ifgemail"
]; ?></a>
            </p>
			<?php }
if ($fname != "forgot-password") { ?>
	    <p class="mb-1">
                <a href="<?php echo SITE_PATH; ?>signin/forgot-password"><?php echo $lang[
    "ifgpass"
]; ?></a>
            </p>
			<?php }
if ($fname != "forgot-pin") { ?>
            <p class="mb-1">
                <a href="<?php echo SITE_PATH; ?>signin/forgot-pin"><?php echo $lang[
    "ifgpin"
]; ?></a>
            </p>
			<?php }
if ($fname != "register") { ?>
            <p class="mb-0">
                <a href="<?php echo SITE_PATH; ?>signin/register" class="text-center"><?php echo $lang[
    "nwuser"
]; ?></a>
            </p>
<?php }
?>
