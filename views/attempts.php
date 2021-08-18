<?php if (!empty($_SESSION['ErrorMessage'])) { ?>
    <div class="container">
        <div class="row">
            <div class="alert alert-danger alert-container" id="alert">
                <strong><center><?php echo htmlentities($_SESSION['ErrorMessage']) ?></center></strong>
                <?php unset($_SESSION['ErrorMessage']); ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (!empty($_SESSION['SuccessMessage'])) { ?>
    <div class="container">
        <div class="row">
            <div class="alert alert-success alert-container" id="alert">
                <strong><center><?php echo htmlentities($_SESSION['SuccessMessage']) ?></center></strong>
                <?php unset($_SESSION['SuccessMessage']); ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php
if (isset($_SESSION['error'])) {
    ?>
    <div class="container">
        <div class="row">
            <div class="alert alert-danger text-center" id="alert">
                <?php echo $_SESSION['error']; ?>
            </div>
        </div>
    </div>
    <?php
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    ?>
    <div class="container">
        <div class="row">
            <div class="alert alert-success text-center" id="alert">
                <?php echo $_SESSION['success']; ?>
            </div>
        </div>
    </div>
    <?php
    unset($_SESSION['success']);
}
?>
<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo $base; ?>index.php"><b><?php echo SITE_NAME; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Enter your data to login </p>
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

                        <button type="submit" name="attempts" id="attempts" class="btn btn-primary btn-block">Attempts unlock</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <p class="mb-1">
                <a href="<?php echo $base; ?>signin/login.php">Login</a>
            </p>
            <p class="mb-1">
                <a href="<?php echo $base; ?>signin/forgot-password.php">I forgot my password</a>
            </p>
            <p class="mb-1">
                <a href="<?php echo $base; ?>signin/forgot-password.php">I forgot my PIN</a>
            </p>
            <p class="mb-0">
                <a href="<?php echo $base; ?>signin/register.php" class="text-center">Register a new membership</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>

