<?php

include '../config/dbconnection.php';
if (isset($_POST['install'])) {
    $install = $_POST['install'];
    if ($install == 1) {
        $query = '';
        $sqlScript = file('sql/page.sql');
        foreach ($sqlScript as $line) {

            $startWith = substr(trim($line), 0, 2);
            $endWith = substr(trim($line), -1, 1);

            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }

            $query = $query . $line;
            if ($endWith == ';') {
                $conn->query($query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query . '</b></div>');
                $query = '';
            }
        }
        echo '<div class="success-response sql-import-response">SQL file imported successfully</div>';
        if ($conn->query($query) === TRUE) {
            echo '<div class="alert alert-primary" role="alert">';
            echo "The tables were installed";
            echo '</div>';
            echo "<script>
window.setTimeout(function() {
    window.location.href = '../add.php';
}, 3000);
</script>";
        } else {
            echo '<div class="alert alert-danger" role="alert">';
            echo "Failed";
            echo '</div>';
        }
    }
}
?>
