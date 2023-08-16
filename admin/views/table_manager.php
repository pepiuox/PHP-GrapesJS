<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    if (!isset($_GET['w']) || empty($_GET['w'])) {
        header('Location: dashboard.php?cms=table_manager&w=list');
        exit();
    }
    extract($_POST);
    $w = protect($_GET['w']);
    if ($w == "list") {
        ?>
        <div class="container">
            <div class="row">

                <?php
                if (isset($_POST['addtable'])) {
                    echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">';
                }
                $linkedit = 'dashboard.php?cms=table_manager&w=editor&tbl=';
                $result0 = $conn->query("SHOW COLUMNS FROM table_settings");
                $bq = array();
                echo '<form class="row form-horizontal" method="POST">';

                echo '<div class="col-auto">
    <button type="submit" name="addtable" id="addtable" class="btn btn-secondary mb-3">Add table</button>
  </div>';
                echo '<table class="table">';
                echo '<thead>';
                echo '<tr>';
                while ($row0 = $result0->fetch_array()) {

                    if ($row0['Field'] == 'IdTbset') {
                        continue;
                    } else {
                        $remp = str_replace("_", " ", $row0['Field']);
                        $bq[] = '<th>' . ucfirst($remp) . '</th>';
                    }
                }
                echo implode(" \n", $bq);
                echo '<th></th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                $tbset = $conn->query("SELECT * FROM table_settings");
                $tbnums = $tbset->num_rows;
                if ($tbnums > 0) {
                    while ($tbs = $tbset->fetch_array()) {
                        echo '<tr>';
                        echo '<td><b>' . $tbs['table_name'] . '</b></td>';
                        echo '<td>';
                        if ($tbs['table_list'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>';
                        echo '<td>';
                        if ($tbs['table_view'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>';
                        echo '<td>';
                        if ($tbs['table_add'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>';
                        echo '<td>';
                        if ($tbs['table_update'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>';
                        echo '<td>';
                        if ($tbs['table_delete'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>';
                        echo '<td>';
                        if ($tbs['table_secure'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>';
                        echo '<td><a href="' . $linkedit . $tbs['table_name'] . '"><i class="fas fa-edit"></i> Edit</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">';
                }
                echo '</tbody>';
                echo '</table>';

                echo '<div class="col-auto">
    <button type="submit" name="addtable" id="addtable" class="btn btn-secondary mb-3">Add table</button>
  </div>';
                echo '</form>';
                ?>
            </div>
        </div>
        <?php
    } elseif ($w == "editor") {
        $tble = protect($_GET['tbl']);
        $result = $conn->query("SELECT table_name FROM table_settings");
        $total_found = $result->num_rows;
        $rc = array();
        if ($total_found > 0) {
            while ($row = $result->fetch_array()) {
                $rc[] = $row['table_name'];
            }
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Editor table settings</h2>
                    <h4>Select table for edit settings</h4>
                    <form class="row form-horizontal" method="POST">
                        <div class="mb-3 py-4">
                            <select id="selecttb" name="selecttb" class="form-select form-select-lg">
                                <option value="">Select a Table </option>
                                <?php
                                foreach ($rc as $tname) {
                                    $remp = str_replace("_", " ", $tname);
                                    echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                                }
                                ?>
                            </select>
                            <script>
                                let select = document.querySelector('#selecttb');
                                select.addEventListener('change', function () {
                                    let url = 'dashboard.php?cms=table_manager&w=editor&tbl=' + this.value;
                                    window.location.replace(url);
                                });
                            </script>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <form class="row form-horizontal" method="post">
                        <div class="mb-3">
                            <button type="submit" name="list" class="btn btn-secondary mb-3">List settings</button>
                            <button type="submit" name="add" class="btn btn-success mb-3">Add table settings</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-12 py-4">
                    <h3>Table selected - <?php echo ucfirst(str_replace("_", " ", $tble)); ?></h3>
                    <?php
                    //
                    //extract($_POST);
                    if (isset($_POST['list'])) {
                        echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">';
                    }
                    if (isset($_POST['add'])) {
                        echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">';
                    }
                    if (isset($_POST['submit'])) {
                        $cols = array();
                        $col = array();
                        $cl = array('table_list' => 0, 'table_view' => 0, 'table_add' => 0, 'table_update' => 0, 'table_delete' => 0, 'table_secure' => 0);

                        foreach ($_POST as $key => $value) {
                            $cols[] = $key;
                        }

                        foreach ($cl as $key => $value) {

                            if (in_array($key, $cols)) {
                                $col[] = $key . "='1'";
                            } else {
                                $col[] = $key . "='" . $value . "'";
                            }
                        }

                        $colset = implode(", ", $col);

                        $upset = "UPDATE table_settings SET $colset WHERE table_name='$tble'";
                        if ($conn->query($upset) === TRUE) {
                            echo "Record updated successfully";
                            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">';
                        } else {
                           echo "Error updating record: " . $conn->error;
                        }
                    }


                    $result0 = $conn->query("SHOW COLUMNS FROM table_settings");
                    $bq = array();
                    echo '<form class="row form-horizontal" method="POST">';

                    echo '<div class="mb-3">
            <button type="submit" name="submit" class="btn btn-primary mb-3">Update settings</button>
            </div>';
                    echo '<table class="table">';
                    echo '<thead>';
                    echo '<tr>';
                    while ($row0 = $result0->fetch_array()) {

                        if ($row0['Field'] == 'IdTbset') {
                            continue;
                        } else {
                            $remp = str_replace("_", " ", $row0['Field']);
                            $bq[] = '<th>' . ucfirst($remp) . '</th>';
                        }
                    }
                    echo implode(" \n", $bq);
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    $tbset = $conn->query("SELECT * FROM table_settings WHERE table_name='$tble'");
                    $tbnums = $tbset->num_rows;
                    if ($tbnums > 0) {
                        while ($tbs = $tbset->fetch_array()) {
                            echo '<tr>';
                            echo '<td><b>' . $tbs['table_name'] . '</b></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_list" id="table_list"';
                            if ($tbs['table_list'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_view" id="table_view"';
                            if ($tbs['table_view'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_add" id="table_add"';
                            if ($tbs['table_add'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_update" id="table_update"';
                            if ($tbs['table_update'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_delete" id="table_delete"';
                            if ($tbs['table_delete'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_secure" id="table_secure"';
                            if ($tbs['table_secure'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '<div class="mb-3">
            <button type="submit" name="submit" class="btn btn-primary mb-3">Update settings</button>
            </div>';
                    echo '<div class="mb-3">
            <button type="submit" name="list" class="btn btn-secondary mb-3">List settings</button>
            <button type="submit" name="add" class="btn btn-success mb-3">Add table settings</button>
            </div>';

                    echo '</form>';
                    ?>
                </div>
            </div>
        </div>
        <?php
    } elseif ($w == "add") {
        if (isset($_POST['list'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">';
        }
        ?>
        <div class="container">
            <div class="row">
                <h2>Add table for option settings</h2>
                <?php
                if ($result = $conn->query("SELECT * FROM table_config")) {
                    $total_found = $result->num_rows;

                    if ($total_found > 0) {
                        $row = $result->fetch_assoc();
                        $tbleNames = explode(',', $row['table_name']);
                    }
                }
                if (isset($_POST['submit'])) {
                    $tble_name = $_POST['addtb'];
                    $ins_qry = "INSERT INTO table_settings(table_name) VALUES('" . $tble_name . "')";
                    $conn->query($ins_qry);
                    echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=editor&tbl=' . $tble_name . '">';
                }
                ?>
                <form class="row form-horizontal" method="post">
                    <div class="mb-3">
                        <select id="addtb" name="addtb" class="form-control">
                            <option value="">Select a Table </option>
                            <?php
                            foreach ($tbleNames as $tname) {
                                $remp = str_replace("_", " ", $tname);
                                echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" id="submit" name="submit" class="btn btn-primary">
                            <span class="fas fa-plus-square"></span> Add table
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="list" class="btn btn-secondary mb-3">List settings</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    } elseif ($w == "set") {

        if ($result = $conn->query("SELECT table_name FROM table_settings")) {
            $total_found = $result->num_rows;

            $rc = array();
            if ($total_found > 0) {
                while ($row = $result->fetch_array()) {
                    $rc[] = $row['table_name'];
                }
            }
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3>List of application tables</h3>
                    <form class="row form-horizontal" method="POST">
                        <div class="form-group">
                            <select id="selecttb" name="selecttb" class="form-control">
                                <option value="">Select a Table </option>
                                <?php
                                foreach ($rc as $tname) {
                                    $remp = str_replace("_", " ", $tname);
                                    echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                                }
                                ?>
                            </select>
                            <script>
                                let select = document.querySelector('#selecttb');
                                select.addEventListener('change', function () {
                                    let url = 'dashboard.php?cms=table_manager&w=editor&tbl=' + this.value;
                                    window.location.replace(url);
                                });
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
