		<?php
include '../elements/alerts.php';

?>
<div class="login-box">
	<div class="login-logo">
		<a href="<?php echo SITE_PATH; ?>index.php"><b><?php echo SITE_NAME; ?></b></a>
	</div>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">Sign in to start your session</p>
			<form action="login.php" method="post" class="form-inline d-flex justify-content-center">

				<div class="input-group mb-3">
					<input type="email" class="form-control" name="email" id="email" placeholder="Email" autocomplete="off"
						   required autofocus>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
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
					<div class="mb-3">
						<div class="icheck-primary">
							<input type="checkbox" id="remember" name="remember" value=”Yes”>
							<label for="remember">
								Remember Me
							</label>
						</div>
					</div>
					<!-- /.col -->
					<div class="mb-3">
						<button type="submit" name="signin" id="signin" class="btn btn-primary btn-block">Sign In</button>
					</div>
					<!-- /.col -->
				</div>
			</form>

			<p class="mb-1">
				<a href="<?php echo SITE_PATH; ?>signin/forgot-password.php">I forgot my password</a>
			</p>
			<p class="mb-1">
				<a href="<?php echo SITE_PATH; ?>signin/forgot-password.php">I forgot my PIN</a>
			</p>
			<p class="mb-0">
				<a href="<?php echo SITE_PATH; ?>signin/register.php" class="text-center">Register a new membership</a>
			</p>
		</div>
		<!-- /.login-card-body -->
	</div>
</div>
