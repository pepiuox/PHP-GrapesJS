<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$login = new UsersClass();
$check = new CheckValidUser();
$level = new AccessLevel();

if (isset($_GET['user']) && !empty($_GET['user'])) {
    $user = $_GET['user'];
} else {
    $user = '';
}

if ($login->isLoggedIn() === true) {
?>                      
                            <!-- Navbar -->
    <?php include $source . '/core/elements/navbar.php'; ?>
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
    <?php include $source . '/core/elements/sidenav.php'; ?>
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
        $vpages = 'Información personal del usuario';
    } elseif ($user == 'pdetail') {
        $vpages = 'Personal details';
    } elseif ($user == 'sphra') {
        $vpages = 'New recovery phrase';
    } elseif ($user == 'uphra') {
        $vpages = 'Change recovery phrase';
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

                                <!-- Main content -->
                                <section class="content">
    <?php include $source . '/core/elements/alerts.php'; ?>
    <?php
    if ($user == 'pinfo') {
        include 'personalInfo.php';
    } elseif ($user == 'pdetail') {
        include 'infodetails.php';
    } elseif ($user == 'chpass') {
        include 'views/changePassword.php';
    } elseif ($user == 'chpin') {
        include 'views/changePIN.php';
    } elseif ($user == 'sphra') {
        include 'views/recoveryPhrase.php';
    } elseif ($user == 'uphra') {
        include 'views/updatePhrase.php';
    } elseif ($user == 'contacts') {
        include 'contacts.php';
    } else {
        include 'info.php';
    }
    ?>
                                </section>
                                <!-- /.content -->
                            </div>
                            <!-- /.content-wrapper -->
                            <!-- Control Sidebar -->
                            <aside class="control-sidebar control-sidebar-dark">
                                <!-- Control sidebar content goes here -->
                            </aside>
                            <!-- /.control-sidebar -->
                        
                        <!-- ./wrapper -->
                       
    <?php
} else {
    ?>
        }
             <script>
                                window.location.href = "<?php echo SITE_PATH; ?>"; 
                            </script>
    <?php
}
    ?>

