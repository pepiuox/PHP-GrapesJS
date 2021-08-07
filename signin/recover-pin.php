<?php
if (!isset($_SESSION)) {
    session_start();
}
require '../config/dbconnection.php';
require 'Autoload.php';

$login = new UserClass();
$forgotpass = new userForgot();
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
            <a href="<?php echo $base; ?>index2.php"><b><?php echo SITE_NAME; ?></b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You are only one step a way from your new PIN, recover your PIN now.</p>

                <form action="login.php" method="post">
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Change password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="<?php echo $base; ?>signin/login.php">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <?php include '../elements/footer.php'; ?>
</body>
</html>
