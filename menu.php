<?php
/* Menu Generator */
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"
     id="main_navbar">
    <a class="navbar-brand" href="<?php echo $base; ?>"><img src="<?php echo $base; ?>img/logo.png" style="width: 280px; height:auto;"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php

            function getLink($mid) {
                global $conn;
                $result = $conn->query("SELECT * FROM page WHERE id='$mid'");
                $row = $result->fetch_assoc();
                return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $row['link'] . '/';
            }

            function second($mid) {
                global $conn;
                $mresult = $conn->query("SELECT * FROM page WHERE parent='$mid'");
                echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">' . "\n";
                while ($mrow = $mresult->fetch_array()) {
                    echo '<li><a class="dropdown-item" href="' . getLink($mid) . $mrow['link'] . '">' . $mrow['title'] . '</a></li>' . "\n";
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
                    <a class="nav-link dropdown-toggle" href="http://' . $_SERVER['HTTP_HOST'] . '/' . $row['link'] . '" id="navbarDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        ' . $row['title'] . '
                    </a>' . "\n";
                        echo second($mid);
                        echo '</li>' . "\n";
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="http://' . $_SERVER['HTTP_HOST'] . '/' . $row['link'] . '">' . $row['title'] . '</a></li>' . "\n";
                    }
                }
            }

            echo dropdown();
            ?>
        </ul>
    </div>
</nav>
