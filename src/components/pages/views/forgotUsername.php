<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo SITE_PATH; ?>"><b><?php echo SITE_NAME; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg"><?php echo $lang[$fname]['box_msg']; ?></p>

            <form action="forgot_username.php" method="post" class="form-inline d-flex justify-content-center">
				<div class="input-group mb-3">
                        <input type="text" name="recoveryphrase" id="recoveryphrase" class="form-control" placeholder="Recover phrase">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo $lang['email']; ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" name="forgotUsername" id="forgotUsername" class="btn btn-primary btn-block">Send my Username</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
             <?php
include_once 'options.php';
?>	
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
