<?php
    $login = new UserClass();
    $check = new CheckValidUser();
    $level = new AccessLevel();
    $vp = new DashboardRoutes();
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

$vpages = $vp->vPages($cms);
$w = '';

    if ($login->isLoggedIn() === true && $level->levels() === 9) {
        ?>
              
            <!-- Navbar -->
            <?php include 'elements/navbar.php'; ?>
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
                    <?php
                    include_once 'elements/sidenav.php';
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
                    <?php include_once 'elements/alerts.php'; ?>
                    <!-- Main row -->
                    <?php
                    include_once $vp->ViewIncludes($cms);
                    ?>                        
                </section>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
            <!-- /.content -->
            <!-- /.content-wrapper -->
            <?php
            include_once 'elements/footprint.php';
            ?>
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        
        <!-- ./wrapper -->
        <?php
    } else {
        ?>
        <script>
				  
window.location.href = "<?php echo SITE_PATH; ?>"; 

		</script>
                <?php
    }
    ?>

