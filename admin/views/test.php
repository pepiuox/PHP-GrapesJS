<?php
$tble = protect($_GET['tbl']);
$id = protect($_GET['id']);

$ttl = $conn->prepare("SELECT * FROM table_column_settings WHERE tqop_Id='$id' AND name_table='$tble'");
$ttl->bind_param("is", $id, $tble);
$ttl->execute();
$ucol = $ttl->get_result();
$ttl->close();
$ttn = $ucol->fetch_assoc();
$cnm = $ttn['col_name'];

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
                <h4>Web Site Configuration</h4>
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
                        <div class="active tab-pane" role="tabpanel" id="optionsshow">
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM table_queries WHERE name_table=?, col_name=?");
                            $stmt->bind_param("ss", $tble, $cnm);
                            $stmt->execute();
                            $upcol = $stmt->get_result();
                            $stmt->close();
                            $finfo = $upcol->fetch_assoc();
                            $cln = ucfirst(str_replace("_", " ", $finfo['col_name']));
                            ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <h3>Column selected - <?php echo ucfirst(str_replace("_", " ", $ttn['col_name'])) . ' from table ' . ucfirst(str_replace("_", " ", $tble)); ?></h3>
                                <?php
                                $result0 = $conn->query("SHOW COLUMNS FROM table_column_settings");
                                $bq = array();

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
                                $tbcol = $conn->prepare("SELECT * FROM table_column_settings WHERE tqop_Id=? AND name_table=?");
                                $tbcol->bind_param("ss", $id, $tble);
                                $tbcol->execute();
                                $tbsc = $tbcol->get_result();
                                $tbnums = $tbsc->num_rows;
                                if ($tbnums > 0) {
                                    while ($tbs = $tbsc->fetch_array()) {
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
                                ?>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="properties">
                            <?php
                            if (isset($_POST[sumitin])) {

                                $stmt = $conn->prepare("UPDATE table_queries SET input_type = ? WHERE tque_Id = ?");
                                $stmt->bind_param('ii', $sltb, $inp);
                                $status = $stmt->execute();
                                if ($status === false) {
                                    trigger_error($stmt->error, E_USER_ERROR);
                                } else {
                                    echo "Updated column successfully" . $stmt->affected_rows;
                                }
                            }
                            ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <fieldset>
                                    <script>
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
                                    </script>

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
                        </div>
                        <div class="tab-pane" role="tabpanel" id="addquery">
                            <form action="" method="post" enctype="multipart/form-data">
                                <fieldset>
                                    <?php
                                    if (isset($_POST['submitqr'])) {

                                        $stmt = $conn->prepare("UPDATE table_queries SET query = ? WHERE tque_Id = ?");
                                        $stmt->bind_param('si', $sltb, $inp);
                                        $status = $stmt->execute();
                                        if ($status === false) {
                                            trigger_error($stmt->error, E_USER_ERROR);
                                        } else {
                                            echo "Updated column successfully" . $stmt->affected_rows;
                                        }
                                    }
                                    ?>
                                    <?php
                                    echo '<div class="form-group">
                            <label for="' . $finfo['col_name'] . '">Query for ' . ucfirst($remp) . ':</label>
                            <textarea type="text" class="form-control" id="' . $finfo['col_name'] . '" name="' . $finfo['col_name'] . '">' . $finfo['query'] . '</textarea>
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
                                $inp = protect($_POST['idtb']);
                                $sltb = protect($_POST['stb']);
                                $tb = protect($_POST['j_table']);
                                $joins = protect($_POST['joins']);
                                $sr = protect($_POST['column_id']);
                                $sv = protect($_POST['column_value']);

                                $stmt = $conn->prepare("UPDATE table_queries SET joins = ?, j_table = ?, j_id = ?, j_value = ? WHERE tque_Id = ?");
                                $stmt->bind_param('ssssi', $joins, $tb, $sr, $sv, $inp);
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
                            <form class="row form-horizontal" method="POST" id="queries">
                                <fieldset>
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

                                    <input type="text" id="idtb" name="idtb" value="" style="display: none;" />
                                    <input type="text" id="stb" name="stb" value="" style="display: none;" />
                                    <h3 class="py-4">Relate column value to different tables</h3>
                                    <h4 class="text-primary">Select table to relate</h4>
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
