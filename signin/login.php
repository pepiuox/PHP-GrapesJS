
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
