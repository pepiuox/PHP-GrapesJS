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
