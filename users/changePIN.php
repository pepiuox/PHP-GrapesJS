<div class="container">
    <div class="row">
<?php if (!empty($_SESSION['SuccessMessage'])) { ?>
            <div class="alert alert-success alert-container" id="alert">
                <strong><?php echo htmlentities($_SESSION['SuccessMessage']) ?></strong>
                <?php unset($_SESSION['SuccessMessage']); ?>
            </div>
        <?php } ?>
        <?php if (!empty($_SESSION['ErrorMessage'])) { ?>
            <div class="alert alert-danger alert-container" id="alert">
                <strong><?php echo htmlentities($_SESSION['ErrorMessage']) ?></strong>
                <?php unset($_SESSION['ErrorMessage']); ?>
            </div>
        <?php }; ?>
        <?php if (!empty($_SESSION['AlertMessage'])) { ?>
            <div class="alert alert-danger alert-container" id="alert">
                <strong><center><?php echo htmlentities($_SESSION['AlertMessage']) ?></center></strong>                                       
            </div>
        <?php } ?>
    </div>
    <div class="row">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Change to a new PIN.</p>

                <form action="changePIN.php" method="post" class="form-inline d-flex justify-content-center">
                    <div class="input-group mb-3">
                        <input type="text" name="recoveryphrase" id="recoveryphrase" class="form-control" placeholder="Recover phrase">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>                   
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="changePIN" class="btn btn-primary btn-block">Change PIN</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
