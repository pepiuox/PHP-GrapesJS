<?php
if (!isset($_SESSION)) {
    session_start();
}

require '../config/dbconnection.php';
require 'Autoload.php';
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    $login = new UserClass();
    $forgotpass = new userForgot();
} else {
    header('Location: ../installer/install.php');
}
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition login-page">

    <?php include '../../elements/footer.php'; ?>
</body>
</html>
