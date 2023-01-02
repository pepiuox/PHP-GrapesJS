<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/index.php" class="nav-link <?php
                    if ($fname === 'dashboard') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=siteconf" class="nav-link <?php
                    if ($cms === 'siteconf') {
                        echo 'active';
                    }
                    ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Site Definitions                  
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=menu" class="nav-link <?php
                    if ($cms === 'menu') {
                        echo 'active';
                    }
                    ?>">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            Menu template                  
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=plugins" class="nav-link <?php
                    if ($cms === 'plugins') {
                        echo 'active';
                    }
                    ?>">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>
                            Plugins App                  
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=volunteer" class="nav-link <?php
                    if ($cms === 'volunteer') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-table nav-icon"></i>
                        <p>Volunteer</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=users" class="nav-link <?php
                    if ($cms === 'users') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=adduser" class="nav-link <?php
                    if ($cms === 'adduser') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Add User</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Pages
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <?php
                $pages = $conn->query("SELECT * FROM page");
                while ($page = $pages->fetch_array()) {
                    $plink = $page['link'];
                    $ptitle = $page['title'];
                    echo'<li class="nav-item">                   
                        <a href="' . $base . $plink . '" target="_blank" class="nav-link ';
                    if ($fname === $plink) {
                        echo 'active';
                    }
                    echo '">
                            <i class="far fa-circle nav-icon"></i>
                            <p>' . $ptitle . '</p>
                        </a>
                    </li>
                    ';
                }
                ?>

            </ul>
        </li>
        <li class="nav-item">
            <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=pagelist" class="nav-link <?php
            if ($cms === 'pagelist') {
                echo 'active';
            }
            ?>">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    Page list                    
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=addpage" class="nav-link <?php
            if ($cms === 'addpage') {
                echo 'active';
            }
            ?>">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>
                    Add page                   
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                    Personal info
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>users/profile.php?user=pinfo" class="nav-link <?php
                    if ($user === 'pinfo') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-id-card nav-icon"></i>
                        <p>Personal Info</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>users/profile.php?user=pdetail" class="nav-link <?php
                    if ($user === 'pdetail') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-id-card nav-icon"></i>
                        <p>Personal details</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>users/profile.php?user=sphra" class="nav-link <?php
                    if ($user === 'sphra') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-user-lock nav-icon"></i>
                        <p>Secure Phrase</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>users/profile.php?user=chpass" class="nav-link <?php
                    if ($user === 'chpass') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-key nav-icon"></i>
                        <p>Change password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>users/profile.php?user=chpin" class="nav-link <?php
                    if ($user === 'chpin') {
                        echo 'active';
                    }
                    ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Change PIN                  
                        </p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-database"></i>
                <p>
                    CRUD System       
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">                
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=table_config" class="nav-link <?php
                    if ($cms === 'table_config') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-server nav-icon"></i>
                        <p>Table Config</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=querybuilder&w=select" class="nav-link <?php
                    if ($cms === 'querybuilder') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-calendar-check nav-icon"></i>
                        <p>Query Builder</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/dashboard.php?cms=crud&w=select" class="nav-link <?php
                    if ($cms === 'crud') {
                        echo 'active';
                    }
                    ?>">
                        <i class="fas fa-table nav-icon"></i>
                        <p>CRUD</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon far fa-plus-square"></i>
                <p>
                    Extras
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">                
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/recover-password.php" class="nav-link <?php
                    if ($fname === 'recover-password') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Recover Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/lockscreen.php" class="nav-link <?php
                    if ($fname === 'lockscreen') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Lockscreen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/legacy-user-menu.php" class="nav-link <?php
                    if ($fname === 'legacy-user-menu') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Legacy User Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/language-menu.php" class="nav-link <?php
                    if ($fname === 'language-menu') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Language Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/404.php" class="nav-link <?php
                    if ($fname === '404') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Error 404</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/500.php" class="nav-link <?php
                    if ($fname === '500') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Error 500</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/pace.php" class="nav-link <?php
                    if ($fname === 'pace') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pace</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>admin/blank.php" class="nav-link <?php
                    if ($fname === 'blank') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Blank Page</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>starter.php" class="nav-link <?php
                    if ($fname === 'starter') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Starter Page</p>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</nav>
