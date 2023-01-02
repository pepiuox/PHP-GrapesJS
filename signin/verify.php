<?php
if (!isset($_SESSION)) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';

    $verify = new UserVerify();
    
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

} else {
    header('Location: ../installer/install.php');
    exit();
}
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition login-page">
    <?php
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit();
    } else {
        ?>
        <?php
        include '../elements/alerts.php';
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
        include '../elements/footer.php';
    }
    ?>
</body>
</html>
