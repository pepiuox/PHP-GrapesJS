<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition register-page">
    <?php
    if ($login->isLoggedIn() === true) {
        header('Location: ../users/profile.php');
        exit();
    } else {
        include 'views/register.php';
        include '../elements/footer.php';
    }
    ?>
</body>
</html>
