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
                    <a href="<?php echo $base; ?>admin/index.php" class="nav-link <?php
                    if ($fname === 'index') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>admin/dashboard.php?cms=siteconf" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Site configuration                  
                        </p>
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
                        <a href="' . $base . $plink . '" class="nav-link ';
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
            <a href="<?php echo $base; ?>admin/dashboard.php?cms=pagelist" class="nav-link">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    Page list                    
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo $base; ?>admin/dashboard.php?cms=addpage" class="nav-link">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>
                    Add page                   
                </p>
            </a>
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
                    <a href="<?php echo $base; ?>pages/examples/login.php" class="nav-link <?php
                    if ($fname === 'login') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Login</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/register.php" class="nav-link <?php
                    if ($fname === 'register') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Register</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/forgot-password.php" class="nav-link <?php
                    if ($fname === 'forgot-password') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Forgot Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/recover-password.php" class="nav-link <?php
                    if ($fname === 'recover-password') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Recover Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/lockscreen.php" class="nav-link <?php
                    if ($fname === 'lockscreen') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Lockscreen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/legacy-user-menu.php" class="nav-link <?php
                    if ($fname === 'legacy-user-menu') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Legacy User Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/language-menu.php" class="nav-link <?php
                    if ($fname === 'language-menu') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Language Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/404.php" class="nav-link <?php
                    if ($fname === '404') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Error 404</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/500.php" class="nav-link <?php
                    if ($fname === '500') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Error 500</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/pace.php" class="nav-link <?php
                    if ($fname === 'pace') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pace</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>pages/examples/blank.php" class="nav-link <?php
                    if ($fname === 'blank') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Blank Page</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>starter.php" class="nav-link <?php
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
        <li class="nav-header">MISCELLANEOUS</li>
        <li class="nav-item">
            <a href="https://adminlte.io/docs/3.0/" class="nav-link">
                <i class="nav-icon fas fa-file"></i>
                <p>Documentation</p>
            </a>
        </li>
        <li class="nav-header">MULTI LEVEL EXAMPLE</li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Level 1</p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Level 1
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Level 2</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            Level 2
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Level 3</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Level 3</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>Level 3</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Level 2</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Level 1</p>
            </a>
        </li>
        <li class="nav-header">LABELS</li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-danger"></i>
                <p class="text">Important</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-warning"></i>
                <p>Warning</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-info"></i>
                <p>Informational</p>
            </a>
        </li>
    </ul>
</nav>
