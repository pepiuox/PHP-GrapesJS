<?php

class MyCRUD {

    public $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
    }

    public function protect($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES);
    }

    public function sQueries($tble) {

        $sql = "SELECT * FROM $tble";
        return $this->connection->query($sql);
    }

    public function wQueries($query) {

        return $this->connection->query($query);
    }

    public function getID($tble) {
        if ($result = $this->sQueries($tble)) {
            /* Get field information for 2nd column */
            $result->field_seek(0);
            $finfo = $result->fetch_field();
            return $finfo->name;
        }
    }

    public function getColumnNames($tble) {
        $sql = 'DESCRIBE ' . $tble;
        $result = $this->wQueries($sql);
        $rows = array();
        while ($row = $result->fetch_fields()) {
            $rows[] = $row['Field'];
        }
        return $rows;
    }

    public function showCol($tble) {
        $nDB = DBNAME;
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$nDB' AND TABLE_NAME = '$tble'";
        $result = $this->wQueries($sql);
        $columnArr = array();
        while ($row = $result->fetch_assoc()) {
            $columnArr[] = $row['DATA_TYPE'];
        }
        return $columnArr;
    }

    public function viewColumns($tble) {
        $hostDB = DBHOST;
        $userDB = DBUSER;
        $passDB = DBPASS;
        $baseDB = DBNAME;
        try {
            $dbDdata = new PDO("mysql:host=$hostDB;dbname=$baseDB", $userDB, $passDB);
        } catch (Exception $e) {
            echo "Ocurri� algo con la base de datos: " . $e->getMessage();
        }
        return $dbDdata->query("SELECT COLUMN_NAME AS name, DATA_TYPE AS type
            FROM information_schema.columns WHERE
            table_schema = '$baseDB'
            AND table_name = '$tble'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function tblQueries($tble) {
        $sqlq = "SELECT * FROM table_queries WHERE name_table='$tble' AND input_type IS NOT NULL";
        $resultq = $this->wQueries($sqlq);
        $rowcq = $resultq->num_rows;
        $r = 0;

        if ($r < $rowcq) {
            $nif = array();
            $qers = array();
            $ctl = array();
            while ($rqu = $resultq->fetch_array()) {

                $c_nm = $rqu['col_name'];
                $c_jo = $rqu['joins'];
                $c_tb = $rqu['j_table'];
                $c_id = $rqu['j_id'];
                $c_vl = $rqu['j_value'];
                $ctl[] = '$finfo->name != "' . $c_id . '" && $finfo->name != "' . $c_vl . '"';
                $qers[] = $c_jo . " (SELECT " . $c_id . ', ' . $c_vl . ' FROM ' . $c_tb . ') ' . $c_tb . ' ON ' . $tble . '.' . $c_nm . '=' . $c_tb . '.' . $c_id;
                $nif[] = "if (\$finfo->name === '{$c_nm}') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <select type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" >';

                    \$qres = \$this->connection->query(\"SELECT * FROM  {$c_tb}\");
                    while (\$rqj = \$qres->fetch_array()) {
                        echo '<option value=\"' . \$rqj['{$c_id}'] . '\">' . \$rqj['{$c_vl}'] . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }";
            }

            $valr = implode(" ", $qers);
            $nifs = implode("else", $nif);
            $ctls = implode(" && ", $ctl);

            $sql = "SELECT * FROM $tble $valr";
        } else {
            $sql = "SELECT * FROM $tble";
        }
        return;
    }

    public function getList($sql, $col) {
        $result = $this->wQueries($sql);

        if (mysqli_num_fields($result) > 0) {
            while ($th = $result->fetch_field()) {
                $ths[] = $th->name;
            }

            echo '<table class="table">
<thead>
<tr>' . "\n";
            foreach ($ths as $tnms) {
                $tremp = ucfirst(str_replace("_", " ", $tnms));
                $remp = str_replace(" id", " ", $tremp);
                echo '<th>' . $remp . '</th>' . "\n";
            }
            echo '</tr>
</thead>
<tbody>' . "\n";
            $r = 1;
            while ($td = $result->fetch_array()) {
                $nr = $r++;
                echo '<tr id="row_' . $nr . '">';
                foreach ($ths as $tnms) {
                    if ($tnms === $col) {
                        echo '<td id="' . $tnms . '"><input type="text" name="' . $tnms . '[]" value="' . $td[$tnms] . '"></td>' . "\n";
                    } else {
                        echo '<td id="' . $tnms . '"><input type="text" name="' . $tnms . '[]" value="' . $td[$tnms] . '" readonly></td>' . "\n";
                    }
                }
                echo '</tr>' . "\n";
            }
            echo '</tbody>
</table>' . "\n";
        }
    }

    public function listColm($tble) {
        $result = $this->sQueries($tble);

        $i = 0;
        echo '<form method="POST" enctype="multipart/form-data">' . "\n";
        echo '<table class="table table-bordered">' . "\n";
        echo '<thead class="bg-info">' . "\n";
        echo '<tr>' . "\n";
        while ($i < mysqli_num_fields($result)) {
            $meta = mysqli_fetch_field($result);
            $remp = str_replace("_", " ", $meta->name);
            echo '<th>' . ucfirst($remp) . '</th>' . "\n";
            $i = $i + 1;
        }
        echo '<th><a href="./forms.php?a=' . $tble . '&b=add" id="addrow" name="addrow" class="btn btn-primary">Add new</a></th>' . "\n";
        echo '</tr>' . "\n";
        echo '</thead>' . "\n";
        echo '<tbody>' . "\n";

        // pagination
        $searching = 0;
        if (isset($_POST['qry'])) {
            $searching = 1;
            $qry = protect($_POST['qry']);
        }
        $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
        $limit = 20;
        $startpoint = ($page * $limit) - $limit;
        if ($page == 1) {
            $i = 1;
        } else {
            $i = $page * $limit;
        }

        if ($searching == 1) {
            if (empty($qry)) {
                $qry = 'empty query';
            }

            $result = $this->wQueries("SELECT * FROM $tble WHERE id LIKE '%$qry%' ORDER BY id");
        } else {
            $result = $this->wQueries("SELECT * FROM $tble ORDER BY id LIMIT {$startpoint} , {$limit}");
        }
        // end pagination
        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_row()) {
                echo '<tr>' . "\n";
                $count = count($row);
                $y = 0;
                while ($y < $count) {
                    $c_row = current($row);
                    if ($y == 0) {
                        echo '<td id="' . $c_row . '">' . $c_row . '</td>' . "\n";
                    } else {
                        echo '<td>' . $c_row . '</td>' . "\n";
                    }
                    next($row);
                    $y = $y + 1;
                }

                $i_row = $row[0];
                echo '<td><!-- Button -->
                      <a href="./?a=' . $tble . '&b=edit&id=' . $i_row . '" title="Edit"><i class="fa fa-pencil"></i></a>
                      <a href="./?a=' . $tble . '&b=delete&id=' . $i_row . '" title="Delete"><i class="fa fa-times"></i></a>
</td>';

                echo '</tr>' . "\n";
                $i = $i + 1;
            }
        } else {
            if ($searching == "1") {
                echo '<tr><td colspan="8">No hay resultados para <b>' . $qry . '</b>.</td></tr>';
            } else {
                echo '<tr><td colspan="8">Still no have exchanges.</td></tr>';
            }
        }
        echo '</tbody>' . "\n";
        echo '</table>' . "\n";

        if ($searching == "0") {
            $ver = "./forms.php?a=" . $tble;
            if (admin_pagination($tble, $ver, $limit, $page)) {
                echo admin_pagination($tble, $ver, $limit, $page);
            }
        }

        echo '</form>' . "\n";
        mysqli_free_result($result);
    }

    public function getDatalist($tble) {

        $total_pages = $this->connection->query("SELECT * FROM $tble")->num_rows;

        $colmns = $this->viewColumns($tble);

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

        $num_results_on_page = 10;

        if ($stmt = $this->connection->prepare("SELECT * FROM $tble LIMIT ?,?")) {

            $calc_page = ($page - 1) * $num_results_on_page;
            $stmt->bind_param('ii', $calc_page, $num_results_on_page);
            $stmt->execute();

            $result = $stmt->get_result();

            echo '
	<table class="table">
			<thead>
				<tr>
<th><a id="addrow" name="addrow" title="Agregar" class="btn btn-primary" href="dashboard.php?cms=crud&w=add&tbl=' . $tble . '">Agregar <i class="fa fa-plus-square" aria-hidden="true"></i></a></th>';
            foreach ($colmns as $colmn) {
                $tremp = ucfirst(str_replace("_", " ", $colmn->name));
                $remp = str_replace(" id", " ", $tremp);
                echo '<th>' . $remp . '</th>';
            }
            echo '
			</tr>
			</thead>
			<tbody>' . "\n";
            while ($row = $result->fetch_array()) {

                echo '<tr>' . "\n";
                echo '<td><!--Button -->
                <a id="editrow" name="editrow" title="Editar" class="btn btn-success" href="dashboard.php?cms=crud&w=edit&tbl=' . $tble . '&id=' . $row[0] . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
<a id="deleterow" name="deleterow" title="Eliminar" class="btn btn-danger" href="dashboard.php?cms=crud&w=delete&tbl=' . $tble . '&id=' . $row[0] . '"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </td>' . "\n";
                foreach ($colmns as $colmn) {
                    $fd = $row[$colmn->name];
                    $resultq = $this->connection->query("SELECT * FROM table_queries WHERE name_table='$tble' AND col_name='$colmn->name' AND input_type IS NOT NULL");

                    if ($resultq->num_rows > 0) {
                        while ($trow = $resultq->fetch_array()) {

                            if ($colmn->name === 'imagen') {
                                echo '<td><img src="' . $row[$colmn->name] . '" style="width:auto; height: 100px;"></td>';
                            } else {
                                $tb = $trow['j_table'];
                                $id = $trow['j_id'];
                                $val = $trow['j_value'];
                                $rest = $this->connection->query("SELECT * FROM $tb WHERE $id='$fd'");
                                $tow = $rest->fetch_assoc();
                                echo '<td><a class="goto" href="buscar.php?w=find&tbl=' . $tb . '&id=' . $fd . '">' . $tow[$val] . '</a></td>';
                            }
                        }
                    } else {
                        echo '<td>' . $row[$colmn->name] . '</td>';
                    }
                }

                echo '</tr>' . "\n";
            }
            echo '</tbody>
		</table>' . "\n";

            if (ceil($total_pages / $num_results_on_page) > 0) {
                $url = 'dashboard.php?cms=crud&w=list&tbl=' . $tble;
                ?>
                <nav aria-label="navigation mx-auto">
                    <ul class="pagination justify-content-center">
                <?php if ($page > 1) { ?>
                            <li class="prev"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page - 1 ?>">Anterior</a></li>
                        <?php } ?>

                <?php if ($page > 3) { ?>
                            <li class="start"><a href="<?php echo $url; ?>&page=1">1</a></li>
                            <li class="dots">...</li>
                        <?php } ?>

                <?php if ($page - 2 > 0) { ?>
                            <li class="page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a></li>
                            <?php } ?>
                <?php if ($page - 1 > 0) { ?>
                            <li class="page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a></li>
                <?php } ?>

                        <li class="currentpage"><a
                                href="<?php echo $url; ?>&page=<?php echo $page ?>"><?php echo $page ?></a></li>

                <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1) { ?>
                            <li class="page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a></li>
                            <?php } ?>
                <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1) { ?>
                            <li class="page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a></li>
                        <?php } ?>

                <?php if ($page < ceil($total_pages / $num_results_on_page) - 2) { ?>
                            <li class="dots">...</li>
                            <li class="end"><a
                                    href="<?php echo $url; ?>&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
                        <?php } ?>

                <?php if ($page < ceil($total_pages / $num_results_on_page)) { ?>
                            <li class="next"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page + 1 ?>">Siguiente</a></li>
                <?php } ?>
                    </ul>
                </nav>
                <?php
            }
            $stmt->close();
        }
    }

    public function listData($tble) {
        $colms = $this->viewColumns($tble);
        $ncol = $this->getID($tble);

        $resultq = $this->wQueries("SELECT * FROM table_queries WHERE name_table='$tble' AND input_type IS NOT NULL");
        $resv = $resultq->num_rows;

        $r = 0;
        // start vars
        if ($resv > $r) {

            $qers = array();
            $ttl = array();
            $ctl = array();
            $fcols = array();

            while ($row = $resultq->fetch_array()) {
                $c_nm = $row['col_name'];
                $c_jo = $row['joins'];
                $c_tb = $row['j_table'];
                $c_id = $row['j_id'];
                $c_vl = $row['j_value'];
                $ttl[] = '$meta->name != "' . $c_id . '" && $meta->name != "' . $c_vl . '"';
                $ctl[] = '$name != "' . $c_id . '" && $name != "' . $c_vl . '"';
                $fcols[] = "if(\$name == '{$c_nm}'){echo '<td>'.\$rw['{$c_vl}'].'</td>';}" . "\n";
                $qers[] = $c_jo . " (SELECT " . $c_id . ', ' . $c_vl . ' FROM ' . $c_tb . ') ' . $c_tb . ' ON ' . $tble . '.' . $c_nm . '=' . $c_tb . '.' . $c_id;
            }
            $vtl = implode(" && ", $ttl);
            $valr = implode(" ", $qers);
            $fcol = implode(" else", $fcols);
            $ctls = implode(" && ", $ctl);
        }

        // end vars

        $start = 1;
        $range = 10;
        $startpage = 1;

        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $pg = $this->protect($_GET['page']);
            $pg = filter_var($pg, FILTER_SANITIZE_NUMBER_INT);
            $page = $pg - $start;
            $pages = "OFFSET " . ($range * $page);
            if ($r < $resv) {
                $sel = "SELECT * FROM {$tble} {$valr} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} {$valr} LIMIT {$range} {$pages}";
            } else {
                $sel = "SELECT * FROM {$tble} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} LIMIT {$range} {$pages}";
            }
        } else {
            $pg = 1;
            $page = 0;
            if ($resv > $r) {
                $sel = "SELECT * FROM {$tble} {$valr} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} {$valr} LIMIT {$range}";
            } else {
                $sel = "SELECT * FROM {$tble} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} LIMIT {$range}";
            }
        }

        $endpage = '';
        if ($nres = $this->sQueries($tble)) {
            $rowcq = $nres->num_rows;
            $endpage = ceil($rowcq / $range);
        }

        $res = $this->wQueries($sel);
        $result = $this->wQueries($select);

        $i = 0;
        if ($resv > $i) {
            $rvfile = 'ftmp.php';
            $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
            $content = '<?php' . "\n";
            $content .= "if ({$vtl}) {" . "\n";
            $content .= "echo '<th>' . ucfirst(\$remp) . '</th>';" . "\n";
            $content .= "}" . "\n";
            $content .= "?> \n";

            fwrite($mfile, $content);
            fclose($mfile);
        }

        // start form
        // start table head
        $names = array();
        echo '<form method="POST">' . "\n";
        echo '<table class="table table-bordered table table-striped table-hover">' . "\n";
        echo '<thead class="bg-info">' . "\n";
        echo '<tr>' . "\n";
        foreach ($colms as $meta) {
            $names[] = $meta->name;
            $tremp = ucfirst(str_replace("_", " ", $meta->name));
            $remp = str_replace(" id", " ", $tremp);
            if ($resv > $i) {
                include 'ftmp.php';
            } else {
                echo '<th>' . $remp . '</th>';
            }
        }

        echo '<th><a id="addrow" name="addrow" class="btn btn-primary" href="dashboard.php?cms=crud&w=add&tbl=' . $tble . '">Agregar</a></th>' . "\n";
        echo '</tr>' . "\n";
        echo '</thead>' . "\n";
        echo '<tbody>' . "\n";
        // end table head
        // start body table
        while ($row = mysqli_fetch_row($res)) {
            echo '<tr>' . "\n";
            $rw = $result->fetch_array();
            $count = count($row);

            $y = 0;
            if ($count > $y) {

                foreach ($names as $key => $name) {
                    if ($resv > $y) {

                        $vrfile = 'vtmp.php';
                        $vfile = fopen("$vrfile", "w") or die("Unable to open file!");
                        $varcont = '<?php' . "\n";
                        $varcont .= "if (\$key == 0) {" . "\n";
                        $varcont .= "echo '<td id=\"'.\$rw['" . $ncol . "'].'\">'.\$rw['" . $ncol . "'].'</td>';" . "\n";
                        $varcont .= "}else";
                        $varcont .= $fcol;
                        $varcont .= "elseif({$ctls}){" . "\n";
                        $varcont .= "echo '<td>' . \$rw[\$name] . '</td>';" . "\n";
                        $varcont .= "} ?> \n";

                        fwrite($vfile, $varcont);
                        fclose($vfile);

                        include 'vtmp.php';
                    } else {
                        if ($key == 0) {
                            echo '<td id="' . $rw[$key] . '">' . $rw[$key] . '</td>' . "\n";
                        } else {
                            echo '<td>' . $rw[$name] . '</td>' . "\n";
                        }
                    }
                }
                next($row);
                $y++;
            }

            $i_row = $row[0];
            echo '<td><!--Button -->
                <a id="editrow" name="editrow" class="btn btn-success" href="dashboard.php?cms=crud&w=edit&tbl=' . $tble . '&id=' . $i_row . '">Editar</a>
                <a id="deleterow" name="deleterow" class="btn btn-danger" href="dashboard.php?cms=crud&w=delete&tbl=' . $tble . '&id=' . $i_row . '">Borrar</a>
                </td>';

            echo '</tr>' . "\n";
            $i++;
        }
        echo '</tbody>' . "\n";
        echo '</table>' . "\n";
        // end body table
        // end
        $url = 'dashboard.php?cms=crud&w=list&tbl=' . $tble;

        if ($i < $rowcq) {
            echo '<nav aria-label="navigation">';
            echo '<ul class="pagination justify-content-center">' . "\n";

            echo '<li class="page-item';
            if ($page < $startpage) {
                echo ' disabled';
            }
            echo '"><a class="page-link" href="' . $url . '&page=' . $startpage . '">First</a></li>' . "\n";

            echo '<li class="page-item ';
            if ($page < 1) {
                echo 'disabled';
            }
            echo '"><a class="page-link" href="';
            if ($page <= 1) {
                echo '#';
            } else {
                echo $url . "&page=" . $page;
            }
            echo '">Prev</a></li>' . "\n";
            //
            for ($x = 1; $x <= $range; $x++) {
                if ($pg < $endpage) {
                    echo '<li class="page-item ';
                    if ($pg == ($page + 1)) {
                        echo 'disabled';
                    }
                    echo '"><a class="page-link" href="';
                    if ($endpage < $page) {
                        echo '#';
                    } else {
                        echo $url . "&page=" . $pg;
                    }
                    echo '">' . $pg++ . '</a></li>' . "\n";
                } elseif ($pg > $endpage) {
                    continue;
                } else {
                    echo '<li class="page-item ';
                    if ($pg == ($page + 1)) {
                        echo 'disabled';
                    }
                    echo '"><a class="page-link" href="';
                    if ($endpage < $page) {
                        echo '#';
                    } else {
                        echo $url . "&page=" . $pg;
                    }
                    echo '">' . $pg++ . '</a></li>' . "\n";
                }
            }
            //
            echo '<li class="page-item ';
            if ($endpage == $_GET['page']) {
                echo 'disabled';
            }
            echo '"><a class="page-link" href="';
            if ($pg < $endpage) {
                echo '#';
            } else {
                echo $url . "&page=" . ($pg + 1);
            }
            echo '">Next</a></li>' . "\n";

            echo '<li class="page-item';
            if ($endpage == $_GET['page']) {
                echo ' disabled';
            }
            echo '"><a class="page-link" href="' . $url . '&page=' . $endpage . '">Last</a></li>' . "\n";

            echo '</ul>' . "\n";
            echo '</nav>' . "\n";
        }
    }

    public function joinCols($tble) {

        $columns = $this->viewColumns($tble);
        $ncol = $this->getID($tble);
        //
        $sqlq = "SELECT * FROM table_queries WHERE name_table='$tble'";
        $resultq = $this->connection->query($sqlq);
        $rowcq = mysqli_num_rows($resultq);
        if ($rowcq > 0) {
            while ($rqu = $resultq->fetch_assoc()) {

                $c_nm = $rqu['col_name'];
                $c_tp = $rqu['col_type'];
                $i_tp = $rqu['input_type'];
                // $c_jo = $rqu['joins'];
                $c_tb = $rqu['j_table'];
                $c_id = $rqu['j_id'];
                $c_vl = $rqu['j_value'];

                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                if ($c_nm === $ncol) {
                    continue;
                }

                if ($c_tp === 'int' || $c_tp === 'tinyint' || $c_tp === 'smallint' || $c_tp === 'mediumint' || $c_tp === 'bigint' || $c_tp === 'bit' || $c_tp === 'float' || $c_tp === 'double' || $c_tp === 'decimal') {

                    if ($i_tp != 3) {
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
                    } else {
                        // -------------
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <select type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                        $sqp1 = "select * from $c_tb";

                        $qres = $this->connection->query($sqp1);

                        while ($options = $qres->fetch_array()) {
                            echo '<option value="' . $options[$c_id] . '">' . $options[$c_vl] . '</option>' . "\n";
                        }

                        echo '</select>' . "\n";
                        echo '</div>' . "\n";
                        // --------------
                    }
                }
                if ($c_tp === 'time' || $c_tp === 'year') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
                }
                if ($c_tp === 'date' || $c_tp === 'datetime' || $c_tp === 'timestamp') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
                    echo '<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#' . $c_nm . '").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#' . $c_nm . '").datepicker("setDate", new Date());
                                        });
                                    </script>' . "\n";
                }
                if ($c_tp === 'varchar' || $c_tp === 'char') {
                    if ($c_nm === 'imagen') {
                        echo "<script>$('.custom-file-input').on('change',function(){
                            var fileName = document.getElementById('imagen').files[0].name;
                            $(this).next('.form-control-file').addClass('selected').php(fileName);
                        });</script>";
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="' . $c_nm . '">Subir</span>
  </div>
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="' . $c_nm . '" name="' . $c_nm . '"
      aria-describedby="i' . $c_nm . '">
    <label class="custom-file-label" for="' . $c_nm . '">Elegir archivo</label>
  </div>
