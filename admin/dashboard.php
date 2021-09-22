<?php
if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';

    $login = new UserClass();
    $check = new CheckValidUser();
    $level = new AccessLevel();
} else {
    header('Location: ../installer/install.php');
    exit();
}
if (isset($_GET['cms']) && !empty($_GET['cms'])) {
    $cms = $_GET['cms'];
} else {
    $cms = '';
}

$vpages = '';
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    if ($login->isLoggedIn() === true && $level->levels() === 9) {
        ?>
        <div class="wrapper">        
            <!-- Navbar -->
            <?php include '../elements/navbar.php'; ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="index.php" class="brand-link">
                    <?php
                    $logo = IMG_PAGE;
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
                    <?php
                    include '../elements/sidenav.php';
                    ?>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <?php
                                if ($cms == 'pagelist') {
                                    $vpages = 'Page List';
                                } elseif ($cms == 'addpage') {
                                    $vpages = 'Add Page';
                                } elseif ($cms == 'editpage') {
                                    $vpages = 'Edit Page';
                                } elseif ($cms == 'deletepage') {
                                    $vpages = 'Delete Page';
                                } elseif ($cms == 'siteconf') {
                                    $vpages = 'Site Definitions';
                                } elseif ($cms == 'menu') {
                                    $vpages = 'Menu Template';
                                } elseif ($cms == 'plugins') {
                                    $vpages = 'Plugins App';
                                } elseif ($cms == 'crud') {
                                    $vpages = 'CRUD';
                                } elseif ($cms == 'querybuilder') {
                                    $vpages = 'Query Builder';
                                } elseif ($cms == 'table_config') {
                                    $vpages = 'Table Config';
                                } else {
                                    $vpages = 'Dashboard';
                                }
                                ?>
                                <h1 class="m-0 text-dark"><?php echo $vpages; ?></h1>

                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item active"><?php echo $vpages; ?></li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->
                <!-- Main content -->
                <section class="content">
                    <?php include '../elements/alerts.php'; ?>
                    <!-- Main row -->
                    <?php
                    if ($cms == 'pagelist') {
                        include 'views/pageList.php';
                    } elseif ($cms == 'addpage') {
                        include 'views/addPage.php';
                    } elseif ($cms == 'editpage') {
                        include 'views/editPage.php';
                    } elseif ($cms == 'deletepage') {
                        include 'views/deletePage.php';
                    } elseif ($cms == 'siteconf') {
                        include 'views/settings.php';
                    } elseif ($cms == 'menu') {
                        include 'views/menu.php';
                    } elseif ($cms == 'plugins') {
                        include 'views/plugins.php';
                    } elseif ($cms == 'users') {
                        include 'admin.php';
                    } elseif ($cms == 'adduser') {
                        include 'adduser.php';
                    } elseif ($cms == 'crud') {
                        include 'views/crud.php';
                    } elseif ($cms == 'querybuilder') {
                        include 'views/querybuilder.php';
                    } elseif ($cms == 'table_config') {
                        include 'views/table_config.php';
                    } elseif ($cms == 'table_manager') {
                        include 'views/table_manager.php';
                    } else {
                        ?>
                        <div class="container-fluid">
                            <!-- Small boxes (Stat box) -->
                            <div class="row">
                                <div class="col-lg-3 col-6">                            
                                    <!-- small box -->
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <?php
                                            echo '<h3>' . numusers() . '</h3>';
                                            ?>
                                            <p>User Registrations</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag"></i>
                                        </div>
                                        <a href="dashboard.php?cms=users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>53<sup style="font-size: 20px">%</sup></h3>

                                            <p>Bounce Rate</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-stats-bars"></i>
                                        </div>
                                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <?php
                                            echo '<h3>' . numpages() . '</h3>';
                                            ?>
                                            <p>Number of pages and Contents </p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person-add"></i>
                                        </div>
                                        <a href="dashboard.php?cms=pagelist" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <?php
                                            echo '<h3>' . numvisitor() . '</h3>';
                                            ?>

                                            <p>Unique Visitors</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-pie-graph"></i>
                                        </div>
                                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <?php
                    }
                    ?>                        
                </section>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
            <!-- /.content -->
            <!-- /.content-wrapper -->
            <?php
            include '../elements/footprint.php';
            ?>
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
        <?php
    } else {
        header('Location: ../signin/login.php');
        exit();
    }
    include '../elements/footer.php';
    ?>
</body>
</html>
