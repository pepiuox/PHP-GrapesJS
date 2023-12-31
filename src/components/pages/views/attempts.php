<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo SITE_PATH; ?>"><b><?php echo SITE_NAME; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="text-center"><?php echo $lang[$fname]['attempts']; ?></h4>
            <p class="login-box-msg"><?php echo $lang[$fname]['att_msg']; ?></p>
            <form action="login.php" method="post" class="form-inline d-flex justify-content-center">

                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off"
                           required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off"
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
                    <div class="col-12">
                       <button type="submit" name="attempts" id="attempts" class="btn btn-primary btn-block"><?php echo $lang[$fname]['att_btn']; ?></button>
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

