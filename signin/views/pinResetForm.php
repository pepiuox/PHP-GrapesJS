<?php
if (!empty($_GET['email']) && !empty($_GET['key']) && !empty($_GET['hash'])) {
	?>
	<?php
	include '../elements/alerts.php';
	?>
	<div class="login-box">
		<div class="login-logo">
			<a href="<?php echo SITE_PATH; ?>index2.php"><b><?php echo SITE_NAME; ?></b></a>
		</div>
		<!-- /.login-logo -->
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">You are only one step a way from your new PIN, recover your PIN now.</p>

				<form action="pin_reset.php" method="post" class="form-inline d-flex justify-content-center">
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
							<button type="submit" name="updatePIN" class="btn btn-primary btn-block">Recover PIN</button>
						</div>
						<!-- /.col -->
					</div>
				</form>

				<p class="mt-3 mb-1">
					<a href="<?php echo SITE_PATH; ?>signin/login.php">Login</a>
				</p>
			</div>
			<!-- /.login-card-body -->
		</div>
	</div>
	<!-- /.login-box -->
	<?php
} else {
	header('Location: index.php');
}
?>
