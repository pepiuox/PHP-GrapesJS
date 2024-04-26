<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo SITE_PATH; ?>"><b><?php echo SITE_NAME; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg"><?php echo $lang[$fname]["box_msg"]; ?></p>
            <form action="" method="post" class="form-inline d-flex justify-content-center">

                <div class="input-group mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo $lang[
                        "email"
                    ]; ?>" autocomplete="off"
                           required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="<?php echo $lang[
                        "password"
                    ]; ?>" autocomplete="off"
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="PIN" id="PIN" placeholder="PIN"  maxlength="6" autocomplete="off"
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-key"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
			<div class="form-check form-check-inline icheck-primary">
  <input class="form-check-input border border-primary" type="checkbox" value="Yes" id="remember" name="remember">
  <label class="form-check-label" for="remember">
    <?php echo $lang["remember_me"]; ?>
  </label>
                </div>
                    </div>
                    <!-- /.col -->
                    <div class="mb-3">
                        <button type="submit" name="signin" id="signin" class="btn btn-primary btn-block"><?php echo $lang[
                            "sign_in"
                        ]; ?></button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
<?php include_once $source.'/core/pages/options.php'; ?>	
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
