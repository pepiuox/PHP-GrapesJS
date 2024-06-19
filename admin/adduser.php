<div class="container-fluid">
    <div class="row">
        <?php
        $form = new Form();
        $newuser = new NewUser();
        if (isset($_SESSION['regsuccess'])) {
            /* Registration was successful */
            if ($_SESSION['regsuccess']) {
                echo "<h1>Registered!</h1>";
                if (EMAIL_WELCOME) {
                    echo "<p>Thank you <b>" . $_SESSION['username'] . "</b>, you have been sent a confirmation email which should be arriving shortly.  Please confirm your registration before you continue.<br />Volver a <a href='index.php'>Principal</a></p>";
                } else {
                    echo "<p>Thank you <b>" . $_SESSION['username'] . "</b>, your information has been added to the database, "
                    . "you may now <a href=\"index.php\">log in</a>.</p>";
                }
            }
            /* Registration failed */ else {
                echo "<h1>Registration Failed</h1>";
                echo "<p>We're sorry, but an error has occurred and your registration for the username <b>" . $_SESSION['username'] . "</b>, "
                . "could not be completed.<br />Please try again at a later time.</p>";
            }
        }
        /**
         * The user has not filled out the registration form yet.
         * Below is the page with the sign-up form, the names
         * of the input fields are important and should not
         * be changed.
         */ else {
            ?>

            <h1>Add new user</h1>
            <?php
            if ($form->num_errors > 0) {
                echo "<td><font size=\"2\" color=\"#ff0000\">" . $form->num_errors . " error(es) econtrados</font></td>";
            }
            ?>
            <div class="card">
                <div class="card-body register-card-body">
                    <p class="login-box-msg">Add a new user</p>
                    <form method="post" class="form-inline d-flex justify-content-center">
                        <div class="input-group mb-3">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?php echo $form->value("username"); ?>"><?php echo $form->error("username"); ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>                    
                        <div class="input-group mb-3">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $form->value("email"); ?>"><?php echo $form->error("email"); ?>>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" value="<?php echo $form->value("password"); ?>"><?php echo $form->error("password"); ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password2" id="password2" class="form-control" placeholder="Retype password" value="<?php echo $form->value("password2"); ?>"><?php echo $form->error("password2"); ?>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- /.col -->
                            <div class="mb-3">
                                <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>        
            </div>
    <?php
}
?>    
    </div>
</div> 
