<?php
if (!isset($_SESSION)) {
    session_start();
}
require '../config/dbconnection.php';
require 'Autoload.php';

$login = new UserClass();
$level = new AccessLevel();
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition login-page">
    <?php
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
    } else {
        /* login-box */
        if (isset($_SESSION['attempt']) || isset($_SESSION['attempt_again'])) {
            if ($_SESSION['attempt'] === 3 || $_SESSION['attempt_again'] === 3) {
                include '../views/attempts.php';
            }
        } else {
            include '../views/login.php';
        }
    }
    ?>
    <?php require '../elements/footer.php'; ?>

</body>
</html>
