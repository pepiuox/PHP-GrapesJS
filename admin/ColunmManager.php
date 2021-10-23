<?php
$w = '';
if (isset($_GET['w'])) {
    $w = $_GET['w'];
}
if ($w == "set") {
    $table = '';
    if (isset($_GET['tbl'])) {
        $table = $_GET['tbl'];
    }
    if ($result = $conn->query("SELECT * FROM table_config")) {
        $total_found = $result->num_rows;

        if ($total_found > 0) {
            $row = $result->fetch_assoc();
            $tableNames = explode(',', $row['table_name']);
        }
    }
    ?>
    <script>
    $(function () {
        $("#selecttb").change(function () {
            var selecttb = $(this).val();
            var value = selecttb;
            //var path=$(location).attr('href');     
            value = value.replace("_", " ");
            var url = 'ColunmManager.php?w=set&tbl=' + selecttb;
            $('#fttl').text('Form ' + value);
            window.location.replace(url);
        });
    });
    </script>
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-6">
                <h3 id="fttl">List of application tables</h3>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select id="selecttb" name="selecttb" class="form-control">
                        <option value="">Select a Table </option>
                        <?php
                        foreach ($tableNames as $tname) {
                            $remp = str_replace("_", " ", $tname);
                            echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
    extract($_POST);
    $check_exist_qry = "SELECT * FROM table_settings WHERE table_name='$table'";

    $run_qry = $conn->query($check_exist_qry);
    $total_found = $run_qry->num_rows;
    if ($total_found > 0) {
        $my_value = $run_qry->fetch_assoc();
        $vTable = explode(',', $my_value['views_name']);
        $aTable = explode(',', $my_value['adds_name']);
        $uTable = explode(',', $my_value['updates_name']);
        $dTable = explode(',', $my_value['deletes_name']);
        $pTable = explode(',', $my_value['permits_name']);

        if (isset($submit)) {
            $views_value = implode(",", $_POST['views']);
            $adds_value = implode(",", $_POST['adds']);
            $updates_value = implode(",", $_POST['updates']);
            $deletes_value = implode(",", $_POST['deletes']);
            $permits_value = implode(",", $_POST['permits']);

// update
            $upd_qry = "UPDATE table_settings SET views_name='" . $views_value . "', adds_name='" . $adds_value . "', updates_name='" . $updates_value . "', deletes_name='" . $deletes_value . "', permits_name='" . $permits_value . "' WHERE table_name='.$table.";
            $conn->query($upd_qry);
            header("Location: ColunmManager.php?w=set&tbl=$table");
        }
        ?>        
        <div class="container">
            <div class="row">
                <form class="form-horizontal" method="post" action="">
                    <div class="col_md_12">
                        <h3 class="col-md-4 control-label" for="checkviews">Tables you want to view:</h3>
                    </div>
                    <div class="col-md-4">
                        <table width="100%" cellspacing="0" cellpadding="0" border="1">
                            <thead>
                                <tr>
                                    <th valign="top" align="center">Columns<br>
                                    </th>
                                    <th valign="top" align="center">View<br>
                                    </th>
                                    <th valign="top" align="center">Add<br>
                                    </th>
                                    <th valign="top" align="center">Update<br>
                                    </th>
                                    <th valign="top" align="center">Delete<br>
                                    </th>
                                    <th valign="top" align="center">Permissions <br>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                $x = 0;
                                $result = $conn->query("SELECT * FROM " . $table);
                                $finfos = $result->fetch_fields();

                                foreach ($finfos as $val) {
                                    $ColNames[] = $val->name;
                                }

                                foreach ($ColNames as $key => $tname) {
                                    $remp = str_replace("_", " ", $tname);
                                    echo '<tr>';
                                    echo '<td>';
                                    echo ' <div class="form-group">';
                                    echo '<div class="checkbox">' . "\n";
                                    echo '<label for="checknames-' . $key . '">';
                                    echo ucfirst($remp) . '</label>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</td>';

                                    echo '<td>';
                                    echo ' <div class="form-group">';
                                    echo '<div class="checkbox">' . "\n";
                                    echo '<label for="checkviews-' . $key . '">';
                                    echo '<input class="form-check-input" type="checkbox" id="checkviews-' . $key . '" name="views[]" value="' . $tname . '" ';
                                    if (in_array($tname, $vTable)) {
                                        echo "checked";
                                    }
                                    echo '> ';
                                    echo ucfirst($remp) . '</label>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</td>';

                                    echo '<td>';
                                    echo ' <div class="form-group">';
                                    echo '<div class="checkbox">' . "\n";
                                    echo '<label for="checkadds-' . $key . '">';
                                    echo '<input class="form-check-input" type="checkbox" id="checkadds-' . $key . '" name="adds[]" value="' . $tname . '" ';
                                    if (in_array($tname, $aTable)) {
                                        echo "checked";
                                    }
                                    echo '> ';
                                    echo ucfirst($remp) . '</label>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</td>';

                                    echo '<td>';
                                    echo ' <div class="form-group">';
                                    echo '<div class="checkbox">' . "\n";
                                    echo '<label for="checkupdates-' . $key . '">';
                                    echo '<input class="form-check-input" type="checkbox" id="checkupdates-' . $key . '" name="updates[]" value="' . $tname . '" ';
                                    if (in_array($tname, $uTable)) {
                                        echo "checked";
                                    }
                                    echo '> ';
                                    echo ucfirst($remp) . '</label>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</td>';

                                    echo '<td>';
                                    echo ' <div class="form-group">';
                                    echo '<div class="checkbox">' . "\n";
                                    echo '<label for="checkdeletes-' . $key . '">';
                                    echo '<input class="form-check-input" type="checkbox" id="checkdeletes-' . $key . '" name="deteles[]" value="' . $tname . '" ';
                                    if (in_array($tname, $dTable)) {
                                        echo "checked";
                                    }
                                    echo '> ';
                                    echo ucfirst($remp) . '</label>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</td>';

                                    echo '<td>';
                                    echo ' <div class="form-group">';
                                    echo '<div class="checkbox">' . "\n";
                                    echo '<label for="checkpermits-' . $key . '">';
                                    echo '<input class="form-check-input" type="checkbox" id="checkpermits-' . $key . '" name="permits[]" value="' . $tname . '" ';
                                    if (in_array($tname, $pTable)) {
                                        echo "checked";
                                    }
                                    echo '> ';
                                    echo ucfirst($remp) . '</label>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</div>' . "\n";
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                        <div class="form-group">
                            <button type="submit" id="submit" name="submit"
                                    class="btn btn-primary">
                                <span class="fas fa-plus-square"></span> View Table
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    } else {
        if (isset($_POST['submit'])) {
            $table_name = $_POST['addtb'];
            $ins_qry = "INSERT INTO table_settings(table_name) VALUES('" . $table_name . "')";
            $conn->query($ins_qry);
            header("Location: ColunmManager.php?w=set&tbl=$table_name");
        }
        ?>
        <form method="post">
            <div class="form-group">
                <select id="addtb" name="addtb" class="form-control">
                    <option value="">Select a Table </option>
                    <?php
                    foreach ($tableNames as $tname) {
                        $remp = str_replace("_", " ", $tname);
                        echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" id="submit" name="submit"
                        class="btn btn-primary">
                    <span class="fas fa-plus-square"></span> Add table
                </button>
            </div>
        </form>
        <?php
        echo '<h4>The table is not added <h4>';
    }
}
?>




