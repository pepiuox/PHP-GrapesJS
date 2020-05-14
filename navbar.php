<!-- Static navbar -->
<nav class="navbar navbar-expand-md navbar-light bg-light">
    <a class="navbar-brand" href="<?php echo $base; ?>viewer.php">Web Builder</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">

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
                    echo '<li><a class="dropdown-item" href="builder.php?id=' . $mrow['id'] . '">' . $mrow['title'] . '</a></li>' . "\n";
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
                    <a class="nav-link dropdown-toggle" href="viewer.php?page=' . $row['id'] . '" id="navbarDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        ' . $row['title'] . '
                    </a>' . "\n";
                        echo second($mid);
                        echo '</li>' . "\n";
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="viewer.php?page=' . $row['id'] . '">' . $row['title'] . '</a></li>' . "\n";
                    }
                }
            }

            echo dropdown();
            ?>

        </ul>	
    </div>
</nav>

