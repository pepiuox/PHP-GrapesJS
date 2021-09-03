<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    $myTable = '';

    extract($_POST);
    $check_exist_qry = "SELECT * FROM table_config";
    $run_qry = $conn->query($check_exist_qry);
    $total_found = $run_qry->num_rows;
    if ($total_found > 0) {
        $my_value = $run_qry->fetch_assoc();
        $myTable = explode(',', $my_value['table_name']);
    }

    if (isset($submit)) {
        $all_table_value = implode(",", $_POST['tables']);
        if ($total_found > 0) {
            // update
            $upd_qry = "UPDATE table_config SET table_name='" . $all_table_value . "'";
            $conn->query($upd_qry);
            header("Location: dashboard.php?cms=table_config");
            exit();
        } else {
            // insert
            $ins_qry = "INSERT INTO table_config(table_name) VALUES('" . $all_table_value . "')";
            $conn->query($ins_qry);
            header("Location: dashboard.php?cms=table_config");
            exit();
        }
    }
    ?>
    <div class="container">
        <form class="form-horizontal" method="post" action="">
            <div class="col_md_12">
                <?php
                if ($result = $conn->query("SELECT DATABASE()")) {
                    $row = $result->fetch_row();
                    printf("<h4>Default database is %s </h4>.\n", $row[0]);
                    $result->close();
                }
                ?>
                <h3 class="col-md-4 control-label" for="checkboxes">Tables you want to view :</h3>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    $i = 0;
                    $x = 0;

                    $result = $conn->query("SHOW TABLES FROM $row[0]");
                    $tableNames = array();
                    while ($row = mysqli_fetch_row($result)) {
                        $tableNames[] = $row[0];
                    }
                    foreach ($tableNames as $tname) {
                        $remp = str_replace("_", " ", $tname);
                        echo '<div class="checkbox">' . "\n";
                        echo '<label for="checkboxes-' . $i++ . '">';
                        echo '<input type="checkbox" id="checkboxes-' . $x++ . '" name="tables[]" value="' . $tname . '" ';
                        if (!empty($myTable)) {
                            if (in_array($tname, $myTable)) {
                                echo "checked";
                            }
                        }
                        echo '> ';
                        echo ucfirst($remp) . '</label>' . "\n";
                        echo '</div>' . "\n";
                    }
                    ?>
                </div>
                <div class="form-group">
                    <button type="submit" id="submit" name="submit"
                            class="btn btn-primary">
                        <span class="glyphicon glyphicon-plus"></span> View tables 
                    </button>
                </div>
            </div>
        </form>
    </div>
    <?php
} else {
    header("Location: ../signin/login.php");
    exit();
}
?>
