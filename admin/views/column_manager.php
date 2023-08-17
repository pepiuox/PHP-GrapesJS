<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    extract($_POST);

    if (!isset($_GET['w']) || empty($_GET['w'])) {
        header('Location: dashboard.php?cms=column_manager&w=select');
        exit();
    }

    $w = protect($_GET['w']);
    $c = new MyCRUD();

    if ($w == "select") {

        $tableNames = '';
        $result = $conn->query("SELECT * FROM table_settings");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tableNames = explode(',', $row['table_name']);
        } else {
            echo 'NO exists record tables ';
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-primary">Select your table for column options</h2>

                    <form class="row form-horizontal" method="post">
                        <div class="form-group col-md-12">
                            <label class="control-label" for="selecttb">Select Table</label> 
                            <select id="selecttb" name="selecttb" class="form-control">
                                <option value="">Select Table</option>
                                <?php
                                if (!empty($tableNames)) {
                                    foreach ($tableNames as $tname) {
                                        $remp = str_replace("_", " ", $tname);
                                        echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                                    }
                                }
                                ?>
                            </select>
                            <script>
                                let select = document.querySelector('#selecttb');
                                select.addEventListener('change', function () {
                                    let url = 'dashboard.php?cms=column_manager&w=add&tbl=' + this.value;
                                    window.location.replace(url);
                                });
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } elseif ($w == "add") {
        ?>
        <div class="container">
            <div class="row">
                <h2 class="text-primary">Add table for column edit option in CRUD</h2>
                <?php
                $tble = protect($_GET['tbl']);

                $vfile = 'qtmp.php';
                if (file_exists($vfile)) {
                    unlink($vfile);
                }

                $query = "SELECT name_table FROM table_queries WHERE name_table = '$tble'";
                $result = $conn->query($query);

                // Return the number of rows in result set
                if ($result->num_rows > 0) {
                    echo 'This table has already been added in the query builder ';
                    echo '<script>
                    window.location.href = "dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '";
                    </script>';
                } else {

                    $ncol = $c->getID($tble);

                    if (!$conn) {
                        die('Error: Could not connect: ' . mysqli_error());
                    }

                    $sql = "SELECT * FROM " . $tble;
                    $qresult = $conn->query($sql);
                    $dq = '$query = "INSERT INTO table_queries (name_table, col_name, col_type) VALUES' . "\n";
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

                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

                    $metad = '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '">';

                    $vfile = 'qtmp.php';

                    $content = '<?php' . "\n";
                    $content .= '//This is temporal file only for add new row' . "\n";
                    $content .= "if(isset(\$_POST['addtable'])){" . "\n";
                    $content .= "\$result = \$conn->query(\"SELECT name_table FROM table_queries WHERE name_table = '" . $tble . "'\");" . "\n";
                    $content .= "if (\$result->num_rows > 0) {" . "\n";
                    $content .= "echo 'This table already exists, It was already added.';" . "\n";
                    $content .= "}else{" . "\n";
                    $content .= $dq . "\n";
                    $content .= 'if ($conn->query($query) === TRUE) {' . "\n";
                    $content .= ' echo "Record added successfully";' . "\n";
                    $content .= " echo '" . $metad . "';" . "\n";
                    $content .= '} else {' . "\n";
                    $content .= '   echo "Error added record: " . $conn->error;' . "\n";
                    $content .= '   }' . "\n";
                    $content .= '}' . "\n";
                    $content .= '}' . "\n";
                    $content .= "?>";
                    if (!file_exists($vfile)) {
                        file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);
                    } else {
                        unlink($rvfile);
                        file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);
                    }

                    include 'qtmp.php';
                    echo '<form method="POST" role="form" class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Table ' . $tble . ' for option builder</legend>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="addtable"></label>
  <div class="col-md-4">
    <button id="addtable" name="addtable" class="btn btn-info">Add table</button>
  </div>
</div>

