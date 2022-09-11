<div class="container-fluid">
    <div class="row">
        <?php
        $form = new Form();
        $process = new AdminProcess();
        /**
         * The user is already logged in, not allowed to register.
         */
        if (isset($_SESSION['regsuccess'])) {
            /* Registration was successful */
            if ($_SESSION['regsuccess']) {
                echo "<h1>Registered!</h1>";
                if (EMAIL_WELCOME) {
                    echo "<p>Gracias <b>" . $_SESSION['reguname'] . "</b>, you have been sent a confirmation email which should be arriving shortly.  Please confirm your registration before you continue.<br />Volver a <a href='index.php'>Principal</a></p>";
                } else {
                    echo "<p>Thank you <b>" . $_SESSION['reguname'] . "</b>, your information has been added to the database, "
                    . "you may now <a href=\"index.php\">log in</a>.</p>";
                }
            }
            /* Registration failed */ else {
                echo "<h1>Registration Failed</h1>";
                echo "<p>We're sorry, but an error has occurred and your registration for the username <b>" . $_SESSION['reguname'] . "</b>, "
                . "could not be completed.<br />Please try again at a later time.</p>";
            }
            unset($_SESSION['regsuccess']);
            unset($_SESSION['reguname']);
        }
        /**
         * The user has not filled out the registration form yet.
         * Below is the page with the sign-up form, the names
         * of the input fields are important and should not
         * be changed.
         */ else {
            ?>

            <h1>Register</h1>
            <?php
            if ($form->num_errors > 0) {
                echo "<td><font size=\"2\" color=\"#ff0000\">" . $form->num_errors . " error(es) econtrados</font></td>";
            }
            ?>
            <div id="register">
                <form action="process.php" method="POST">
                    <label class="col-form-label">Name: </label><p><input class="form-control" type="text" name="name" maxlength="30" value="<?php echo $form->value("name"); ?>"><?php echo $form->error("name"); ?></p>
                    <label class="col-form-label">Username: </label><p><input class="form-control" type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"><?php echo $form->error("user"); ?></p>
                    <label class="col-form-label">Password: </label><p><input class="form-control" type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"><?php echo $form->error("pass"); ?></p>
                    <label class="col-form-label">Email: </label><p><input class="form-control" type="text" name="email" maxlength="50" value="<?php echo $form->value("email"); ?>"><?php echo $form->error("email"); ?></p>
                    <p><input type="hidden" name="subjoin" value="1"><input class="form-control" type="submit" value="Join!"></p>
                    <p><a href="index.php">[Back to Main]</a></p>
                </form>
            </div>
            <?php
        }
        ?>
    </div>
</div> 
