<!-- menu -->
<?php
$rmenu = $conn->query("SELECT * FROM menu_options WHERE id='$menu'");
$rmopt = $rmenu->fetch_assoc();
$id_menu = $rmopt['id_menu'];
$fluid = '';
$placement = '';
$aligment = '';
$background = '';
$color = '';

if ($rmopt['fluid'] === 'yes') {
    $fluid = 'container-fluid';
} else {
    $fluid = "container";
}

if ($rmopt['placement'] === 'top') {
    $placement = 'fixed-top';
} elseif ($rmopt['placement'] === 'bottom') {
    $placement = 'fixed-bottom';
} else {
    $placement = 'sticky-top';
}

if ($rmopt['aligment'] === 'start') {
    $aligment = 'justify-content-start';
} elseif ($rmopt['aligment'] === 'center') {
    $aligment = 'justify-content-center';
} else {
    $aligment = 'justify-content-end';
}

if ($rmopt['background'] === 'primary') {
    $background = 'bg-primary';
} elseif ($rmopt['background'] === 'secondary') {
    $background = 'bg-secondary';
} elseif ($rmopt['background'] === 'light') {
    $background = 'bg-light';
} elseif ($rmopt['background'] === 'dark') {
    $background = 'bg-dark';
} elseif ($rmopt['background'] === 'info') {
    $background = 'bg-info';
} elseif ($rmopt['background'] === 'success') {
    $background = 'bg-success';
} elseif ($rmopt['background'] === 'warning') {
    $background = 'bg-warning';
} else {
    $background = 'bg-danger';
}

if ($rmopt['color'] === 'light') {
    $color = 'navbar-light';
} else {
    $color = 'navbar-dark';
}
?>
<nav id="<?php echo $id_menu; ?>" class="navbar navbar-expand-lg <?php echo $background; ?> <?php echo $color; ?>">
    <div class="<?php echo $fluid; ?>">
        <a class="navbar-brand" href="<?php echo SITE_PATH; ?>">
            <?php
            if (file_exists(SITE_BRAND_IMG)) {
                ?>
                <img src="<?php echo SITE_BRAND_IMG; ?>" alt="" width="30" height="24">
            <?php } else { ?>
                <?php echo SITE_NAME; ?>
            <?php } ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"  aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse <?php echo $aligment; ?>" id="main_nav">
            <ul class="navbar-nav dropdown-hover-all">
                <?php

                function getLink($mid) {
                    global $conn;
                    $result = $conn->query("SELECT * FROM pages WHERE id='$mid'");
                    $row = $result->fetch_assoc();
                    return 'builder.php?id=' . $row['id'];
                }

                function dropdown() {
                    global $conn;
                    $mresult = $conn->query("SELECT * FROM pages");
                    while ($mrow = $mresult->fetch_array()) {
                        $parents[] = $mrow['parent'];
                    }
                    $result = $conn->query("SELECT * FROM pages WHERE parent=0");
                    while ($row = $result->fetch_array()) {
                        $mid = $row['id'];
                        $plink = SITE_PATH . $row['link'];

                        if (in_array($row['id'], $parents)) {
                            echo '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" href="' . $plink . '">' . $row['title'] . '</a>' . "\n";
                            echo second($mid, $plink);
                            echo '</li>' . "\n";
                        } else {
                            echo '<li class="nav-item"><a class="nav-link" href="' . $plink . '">' . $row['title'] . '</a></li>' . "\n";
                        }
                    }
                }

                function second($mid, $plink) {
                    global $conn;
                    $result = $conn->query("SELECT * FROM pages");
                    while ($mrow = $result->fetch_array()) {
                        $parents[] = $mrow['parent'];
                    }
                    $mresult = $conn->query("SELECT * FROM pages WHERE parent='$mid'");
                    echo '<ul class="dropdown-menu">' . "\n";
                    while ($row = $mresult->fetch_array()) {
                        $sid = $row['id'];
                        $slink = $plink . '/' . $row['link'];
                        if (in_array($row['id'], $parents)) {
                            echo '<li class="nav-item dropdown dropend"><a class="dropdown-item" href="' . $slink . '">' . $row['title'] . '</a>' . "\n";
                            echo third($sid, $slink);
                            echo '</li>' . "\n";
                        } else {

                            echo '<li><a class="dropdown-item" href="' . $slink . '">' . $row['title'] . '</a></li>' . "\n";
                        }
                    }
                    echo '</ul>' . "\n";
                }

                function third($sid, $plink) {
                    global $conn;
                    $result = $conn->query("SELECT * FROM pages");
                    while ($mrow = $result->fetch_array()) {
                        $parents[] = $mrow['parent'];
                    }
                    $mresult = $conn->query("SELECT * FROM pages WHERE parent='$sid'");
                    echo '<ul class="dropdown-menu dropdown-submenu">' . "\n";
                    while ($row = $mresult->fetch_array()) {
                        $sbid = $row['id'];
                        $slink = $plink . '/' . $row['link'];

                        if (in_array($row['id'], $parents)) {
                            echo '<li><a class="dropdown-item" href="' . $slink . '">' . $row['title'] . '</a>' . "\n";
                            echo third($sbid, $slink);
                            echo '</li>' . "\n";
                        } else {

                            echo '<li><a class="dropdown-item" href="' . $slink . '">' . $row['title'] . '</a></li>' . "\n";
                        }
                    }
                    echo '</ul>' . "\n";
                }

                echo dropdown();
                ?>
            </ul>	
        </div>
    </div>
</nav>

