<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    extract($_POST);

    if (!isset($_GET['w']) || empty($_GET['w'])) {
        header('Location: dashboard.php?cms=column_manager&w=select');
        exit();
    }

    $w = protect($_GET['w']);
    $c = new MyCRUD();

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
                            <label class="control-label" for="j_table">Select Table</label>
                            <div class="col-md-12">
                              <select id="j_table" name="j_table" class="form-select">
                                <option value="">Select Table</option>' . "\n";
        foreach ($tableNames as $tname) {
            $rem = str_replace("_", " ", $tname);
            echo '<option value="' . $tname . '">' . ucfirst($rem) . '</option>' . "\n";
        }
        echo '   </select>                    
                           </div>                        
                        </div>' . "\n";
    }

    if ($w == "select") {

        $tableNames = '';
        $result = $conn->query("SELECT * FROM table_settings");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tableNames = explode(',', $row['table_name']);
        } else {
            echo 'NO exists record tables ';
        }

        if (isset($_POST['add'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">';
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-primary">Select your table for column options</h2>

                    <form class="row form-horizontal" method="post">
                        <div class="form-group col-md-12">
                            <label class="control-label" for="selecttb">Select Table</label> 
                            <select id="selecttb" name="selecttb" class="form-select">
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
                                    let url = 'dashboard.php?cms=column_manager&w=build&tbl=' + this.value;
                                    window.location.replace(url);
                                });
                            </script>
                        </div>
                        <div class="col-auto py-4">
                            <a class="btn btn-primary mb-3" href="dashboard.php?cms=table_manager&w=add">Go to add more tables</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        // end record
    } elseif ($w == "build") {
        $tble = protect($_GET['tbl']);
        $tname = ucfirst(str_replace("_", " ", $tble));
        ?>
        <div class="container">
            <div class="row">

                <?php
                echo '<div class="col-12">';
                echo '<h2 class="text-primary">Build option ' . $tname . ' table for columns</h2>';
                echo '</div>';

                function insertTQO($table, $column) {
                    global $conn;
                    $query = $conn->query("SELECT table_name,col_name FROM table_column_settings WHERE table_name='$table' AND col_name='$column'");
                    $num = $query->num_rows;
                    if ($num == 0) {
                        $qry = "INSERT INTO table_column_settings (table_name,col_name) VALUES ('" . $table . "','" . $column . "')";

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

                $sql = "SELECT * FROM table_column_settings WHERE table_name='$tble'";

                $result = $conn->query($sql);
                echo '<div class="col-12">';
                echo '<form class="row form-horizontal" method="post">';
                echo '<div class="col-auto">';
                echo '<a class="btn btn-primary mb-3" href="dashboard.php?cms=column_manager&w=select">Select table options</a>';
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
                    echo '</td><td><a href="' . $lnk . $row['tqop_Id'] . '"><i class="fas fa-edit"></i> Edit options</a></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '<table>';
                echo '<div class="col-auto">';
                echo '<a class="btn btn-primary mb-3" href="dashboard.php?cms=column_manager&w=select">Select table options</a>';
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

        $ttl = $conn->prepare("SELECT * FROM table_column_settings WHERE tqop_Id=? AND table_name=?");
        $ttl->bind_param("is", $id, $tble);
        $ttl->execute();
        $ucol = $ttl->get_result();
        $ttl->close();
        $ttn = $ucol->fetch_assoc();
        $cnm = $ttn['col_name'];

        $stmt = $conn->prepare("SELECT * FROM table_queries WHERE table_name=? AND col_name=?");
        $stmt->bind_param("ss", $tble, $cnm);
        $stmt->execute();
        $upcol = $stmt->get_result();
        $stmt->close();

        $finfo = $upcol->fetch_assoc();
        $fid = $finfo['tque_Id'];

        $cln = ucfirst(str_replace("_", " ", $finfo['col_name']));
// 
//extract($_POST);
        if (isset($_POST['build'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '">';
        }

        if (isset($_POST['select'])) {
            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=select">';
        }
        ?>
        <script src="../assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>

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
                <div class="card">
                    <div class="card-header p-2">
                        <h3>Column properties and configuration</h3>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" href="#optionsshows" data-toggle="tab">Options shows</a></li>
                            <li class="nav-item"><a class="nav-link" href="#properties" data-toggle="tab">Properties</a></li>
                            <li class="nav-item"><a class="nav-link" href="#addquery" data-toggle="tab">Add query</a></li>
                            <li class="nav-item"><a class="nav-link" href="#relatedtables" data-toggle="tab">Related tables</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 py-4">
                            <div class="tab-content">
                                <div class="active tab-pane" role="tabpanel" id="optionsshows">
                                    <?php
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

                                        $upset = "UPDATE table_column_settings SET $colset WHERE tqop_Id='$id' AND table_name='$tble'";
                                        if ($conn->query($upset) === TRUE) {
                                            echo "Record updated successfully";
                                            echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=update&tbl=' . $tble . '&id=' . $id . '#optionsshows">';
                                        } else {
                                            echo "Error updating record: " . $conn->error;
                                        }
                                    }
                                    ?>
                                    <h3>Column selected - <?php echo ucfirst(str_replace("_", " ", $ttn['col_name'])) . ' from table ' . ucfirst(str_replace("_", " ", $tble)); ?></h3>
                                    <h5 class="text-secondary">Options for listing, adding editing and viewing</h5>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <button type="submit" name="submit" class="btn btn-primary mb-3">Update Column</button>
                                        </div>

                                        <?php
                                        $result0 = $conn->query("SHOW COLUMNS FROM table_column_settings");
                                        $bq = array();

                                        echo '<table class="table">';
                                        echo '<thead>';
                                        echo '<tr>';
                                        while ($row0 = $result0->fetch_array()) {

                                            if ($row0['Field'] == 'tqop_Id') {
                                                continue;
                                            } elseif ($row0['Field'] == 'table_name') {
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
                                        $tbcol = $conn->prepare("SELECT * FROM table_column_settings WHERE tqop_Id=? AND table_name=?");
                                        $tbcol->bind_param("ss", $id, $tble);
                                        $tbcol->execute();
                                        $tbsc = $tbcol->get_result();
                                        $tbnums = $tbsc->num_rows;
                                        if ($tbnums > 0) {
                                            while ($tbs = $tbsc->fetch_array()) {
                                                echo '<tr>' . "\n";
                                                echo '<td><b>' . $tbs['col_name'] . '</b></td>' . "\n";
                                                echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_list" id="col_list"';
                                                if ($tbs['col_list'] == 1) {
                                                    echo ' checked';
                                                }
                                                echo '></td>' . "\n";
                                                echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_add" id="col_add"';
                                                if ($tbs['col_add'] == 1) {
                                                    echo ' checked';
                                                }
                                                echo '></td>' . "\n";
                                                echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_update" id="col_update"';
                                                if ($tbs['col_update'] == 1) {
                                                    echo ' checked';
                                                }
                                                echo '></td>' . "\n";
                                                echo '<td><input class="form-check-input" type="checkbox" value="1" name="col_view" id="col_view"';
                                                if ($tbs['col_view'] == 1) {
                                                    echo ' checked';
                                                }
                                                echo '></td>' . "\n";
                                                echo '</tr>' . "\n";
                                            }
                                        }
                                        echo '</tbody>' . "\n";
                                        echo '</table>' . "\n";
                                        echo '<div class="mb-3">
            <button type="submit" name="submit" class="btn btn-primary mb-3">Update column</button>
            </div>' . "\n";
                                        ?>
                                    </form>
                                </div>
                                <div class="tab-pane" role="tabpanel" id="properties">
                                    <?php
                                    if (isset($_POST['submitin'])) {

                                        $sltb = protect($_POST['input_type']);

                                        $stmt = $conn->prepare("UPDATE table_queries SET input_type = ? WHERE tque_Id = ?");
                                        $stmt->bind_param('ii', $sltb, $fid);
                                        $status = $stmt->execute();
                                        if ($status === false) {
                                            trigger_error($stmt->error, E_USER_ERROR);
                                        } else {
                                            echo "Updated column successfully" . $stmt->affected_rows;
                                        }
                                    }
                                    ?>
                                    <h3>Configure more options for the column</h3>
                                    <h4 class="text-primary">Select input type to add and edit</h4>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <fieldset>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="input_type">Select Input Type for <?php echo $cln; ?></label>                                
                                                <div class="col-md-4">
                                                    <select id="input_type" name="input_type" class="form-select">
                                                        <?php
                                                        echo '<option value="1" ';
                                                        if ($finfo['input_type'] == 1) {
                                                            echo 'selected="selected"';
                                                        }
                                                        echo '>Input</option>' . "\n";
                                                        echo '<option value="2" ';
                                                        if ($finfo['input_type'] == 2) {
                                                            echo 'selected="selected"';
                                                        }
                                                        echo '>Text Area</option>' . "\n";
                                                        echo '<option value="3" ';
                                                        if ($finfo['input_type'] == 3) {
                                                            echo 'selected="selected"';
                                                        }
                                                        echo '>Select</option>' . "\n";
                                                        echo '<option value="4" ';
                                                        if ($finfo['input_type'] == 4) {
                                                            echo 'selected="selected"';
                                                        }
                                                        echo '>File - Imagen</option>' . "\n";
                                                        echo '<option value="5" ';
                                                        if ($finfo['input_type'] == 5) {
                                                            echo 'selected="selected"';
                                                        }
                                                        echo '>Check box</option>' . "\n";
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <button type="submit" id="submitin" name="submitin" class="btn btn-warning">Save</button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                    <script>
                                /*
                                 $(document).ready(function () {
                                 $("#btsel").hide();
                                 $("#texta").hide();
                                 $("#typei").on("change", function () {
                                         
                                 let value = $("#input_type option:selected").val();
                                 if (value === 1) {
                                 $("#btsel").hide();
                                 $("#texta").hide();
                                 $("#stb").val("");
                                 }
                                 if (value === 2) {
                                 $("#texta").show();
                                 $("#btsel").hide();
                                 $("#stb").val(value);
                                 }
                                 if (value === 3) {
                                 $("#btsel").show();
                                 $("#texta").hide();
                                 $("#stb").val(value);
                                 }
                                 if (value === 4) {
                                 $("#btsel").show();
                                 $("#texta").hide();
                                 $("#stb").val(value);
                                 }
                                 });
                                 });
                                 * */
                                    </script>
                                </div>
                                <div class="tab-pane" role="tabpanel" id="addquery">
                                    <?php
                                    if (isset($_POST['submitqr'])) {
                                        $qry = protect($_POST['query']);

                                        $stmt = $conn->prepare("UPDATE table_queries SET query = ? WHERE tque_Id = ?");
                                        $stmt->bind_param('si', $qry, $inp);
                                        $status = $stmt->execute();
                                        if ($status === false) {
                                            trigger_error($stmt->error, E_USER_ERROR);
                                        } else {
                                            echo "Updated column successfully" . $stmt->affected_rows;
                                        }
                                    }
                                    ?>
                                    <h3>Add a special query for the column</h3>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <fieldset>                                           
                                            <?php
                                            echo '<div class="form-group">
                            <label for="' . $finfo['col_name'] . '">Query for ' . ucfirst($remp) . ':</label>
                            <textarea type="text" class="form-control" id="query" name="query">' . $finfo['query'] . '</textarea>
                          </div>' . "\n";
                                            ?>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <button type="submit" id="submitqr" name="submitqr" class="btn btn-warning">Save</button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <div class="tab-pane" role="tabpanel" id="relatedtables">
                                    <?php
                                    if (isset($_POST['submitrv'])) {


                                        $tb = protect($_POST['j_table']);
                                        $joins = protect($_POST['joins']);
                                        $sr = protect($_POST['column_id']);
                                        $sv = protect($_POST['column_value']);

                                        $stmt = $conn->prepare("UPDATE table_queries SET joins = ?, j_table = ?, j_id = ?, j_value = ? WHERE tque_Id = ?");
                                        $stmt->bind_param('ssssi', $joins, $tb, $sr, $sv, $fid);
                                        $status = $stmt->execute();
                                        if ($status === false) {
                                            trigger_error($stmt->error, E_USER_ERROR);
                                        } else {
                                            echo "Updated column successfully" . $stmt->affected_rows;
                                        }
                                    }
                                    ?>
                                    <h3 class="py-4">Relate column value to different tables</h3>
                                    <h4 class="text-primary">Select table to relate</h4>
                                    <form class="row form-horizontal" method="POST" id="queries">
                                        <fieldset>

                                            <div class="form-group" id="vtble">
                                                <label class="control-label" for="joins">Select type JOIN</label>
                                                <div class="col-md-12">
                                                    <select id="joins" name="joins" class="form-select">
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
                                            <script>
                                                $(document).ready(function () {
                                                    $('#j_table').on("change", function (e) {
                                                        e.preventDefault();
                                                        var tbname = $('#j_table').val();
                                                        var params = {
                                                            "tbname": tbname
                                                        };
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'tbq.php',
                                                            data: params,
                                                            success: function (response) {
                                                                $('#seltables').html(response);
                                                            },
                                                            error: function () {
                                                                alert("Something went wrong!");
                                                            }
                                                        });
                                                    }).trigger("change");
                                                });
                                            </script>
                                            <div class="form-group">
                                                <div class="col-md-12" id="seltables" name="seltables"></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <button type="submit" id="submitrv" name="submitrv" class="btn btn-warning">Save</button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 py-4">

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
        extract($_POST);
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

                $qresult = $conn->prepare("SELECT * FROM table_queries WHERE table_name=?");
                $qresult->bind_param("s", $tble);
                $qresult->execute();
                $tbsc = $qresult->get_result();
                $count = $tbsc->num_rows;
                $q = 1;

                echo '<form class="row form-horizontal" method="POST" role="form" id="query_' . $tble . '">' . "\n";
                echo '<div class="form-group">
                        <button type = "submit" id="updatequeries" name="updatequeries" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Save query to columns</button>
<a class="btn btn-success" href="dashboard.php?cms=table_crud&w=list&tbl=' . $tble . '">View Table</a>                      
</div>' . "\n";
                while ($finfo = $tbsc->fetch_assoc()) {
                    $remp = str_replace("_", " ", $finfo['col_name']);
                    $column = $finfo['col_name'];
                    echo '<style>
                            #vtable-' . $finfo['tque_Id'] . '{
                                display: none;
                            }
                          </style>' . "\n";
                    echo '<script>
$(document).ready(function () {
                $("#btsel_' . $finfo['tque_Id'] . '").hide();
                $("#text_' . $finfo['tque_Id'] . '").hide();
                $("#type_' . $finfo['tque_Id'] . '").on("change", function() {
                
                    let value = $("#type_' . $finfo['tque_Id'] . ' option:selected").val();
                    if (value === 1) {
                        $("#btsel_' . $finfo['tque_Id'] . '").hide();
                        $("#text_' . $finfo['tque_Id'] . '").hide();
                        $("#stb").val("");
                    }
                    if (value === 2) {
                        $("#text_' . $finfo['tque_Id'] . '").show();
                        $("#btsel_' . $finfo['tque_Id'] . '").hide();
                        $("#stb").val(value);
                    }
                    if (value === 3) {
                        $("#btsel_' . $finfo['tque_Id'] . '").show();
                        $("#text_' . $finfo['tque_Id'] . '").hide();
                        $("#stb").val(value);
                    }
                    if (value === 4) {
                        $("#btsel_' . $finfo['tque_Id'] . '").show();
                        $("#text_' . $finfo['tque_Id'] . '").hide();
                        $("#stb").val(value);
                    }
                }
            });                            
                      </script>' . "\n";

                    echo '<div class="form-group">
                            <label class="col-md-4 control-label" for="type">Select Input Type for ' . ucfirst($remp) . '</label>                                
                            <div class="col-md-4">
                            <select id="type_' . $finfo['tque_Id'] . '" name="type_' . $finfo['tque_Id'] . '" class="form-select">
                              <option value="1" ';
                    if ($finfo['input_type'] == 1) {
                        echo 'selected="selected"';
                    }
                    echo '>Input</option>' . "\n";
                    echo '<option value="2" ';
                    if ($finfo['input_type'] == 2) {
                        echo 'selected="selected"';
                    }
                    echo '>Text Area</option>' . "\n";
                    echo '<option value="3" ';
                    if ($finfo['input_type'] == 3) {
                        echo 'selected="selected"';
                    }
                    echo '>Select</option>' . "\n";
                    echo '<option value="4" ';
                    if ($finfo['input_type'] == 4) {
                        echo 'selected="selected"';
                    }
                    echo '>File - Imagen</option>' . "\n";
                    echo '</select>
                            </div>
                          </div>' . "\n";

                    echo '<div class="form-group">
                          <button type="button" id="btsel_' . $finfo['tque_Id'] . '" name="btsel_' . $finfo['tque_Id'] . '" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#Modal1">
                          Add Colmn Id and Value 
                          </button>
                          </div>' . "\n";

                    echo '<script type="text/javascript">' . "\n";
                    echo "$(document).ready(function () {" . "\n";
                    echo '$("#btsel_' . $finfo['tque_Id'] . '").on("click", function (e) {' . "\n";
                    echo "e.preventDefault();
                                    let seltb = '" . $finfo['tque_Id'] . "';
                                    $('#idtb').val(seltb);                                    
                                });
                            });" . "\n";
                    echo "</script>" . "\n";

                    echo '<div class="form-group" id="text_' . $finfo['tque_Id'] . '">
                            <label for="' . $finfo['col_name'] . '">Query for ' . ucfirst($remp) . ':</label>
                            <textarea type="text" class="form-control" id="' . $finfo['col_name'] . '" name="' . $finfo['col_name'] . '">' . $finfo['query'] . '</textarea>
                          </div>' . "\n";
                }
                echo '<div class="form-group">
                        <button type = "submit" id="updatequeries" name="updatequeries" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Save query to columns</button>
                        <a class="btn btn-success" href="dashboard.php?cms=crud&w=list&tbl=' . $tble . '">View Table</a>                       
                        </div>' . "\n";
                echo '</form>' . "\n";

                $vfile = 'qtmp.php';
                if (file_exists($vfile)) {
                    unlink($vfile);
                }

                function queries($tble) {
                    global $conn;
                    $sql = "SELECT * FROM table_queries WHERE table_name='{$tble}'";
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
                if (file_exists($rvfile)) {
                    unlink($rvfile);
                }

                $redir = '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '">';

                $content = '<?php' . "\n";
                $content .= '//This is temporal file only for add new row' . "\n";
                $content .= 'if (isset($_POST["updatequeries"])) {' . "\n";
                $content .= queries($tble);
                $content .= 'echo "<h4>Updated Query column successfully.</h4>";' . "\n";
                $content .= "echo '$redir';" . "\n";
                $content .= "} \n";
                $content .= "?> \n";

                file_put_contents($rvfile, $content, FILE_APPEND | LOCK_EX);

                include_once 'qtmp.php';

                if (isset($_POST['submitrv'])) {
                    $inp = protect($_POST['idtb']);
                    $sltb = protect($_POST['stb']);
                    $tb = protect($_POST['j_table']);
                    $joins = protect($_POST['joins']);
                    $sr = protect($_POST['column_id']);
                    $sv = protect($_POST['column_value']);

                    $stmt = $conn->prepare("UPDATE table_queries SET input_type = ?, joins = ?, j_table = ?, j_id = ?, j_value = ? WHERE tque_Id = ?");
                    $stmt->bind_param('issssi', $sltb, $joins, $tb, $sr, $sv, $inp);
                    $status = $stmt->execute();
                    if ($status === false) {
                        trigger_error($stmt->error, E_USER_ERROR);
                    } else {
                        echo "Updated column successfully" . $stmt->affected_rows;
                    }
                    $stmt->close();
                }
                ?>
            </div>
        </div>
        <script>

            const myModal = document.getElementById('Modal');
            const joins = document.getElementById('joins');

            myModal.addEventListener('shown.bs.modal', () => {
                joins.focus();
            });

            if (myModal) {
                myModal.addEventListener('show.bs.modal', event => {
                    // Button that triggered the modal
                    const button = event.relatedTarget;
                    // Extract info from data-bs-* attributes
                    const recipient = button.getAttribute('data-bs-whatever');
                    // If necessary, you could initiate an Ajax request here
                    // and then do the updating in a callback.

                    // Update the modal's content.
                    const modalTitle = myModal.querySelector('.modal-title');
                    const modalBodyInput = myModal.querySelector('.modal-body input');

                    modalTitle.textContent = `New message to ${recipient}`;
                    modalBodyInput.value = recipient;
                });
            }
        </script>
        <div class="modal fade" id="Modal1" tabindex="-1" aria-labelledby="Modal1Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <script src="../assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Select a column to relate</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
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
                        success: function (response) {
                            $('#seltables').html(response);
                        },
                        error: function () {
                            alert("Something went wrong!");
                        }
                    });
                }).trigger("change");
            });
                        </script>

                        <form class="row form-horizontal" method="POST" if="queries">
                            <fieldset>
                                <input type="text" id="idtb" name="idtb" value="" style="display: none;" />
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

