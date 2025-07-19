<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$login = new UsersClass();
    $verify = new UserVerify();
    if ($login->isLoggedIn() === true) {
         ?>
 <script>
    window.location.replace("<?php echo SITE_PATH; ?>profile/userprofile");
        </script>
<?php
    } else {
        ?>
        <div class="container">
            <div class="row">
                <div class="login-box">
                    <div class="login-logo">
                        <a href="<?php echo SITE_PATH; ?>index.php"><b><?php echo SITE_NAME; ?></b></a>
                    </div>
                    <div class="col-md-12 p-3">   
                        <?php
                        if (isset($_GET['id']) && isset($_GET['code']) && isset($_GET['hash'])) {
                            ?>
                            <h3 class="text-center">You can now activate your account.</h3>
                            <form action="verify.php" method="post">
                                <div class="col-6">
                                    <input type="text" name="id" value="<?php echo $_GET['id']; ?>" readonly="yes" hidden="yes">
                                    <input type="text" name="code" value="<?php echo$_GET['code']; ?>" readonly="yes" hidden="yes">
                                    <input type="text" name="hash" value="<?php echo$_GET['hash']; ?>" readonly="yes" hidden="yes">
                                    <button type="submit" name="bverify" id="bverify" class="btn btn-primary btn-block">Activate Account</button>
                                </div>
                                <div class="col-6">
                                    <p class="text-center">
                                        Click here to <a href="login.php" class="btn btn-primary btn-sm">log in</a>
                                    </p>
                                </div>
                            </form>

                            <?php
                        } else {
                            ?>
                            <h3 class="text-center">There was an error activating your account.</h3>
                            <p class="text-center">
                                Please contact support at <a
                                    href="mailto:<?php echo MAIL_SUPPORT; ?>?Subject=<?php echo SUBJECT_SUPPORT; ?>"
                                    class="link-blue"><?php echo MAIL_SUPPORT; ?></a>.
                            </p> 
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>   
        </div>
        <?php
    }
    ?>

