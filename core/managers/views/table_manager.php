<?php
global $conn;
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    
    extract($_POST, EXTR_SKIP); // ✅ EXTR_SKIP: no sobrescribe variables locales    
    $c = new MyCRUD();

    if ($w == "list") {
        ?>
        <div class="container">
            <div class="row">

                <?php
                if (isset($_POST['addtable'])) {
                    echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/table_manager/add">';
                }
                $linkedit = SITE_PATH.'admin/dashboard/table_manager/editor/';
                $result0 = $conn->query("SHOW COLUMNS FROM table_settings");
                $bq = array();
                echo '<form class="row form-horizontal" method="POST">' . \PepiuoX\Security\Csrf::field() . '' . "\n";
                echo '<div class="col-auto">' . "\n";
                echo '<button type="submit" name="addtable" id="addtable" class="btn btn-secondary mb-3">Add table</button>' . "\n";
                echo '</div>' . "\n";
                echo '<table class="table">' . "\n";
                echo '<thead>' . "\n";
                echo '<tr>' . "\n";
                while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) {

                    if ($row0['Field'] === 'IdTbset') {
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
                $tbnums = $tbset->rowCount();
                if ($tbnums > 0) {
                    while ($tbs = $tbset->fetch(PDO::FETCH_ASSOC)) {
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
                    echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/table_manager/add">' . "\n";
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
            echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/table_manager/list">' . "\n";
        }

        if (defined('TBL')) {
            $tble = \PepiuoX\Security\SqlIdentifier::assert(TBL);

            $vfile = URL.'/managers/qtmp.php';
            if (file_exists($vfile)) {
                unlink($vfile);
            }

            $query = "SELECT table_name FROM table_column_settings WHERE table_name = :table_name";
            $result = $conn->prepare($query);
            $result->execute([':table_name' => $tble]);

            // Return the number of rows in result set
            if ($result->rowCount() > 0) {
                echo '<h4>This table has already been added in the query builder.</h4> ' . "\n";
                echo '<script>' . "\n";
                echo 'window.location.href = "'.SITE_PATH.'admin/dashboard/table_manager/editor/' . $tble . '"' . "\n";
                echo '</script>' . "\n";
            } else {

                $ncol = $c->getID($tble);

                if (!($conn instanceof PDO)) {
                    die('Error: Could not connect to the database.');
                }

                $sql = "SELECT * FROM " . $tble;
                $qresult = $conn->query($sql);
                $dq = '$query = "INSERT INTO table_column_settings (table_name, col_name, col_type) VALUES' . "\n";
                $addq = array();
                $addqParams = array();
                $colsn = $c->viewColumns($tble);
                $qi = 0;
                foreach ($colsn as $finfo) {
                    if ($finfo->name == $ncol) {
                        continue;
                    } else {
                        $addq[] = "(:table_name_" . $qi . ", :col_name_" . $qi . ", :col_type_" . $qi . ")";
                        $addqParams[":table_name_" . $qi] = (string) $tble;
                        $addqParams[":col_name_" . $qi] = (string) $finfo->name;
                        $addqParams[":col_type_" . $qi] = (string) $finfo->type;
                        $qi++;
                    }
                }
                $dq .= implode(", \n", $addq);
                $dq .= '";';
                $dq .= "\n" . '$stmt = $conn->prepare($query);';
                $dq .= "\n" . '$addqParams = ' . var_export($addqParams, true) . ';';

                $redir = '<meta http-equiv="refresh" content="0;url="'.SITE_PATH.'admin/dashboard/table_manager/editor/' . $tble . '">';
                $content = '<?php' . "\n";
                $content .= '//This is temporal file only for add new row' . "\n";
                $content .= "if(isset(\$_POST['addtable'])){" . "\n";
                $content .= "\$stmt = \$conn->prepare(\"SELECT table_name FROM table_column_settings WHERE table_name = :table_name\");" . "\n";
                $content .= "\$stmt->execute([':table_name' => " . var_export((string) $tble, true) . "]);" . "\n";
                $content .= "if (\$stmt->rowCount() > 0) {" . "\n";
                $content .= "echo 'This table already exists, It was already added.';" . "\n";
                $content .= "}else{" . "\n";
                $content .= $dq . "\n";
                $content .= 'if ($stmt->execute($addqParams) !== false) {' . "\n";
                $content .= "\$ins_qry = \"INSERT INTO table_settings(table_name) VALUES(:table_name)\";" . "\n";
                $content .= "\$ins_stmt = \$conn->prepare(\$ins_qry);" . "\n";
                $content .= "if (\$ins_stmt->execute([':table_name' => " . var_export((string) $tble, true) . "]) !== false){" . "\n";
                $content .= 'echo "Record added successfully";' . "\n";
                $content .= "echo '" . $redir . "';" . "\n";
                $content .= '} else {' . "\n";
                $content .= 'echo "Error added record: " . $conn->errorInfo()[2];' . "\n";
                $content .= '}' . "\n";
                $content .= '} else {' . "\n";
                $content .= 'echo "Error added record: " . $conn->errorInfo()[2];' . "\n";
                $content .= '}' . "\n";
                $content .= '}' . "\n";
                $content .= '}' . "\n";
                $content .= "?>";

                file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);

                include_once URL.'/managers/qtmp.php';
            }
        }
        ?>
        <div class="container">
            <div class="row">
                <h2>Add table to admin for option settings.</h2>
                <?php
                if ($result = $conn->query("SELECT * FROM table_config")) {
                    $total_found = $result->rowCount();

                    if ($total_found > 0) {
                        $row = $result->fetch(PDO::FETCH_ASSOC);
                        $tbleNames = explode(',', $row['table_name']);
                    }
                }
                ?>
                <form class="row form-horizontal" method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                        let url = '<?php echo SITE_PATH; ?>admin/dashboard/table_manager/add/' + this.value;
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
        
        $result = $conn->query("SELECT table_name FROM table_settings");
        $total_found = $result->rowCount();
        $rc = array();
        if ($total_found > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $rc[] = $row['table_name'];
            }
        }
        if (isset($_POST['list'])) {
            echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/table_manager/list">' . "\n";
        }
        if (isset($_POST['add'])) {
            echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/table_manager/add">' . "\n";
        }
        if (isset($_POST['submit'])) {
            $cols = array();
            $col = array();
            $colParams = array();
            $cl = array('table_list' => 0, 'table_view' => 0, 'table_add' => 0, 'table_update' => 0, 'table_delete' => 0, 'table_secure' => 0);

            foreach ($_POST as $key => $value) {
                $cols[] = $key;
            }

            foreach ($cl as $key => $value) {
                $key = \PepiuoX\Security\SqlIdentifier::assert($key);

                if (in_array($key, $cols)) {
                    $col[] = $key . "=:c_" . $key;
                    $colParams[":c_" . $key] = '1';
                } else {
                    $col[] = $key . "=:c_" . $key;
                    $colParams[":c_" . $key] = $value;
                }
            }

            $colset = implode(", ", $col);

            $upset = "UPDATE table_settings SET $colset WHERE table_name=:table_name";
            $stmt = $conn->prepare($upset);
            if ($stmt->execute($colParams + array(':table_name' => $tble)) !== false) {
                echo "Record updated successfully";
                echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/table_manager/list">' . "\n";
            } else {
                echo "Error updating record: " . $conn->errorInfo()[2];
            }
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Editor table settings</h2>
                    <h4>Select table for edit settings</h4>
                    <form class="row form-horizontal" method="POST">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                                let url = '<?php echo SITE_PATH; ?>admin/dashboard/table_manager/editor/' + this.value;
                                window.location.replace(url);
                                });
                            </script>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <form class="row form-horizontal" method="post">
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                    echo '<form class="row form-horizontal" method="POST">' . \PepiuoX\Security\Csrf::field() . '' . "\n";
                    echo '<div class="mb-3">' . "\n";
                    echo '<button type="submit" name="submit" class="btn btn-primary mb-3">Update settings</button>' . "\n";
                    echo '</div>' . "\n";
                    echo '<table class="table">' . "\n";
                    echo '<thead>' . "\n";
                    echo '<tr>' . "\n";
                    while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) {
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
                    $tbset = $conn->prepare("SELECT * FROM table_settings WHERE table_name=:table_name");
                    $tbset->execute([':table_name' => $tble]);
                    $tbnums = $tbset->rowCount();
                    if ($tbnums > 0) {
                        while ($tbs = $tbset->fetch(PDO::FETCH_ASSOC)) {
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
            $total_found = $result->rowCount();

            $rc = array();
            if ($total_found > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
<?php echo \PepiuoX\Security\Csrf::field(); ?>
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
                                let url = '<?php echo SITE_PATH; ?>admin/dashboard/table_manager/editor/' + this.value;
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