</div>
<div id="preview">
                    		<?php echo $preview;?>
                    	</div>
</div>
' . "\n";
                    } else {
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
                    }
                }
                if ($c_tp === 'text' || $c_tp === 'tinytext' || $c_tp === 'mediumtext' || $c_tp === 'longtext' || $c_tp === 'json') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'point' || $c_tp === 'linestring' || $c_tp === 'polygon' || $c_tp === 'geometry' || $c_tp === 'multipoint' || $c_tp === 'multilinestring' || $c_tp === 'multipolygon' || $c_tp === 'geometrycollection') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'binary' || $c_tp === 'varbinary' || $c_tp === 'tinyblob' || $c_tp === 'blob' || $c_tp === 'mediumblob' || $c_tp === 'longblob') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'enum' || $c_tp === 'set') {
                    // ----------------------
                    $isql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tble . "' AND COLUMN_NAME = '" . $c_nm . "'";

                    $iresult = $this->connection->query($isql);
                    $row = mysqli_fetch_array($iresult);
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
                    //
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <select type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                    $options = $enum_list;
                    foreach ($options as $option) {
                        $soption = '<option value="' . $option . '"';
                        $soption .= ($default_value === $option) ? ' SELECTED' : '';
                        $soption .= '>' . $option . '</option>' . "\n";
                        echo $soption . "\n";
                    }
                    echo '</select>' . "\n";
                    echo '</div>' . "\n";

                    // ----------------------
                }
            }
        } else {
            foreach ($columns as $dtpe) {
                $remp = ucfirst(str_replace("_", " ", $dtpe->name));
                $frmp = str_replace(" id", "", $remp);

                if ($dtpe->name === $ncol) {
                    continue;
                }

                if ($dtpe->type === 'int' || $dtpe->type === 'tinyint' || $dtpe->type === 'smallint' || $dtpe->type === 'mediumint' || $dtpe->type === 'bigint' || $dtpe->type === 'bit' || $dtpe->type === 'float' || $dtpe->type === 'double' || $dtpe->type === 'decimal') {

                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                }
                if ($dtpe->type === 'time' || $dtpe->type === 'year') {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                }
                if ($dtpe->type === 'date' || $dtpe->type === 'datetime' || $dtpe->type === 'timestamp') {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                    echo '<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#' . $dtpe->name . '").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#' . $dtpe->name . '").datepicker("setDate", new Date());
                                        });
                                    </script>' . "\n";
                }
                if ($dtpe->type === 'varchar' || $dtpe->type === 'char') {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                }
                if ($dtpe->type === 'text' || $dtpe->type === 'tinytext' || $dtpe->type === 'mediumtext' || $dtpe->type === 'longtext' || $dtpe->type === 'json') {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '"></textarea>
                  </div>' . "\n";
                }
                if ($dtpe->type === 'point' || $dtpe->type === 'linestring' || $dtpe->type === 'polygon' || $dtpe->type === 'geometry' || $dtpe->type === 'multipoint' || $dtpe->type === 'multilinestring' || $dtpe->type === 'multipolygon' || $dtpe->type === 'geometrycollection') {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '"></textarea>
                  </div>' . "\n";
                }
                if ($dtpe->type === 'binary' || $dtpe->type === 'varbinary' || $dtpe->type === 'tinyblob' || $dtpe->type === 'blob' || $dtpe->type === 'mediumblob' || $dtpe->type === 'longblob') {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '"></textarea>
                  </div>' . "\n";
                }
                if ($dtpe->type === 'enum' || $dtpe->type === 'set') {
                    // ----------------------
                    $isql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tble . "' AND COLUMN_NAME = '" . $dtpe->name . "'";

                    $iresult = $this->connection->query($isql);
                    $row = mysqli_fetch_array($iresult);
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
                    //
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <select type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '" >' . "\n";

                    $options = $enum_list;
                    foreach ($options as $option) {
                        $soption = '<option value="' . $option . '"';
                        $soption .= ($default_value === $option) ? ' SELECTED' : '';
                        $soption .= '>' . $option . '</option>' . "\n";
                        echo $soption . "\n";
                    }
                    echo '</select>' . "\n";
                    echo '</div>' . "\n";

                    // ----------------------
                }
            }
        }
    }

    // addrow
    public function addData($tble) {


        $ncol = $this->getID($tble);
        //
        $sql = "SELECT * FROM $tble";
        //
        $qresult = $this->connection->query($sql);
        while ($finfo = $qresult->fetch_field()) {
            if ($finfo->name == $ncol) {
                continue;
            }
            $vname[] = $finfo->name;
            $pname[] = "'$" . $finfo->name . "'";
            $ptadd[] = "$" . $finfo->name . " = \$_POST['" . $finfo->name . "'];" . "\n";
        }

        $vnames = implode(", ", $vname);
        $pnames = implode(", ", $pname);
        $ptadds = implode(" ", $ptadd);

        $rvfile = 'ftmp.php';
        $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
        $content = '<?php' . "\n";
        $content .= "if(isset(\$_POST['addrow'])){" . "\n";
        $content .= $ptadds . "\n";
        $content .= '$sql = "INSERT INTO ' . $tble . ' (' . $vnames . ')' . "\n";
        $content .= 'VALUES (' . $pnames . ')";' . "\n";
        $content .= "if (\$this->connection->query(\$sql) === TRUE) {
    echo 'Se agrego el dato correctamente';
header('Location: dashboard.php?cms=crud&w=list&tbl=" . $tble . "');
} else {
    echo 'Error: ' . \$this->connection->error;
}

