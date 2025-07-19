<?php
require_once $source . "/core/config/loader.php";

$login = new UsersClass();
$check = new CheckValidUser();
$level = new AccessLevel();
$visitor = new GetVisitor();

if (isset($_GET['cms']) && !empty($_GET['cms'])) {
    $cms = $_GET['cms'];
} else {
    $cms = '';
}

if (isset($_GET['user']) && !empty($_GET['user'])) {
    $user = $_GET['user'];
} else {
    $user = '';
}
?>
<?php include_once $source . '/core/elements/header.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
<?php
if ($login->isLoggedIn() === true) {
?>
            <div class="wrapper">
                <!-- Navbar -->
    <?php include_once $source . '/core/elements/navbar.php'; ?>
                <!-- /.navbar -->

                <!-- Main Sidebar Container -->
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <!-- Brand Logo -->
                    <a href="index.php" class="brand-link">
    <?php
    $logo = SITE_BRAND_IMG;
    if (file_exists($logo)) {
    ?>
                                <img src="<?php echo $logo; ?>" alt="<?php echo SITE_NAME; ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
                                <span class="brand-text font-weight-light"><?php echo SITE_NAME; ?></span>
        <?php
    } else {
        echo SITE_NAME;
    }
        ?>
                    </a>
                    <!-- Sidebar -->
                    <div class="sidebar">                   
                        <!-- Sidebar Menu -->
    <?php include_once $source . '/core/elements/sidenav.php'; ?>
                        <!-- /.sidebar-menu -->
                    </div>
                    <!-- /.sidebar -->
                </aside>
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
    <?php
    if ($user == 'pinfo') {
        $vpages = 'Personal user information';
    } elseif ($user == 'pdetail') {
        $vpages = 'Personal details';
    } elseif ($user == 'sphra') {
        $vpages = 'Security phrase';
    } elseif ($user == 'chpass') {
        $vpages = 'Change of password';
    } elseif ($user == 'chpin') {
        $vpages = 'Security PIN change ';
    } elseif ($user == 'contacts') {
        $vpages = 'Contacts ';
    } else {
        $vpages = 'Profile';
    }
    ?>
                                    <h1 class="m-0 text-dark"><?php echo $vpages; ?></h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="<?php echo SITE_PATH; ?>">Home</a></li>
                                        <li class="breadcrumb-item active"><?php echo $vpages; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div><!-- /.container-fluid -->
                    </section>
                    <!-- Content Main (Page header) -->
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row m-5">
    <?php
    require_once 'views/profile.php';
    ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
    <?php
} else {
    header('Location: ../index.php');
    exit;
}
require_once $source . '/core/elements/footer.php';
    ?>

</body>
</html>



