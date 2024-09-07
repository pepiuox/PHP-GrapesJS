<?php
if (!isset($_SESSION)) {
	session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
	require_once '../config/dbconnection.php';
	require_once 'Autoload.php';

	$login = new UserClass();
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
		header('Location: ../admin/dashboard.php');
		exit();
	} else {
		/* login-box */
		if (isset($_SESSION['attempt']) || isset($_SESSION['attempt_again'])) {
			if ($_SESSION['attempt'] === 3 || $_SESSION['attempt_again'] >= 3) {
				include 'views/attempts.php';
			} else {
				include 'views/login.php';
			}
		} else {
			include 'views/login.php';
		}
	}
	?>
	<?php require_once '../elements/footer.php'; ?>

</body>
</html>