\$this->connection->close();" . "\n";
        $content .= "}";
        $content .= "?> \n";

        fwrite($mfile, $content);
        fclose($mfile);
        include 'ftmp.php';

        echo '<form method="post" class="form-horizontal" role="form" id="add_' . $tble . '" enctype="multipart/form-data">' . "\n";

        $this->joinCols($tble);

        echo '<div class="form-group">
        <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="glyphicon glyphicon-plus" onclick="dVals();"></span> Add</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

    // addScript
    public function updateScript($tble, $id) {
        $result = $this->sQueries($tble);
        $ncol = $this->getID($tble);
        $r = 0;
        $postnames = array();
        $varnames = array();

        if (mysqli_num_fields($result) > $r) {
            while ($info = mysqli_fetch_field($result)) {
                if ($info->name != $ncol) {
                    $postnames[] = '$' . $info->name . ' = $_POST["' . $info->name . '"]; ' . "\r\n";
                    $varnames[] = $info->name . " = '$" . $info->name . "'";
                }
            }
        }
        $scpt = implode("", $postnames);
        $ecols = implode(", ", $varnames);

        $fichero = 'updatetmp.php';
        $myfile = fopen("$fichero", "w") or die("Unable to open file!");
        $content = '<?php' . "\n";
        $content .= '//This is temporal file only for add new row' . "\n";
        $content .= "if (isset(\$_POST['editrow'])) { \r\n";
        $content .= $scpt . "\r\n";
        $content .= '        $query="UPDATE `$tble` SET ' . $ecols . ' WHERE ' . $ncol . '=$id ";' . "\r\n";
        $content .= 'if ($this->connection->query($query) === TRUE) {
               echo "Los datos fueron actualizados correctamente.";
               header("Location: dashboard.php?cms=crud&w=list&tbl=' . $tble . '");
            } else {
               echo "Error en actualizar datos: " . $this->connection->error;
            }' . "\r\n";
        $content .= "    } \r\n";
        $content .= "?> \n";

        fwrite($myfile, $content);
        fclose($myfile);
    }

    public function inputQEdit($tble, $id) {
        $columns = $this->viewColumns($tble);
        $ncol = $this->getID($tble);
        $resultq = $this->wQueries("SELECT * FROM table_queries WHERE name_table='$tble'");
        $rowcq = mysqli_num_rows($resultq);
        $r = 0;
        if ($rowcq > $r) {
            echo '<form class="form-horizontal" role="form" id="add_' . $tble . '" method="POST" enctype="multipart/form-data">' . "\n";
            while ($rqu = $resultq->fetch_array()) {

                $qresult = $this->wQueries("SELECT * FROM $tble WHERE $ncol = '$id' ");
                $row = $qresult->fetch_assoc();

                $c_nm = $rqu['col_name'];
                $c_tp = $rqu['col_type'];
                $i_tp = $rqu['input_type'];
                // $c_jo = $rqu['joins'];
                $c_tb = $rqu['j_table'];
                $c_id = $rqu['j_id'];
                $c_vl = $rqu['j_value'];

                $cdta = $row[$c_nm];

                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                if ($c_tp === 'int' || $c_tp === 'tinyint' || $c_tp === 'smallint' || $c_tp === 'mediumint' || $c_tp === 'bigint' || $c_tp === 'bit' || $c_tp === 'float' || $c_tp === 'double' || $c_tp === 'decimal') {
                    if ($i_tp === 3) {

                        echo '
			<div class="form-group">
        <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
        <select type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" >';

                        $qres = $this->sQueries($c_tb);

                        while ($rqj = $qres->fetch_array()) {
                            if ($cdta == $rqj[$c_id]) {
                                echo '<option value="' . $rqj[$c_id] . '" selected="selected">' . $rqj[$c_vl] . '</option>';
                            } else {
                                echo '<option value="' . $rqj[$c_id] . '">' . $rqj[$c_vl] . '</option>';
                            }
                        }

                        echo '</select>';
                        echo '</div>';
                    } else {
                        echo '<div class="form-group">
				<label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label> <input type="text"
					class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"
					value="' . $cdta . '">
			</div>
			' . "\n";
                    }
                }
                if ($c_tp === 'time' || $c_tp === 'year') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                }
                if ($c_tp === 'date' || $c_tp === 'datetime' || $c_tp === 'timestamp') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                    echo '<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#' . $c_nm . '").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#' . $c_nm . '").datepicker("setDate", new Date());
                                        });
                                    </script>' . "\n";
                }
                if ($c_tp === 'varchar' || $c_tp === 'char') {
                    if ($i_tp === 4) {
                        echo '<div class="form-group">
                    <label for="' . $c_nm . '">' . $frmp . ':

                    <input type="file" accept="image/*" class="form-control custom-file-input" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
<span class="custom-file-control form-control-file"></span>
                    	<div id="preview">
                    		<?php echo $preview;?>
                    	</div>
                  </div>' . "\n";
                    } else {
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                    }
                }
                if ($c_tp === 'text' || $c_tp === 'tinytext' || $c_tp === 'mediumtext' || $c_tp === 'longtext' || $c_tp === 'json') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'point' || $c_tp === 'linestring' || $c_tp === 'polygon' || $c_tp === 'geometry' || $c_tp === 'multipoint' || $c_tp === 'multilinestring' || $c_tp === 'multipolygon' || $c_tp === 'geometrycollection') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'binary' || $c_tp === 'varbinary' || $c_tp === 'tinyblob' || $c_tp === 'blob' || $c_tp === 'mediumblob' || $c_tp === 'longblob') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'enum' || $c_tp === 'set') {
                    // ----------------------
                    $isql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tble . "' AND COLUMN_NAME = '" . $c_nm . "'";
                    $iresult = $this->wQueries($isql);
                    $row = mysqli_fetch_array($iresult);
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
                    //
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <select type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                    $options = $enum_list;
                    foreach ($options as $option) {
                        $soption = '<option value="' . $option . '"';
                        $soption .= ($default_value === $option) ? ' SELECTED' : '';
                        $soption .= '>' . $option . '</option>' . "\n";
                        echo $soption . "\n";
                    }
                    echo '</select>' . "\n";
                    echo '</div>' . "\n";

                    // ----------------------
                }
            }
            /* test input */
            echo '<div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Actualizar</button>
    </div>' . "\n";
            echo '</form>' . "\n";
        } else {
            echo '<form role="form" id="add_' . $tble . '" method="POST">' . "\n";
            foreach ($columns as $finfo) {

                $qresult = $this->wQueries("select * from $tble where $ncol = '$id' ");
                $row = $qresult->fetch_assoc();
                if ($finfo->name === $ncol) {
                    continue;
                }
                $c_nm = $finfo->name;
                $c_tp = $finfo->type;

                $cdta = $row[$c_nm];

                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                if ($c_tp === 'int' || $c_tp === 'tinyint' || $c_tp === 'smallint' || $c_tp === 'mediumint' || $c_tp === 'bigint' || $c_tp === 'bit' || $c_tp === 'float' || $c_tp === 'double' || $c_tp === 'decimal') {
                    echo '<div class="form-group">
				<label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label> <input type="text"
					class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"
					value="' . $cdta . '">
			</div>
			' . "\n";
                }
                if ($c_tp === 'time' || $c_tp === 'year') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                }
                if ($c_tp === 'date' || $c_tp === 'datetime' || $c_tp === 'timestamp') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                    echo '<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#' . $c_nm . '").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#' . $c_nm . '").datepicker("setDate", new Date());
                                        });
                                    </script>' . "\n";
                }
                if ($c_tp === 'varchar' || $c_tp === 'char') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                }
                if ($c_tp === 'text' || $c_tp === 'tinytext' || $c_tp === 'mediumtext' || $c_tp === 'longtext' || $c_tp === 'json') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'point' || $c_tp === 'linestring' || $c_tp === 'polygon' || $c_tp === 'geometry' || $c_tp === 'multipoint' || $c_tp === 'multilinestring' || $c_tp === 'multipolygon' || $c_tp === 'geometrycollection') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'binary' || $c_tp === 'varbinary' || $c_tp === 'tinyblob' || $c_tp === 'blob' || $c_tp === 'mediumblob' || $c_tp === 'longblob') {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                }
                if ($c_tp === 'enum' || $c_tp === 'set') {
                    // ----------------------
                    $isql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tble . "' AND COLUMN_NAME = '" . $c_nm . "'";

                    $iresult = $this->wQueries($isql);
                    $row = mysqli_fetch_array($iresult);
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
                    //
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <select type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                    $options = $enum_list;
                    foreach ($options as $option) {
                        $soption = '<option value="' . $option . '"';
                        $soption .= ($default_value === $option) ? ' SELECTED' : '';
                        $soption .= '>' . $option . '</option>' . "\n";
                        echo $soption . "\n";
                    }
                    echo '</select>' . "\n";
                    echo '</div>' . "\n";

                    // ----------------------
                }
            }
            /* test input */
            echo '<div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Edit</button>
    </div>' . "\n";
            echo '</form>' . "\n";
        }
    }

    // editrow
    public function editData($tble, $id) {
        $columns = $this->viewColumns($tble);
        foreach ($columns as $finfo) {
            if ($finfo->name === $this->getID($tble)) {
                continue;
            }
            $ptadd[] = "$" . $finfo->name . " = \$_POST['" . $finfo->name . "'];" . "\n";
            $pname[] = $finfo->name . "='$" . $finfo->name . "'";
        }
        $ptadds = implode(" ", $ptadd);
        $pnames = implode(", ", $pname);

        $rvfile = 'ftmp.php';
        $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
        $content = '<?php' . "\n";
        $content .= "if(isset(\$_POST['editrow'])){" . "\n\n";
        $content .= $ptadds . "\n";
        $content .= '$sql = "UPDATE $tble SET ' . $pnames . ' WHERE $ncol=' . $id . '";' . "\n";
        $content .= "if (\$this->connection->query(\$sql) === TRUE) {
        echo \"New record created successfully\";
    } else {
        echo \"Error: \" . \$sql . \"<br>\" . \$this->connection->error;
    }" . "\n";
        $content .= " }" . "\n";
        $content .= "?> \n";

        fwrite($mfile, $content);
        fclose($mfile);
        include 'ftmp.php';
    }

    // deleterow
    function deleteData($tble, $id) {
        $ncol = $this->getID($tble);
        $qresult = $this->wQueries("select * from $tble where $ncol = '$id' ");
        echo '<form role="form" id="delete_' . $tble . '" method="POST">' . "\n";
        $row = mysqli_fetch_array($qresult, MYSQLI_ASSOC);
        while ($finfo = $qresult->fetch_field()) {
            $cdta = $row[$finfo->name];
            if ($finfo->name == $ncol) {
                continue;
            } else {
                $remp = str_replace("_", " ", $finfo->name);
                echo '<div class="form-group">
        <label for="' . $finfo->name . '">' . ucfirst($remp) . ':</label>
        <input type="text" class="form-control" id="' . $finfo->name . '" name="' . $finfo->name . '" value="' . $cdta . '" readonly>
    </div>' . "\n";
            }
        }
        echo '<div class="form-group">
        <button type = "submit" id="deleterow" name="deleterow" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Delete</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

    // adduery
    public function addQuery($tble) {
        $qresult = $this->sQueries($tble);
        echo '<form method="post" role="form" id="query_' . $tble . '">' . "\n";
        while ($finfo = $qresult->fetch_field()) {
            if ($finfo->name === $this->getID($tble)) {
                continue;
            } else {
                $remp = str_replace("_", " ", $finfo->name);

                echo '<div class="form-group">
        <label for="' . $finfo->name . '">' . ucfirst($remp) . ':</label>
        <textarea type="text" class="form-control" id="' . $finfo->name . '" name="' . $finfo->name . '"></textarea>
    </div>' . "\n";
            }
        }
        echo '<div class="form-group">
        <button type = "submit" id="addqueries" name="addqueries" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Add queries</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

    // addpost
    public function addpost($tble) {
        $result = $this->sQueries($tble);
        $r = 0;
        $postnames = array();
        while (mysqli_num_fields($result) > $r) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $this->getID($tble)) {
                $postnames[] = '$' . $info->name . ' = $_POST["' . $info->name . '"]; ' . "\r\n";
            }
            $r = $r + 1;
        }
        return implode("", $postnames);
    }

    // updatedata
    public function updateData($tble) {
        $result = $this->sQueries($tble);
        $varnames = array();
        $r = 0;
        while (mysqli_num_fields($result) > $r) {
            $name = mysqli_fetch_field($result);

            if ($name->name != $this->getID($tble)) {
                $varnames[] = $name->name . " = '$" . $name->name . "'";
            }
            $r = $r + 1;
        }
        return implode(", ", $varnames);
    }

    // ifmpty
    public function ifMpty($tble) {
        $result = $this->sQueries($tble);
        $checkd = array();
        $r = 0;
        while (mysqli_num_fields($result) > $r) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $this->getID($tble)) {
                $checkd[] = '!empty($' . $info->name . ')';
            }

            $r = $r + 1;
        }
        return implode(" && ", $checkd);
    }

    // addttl
    public function addTtl($tble) {
        $result = $this->sQueries($tble);
        $checkd = array();
        $r = 0;
        while (mysqli_num_fields($result) > $r) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $this->getID($tble)) {
                $checkd[] = '`' . $info->name . '`';
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    // addtpost
    public function addTPost($tble) {
        $result = $this->sQueries($tble);
        $checkd = array();
        $r = 0;
        while (mysqli_num_fields($result) > $r) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $this->getID($tble)) {
                $checkd[] = "'$" . $info->name . "'";
            }
            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    // ifempty
    public function ifEmpty($tble) {
        $result = $this->sQueries($tble);
        $checkd = array();
        $r = 0;
        if (mysqli_num_fields($result) > $r) {
            while ($info = mysqli_fetch_field($result)) {
                if ($info->name != $this->getID($tble)) {
                    $checkd[] = '!empty($_POST["' . $info->name . '"])';
                }
            }
            return implode(" && ", $checkd);
        }
    }

    // ------------------------------->
    // edit row
    public function editColm($tble, $id) {
        $ncol = $this->getID($tble);
        $result = $this->wQueries("select * from $tble where $ncol = '$id' ");
        if (!$result) {
            return 'ERROR:' . mysqli_error();
        } else {
            $i = 0;
            $ttle = str_replace("_", " ", $tble);
            echo '<form class="form-horizontal" method="POST" enctype="multipart/form-data">
    <fieldset>

        <!-- Form Name -->

        <legend>' . ucfirst($ttle) . '</legend>';

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            while ($i < mysqli_num_fields($result)) {
                $meta = mysqli_fetch_field($result);
                if ($meta->name == $ncol) {
                    continue;
                } else {
                    $remp = ucfirst(str_replace("_", " ", $meta->name));
                    $premp = str_replace(" id", " ", $remp);
                    $mdat = $row[$meta->name];

                    echo '<!-- Text input-->
        <div class="form-group">
            <label for="' . $meta->name . '" class ="control-label col-md-3">' . $premp . ':</label>
<div class="col-md-8">
            <input id="' . $meta->name . '" name="' . $meta->name . '" value="' . $mdat . '" class="form-control input-md" type="text">
            <span class="help-block">' . $meta->name . '</span>
               </div>
        </div>';
                }
                $i = $i + 1;
            }

            echo '<!-- Button -->
        <div class="form-group">
            <div class="col-md-4">
                <button type="button" id="editrow" name="editrow" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Edit</button>
            </div>
        </div>';
            echo '</fieldset>
</form>';
            mysqli_free_result($result);
        }
    }

    // add colm
    public function addColm($tble) {
        $result = $this->sQueries($tble);

        if (!$result) {
            return 'ERROR:' . mysqli_error();
        } else {
            $i = 0;
            echo '<form class="form-horizontal">
    <fieldset>

        <!-- Form Name -->
        <legend>' . $tble . '</legend>';
            if (mysqli_num_fields($result) > $i) {
                while ($meta = mysqli_fetch_field($result)) {
                    $remp = str_replace("_", " ", $meta->name);
                    echo '<!-- Text input-->
        <div class="form-group">
            <label class="col-md-3 control-label" for="textinput">' . ucfirst($remp) . '</label>
            <div class="col-md-8">
                <input id="' . $meta->name . '" name="' . $meta->name . '" placeholder="' . ucfirst($remp) . '" class="form-control input-md" type="text">
                <span class="help-block">' . $meta->name . '</span>
            </div>
        </div>';
                }
            }
            echo '<!-- Button -->
        <div class="form-group">
            <div class="col-md-4">
                <button id="submit" name="submit" class="btn btn-primary">Save</button>
            </div>
        </div>';
            echo '</fieldset>
</form>';
            mysqli_free_result($result);
        }
    }

    public function supdateData($tble) {
        $result = $this->sQueries($tble);
        $varnames = array();
        $r = 0;
        if (mysqli_num_fields($result) > $r) {
            while ($name = mysqli_fetch_field($result)) {
                $varnames[] = $name->name . ': $' . $name->name;
            }
            echo implode(", ", $varnames);
        }
    }

    public function supdateD($tble) {
        $result = $this->sQueries($tble);
        $varnames = array();
        $r = 0;
        if (mysqli_num_fields($result) > $r) {
            while ($info = mysqli_fetch_field($result)) {
                $varnames[] = $info->name . ':' . $info->name;
            }
            echo implode(", ", $varnames);
        }
    }

    public function addReq($tble) {
        $result = $this->sQueries($tble);
        $r = 0;
        $varnames = '';
        if (mysqli_num_fields($result) > $r) {
            while ($info = mysqli_fetch_field($result)) {
                if ($info->name != $this->getID($tble)) {
                    $varnames = '$' . $info->name . ' = mysqli_real_escape_string($conn,$_REQUEST["' . $info->name . '"]); ' . "\n\r";
                }
                return $varnames;
            }
        }
    }

    public function addReqch($tble) {
        $result = $this->sQueries($tble);
        $checkd = array();
        $r = 0;
        if (mysqli_num_fields($result) > $r) {
            while ($info = mysqli_fetch_field($result)) {
                if ($info->name != $this->getID($tble)) {
                    $checkd[] = "' " . $info->name . " : $" . $info->name . " '";
                }
            }
            return implode(" , ", $checkd);
        }
    }

    public function addvTtl($tble) {
        $result = $this->sQueries($tble);
        $checkd = array();
        $r = 0;
        if (mysqli_num_fields($result) > $r) {
            while ($info = mysqli_fetch_field($result)) {
                if ($info->name != $this->getID($tble)) {
                    $checkd[] = "'$" . $info->name . "'";
                }
                return implode(" , ", $checkd);
            }
        }
    }

    public function sValues($tble) {
        $result = $this->sQueries($tble);
        $r = 0;
        if (mysqli_num_fields($result) > $r) {
            while ($info = mysqli_fetch_field($result)) {
                if ($info->name != $this->getID($tble)) {
                    $checkd = 'var ' . $info->name . ' = $("#' . $info->name . '").val();' . "\n";
                }
                echo implode(" ", $checkd);
            }
        }
    }

}
?>