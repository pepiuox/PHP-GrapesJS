<?php
if (!empty($_GET['email']) && !empty($_GET['key']) && !empty($_GET['hash'])) {
    ?>
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
                <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

                <form action="passwordResetForm.php" method="post" class="form-inline d-flex justify-content-center">
                    <div class="input-group mb-3">
                        <input type="text" name="recoveryphrase" id="recoveryphrase" class="form-control" placeholder="Recover phrase">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password2" id="password2" class="form-control" placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="updatePassword" class="btn btn-primary btn-block">Recover password</button>
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
    <?php
} else {
    header('Location: index.php');
}
?>