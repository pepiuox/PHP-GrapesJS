<?php
if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';

    $login = new UserClass();
    $level = new AccessLevel();
    $newuser = new newUser();
} else {
    header('Location: ../installer/install.php');
}
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition register-page">

    <?php include '../views/register.php'; ?>
    <!-- /.register-box -->
    <?php include '../elements/footer.php'; ?>
</body>
</html>
