<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    if (!isset($_GET['w']) || empty($_GET['w'])) {
        header('Location: dashboard.php?cms=table_manager&w=list');
        exit();
    }
    extract($_POST);
    $w = protect($_GET['w']);
    $c = new MyCRUD();

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
                echo '<form class="row form-horizontal" method="POST">' . "\n";
                echo '<div class="col-auto">' . "\n";
                echo '<button type="submit" name="addtable" id="addtable" class="btn btn-secondary mb-3">Add table</button>' . "\n";
                echo '</div>' . "\n";
                echo '<table class="table">' . "\n";
                echo '<thead>' . "\n";
                echo '<tr>' . "\n";
                while ($row0 = $result0->fetch_array()) {

                    if ($row0['Field'] == 'IdTbset') {
                        continue;
                    } else {
                        $remp = str_replace("_", " ", $row0['Field']);
                        $bq[] = '<th>' . ucfirst($remp) . '</th>';
                    }
                }
                echo implode(" \n", $bq);
                echo '<th></th>' . "\n";
                echo '</tr>' . "\n";
                echo '</thead>' . "\n";
                echo '<tbody>' . "\n";
                $tbset = $conn->query("SELECT * FROM table_settings");
                $tbnums = $tbset->num_rows;
                if ($tbnums > 0) {
                    while ($tbs = $tbset->fetch_array()) {
                        echo '<tr>' . "\n";
                        echo '<td><b>' . $tbs['table_name'] . '</b></td>' . "\n";
                        echo '<td>' . "\n";
                        if ($tbs['table_list'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>' . "\n";

                        echo '<td>' . "\n";
                        if ($tbs['table_add'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>' . "\n";
                        echo '<td>' . "\n";
                        if ($tbs['table_update'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>' . "\n";
                        echo '<td>' . "\n";
                        if ($tbs['table_delete'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>' . "\n";
                        echo '<td>' . "\n";
                        if ($tbs['table_view'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>' . "\n";
                        echo '<td>' . "\n";
                        if ($tbs['table_secure'] == 1) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        echo '</td>' . "\n";
                        echo '<td><a href="' . $linkedit . $tbs['table_name'] . '"><i class="fas fa-edit"></i> Edit</a></td>' . "\n";
                        echo '</tr>' . "\n";
                    }
                } else {
                    echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">' . "\n";
                }
                echo '</tbody>' . "\n";
                echo '</table>' . "\n";

                echo '<div class="col-auto">' . "\n";
                echo '<button type="submit" name="addtable" id="addtable" class="btn btn-secondary mb-3">Add table</button>' . "\n";
                echo '</div>' . "\n";
                echo '</form>' . "\n";
                ?>
            </div>
        </div>
        <?php
    } elseif ($w == "add") {
        $tableNames = '';
        if (isset($_POST['list'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">' . "\n";
        }

        if (isset($_GET['tbl']) && !empty($_GET['tbl'])) {
            $tble = protect($_GET['tbl']);

            $vfile = 'qtmp.php';
            if (file_exists($vfile)) {
                unlink($vfile);
            }

            $query = "SELECT table_name FROM table_column_settings WHERE table_name = '$tble'";
            $result = $conn->query($query);

            // Return the number of rows in result set
            if ($result->num_rows > 0) {
                echo '<h4>This table has already been added in the query builder.</h4> ' . "\n";
                echo '<script>' . "\n";
                echo 'window.location.href = "dashboard.php?cms=table_manager&w=editor&tbl=' . $tble . '"' . "\n";
                echo '</script>' . "\n";
            } else {

                $ncol = $c->getID($tble);

                if (!$conn) {
                    die('Error: Could not connect: ' . mysqli_error());
                }

                $sql = "SELECT * FROM " . $tble;
                $qresult = $conn->query($sql);
                $dq = '$query = "INSERT INTO table_column_settings (table_name, col_name, col_type) VALUES' . "\n";
                $addq = array();
                $colsn = $c->viewColumns($tble);
                foreach ($colsn as $finfo) {
                    if ($finfo->name == $ncol) {
                        continue;
                    } else {
                        $addq[] = "('" . $tble . "', '" . $finfo->name . "', '" . $finfo->type . "')";
                    }
                }
                $dq .= implode(", \n", $addq);
                $dq .= '";';

                $redir = '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=editor&tbl=' . $tble . '">';
                $content = '<?php' . "\n";
                $content .= '//This is temporal file only for add new row' . "\n";
                $content .= "if(isset(\$_POST['addtable'])){" . "\n";
                $content .= "\$result = \$conn->query(\"SELECT table_name FROM table_column_settings WHERE table_name = '" . $tble . "'\");" . "\n";
                $content .= "if (\$result->num_rows > 0) {" . "\n";
                $content .= "echo 'This table already exists, It was already added.';" . "\n";
                $content .= "}else{" . "\n";
                $content .= $dq . "\n";
                $content .= 'if ($conn->query($query) === TRUE) {' . "\n";
                $content .= "\$ins_qry = \"INSERT INTO table_settings(table_name) VALUES('" . $tble . "')\";" . "\n";
                $content .= 'if ($conn->query($ins_qry) === TRUE){' . "\n";
                $content .= 'echo "Record added successfully";' . "\n";
                $content .= "echo '" . $redir . "';" . "\n";
                $content .= '} else {' . "\n";
                $content .= 'echo "Error added record: " . $conn->error;' . "\n";
                $content .= '}' . "\n";
                $content .= '} else {' . "\n";
                $content .= 'echo "Error added record: " . $conn->error;' . "\n";
                $content .= '}' . "\n";
                $content .= '}' . "\n";
                $content .= '}' . "\n";
                $content .= "?>";

                file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);

                include_once 'qtmp.php';
            }
        }
        ?>
        <div class="container">
            <div class="row">
                <h2>Add table to admin for option settings.</h2>
                <?php
                if ($result = $conn->query("SELECT * FROM table_config")) {
                    $total_found = $result->num_rows;

                    if ($total_found > 0) {
                        $row = $result->fetch_assoc();
                        $tbleNames = explode(',', $row['table_name']);
                    }
                }
                ?>
                <form class="row form-horizontal" method="post">
                    <div class="mb-3">
                        <select id="addtb" name="addtb" class="form-control">
                            <option value="">Select a Table </option>
                            <?php
                            foreach ($tbleNames as $tname) {
                                $remp = str_replace("_", " ", $tname);
                                if (isset($tble)) {
                                    if ($tname === $tble) {
                                        echo '<option value="' . $tname . '" selected>' . ucfirst($remp) . '</option>' . "\n";
                                    }
                                } else {
                                    echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <script>
                        let select = document.querySelector('#addtb');
                        select.addEventListener('change', function () {
                        let url = 'dashboard.php?cms=table_manager&w=add&tbl=' + this.value;
                        window.location.replace(url);
                        });
                    </script>
                    <div class="col-auto">
                        <button type="submit" id="addtable" name="addtable" class="btn btn-primary">
                            <span class="fas fa-plus-square"></span> Add table
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="list" class="btn btn-secondary mb-3">List table manager</button>
                    </div>

                </form>
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
        if (isset($_POST['list'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">' . "\n";
        }
        if (isset($_POST['add'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">' . "\n";
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
                echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">' . "\n";
            } else {
                echo "Error updating record: " . $conn->error;
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
                    <style>
                        input:checked + label {
                            color: blue;
                        }
                        input[type="checkbox"] {
                            box-shadow: 0 0 0 2px green;
                            color: green;
                        }
                        input[type="checkbox"]:checked {
                            box-shadow: 0 0 0 2px hotpink;
                        }
                    </style>
                    <h3>Table selected - <?php echo ucfirst(str_replace("_", " ", $tble)); ?></h3>
                    <?php
                    //
                    //extract($_POST);
                    $result0 = $conn->query("SHOW COLUMNS FROM table_settings");
                    $bq = array();
                    // start form
                    echo '<form class="row form-horizontal" method="POST">' . "\n";
                    echo '<div class="mb-3">' . "\n";
                    echo '<button type="submit" name="submit" class="btn btn-primary mb-3">Update settings</button>' . "\n";
                    echo '</div>' . "\n";
                    echo '<table class="table">' . "\n";
                    echo '<thead>' . "\n";
                    echo '<tr>' . "\n";
                    while ($row0 = $result0->fetch_array()) {
                        if ($row0['Field'] == 'IdTbset') {
                            continue;
                        } else {
                            $remp = str_replace("_", " ", $row0['Field']);
                            $bq[] = '<th>' . ucfirst($remp) . '</th>' . "\n";
                        }
                    }
                    echo implode(" \n", $bq);
                    echo '</tr>' . "\n";
                    echo '</thead>' . "\n";
                    echo '<tbody>' . "\n";
                    $tbset = $conn->query("SELECT * FROM table_settings WHERE table_name='$tble'");
                    $tbnums = $tbset->num_rows;
                    if ($tbnums > 0) {
                        while ($tbs = $tbset->fetch_array()) {
                            echo '<tr>' . "\n";
                            echo '<td><b>' . $tbs['table_name'] . '</b></td>' . "\n";
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_list" id="table_list"';
                            if ($tbs['table_list'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>' . "\n";
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_add" id="table_add"';
                            if ($tbs['table_add'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>' . "\n";
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_update" id="table_update"';
                            if ($tbs['table_update'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>' . "\n";
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_delete" id="table_delete"';
                            if ($tbs['table_delete'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>' . "\n";
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_view" id="table_view"';
                            if ($tbs['table_view'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>' . "\n";
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="table_secure" id="table_secure"';
                            if ($tbs['table_secure'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>' . "\n";
                            echo '</tr>' . "\n";
                        }
                    }
                    echo '</tbody>' . "\n";
                    echo '</table>' . "\n";
                    echo '<div class="mb-3">' . "\n";
                    echo '<button type="submit" name="submit" class="btn btn-primary mb-3">Update settings</button>' . "\n";
                    echo '</div>' . "\n";
                    echo '<div class="mb-3">' . "\n";
                    echo '<button type="submit" name="list" class="btn btn-secondary mb-3">List settings</button>' . "\n";
                    echo '<button type="submit" name="add" class="btn btn-success mb-3">Add table settings</button>' . "\n";
                    echo '</div>' . "\n";
                    echo '</form>' . "\n";
                    //end form
                    ?>
                </div>
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
