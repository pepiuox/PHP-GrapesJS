<?php
if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
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
        include 'views/forgotPin.php';
        include '../elements/footer.php';
    }
    ?>

</body>
</html>
