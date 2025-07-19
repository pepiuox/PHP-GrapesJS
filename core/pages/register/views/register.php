<div class="register-box">
  <div class="register-logo">
    <a href="
			<?php echo SITE_PATH; ?>">
      <b> <?php echo SITE_NAME; ?> </b>
    </a>
  </div>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg"> <?php echo $lang[$fname]['box_msg']; ?> </p>
      <form action="register.php" method="post" class="form-inline d-flex justify-content-center">
        <label for="cspuser" class="col-form-label">Opción de registro</label>
        <div class="input-group mb-3">
          <select id="cspuser" name="cspuser" class="form-select" aria-label="Opciones de registro">
            <option selected>Seleccione una opción</option>
            <option value="clients">Soy cliente</option>
            <option value="services">Soy proveedor de servicios</option>
            <option value="products">Soy proveedor de productos</option>
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fa-solid fa-flag">
                </span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo $lang['username']; ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo $lang['email']; ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $lang['password']; ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password2" id="password2" class="form-control" placeholder="<?php echo $lang['password2']; ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="mb-3">
            <div class="form-check form-check-inline icheck-primary">
              <input class="form-check-input border border-primary" type="checkbox" id="agreeTerms" name="agreeTerms" value="agree">
              <label class="form-check-label" for="agreeTerms">
                <a href="#"> <?php echo $lang['agree_term']; ?> </a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="mb-3">
            <button type="submit" name="cspregister" id="cspregister" class="btn btn-primary btn-block"> <?php echo $lang['btn_register']; ?> </button>
          </div>
          <!-- /.col -->
        </div>
      </form> 
<?php include_once $source.'/core/pages/options.php'; ?>
    </div>
    <!-- /.form-box -->
  </div>
  <!-- /.card -->
</div>
