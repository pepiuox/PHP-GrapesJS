
    <?php
    $login = new UserClass();
    $forgotpass = new userForgot();
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit;
    } else {
        ?>
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo SITE_PATH; ?>index2.php"><b><?php echo SITE_NAME; ?></b></a>
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
                        <a href="<?php echo SITE_PATH; ?>signin/login.php">Login</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->
        <?php
       
    }
    ?>

