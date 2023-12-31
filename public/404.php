<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//

if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../core/config/dbconnection.php';
if (!file_exists($connfile)) {
    header('Location: installer/install.php');
    exit;
} 
require_once $connfile;
    require_once 'Autoload.php';
    $login = new UsersClass();
    $forgotpass = new UsersForgot();
    $menu = 1;
?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
            <?php
            require_once '../core/elements/metalink.php';
            ?>
            <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>" />
            <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />
            <meta name="classification" content="<?php echo SITE_CLASSIFICATION; ?>" />
            <title>This page does not exist.</title>
            <link href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" type="text/css" href="<?php echo SITE_PATH; ?>assets/plugins/fontawesome/css/fontawesome.min.css" />
			<link rel="stylesheet" href="<?php echo SITE_PATH; ?>assets/plugins/adminlte/css/adminlte.min.css">
            <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?php echo SITE_PATH; ?>assets/plugins/popper/popper.min.js" type="text/javascript"></script> 
            <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
            <script>
                jQuery.htmlPrefilter = function (html) {
                    return html;
                };
            </script>
            <link rel="stylesheet" type="text/css" href="<?php echo SITE_PATH; ?>assets/css/menu.css" />
            <script src="<?php echo SITE_PATH; ?>assets/js/menu.js" type="text/javascript"></script>
            <style>
                #wrapper, .container-fluid{
                    margin:0;
                    padding:0;
                }

            </style>
        </head>
        <body>
         <div id="wrapper">
	<?php
    	require_once '../core/elements/menu.php';
	?>
	<div class="hold-transition login-page">
               <div class="login-box">
    <div class="login-logo">
			    <div class="card text-center">
					  <div class="card-body login-card-body">			   
			  <h4 class="card-title"> This page not exist</h4>
			  <p class="card-text">You will be directed to the main page.</p>	
		    </div>
			    </div>
</div>			    
                </div>
            </div>
	 </div>
			<script>
				  setTimeout(function(){ 
window.location.href = "<?php echo SITE_PATH; ?>"; 
}, 5 * 1000);
		</script>
	    <?php
include '../core/elements/footer.php';
?>
        </body>
    </html>
    
