<div class="container">
    <div class="row pt-2">
        <?php if (!empty($_SESSION['SuccessMessage'])) { ?>
            <div class="alert alert-success alert-container" id="alert">
                <strong><?php echo htmlentities($_SESSION['SuccessMessage']) ?></strong>
                <?php unset($_SESSION['SuccessMessage']); ?>
                <meta http-equiv="Refresh" content="10; url='<?php echo $alertpg; ?>'" />
            </div>
        <?php } ?>
        <?php if (!empty($_SESSION['ErrorMessage'])) { ?>
            <div class="alert alert-warning alert-container" id="alert">
                <strong><?php echo htmlentities($_SESSION['ErrorMessage']) ?></strong>
                <?php unset($_SESSION['ErrorMessage']); ?>
                <meta http-equiv="Refresh" content="10; url='<?php echo $alertpg; ?>'" />
            </div>
        <?php } ?>
        <?php if (!empty($_SESSION['AlertMessage'])) { ?>
            <div class="alert alert-danger alert-container" id="alert">
                <strong><?php echo htmlentities($_SESSION['AlertMessage']) ?></strong>
                <?php unset($_SESSION['AlertMessage']); ?>
                <meta http-equiv="Refresh" content="10; url='<?php echo $alertpg; ?>'" />
            </div>
        <?php } ?>
        <?php if (!empty($_SESSION['error'])) { ?>
            <div class="alert alert-danger alert-container" id="alert">
                <strong>
                    <center><?php echo $_SESSION['error']; ?></center>
                </strong>
                <?php unset($_SESSION['error']); ?>
                <meta http-equiv="Refresh" content="10; url='<?php echo $alertpg; ?>'" />
            </div>
        <?php } ?>
        <?php
        if (!empty($_SESSION['success'])) {
            ?>
            <div class="alert alert-success alert-container" id="alert">
                <strong>
                    <center><?php echo $_SESSION['success']; ?></center>
                </strong>
                <?php unset($_SESSION['success']); ?>
                <meta http-equiv="Refresh" content="10; url='<?php echo $alertpg; ?>'" />
            </div>
        <?php } ?>
        <?php
        if (!empty($_SESSION['FullSuccess'])) {
            ?>
            <div class="alert alert-success alert-container" id="alert">
                <strong>
                    <center><?php echo $_SESSION['FullSuccess']; ?></center>
                </strong>
                <?php unset($_SESSION['FullSuccess']); ?>
            </div>
        <?php } ?>
    </div>
</div>
