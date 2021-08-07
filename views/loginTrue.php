<div class="container">
    <div class="row">
        <div class="col-md-6 mr-auto">
            <a name="about">
                <h2>Conectado</h2>
            </a>
            <div class="descText text-center">
                <h4>Bienvenido <?php echo ucfirst($_SESSION['username']); ?></h4>
                <hr class="colorgraph">
                <p>
                    Usted ya esta conectado
                </p>  
                <p>
                    <!-- Profile link -->
                    <a href="profile.php">Configuracion de cuenta</a><br>
                    <!-- Back to main page -->
                    <a href="index.php">Volver al inicio</a>
                </p>
            </div>
        </div>
    </div>
</div>
