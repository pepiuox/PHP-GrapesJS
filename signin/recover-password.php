<?php
if (!isset($_SESSION)) {
	session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
	require_once '../config/dbconnection.php';
	require_once 'Autoload.php';

	$login = new UserClass();
	$forgotpass = new UserForgot();
} else {
	header('Location: ../installer/install.php');
	exit();
}
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition login-page">
	<?php
	if ($login->isLoggedIn() === true) {
		header('Location: ../users/profile.php');
		exit();
	} else {
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
					<p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

					<form action="recover-password.php" method="post">
						<div class="input-group mb-3">
							<input type="password" name="password" id="password" class="form-control" placeholder="Password">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" name="password2" id="password2" class="form-control" placeholder="Confirm Password">
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<button type="submit" name="newpassword" class="btn btn-primary btn-block">Recover password</button>
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
		include '../../elements/footer.php';
	}
	?>
</body>
</html>
