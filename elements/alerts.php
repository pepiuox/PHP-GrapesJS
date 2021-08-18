<div class="container">
    <div class="row pt-2">
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
        <?php } ?>
        <?php if (!empty($_SESSION['AlertMessage'])) { ?>
            <div class="alert alert-danger alert-container" id="alert">
                <strong><center><?php echo htmlentities($_SESSION['AlertMessage']) ?></center></strong>                                       
            </div>
        <?php } ?>
    </div>
</div>
