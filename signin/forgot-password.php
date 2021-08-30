<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../config/checkfile.php';
require '../config/dbconnection.php';
require 'Autoload.php';

$login = new UserClass();
$forgotpass = new userForgot();
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition login-page">
    <?php include '../views/forgotPassword.php'; ?>
    <?php include '../elements/footer.php'; ?>
</body>
</html>
