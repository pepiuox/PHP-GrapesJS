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
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo SITE_PATH; ?>index2.php"><?php echo SITE_NAME; ?></a>
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

                   
                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <a href="<?php echo SITE_PATH; ?>signin/forgot-password.php">I forgot my password</a>
                    </p>
                    <p class="mb-0">
                        <a href="<?php echo SITE_PATH; ?>signin/register.php" class="text-center">Register a new membership</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->
    <?php } ?>
    <script src="<?php echo SITE_PATH; ?>assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo SITE_PATH; ?>assets/js/bootstrap.min.js" type="text/javascript"></script>        
    <script src="<?php echo SITE_PATH; ?>assets/js/popper.min.js" type="text/javascript"></script>

</body>
</html>
