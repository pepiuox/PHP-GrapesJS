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
                    <a href="<?php echo SITE_PATH; ?>index.php" class="nav-link <?php
                    if ($fname === 'index') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard v1</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>index2.php" class="nav-link <?php
                    if ($fname === 'index2') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard v2</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>index3.php" class="nav-link <?php
                    if ($fname === 'index3') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard v3</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="<?php echo SITE_PATH; ?>pages/widgets.php" class="nav-link <?php
            if ($fname === 'widgets') {
                echo 'active';
            }
            ?>">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    Widgets
                    <span class="right badge badge-danger">New</span>
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                    Layout Options
                    <i class="fas fa-angle-left right"></i>
                    <span class="badge badge-info right">6</span>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/layout/top-nav.php" class="nav-link <?php
                    if ($fname === 'top-nav') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Top Navigation</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/layout/top-nav-sidebar.php" class="nav-link <?php
                    if ($fname === 'top-nav-sidebar') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Top Navigation + Sidebar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/layout/boxed.php" class="nav-link <?php
                    if ($fname === 'boxed') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Boxed</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/layout/fixed-sidebar.php" class="nav-link <?php
                    if ($fname === 'fixed-sidebar') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Fixed Sidebar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/layout/fixed-topnav.php" class="nav-link <?php
                    if ($fname === 'fixed-topnav') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Fixed Navbar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/layout/fixed-footer.php" class="nav-link <?php
                    if ($fname === 'fixed-footer') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Fixed Footer</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/layout/collapsed-sidebar.php" class="nav-link <?php
                    if ($fname === 'collapsed-sidebar') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Collapsed Sidebar</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                    Charts
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/charts/chartjs.php" class="nav-link <?php
                    if ($fname === 'chartjs') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>ChartJS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/charts/flot.php" class="nav-link <?php
                    if ($fname === 'flot') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Flot</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/charts/inline.php" class="nav-link <?php
                    if ($fname === 'inline') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Inline</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tree"></i>
                <p>
                    UI Elements
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/general.php" class="nav-link <?php
                    if ($fname === 'general') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>General</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/icons.php" class="nav-link <?php
                    if ($fname === 'icons') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Icons</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/buttons.php" class="nav-link <?php
                    if ($fname === 'buttons') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Buttons</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/sliders.php" class="nav-link <?php
                    if ($fname === 'sliders') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Sliders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/modals.php" class="nav-link <?php
                    if ($fname === 'modals') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Modals & Alerts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/navbar.php" class="nav-link <?php
                    if ($fname === 'navbar') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Navbar & Tabs</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/timeline.php" class="nav-link <?php
                    if ($fname === 'timeline') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Timeline</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/UI/ribbons.php" class="nav-link <?php
                    if ($fname === 'ribbons') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ribbons</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-edit"></i>
                <p>
                    Forms
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/forms/general.php" class="nav-link <?php
                    if ($fname === 'general') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>General Elements</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/forms/advanced.php" class="nav-link <?php
                    if ($fname === 'advanced') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Advanced Elements</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/forms/editors.php" class="nav-link <?php
                    if ($fname === 'editors') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Editors</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/forms/validation.php" class="nav-link <?php
                    if ($fname === 'validation') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Validation</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>
                    Tables
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/tables/simple.php" class="nav-link <?php
                    if ($fname === 'simple') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Simple Tables</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/tables/data.php" class="nav-link <?php
                    if ($fname === 'data') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>DataTables</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/tables/jsgrid.php" class="nav-link <?php
                    if ($fname === 'jsgrid') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>jsGrid</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-header">EXAMPLES</li>
        <li class="nav-item">
            <a href="<?php echo SITE_PATH; ?>pages/calendar.php" class="nav-link <?php
            if ($fname === 'calendar') {
                echo 'active';
            }
            ?>">
                <i class="nav-icon far fa-calendar-alt"></i>
                <p>
                    Calendar
                    <span class="badge badge-info right">2</span>
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo SITE_PATH; ?>pages/gallery.php" class="nav-link <?php
            if ($fname === 'gallery') {
                echo 'active';
            }
            ?>">
                <i class="nav-icon far fa-image"></i>
                <p>
                    Gallery
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon far fa-envelope"></i>
                <p>
                    Mailbox
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/mailbox/mailbox.php" class="nav-link <?php
                    if ($fname === 'mailbox') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Inbox</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/mailbox/compose.php" class="nav-link <?php
                    if ($fname === 'compose') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Compose</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/mailbox/read-mail.php" class="nav-link <?php
                    if ($fname === 'read-mail') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Read</p>
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
                        <a href="' . SITE_PATH . $plink . '" class="nav-link ';
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
                    <a href="<?php echo SITE_PATH; ?>pages/examples/login.php" class="nav-link <?php
                    if ($fname === 'login') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Login</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/register.php" class="nav-link <?php
                    if ($fname === 'register') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Register</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/forgot-password.php" class="nav-link <?php
                    if ($fname === 'forgot-password') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Forgot Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/recover-password.php" class="nav-link <?php
                    if ($fname === 'recover-password') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Recover Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/lockscreen.php" class="nav-link <?php
                    if ($fname === 'lockscreen') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Lockscreen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/legacy-user-menu.php" class="nav-link <?php
                    if ($fname === 'legacy-user-menu') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Legacy User Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/language-menu.php" class="nav-link <?php
                    if ($fname === 'language-menu') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Language Menu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/404.php" class="nav-link <?php
                    if ($fname === '404') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Error 404</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/500.php" class="nav-link <?php
                    if ($fname === '500') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Error 500</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/pace.php" class="nav-link <?php
                    if ($fname === 'pace') {
                        echo 'active';
                    }
                    ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pace</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo SITE_PATH; ?>pages/examples/blank.php" class="nav-link <?php
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