<div class="container">
    <!-- Login form -->
    <div class="loginForm">
        <form  action="login.php" name="loginform" class="form-login form-inline d-flex justify-content-center" method="post">
            <h3 class="cnt">!Bienvenido nuevamente!</h3>
            <hr class="colorgraph">
            
            <label for="username">Usuario:</label>
            <input type="text" name="username" id="username" placeholder="Usuario" class="input form-control" autocomplete="off" required autofocus>
            <label for="password">Clave:</label>
            <input type="password" name="password" id="password" placeholder="Clave" class="input form-control" autocomplete="off" required>
            <a href="forgot.php">¿Olvidaste tu Clave?</a><br><br>
            
            <!-- If there is an error it will be shown. --> 
            <?php if(!empty($_SESSION['ErrorMessage'])): ?>
                <div class="alert alert-danger alert-container" id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['ErrorMessage']) ?></center></strong>
                    <?php unset($_SESSION['ErrorMessage']); ?>
                </div>
            <?php endif; ?>
            
            <input type="submit"  name="login" value="Ingresar" class="btn btn-lg btn-block submit" /> 
            
        </form>

    </div>  <!-- End Login Form-->
 
<!-- URL to registration form -->
<div class="cnt"><a href="register.php">¿No tengo una cuenta? Crear una</a></div>

<!-- Back to main page -->  
<div class="cnt gray"><a href="index.php">Retornar al inicio</a></div>  
  
</div>
<!-- End div -->