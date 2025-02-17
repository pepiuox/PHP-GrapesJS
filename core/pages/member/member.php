<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$login = new UsersClass();
$level = new AccessLevel();
$menu = 1;
if ($login->isLoggedIn() === true) {
    ?>
            <script>
    window.location.replace("<?php echo SITE_PATH; ?>profile/userprofile");
    </script>
    <?php
} else {
    require_once '../elements/menu';
    include_once '../elements/alerts';
    ?>
    <div class="hold-transition login-page">
            <div class="login-box">
                <div class="login-logo">
                    <a href="<?php echo SITE_PATH; ?>"><?php echo SITE_NAME; ?></a>
                </div>
                <!-- /.login-logo -->
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">Sign in to start your session</p>

                        <form action="login" method="post">
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
                            <a href="<?php echo SITE_PATH; ?>signin/forgot-password">I forgot my password</a>
                        </p>
                        <p class="mb-0">
                            <a href="<?php echo SITE_PATH; ?>signin/register" class="text-center">Register a new account</a>
                        </p>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div>
            <!-- /.login-box -->
    </div>
<?php } ?>
	
