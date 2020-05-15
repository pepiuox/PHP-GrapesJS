<?php

include 'conn.php';
if (isset($_POST['check'])) {
    $check = $_POST['check'];
    if ($check == 1) {
        $conf = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

        /* If connection fails for some reason */
        if ($conf->connect_error) {
            echo '<div class="alert alert-danger" role="alert">';
            echo '<h3>Error, Database connection failed</h3>
                <h4>Please: first create your DB name in your database</h4>
                    </div>';
            echo '<hr>';
            echo '<div class="alert alert-primary" role="alert">                  
                  <h3>Now click the buttom</h3>';
            echo '<form><h3>Install you tables for your db</h3>';
            echo '<input type="submit" name="install" class="btn btn-primary" value="Install">';
            echo '</form>';
            echo '</div>';
            exit();
        } else {
            echo "<script>
window.setTimeout(function() {
    window.location.href = 'list.php';
}, 3000);
</script>";
            echo '<meta http-equiv="refresh" content="3; url=list.php" />';
            echo '<div class="alert alert-primary" role="alert">'
            . '<h2>Your DB is connected to ' . DBNAME . '</h2>';
            echo '</div>';
        }
    }
}
?>
