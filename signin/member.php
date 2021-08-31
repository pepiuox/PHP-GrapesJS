<?php
if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
} else {
    header('Location: ../installer/install.php');
}
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition login-page">
    <div class="container">
        <div class="row">
            <?php if (!empty($_SESSION['ErrorMessage'])) { ?>
                <div class="alert alert-danger alert-container" id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['ErrorMessage']) ?></center></strong>
                    <?php unset($_SESSION['ErrorMessage']); ?>
                </div>
            <?php } ?>
            <?php if (!empty($_SESSION['SuccessMessage'])) { ?>
                <div class="alert alert-success alert-container" id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['SuccessMessage']) ?></center></strong>
                    <?php unset($_SESSION['SuccessMessage']); ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="login-box">
        <div class="login-logo">
            <a href="<?php echo $base; ?>index2.php"><?php echo SITE_NAME; ?></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="login.php" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="social-auth-links text-center mb-3">
                    <p>- OR -</p>
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                    </a>
                </div>
                <!-- /.social-auth-links -->

                <p class="mb-1">
                    <a href="<?php echo $base; ?>signin/forgot-password.php">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="<?php echo $base; ?>signin/register.php" class="text-center">Register a new membership</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <script src="<?php echo $base; ?>js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $base; ?>js/bootstrap.min.js" type="text/javascript"></script>        
    <script src="<?php echo $base; ?>js/popper.min.js" type="text/javascript"></script>

</body>
</html>
