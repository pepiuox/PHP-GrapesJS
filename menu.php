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
    $fluid = 'container';
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
<nav class="navbar navbar-expand-lg <?php echo $background; ?> <?php echo $color; ?>" id="<?php echo $id_menu; ?>">
    <div class="<?php echo $fluid; ?>">
        <a class="navbar-brand" href="<?php echo $base; ?>"><?php echo SITE_NAME; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse <?php echo $aligment; ?>" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">

                <?php

                function getLink($mid) {
                    global $conn;
                    $result = $conn->query("SELECT * FROM page WHERE id='$mid'");
                    $row = $result->fetch_assoc();
                    return 'builder.php?id=' . $row['id'];
                }

                function second($mid) {
                    global $conn;
                    $mresult = $conn->query("SELECT * FROM page WHERE parent='$mid'");
                    echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">' . "\n";
                    while ($mrow = $mresult->fetch_array()) {
                        echo '<li><a class="dropdown-item" href="' . $mrow['link'] . '">' . $mrow['title'] . '</a></li>' . "\n";
                    }
                    echo '</ul>' . "\n";
                }

                function dropdown() {
                    global $conn;
                    $mresult = $conn->query("SELECT * FROM page");
                    while ($mrow = $mresult->fetch_array()) {
                        $parents[] = $mrow['parent'];
                    }
                    $result = $conn->query("SELECT * FROM page WHERE parent=0");
                    while ($row = $result->fetch_array()) {
                        $mid = $row['id'];
                        if (in_array($row['id'], $parents)) {
                            echo '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="' . $row['link'] . '" id="navbarDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        ' . $row['title'] . '
                    </a>' . "\n";
                            echo second($mid);
                            echo '</li>' . "\n";
                        } else {
                            echo '<li class="nav-item"><a class="nav-link" href="' . $row['link'] . '">' . $row['title'] . '</a></li>' . "\n";
                        }
                    }
                }

                echo dropdown();
                ?>

            </ul>	
        </div>
    </div>
</nav>

