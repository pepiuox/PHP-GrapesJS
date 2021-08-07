<?php include_once 'header.php'; ?>

<div class="container">

    <!-- Registration form -->
    <div class="registrationForm">
        <form action="registration.php" name="registrationform"
              class="form-registration form-inline d-flex justify-content-center" method="post">
            <h3 class="cnt">¡Regístrate!</h3>
            <hr class="colorgraph">
            <label for="username">Usuario<span class="red">*</span>:
            </label> <input type="text" name="username" id="username"
                            placeholder="Username" class="input form-control" autocomplete="off"
                            required autofocus> <label for="email">E-mail<span class="red">*</span>:
            </label> <input type="email" name="email" id="email"
                            placeholder="Email" class="input form-control" autocomplete="off"
                            required><br> <label for="password">Clave<span class="red">*</span>:
            </label> <input type="password" name="password" id="password"
                            placeholder="Password" class="input form-control" autocomplete="off"
                            required> <label for="password2">Confirma la clave<span class="red">*</span>:
            </label> <input type="password" name="password2" id="password2"
                            placeholder="Re-enter password" class="input form-control"
                            autocomplete="off" required><br>

            <!-- If there is an error it will be shown. --> 
            <?php if (!empty($_SESSION['ErrorMessage'])): ?>
                <div class="alert alert-danger alert-container"
                     id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['ErrorMessage']) ?></center></strong>
                    <?php unset($_SESSION['ErrorMessage']); ?>
                </div>
            <?php endif; ?>
            <!-- If user account is created. -->
            <?php if (!empty($_SESSION['SuccessMessage'])): ?>
                <div class="alert alert-success alert-container"
                     id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['SuccessMessage']) ?></center></strong>
                    <?php unset($_SESSION['SuccessMessage']); ?>
                </div>
            <?php endif; ?>

            <input type="submit" name="registration" value="Registrarse"
                   class="btn btn-lg btn-block submit" />

        </form>

    </div>
    <!-- End registrationForm-->

    <!-- URL to login form -->
    <div class="cnt">
        <a href="login.php">�Ya te has registrado? Entre aquí</a>
    </div>


    <!-- Back to main page -->
    <div>
        <a href="index.php">Volver al inicio</a>
    </div>

</div>
<!-- End div -->

