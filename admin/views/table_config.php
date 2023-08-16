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

    if (isset($_POST['queryb'])) {
        echo '<meta http-equiv="refresh" content="1;url=dashboard.php?cms=column_manager&w=select" />';
    }

    if (isset($_POST['tbmngr'])) {
        echo '<meta http-equiv="refresh" content="1;url=dashboard.php?cms=table_manager&w=list" />';
    }

    if (isset($_POST['submit'])) {
        $all_table_value = implode(",", $_POST['tables']);
        if ($total_found > 0) {
            // update
            $upd_qry = "UPDATE table_config SET table_name='" . $all_table_value . "'";
            $restup = $conn->query($upd_qry);
            if (!$restup) {
                $_SESSION['ErrorMessage'] = 'There was an error updating.';
            } else {
                $_SESSION['SuccessMessage'] = 'Was updated the tables in the table config';
            }
        } else {
            // insert
            $ins_qry = "INSERT INTO table_config(table_name) VALUES('" . $all_table_value . "')";
            $restadd = $conn->query($ins_qry);
            if (!$restadd) {
                $_SESSION['ErrorMessage'] = 'There was an error adding.';
            } else {
                $_SESSION['SuccessMessage'] = 'The tables was adding in the table config';
            }
        }
        echo '<meta http-equiv="refresh" content="1;url=dashboard.php?cms=table_config" />';
    }
    ?>
    <div class="container">
        <form class="row form-horizontal" method="post">
            <div class="col_md_12">
                <?php
                if ($result = $conn->query("SELECT DATABASE()")) {
                    $row = $result->fetch_row();
                    printf("<h5>Default database is %s </h5>.\n", $row[0]);
                    $result->close();
                }
                ?>
                <h3>Tables you want to view in the CRUD system</h3>
                <p>Select a table to view it in the CRUD system and be able to list, view, add, update and delete data</p>
                <p class="text-danger">"Remember to add parameters for its best use and security"</p>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="submit" name="submit"
                            class="btn btn-primary">
                        <span class="fas fa-plus-square"></span> Save Config 
                    </button>
                    <button type="submit" id="tbmngr" name="tbmngr"
                            class="btn btn-success">
                        <span class="fas fa-plus-square"></span> Table Manager 
                    </button>
                    <button type="submit" id="queryb" name="queryb"
                            class="btn btn-secondary">
                        <span class="fas fa-plus-square"></span> Column Manager 
                    </button>
                </div>
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
                        <span class="fas fa-plus-square"></span> Save Config 
                    </button>
                    <button type="submit" id="tbmngr" name="tbmngr"
                            class="btn btn-success">
                        <span class="fas fa-plus-square"></span> Table Manager 
                    </button>
                    <button type="submit" id="queryb" name="queryb"
                            class="btn btn-secondary">
                        <span class="fas fa-plus-square"></span> Column Manager 
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
