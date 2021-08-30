<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../config/checkfile.php';
require '../config/dbconnection.php';
require 'Autoload.php';

$login = new UserClass();
$level = new AccessLevel();
$newuser = new newUser();
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition register-page">
    
    <?php include '../views/register.php'; ?>
    <!-- /.register-box -->
    <?php include '../elements/footer.php'; ?>
</body>
</html>
