<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../config/dbconnection.php';
require_once 'Autoload.php';
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    $login = new UserClass();
    $forgotpass = new userForgot();
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
        include 'views/forgotPassword.php';
        include '../elements/footer.php';
    }
    ?>

</body>
</html>
