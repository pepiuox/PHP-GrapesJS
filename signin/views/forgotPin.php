<?php
include '../elements/alerts.php';
?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo $base; ?>index2.php"><b><?php echo SITE_NAME; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">You forgot your PIN? Here you can easily retrieve a new PIN.</p>

            <form action="forgot-pin.php" method="post" class="form-inline d-flex justify-content-center">
                <div class="input-group mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" name="forgotPIN" id="forgotPIN" class="btn btn-primary btn-block">Request new PIN</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mt-3 mb-1">
                <a href="<?php echo $base; ?>signin/login.php">Login</a>
            </p>
            <p class="mb-0">
                <a href="<?php echo $base; ?>signin/register.php" class="text-center">Register a new membership</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