</fieldset>
</form>';
                }
                ?>
            </div>
        </div>

        <?php
        // end record
    } elseif ($w == "build") {
        $tble = protect($_GET['tbl']);
        ?>
        <div class="container">
            <div class="row">

                <?php
                echo '<div class="col-12">';
                echo '<h2 class="text-primary">Build option ' . $tble . ' table for columns</h2>';
                echo '</div>';

                function insertTQO($table, $column) {
                    global $conn;
                    $query = $conn->query("SELECT name_table,col_name FROM table_column_settings WHERE name_table='$table' AND col_name='$column'");
                    $num = $query->num_rows;
                    if ($num == 0) {
                        $qry = "INSERT INTO table_column_settings (name_table,col_name) VALUES ('" . $table . "','" . $column . "')";

                        if ($conn->query($qry) === TRUE) {
                            return TRUE;
                        } else {
                            return FALSE;
                        }
                    } else {
                        return FALSE;
                    }
                }

                $result0 = $conn->query("SHOW COLUMNS FROM $tble");
                $bq = array();
                $row = $result0->fetch_array();
                $idr = $row[0];

                while ($row0 = $result0->fetch_array()) {
                    if ($row0['Field'] == $idr) {
                        continue;
                    } else {
                        insertTQO($tble, $row0['Field']);
                    }
                }

                $lnk = "dashboard.php?cms=column_manager&w=update&tbl=" . $tble . "&id=";

                $sql = "SELECT * FROM table_column_settings WHERE name_table='$tble'";

                $result = $conn->query($sql);
                echo '<div class="col-12">';
                echo '<form class="row form-horizontal" method="post">';
                echo '<div class="col-auto">';
                echo '<a class="btn btn-primary mb-3" href="dashboard.php?cms=column_manager&w=select">Select table options</a>';
                echo '</div>';
                echo '<div class="col-auto">';
                echo '<a class="btn btn-secondary mb-3" href="dashboard.php?cms=column_manager&w=editor&tbl=' . $tble . '">change input options</a>';
                echo '</div>';
                echo '<table class="table">';
                echo '<thead>';
                echo '<th>Column name</th><th>List</th><th>Add</th><th>Update</th><th>View</th><th>Edit option</th>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = $result->fetch_array()) {

                    echo '<tr>';
                    echo '<td><b>' . $row['col_name'] . '</b></td>'
                    . '<td>';
                    if ($row['col_list'] == 1) {
                        echo 'Yes';
                    } else {
                        echo 'No';
                    }
                    echo '</td><td>';
                    if ($row['col_add'] == 1) {
                        echo 'Yes';
                    } else {
                        echo 'No';
                    }
                    echo '</td><td>';
                    if ($row['col_update'] == 1) {
                        echo 'Yes';
                    } else {
                        echo 'No';
                    }

                    echo '</td><td>';
                    if ($row['col_view'] == 1) {
                        echo 'Yes';
                    } else {
                        echo 'No';
                    }
                    echo '</td><td><a href="' . $lnk . $row['tqop_Id'] . '"><i class="fas fa-edit"></i> Edit</a></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '<table>';
                echo '<div class="col-auto">';
                echo '<a class="btn btn-primary mb-3" href="dashboard.php?cms=column_manager&w=select">Select table options</a>';
                echo '</div>';
                echo '<div class="col-auto">';
                echo '<a class="btn btn-secondary mb-3" href="dashboard.php?cms=column_manager&w=editor&tbl=' . $tble . '">change input options</a>';
                echo '</div>';
                echo '</form>';
                echo '</div>';
                ?>
            </div>
        </div>
        <?php
    } elseif ($w == "update") {
        $tble = protect($_GET['tbl']);
        $id = protect($_GET['id']);

        $ttl = $conn->query("SELECT * FROM table_column_settings WHERE tqop_Id='$id' AND name_table='$tble'");
        $ttn = $ttl->fetch_assoc();

        //
        //extract($_POST);
        if (isset($_POST['build'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '">';
        }
        if (isset($_POST['add'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build">';
        }
        if (isset($_POST['submit'])) {
            $cols = array();
            $col = array();
            $cl = array('col_list' => 0, 'col_add' => 0, 'col_update' => 0, 'col_view' => 0);

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

            $upset = "UPDATE table_column_settings SET $colset WHERE tqop_Id='$id' AND name_table='$tble'";
            if ($conn->query($upset) === TRUE) {
                echo "Record updated successfully";
                echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '">';
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form class="row form-horizontal" method="post">
                        <div class="mb-3">
                            <button type="submit" name="build" class="btn btn-secondary mb-3">Build options</button>
                            <button type="submit" name="select" class="btn btn-success mb-3">Select table options</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-12 py-4">
                    <h3>Column selected - <?php echo ucfirst(str_replace("_", " ", $ttn['col_name'])) . ' from table ' . ucfirst(str_replace("_", " ", $tble)); ?></h3>
                    <?php
                    $result0 = $conn->query("SHOW COLUMNS FROM table_column_settings");
                    $bq = array();
                    echo '<form class="row form-horizontal" method="POST">';
                    echo '<div class="mb-3">
                            <button type="submit" name="submit" class="btn btn-primary mb-3">Update Column</button>
                          </div>';

                    echo '<table class="table">';
                    echo '<thead>';
                    echo '<tr>';
                    while ($row0 = $result0->fetch_array()) {

                        if ($row0['Field'] == 'tqop_Id') {
                            continue;
                        } elseif ($row0['Field'] == 'name_table') {
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
                    $tbset = $conn->query("SELECT * FROM table_column_settings WHERE tqop_Id='$id' AND name_table='$tble'");
                    $tbnums = $tbset->num_rows;
                    if ($tbnums > 0) {
                        while ($tbs = $tbset->fetch_array()) {
                            echo '<tr>';
                            echo '<td><b>' . $tbs['col_name'] . '</b></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_list" id="col_list"';
                            if ($tbs['col_list'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_add" id="col_add"';
                            if ($tbs['col_add'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_update" id="col_update"';
                            if ($tbs['col_update'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_view" id="col_view"';
                            if ($tbs['col_view'] == 1) {
                                echo ' checked';
                            }
                            echo '></td>';
                            echo '</tr>';
                        }
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '<div class="mb-3">
            <button type="submit" name="submit" class="btn btn-primary mb-3">Update column</button>
            </div>';

                    echo '</form>';
                    ?>
                    <form class="row form-horizontal" method="post">
                        <div class="mb-3">
                            <button type="submit" name="build" class="btn btn-secondary mb-3">Build options</button>
                            <button type="submit" name="select" class="btn btn-success mb-3">Select table options</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } elseif ($w == "editor") {
        ?>
        <div class="container">
            <div class="row">
                <h2 class="text-primary">Change options for columns</h2>

                <?php
                $tble = protect($_GET['tbl']);
                $sql1 = "SHOW COLUMNS FROM " . $tble;
                $result1 = $conn->query($sql1);
                $row = mysqli_fetch_array($result1);
                $ncol = $row[0];

                $sql = "SELECT * FROM table_queries WHERE name_table='{$tble}'";

                $qresult = $conn->query($sql);
                $count = $qresult->num_rows;
                $q = 1;
                ?>


                <?php
                echo '<form class="row form-horizontal" method="POST" role="form" id="query_' . $tble . '">' . "\n";
                echo '<div class="form-group">
                        <button type = "submit" id="updatequeries" name="updatequeries" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Add query to columns</button>
<a class="btn btn-success" href="dashboard.php?cms=crud&w=list&tbl=' . $tble . '">View Table</a>                      
</div>' . "\n";
                while ($finfo = $qresult->fetch_assoc()) {
                    $remp = str_replace("_", " ", $finfo['col_name']);
                    $column = $finfo['col_name'];
                    echo '<style>
                #vtable-' . $finfo['tque_Id'] . '{
                    display: none;
                }
            </style>' . "\n";
                    echo '<script type="text/javascript">
                            $(document).ready(function() {
                                $("#btsel_' . $finfo['tque_Id'] . '").hide();
                                $("#text_' . $finfo['tque_Id'] . '").hide();
                            });
                                function getval_' . $finfo['tque_Id'] . '(sel) {
                                    var value = $("#type_' . $finfo['tque_Id'] . ' option:selected").val();
                                    if (value == 1){
                                        $("#btsel_' . $finfo['tque_Id'] . '").hide();
                                        $("#text_' . $finfo['tque_Id'] . '").hide();
                                        $("#stb").val("");
                                    }
                                    if (value == 2) {
                                        $("#text_' . $finfo['tque_Id'] . '").show();                                       
                                        $("#btsel_' . $finfo['tque_Id'] . '").hide();
                                        $("#stb").val(value);
                                    }
                                    if (value == 3) {
                                        $("#btsel_' . $finfo['tque_Id'] . '").show();
                                        $("#text_' . $finfo['tque_Id'] . '").hide();
                                        $("#stb").val(value);
                                    }  
                                }
                            
                      </script>' . "\n";

                    echo '<div class="form-group">
                            <label class="col-md-4 control-label" for="type">Select Input Type for ' . ucfirst($remp) . '</label>
                                
                            <div class="col-md-4">
                            <select id="type_' . $finfo['tque_Id'] . '" name="type_' . $finfo['tque_Id'] . '" class="form-control" onchange="getval_' . $finfo['tque_Id'] . '(this);">
                              <option value="1" ';
                    if ($finfo['input_type'] == 1) {
                        echo 'selected="selected"';
                    }
                    echo '>Input</option>                              
                              <option value="2" ';
                    if ($finfo['input_type'] == 2) {
                        echo 'selected="selected"';
                    }
                    echo '>Text Area</option>
                              <option value="3" ';
                    if ($finfo['input_type'] == 3) {
                        echo 'selected="selected"';
                    }
                    echo '>Select</option>
                    <option value="4" ';
                    if ($finfo['input_type'] == 4) {
                        echo 'selected="selected"';
                    }
                    echo '>File - Imagen</option>
                            </select>
                            </div>
                          </div>' . "\n";
                    echo '<script type="text/javascript">
                            $(document).ready(function () {
                                $("#btsel_' . $finfo['tque_Id'] . '").click(function () {
                                    var seltb = "' . $finfo['tque_Id'] . '";
                                    $("#idtb").val(seltb);                        
                                });
                            });
                         </script>' . "\n";
                    echo '<div class="form-group">
                          <button type="button" id="btsel_' . $finfo['tque_Id'] . '" name="btsel_' . $finfo['tque_Id'] . '" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#Modal1">
                          Add Colmn Id and Value 
                          </button>
                          </div>' . "\n";

                    echo '<div class="form-group" id="text_' . $finfo['tque_Id'] . '">
                            <label for="' . $finfo['col_name'] . '">Query for ' . ucfirst($remp) . ':</label>
                            <textarea type="text" class="form-control" id="' . $finfo['col_name'] . '" name="' . $finfo['col_name'] . '">' . $finfo['query'] . '</textarea>
                          </div>' . "\n";
                }
                echo '<div class="form-group">
                        <button type = "submit" id="updatequeries" name="updatequeries" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Add query to columns</button>
<a class="btn btn-success" href="dashboard.php?cms=crud&w=list&tbl=' . $tble . '">View Table</a>                       
</div>' . "\n";
                echo '</form>' . "\n";

                $vfile = 'qtmp.php';
                if (file_exists($vfile)) {
                    unlink($vfile);
                }

                function tnmes() {
                    global $conn;
                    if ($result = $conn->query("SELECT DATABASE()")) {
                        $row = $result->fetch_row();
                        $result->close();
                    }
                    $sqls = "SHOW TABLES FROM $row[0]";
                    $results = $conn->query($sqls);
                    $arrayCount = 0;
                    while ($row = mysqli_fetch_row($results)) {
                        $tableNames[$arrayCount] = $row[0];
                        $arrayCount++;
                    }
                    echo '<div class="form-group" id="vtble">
                            <label class="control-label" for="table">Select Table</label>
                            <div class="col-md-12">
                            <select id="table" name="table" class="form-control">
                                <option value="">Select Table</option>';

                    foreach ($tableNames as $tname) {
                        $rem = str_replace("_", " ", $tname);
                        echo '<option value="' . $tname . '">' . ucfirst($rem) . '</option>' . "\n";
                    }
                    echo ' </select>                    
                        </div>                        
                        </div>' . "\n";
                }

                function queries($tble) {
                    global $conn;
                    $sql = "SELECT * FROM table_queries WHERE name_table='{$tble}'";
                    $qresult = $conn->query($sql);

                    $r = 0;
                    $cqn = '';
                    while ($info = $qresult->fetch_assoc()) {
                        $id = $info['tque_Id'];
                        $query = $info['col_name'];
                        $npost = "\${$query} = \$_POST['{$query}'];";
                        $cqn .= $npost . "\n";
                        $cq = "query='\${$query}' WHERE tque_Id='{$id}'";
                        $cqn .= '$sql' . $r . ' = "UPDATE table_queries SET ' . $cq . ' ";' . "\n";
                        $cqn .= '$conn->query($sql' . $r . ');' . "\n";
                        $r = $r + 1;
                    }

                    return $cqn;
                }

                $scpt = $c->addPost($tble);
                $ncols = $c->addTtl($tble);
                $nvals = $c->addTPost($tble);
                $mpty = $c->ifMpty($tble);

                $rvfile = 'qtmp.php';

                $content = '<?php' . "\n";
                $content .= '//This is temporal file only for add new row' . "\n";
                $content .= 'if (isset($_POST["updatequeries"])) {' . "\n";
                $content .= queries($tble);
                $content .= 'echo "Record added successfully";' . "\r\n";
                $content .= 'header("Location: dashboard.php?cms=column_manager&w=editor&tbl=' . $tble . '");' . "\r\n";
                $content .= "} \r\n";
                $content .= "?> \n";

                if (!file_exists($rvfile)) {
                    file_put_contents($rvfile, $content, FILE_APPEND | LOCK_EX);
                } else {
                    unlink($rvfile);
                    file_put_contents($rvfile, $content, FILE_APPEND | LOCK_EX);
                }


                include 'qtmp.php';

                if (isset($_POST['submitrv'])) {
                    $sltb = $_POST['stb'];
                    $tb = $_POST['table'];
                    $joins = $_POST['joins'];
                    $inp = $_POST['idtb'];
                    $sr = $_POST['column_id'];
                    $sv = $_POST['column_value'];
                    $sqli = "UPDATE table_queries SET input_type='$sltb', joins='$joins', j_table='$tb', j_id='$sr', j_value='$sv' WHERE tque_Id='$inp'";
                    $conn->query($sqli);
                    if ($conn->query($sqli) === TRUE) {
                        echo "Record updated successfully";
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                }
                ?>
            </div>
        </div>
        <?php
        echo '<div class="modal fade" id="Modal1" tabindex="-1" aria-labelledby="Modal1Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <script src="../assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Select a column to relate</h5>
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">' . "\n";

        $idw = '';
        ?>
        <script>
            $(document).ready(function () {
                $('#table').on("change", function (e) {
                    e.preventDefault();
                    var tbname = $('#table').val();

                    var params = {
                        "tbname": tbname
                    };

                    $.ajax({
                        type: 'POST',
                        url: 'tbq.php',
                        data: params,
                        beforeSend: function () {
                            $('#seltables').html("<b>Loading response...</b>");
                        },
                        success: function (response) {
                            $('#seltables').html(response);
                        }
                    });
                }).trigger("change");
            });
        </script>

        <form class="form-horizontal" method="POST">
            <fieldset>
                <input type="text" id="idtb" name="idtb" value=""
                       style="display: none;" />
                <input type="text" id="stb" name="stb" value="" style="display: none;" />
                <div class="form-group" id="vtble">
                    <label class="control-label" for="joins">Select type JOIN</label>
                    <div class="col-md-12">
                        <select id="joins" name="joins" class="form-control">
                            <option value="">Select JOIN</option>
                            <option value="INNER JOIN">INNER JOIN</option>
                            <option value="LEFT JOIN">LEFT JOIN</option>
                            <option value="RIGHT JOIN">RIGHT JOIN</option>
                            <option value="STRAIGHT JOIN">STRAIGHT JOIN</option>
                            <option value="CROSS JOIN">CROSS JOIN</option>
                            <option value="NATURAL JOIN">NATURAL JOIN</option>
                        </select>
                    </div>
                </div>
                <?php
                tnmes();
                ?>
                <div class="form-group">
                    <div class="col-md-12" id="seltables" name="seltables"></div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" id="submitrv" name="submitrv"
                                class="btn btn-warning">Save</button>
                    </div>
                </div>
            </fieldset>
        </form>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
        </div>
        </div>	
        <?php
    }
    ?>

    <?php
} else {
    header("Location: ../signin/login.php");
    exit();
}
?>
