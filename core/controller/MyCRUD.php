<?php

class MyCRUD {

    protected $connection;
    protected $hostDB;
    protected $userDB;
    protected $passDB;
    protected $baseDB;
    public $pgname;
    private $itpi;
    private $itpc;
    private $itpd;
    private $itpv;
    private $itpt;
    private $itpe;
    private $tpi;
    private $tpb;
    private $tpd;
    private $tps;

    public function __construct() {
        $this->hostDB = DBHOST;
        $this->userDB = DBUSER;
        $this->passDB = DBPASS;
        $this->baseDB = DBNAME;
        global $conn, $rname;
        $this->connection = $conn;
        $this->pgname = $rname;
        // Array to get input type
        $this->itpi = [
            'int',
            'tinyint',
            'smallint',
            'mediumint',
            'bigint',
            'bit',
            'float',
            'double',
            'decimal'
        ];
        $this->itpc = [
            'time',
            'year'
        ];
        $this->itpd = [
            'date',
            'datetime',
            'timestamp'
        ];
        $this->itpv = [
            'varchar',
            'char'
        ];
        $this->itpt = [
            'text',
            'tinytext',
            'mediumtext',
            'longtext',
            'json',
            'point',
            'linestring',
            'polygon',
            'geometry',
            'multipoint',
            'multilinestring',
            'multipolygon',
            'geometrycollection',
            'binary',
            'varbinary',
            'tinyblob',
            'blob',
            'mediumblob',
            'longblob'
        ];
        $this->itpe = [
            'enum',
            'set'
        ];
// Array to get code type for prepare statement
        $this->tpi = [
            'tinyint',
            'smallint',
            'mediumint',
            'int',
            'bigint',
            'bit'
        ];
        $this->tpb = [
            'binary',
            'varbinary',
            'tinyblob',
            'blob',
            'mediumblob',
            'longblob'
        ];
        $this->tpd = [
            'float',
            'double',
            'decimal'
        ];
        $this->tps = [
            'varchar',
            'char',
            'tinytext',
            'text',
            'mediumtext',
            'longtext',
            'json',
            'uuid',
            'date',
            'time',
            'year',
            'datetime',
            'timestamp',
            'point',
            'linestring',
            'polygon',
            'geometry',
            'multipint',
            'multilinestring',
            'multipolygon',
            'geometrycollection',
            'unknown',
            'enum',
            'set'
        ];
    }

    public function protect($str) {
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlentities($str, ENT_QUOTES);
        $str = htmlspecialchars(trim($str), ENT_QUOTES);
        $str = mysqli_real_escape_string($this->connection, $str);
        return $str;
    }

    public function getAllData($tble) {
        return $this->connection->query("SELECT * FROM $tble");
    }

    public function selectData($query) {
        return $this->connection->query($query);
    }

    public function getID($tble) {
        if ($result = $this->getAllData($tble)) {
            /* Get field information for 2nd column */
            $result->field_seek(0);
            $finfo = $result->fetch_field();
            return $finfo->name;
        }
    }

    public function getColumnNames($tble) {
        $sql = 'DESCRIBE ' . $tble;
        $result = $this->selectData($sql);
        $rows = array();
        while ($row = $result->fetch_fields()) {
            $rows[] = $row['Field'];
        }
        return $rows;
    }

    public function listDatatype($tble) {
        $nDB = DBNAME;
        $dsql = "SELECT COLUMN_NAME AS name, DATA_TYPE AS type FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$nDB' AND TABLE_NAME = '$tble'";
        $dresult = $this->selectData($dsql);
        $cnm = array();
        while ($row = $dresult->fetch_assoc()) {
            $cnm[] = $row['type'];
        }
        return $cnm;
    }

    public function showCol($tble) {
        $nDB = DBNAME;
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$nDB' AND TABLE_NAME = '$tble'";
        $result = $this->selectData($sql);
        $columnArr = array();
        while ($row = $result->fetch_assoc()) {
            $columnArr[] = $row['DATA_TYPE'];
        }
        return $columnArr;
    }

    public function viewColumns($tble) {

        try {
            $dbDdata = new PDO("mysql:host=$this->hostDB;dbname=$this->baseDB", $this->userDB, $this->passDB);
        } catch (Exception $e) {
            echo "Something happened to the database: " . $e->getMessage();
        }
        return $dbDdata->query("SELECT COLUMN_NAME AS name, DATA_TYPE AS type
            FROM information_schema.columns WHERE
            table_schema = '$this->baseDB'
            AND table_name = '$tble'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function getList($sql, $col) {
        $result = $this->selectData($sql);

        if ($result->field_count > 0) {
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

            echo '<table class="table">
			<thead>
				<tr>
<th><a id="addrow" name="addrow" title="Add" class="btn btn-primary" href="' . $this->pgname . '?cms=table_crud&w=add&tbl=' . $tble . '">Add <i class="fa fa-plus-square"></i></a></th>' . "\n";
            foreach ($colmns as $colmn) {
                $tremp = ucfirst(str_replace("_", " ", $colmn->name));
                $remp = str_replace(" id", " ", $tremp);
                echo '<th>' . $remp . '</th>' . "\n";
            }
            echo '</tr>' . "\n";
            echo '</thead>' . "\n";
            echo '<tbody>' . "\n";
            while ($row = $result->fetch_array()) {

                echo '<tr>' . "\n";
                echo '<td><!--Button -->
                <a id="editrow" name="editrow" title="Edit" class="btn btn-success" href="' . $this->pgname . '?cms=table_crud&w=edit&tbl=' . $tble . '&id=' . $row[0] . '"><i class="fas fa-edit"></i></a>
<a id="deleterow" name="deleterow" title="Delete" class="btn btn-danger" href="' . $this->pgname . '?cms=table_crud&w=delete&tbl=' . $tble . '&id=' . $row[0] . '"><i class="fas fa-trash-alt"></i></a>
                </td>' . "\n";
                foreach ($colmns as $colmn) {
                    $fd = $row[$colmn->name];

                    $resultq = $this->connection->query("SELECT * FROM table_column_settings WHERE table_name='$tble' AND col_name='$colmn->name' AND input_type IS NOT NULL");

                    if ($resultq->num_rows > 0) {
                        while ($trow = $resultq->fetch_array()) {

                            if ($colmn->name === 'imagen') {
                                echo '<td><img src="' . $row[$colmn->name] . '" style="width:auto; height: 100px;"></td>' . "\n";
                            } else {
                                $tb = $trow['j_table'];
                                $id = $trow['j_id'];
                                $val = $trow['j_value'];
                                $ql = "SELECT * FROM " . $tb . " WHERE " . $id . "='" . $fd . "'";
                                $rest = $this->connection->query($ql);
                                $tow = $rest->fetch_assoc();
                                echo '<td><a class="goto" href="search.php?w=find&tbl=' . $tb . '&id=' . $fd . '">' . $tow[$val] . '</a></td>' . "\n";
                            }
                        }
                    } else {
                        echo '<td>' . $row[$colmn->name] . '</td>' . "\n";
                    }
                }

                echo '</tr>' . "\n";
            }
            echo '</tbody>
		</table>' . "\n";

            if (ceil($total_pages / $num_results_on_page) > 0) {
                $url = $this->pgname . '?cms=table_crud&w=list&tbl=' . $tble;
                ?>
                <nav aria-label="page navigation mx-auto">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1) { ?>
                            <li class="page-item prev"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page - 1 ?>">Previous</a></li>
                            <?php } ?>

                        <?php if ($page > 3) { ?>
                            <li class="page-item start"><a href="<?php echo $url; ?>&page=1">1</a></li>
                            <li class="page-item dots">...</li>
                        <?php } ?>

                        <?php if ($page - 2 > 0) { ?>
                            <li class="page-item page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a></li>
                            <?php } ?>
                            <?php if ($page - 1 > 0) { ?>
                            <li class="page-item page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a></li>
                            <?php } ?>

                        <li class="page-item currentpage"><a
                                href="<?php echo $url; ?>&page=<?php echo $page ?>"><?php echo $page ?></a></li>

                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1) { ?>
                            <li class="page-item page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a></li>
                            <?php } ?>
                            <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1) { ?>
                            <li class="page-item page"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a></li>
                            <?php } ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2) { ?>
                            <li class="page-item dots">...</li>
                            <li class="page-item end"><a
                                    href="<?php echo $url; ?>&page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
                            <?php } ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page)) { ?>
                            <li class="page-item next"><a
                                    href="<?php echo $url; ?>&page=<?php echo $page + 1 ?>">Next </a></li>
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

        $resultq = $this->selectData("SELECT * FROM table_column_settings WHERE table_name='$tble' AND input_type IS NOT NULL");
        $resv = $resultq->num_rows;

        $r = 0;
        // start vars
        if ($resv > $r) {

            $ttl = array();
            $ctl = array();
            $fcols = array();
            $qers = array();

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
            $ctls = implode(" && ", $ctl);
            $fcol = implode(" else", $fcols);
            $valr = implode(" ", $qers);
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
        if ($nres = $this->getAllData($tble)) {
            $rowcq = $nres->num_rows;
            $endpage = ceil($rowcq / $range);
        }

        $res = $this->selectData($sel);
        $result = $this->selectData($select);

        $i = 0;
        if ($resv > $i) {
            $rvfile = 'ftmp.php';
            if (file_exists($rvfile)) {
                unlink($rvfile);
            }
            $content = '<?php' . "\n";
            $content .= "if ({$vtl}) {" . "\n";
            $content .= "echo '<th>' . ucfirst(\$remp) . '</th>';" . "\n";
            $content .= "}" . "\n";
            $content .= "?> \n";

            file_put_contents($rvfile, $content, FILE_APPEND | LOCK_EX);
        }

        // start form
        // start table head
        $names = array();
        echo '<form class="row form-horizontal" role="form" method="POST">' . "\n";
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

        echo '<th><a id="addrow" name="addrow" class="btn btn-primary" href="' . $this->pgname . '?cms=table_crud&w=add&tbl=' . $tble . '">Add</a></th>' . "\n";
        echo '</tr>' . "\n";
        echo '</thead>' . "\n";
        echo '<tbody>' . "\n";
        // end table head
        // start body table
        while ($row = $res->fetch_row()) {
            echo '<tr>' . "\n";
            $rw = $result->fetch_array();
            $count = count($row);

            $y = 0;
            if ($count > $y) {

                foreach ($names as $key => $name) {
                    if ($resv > $y) {

                        $vrfile = 'qtmp.php';
                        if (file_exists($rvfile)) {
                            unlink($rvfile);
                        }

                        $varcont = '<?php' . "\n";
                        $varcont .= "if (\$key == 0) {" . "\n";
                        $varcont .= "echo '<td id=\"'.\$rw['" . $ncol . "'].'\">'.\$rw['" . $ncol . "'].'</td>';" . "\n";
                        $varcont .= "}else";
                        $varcont .= $fcol;
                        $varcont .= "elseif({$ctls}){" . "\n";
                        $varcont .= "echo '<td>' . \$rw[\$name] . '</td>';" . "\n";
                        $varcont .= "} ?> \n";

                        file_put_contents($vrfile, $varcont, FILE_APPEND | LOCK_EX);

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
                <a id="editrow" name="editrow" class="btn btn-success" href="' . $this->pgname . '?cms=table_crud&w=edit&tbl=' . $tble . '&id=' . $i_row . '">Edit</a>
                <a id="deleterow" name="deleterow" class="btn btn-danger" href="' . $this->pgname . '?cms=table_crud&w=delete&tbl=' . $tble . '&id=' . $i_row . '">Borrar</a>
                </td>';

            echo '</tr>' . "\n";
            $i++;
        }
        echo '</tbody>' . "\n";
        echo '</table>' . "\n";
        // end body table
        // end
        $url = $this->pgname . '?cms=table_crud&w=list&tbl=' . $tble;

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

    public function ShowInputData($table, $clmn) {
        $sqlq = "SELECT * FROM table_column_settings WHERE table_name='$table' AND col_name='$clmn'";
        $resultq = $this->connection->query($sqlq);
        $nrows = $resultq->num_rows;
        if ($nrows > 0) {

            $rqu = $resultq->fetch_assoc();
            $t_nm = $rqu['table_name'];
            $c_nm = $rqu['col_name'];
            $c_tp = $rqu['col_type'];
            $c_jo = $rqu['joins'];
            $c_tb = $rqu['j_table'];
            $c_id = $rqu['j_id'];
            $c_vl = $rqu['j_value'];
            $c_as = $rqu['j_as'];
            $c_qr = $rqu['where'];

            $remp = ucfirst(str_replace("_", " ", $c_nm));
            $frmp = str_replace(" id", "", $remp);

            $intdata = ['int', 'tinyint', 'smallint', 'mediumint', 'bigint'];
            $strdata = ['varchar', 'char', 'text', 'tinytext', 'mediumtext', 'longtext', 'time', 'year', 'date', 'datetime', 'timestamp', 'json', 'enum', 'set', 'point', 'linestring', 'polygon', 'geometry', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection'];
            $doudata = ['binary', 'varbinary', 'bit', 'float', 'double', 'decimal'];
            $blodata = ['tinyblob', 'blob', 'mediumblob', 'longblob'];

            $sql = "SELECT * FROM $t_nm";

            if (in_array($c_tp, $intdata)) {
                echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
            } elseif (in_array($c_tp, $strdata)) {
                echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
                  </div>' . "\n";
            } elseif (in_array($c_tp, $doudata)) {
                echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
            } elseif (in_array($c_tp, $blodata)) {
                echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
                  </div>' . "\n";
            }


            if (!empty($c_jo)) {
                $inpQ = "SELECT * FROM $c_tb WHERE ";
            }
        }
    }

    public function get_enum_values($tble, $field) {
        $type = $this->connection->query("SHOW COLUMNS FROM {$tble} WHERE Field = '{$field}'")->fetch_array(MYSQLI_ASSOC)['Type'];
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    public function enum_values($tble, $field, $vals) {

        $type = $this->connection->query("SHOW COLUMNS FROM {$tble} WHERE Field = '{$field}'")->fetch_array(MYSQLI_ASSOC)['Type'];
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        $frmp = ucfirst(str_replace("_", " ", $field));
        echo '<div class="form-group">
                       <label for="' . $field . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $field . '" name="' . $field . '" >' . "\n";
        foreach ($enum as $option) {
            $soption = '<option value="' . $option . '"';
            $soption .= ($vals === $option) ? ' SELECTED' : '';
            $soption .= '>' . $option . '</option>';
            echo $soption . "\n";
        }
        echo '</select>' . "\n";
        echo '</div>' . "\n";
    }

    public function joinCols($tble) {

        $columns = $this->viewColumns($tble);
        $ncol = $this->getID($tble);
        //
        $sqlq = "SELECT * FROM table_column_settings WHERE table_name='$tble'";
        $resultq = $this->connection->query($sqlq);
        $rowcq = $resultq->num_rows;

        if ($rowcq > 0) {
            while ($rqu = $resultq->fetch_assoc()) {

                $c_nm = $rqu['col_name'];
                $c_tp = $rqu['col_type'];
                $i_tp = $rqu['input_type'];
                $c_jo = $rqu['joins'];
                $c_tb = $rqu['j_table'];
                $c_id = $rqu['j_id'];
                $c_vl = $rqu['j_value'];
                $c_as = $rqu['j_as'];
                $c_qr = $rqu['where'];

                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                if ($c_nm === $ncol) {
                    continue;
                }

                if (in_array($c_tp, $this->itpi)) {
                    if ($i_tp != 3) {
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
                    } else {
                        // -------------
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                        $sqp1 = "select * from $c_tb";

                        $qres = $this->connection->query($sqp1);

                        while ($options = $qres->fetch_array()) {
                            echo '<option value="' . $options[$c_id] . '">' . $options[$c_vl] . '</option>' . "\n";
                        }

                        echo '</select>' . "\n";
                        echo '</div>' . "\n";
                        // --------------
                    }
                } elseif (in_array($c_tp, $this->itpc)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
                } elseif (in_array($c_tp, $this->itpd)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
                  </div>' . "\n";
                    echo '<script type="text/javascript">
                                        $(document).ready(function (){
                                            $("#' . $c_nm . '").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#' . $c_nm . '").datepicker("setDate", new Date());
                                        });
                                    </script>' . "\n";
                } elseif (in_array($c_tp, $this->itpv)) {
                    if ($c_nm === 'imagen') {
                        echo "<script>$('.custom-file-input').on('change',function(){
                            var fileName = document.getElementById('imagen').files[0].name;
                            $(this).next('.form-control-file').addClass('selected').php(fileName);
                        });</script>";
                        echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text" id="' . $c_nm . '">Upload</span>
  </div>
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="' . $c_nm . '" name="' . $c_nm . '"
      aria-describedby="i' . $c_nm . '">
    <label class="custom-file-label" for="' . $c_nm . '">Choose file </label>
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
                } elseif (in_array($c_tp, $this->itpt)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
                  </div>' . "\n";
                } elseif (in_array($c_tp, $this->itpe)) {
                    // ----------------------
                    $default_value = '';
                    //
                    $values = $this->get_enum_values($tble, $c_nm);
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                    foreach ($values as $option) {
                        $soption = '<option value="' . $option . '"';
                        $soption .= ($default_value === $option) ? ' SELECTED' : '';
                        $soption .= '>' . $option . '</option>';
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

                if (in_array($dtpe->type, $this->itpi)) {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                }
                if (in_array($dtpe->type, $this->itpc)) {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                }
                if (in_array($dtpe->type, $this->itpd)) {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                    echo '<script type="text/javascript">
                                        $(document).ready(function() {
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
                if (in_array($dtpe->type, $this->itpv)) {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '">
                  </div>' . "\n";
                }
                if (in_array($dtpe->type, $this->itpt)) {
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $dtpe->name . '" name="' . $dtpe->name . '"></textarea>
                  </div>' . "\n";
                }
                if (in_array($dtpe->type, $this->itpe)) {
                    // ----------------------

                    $values = $this->get_enum_values($tble, $dtpe->name);
                    $default_value = '';
                    //
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $dtpe->name . '" name="' . $dtpe->name . '" >' . "\n";

                    foreach ($values as $option) {
                        $soption = '<option value="' . $option . '"';
                        $soption .= ($default_value === $option) ? ' SELECTED' : '';
                        $soption .= '>' . $option . '</option>';
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
        $vname = array();
        $ptadd = array();
        $nvl = array();
        $colID = $this->getID($tble);
//
        $qresult = $this->getAllData($tble);
        while ($finfo = $qresult->fetch_field()) {
            if ($finfo->name == $colID) {
                continue;
            }
            $nvl[] = '?';
            $vname[] = $finfo->name;
            $ptadd[] = "$" . $finfo->name . " = \$_POST['" . $finfo->name . "'];" . "\n";
        }

        $nvls = implode(", ", $nvl);
        $vnames = implode(", ", $vname);
        $ptadds = implode(" ", $ptadd);
        /*
          i - integer
          d - double
          s - string
          b - BLOB
         */

        $colmns = $this->viewColumns($tble);

        foreach ($colmns AS $col) {
            if ($col->name === $colID) {
                continue;
            }
            if (in_array($col->type, $this->tpi)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'i';
            } elseif (in_array($col->type, $this->tpb)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'b';
            } elseif (in_array($col->type, $this->tpd)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'd';
            } elseif (in_array($col->type, $this->tps)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 's';
            }
        }
        $cnames = implode(', ', $cname);
        $ctypes = implode('', $ctype);

        $vfile = 'qtmp.php';
        if (file_exists($vfile)) {
            unlink($vfile);
        }
        $content = '<?php' . "\n";
        $content .= '//This is a temporary file to add data to the table.' . "\n";
        $content .= "if(isset(\$_POST['addrow'])){" . "\n";
        $content .= $ptadds . "\n";
        $content .= '$sql = "INSERT INTO ' . $tble . ' (' . $vnames . ')' . "\n";
        $content .= 'VALUES (' . $nvls . ')";' . "\n";
        $content .= "\$stmt = \$this->connection->prepare(\$sql);" . "\n";
        $content .= '$stmt->bind_param("' . $ctypes . '", ' . $cnames . ');' . "\n";
        $content .= '$stmt->execute();' . "\n";
        $content .= '$stmt->close();' . "\n";
        $content .= "\$_SESSION['success'] = 'The data was added correctly';
header('Location: " . $this->pgname . "?cms=table_crud&w=list&tbl=" . $tble . "');" . "\n";
        $content .= "} \n";
        $content .= "?> \n";

        file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);

        include_once 'qtmp.php';

        echo '<form method="post" class="form-horizontal" role="form" id="add_' . $tble . '" enctype="multipart/form-data">' . "\n";

        $this->joinCols($tble);

        echo '<div class="form-group">
        <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square"></span> Add</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

    // addScript

    public function insertData($tble) {
        $ncol = $this->getID($tble);
        $result = $this->connecion->query("SELECT * FROM $tble");
        while ($finfo = $result->fetch_field()) {
            if ($finfo->name == $ncol) {
                continue;
            }
            $vname[] = $finfo->name;
            $bname[] = "$" . $finfo->name;
            $pname[] = "?";
            $ptadd[] = "$" . $finfo->name . " = \$_POST['" . $finfo->name . "'];" . "\n";
        }

        $vnames = implode(", ", $vname);
        $bnames = implode(", ", $bname);
        $pnames = implode(", ", $pname);
        $ptadds = implode(" ", $ptadd);

        $colmns = $this->viewColumns($tble);

        foreach ($colmns AS $col) {
            if ($col->name === $ncol) {
                continue;
            }
            if (in_array($col->type, $this->tpi)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'i';
            } elseif (in_array($col->type, $this->tpb)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'b';
            } elseif (in_array($col->type, $this->tpd)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'd';
            } elseif (in_array($col->type, $this->tps)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 's';
            }
        }
        $cnames = implode(', ', $cname);
        $ctypes = implode('', $ctype);

        $rvfile = 'qtmp.php';
        if (file_exists($rvfile)) {
            unlink($rvfile);
        }

        $content = '<?php' . "\n";
        $content .= '//This is temporal file only for add new row' . "\n";
        $content .= "if(isset(\$_POST['addrow'])){" . "\n";
        $content .= $ptadds . "\n";
        $content .= '$sql = "INSERT INTO ' . $tble . ' (' . $vnames . ')' . "\n";
        $content .= 'VALUES (' . $pnames . ')";' . "\n";
        $content .= "\$insert = \$this->connection->prepare(\$sql);
\$insert->bind_param('" . $vd . "'," . $bnames . " );
\$insert->execute();
\$insert->close();" . "\n";
        $content .= "}" . "\n";
        $content .= "?> \n";

        file_put_contents($rvfile, $content, FILE_APPEND | LOCK_EX);

        include_once 'qtmp.php';
    }

    public function updateScript($tble) {

        $result = $this->getAllData($tble);
        $ncol = $this->getID($tble);

        $r = 0;
        $postnames = array();
        $varnames = array();

        if ($result->field_count > $r) {

            while ($info = $result->fetch_field()) {
                if ($info->name != $ncol) {
                    $postnames[] = "\$" . $info->name . "  = \$_POST['" . $info->name . "'];" . "\r\n";
                    $varnames[] = $info->name . " = ?";
                }
            }
        }
        $scpt = implode("", $postnames);
        $ecols = implode(", ", $varnames);

        /*
          i - integer
          d - double
          s - string
          b - BLOB
         */
        $tpd = array(
            'tinyint' => 'i',
            'smallint' => 'i',
            'mediumint' => 'i',
            'int' => 'i',
            'bigint' => 'i',
            'bit' => 'i',
            'binary' => 'b',
            'varbinary' => 'b',
            'tinyblob' => 'b',
            'blob' => 'b',
            'mediumblob' => 'b',
            'longblob' => 'b',
            'float' => 'd',
            'double' => 'd',
            'decimal' => 'd',
            'varchar' => 's',
            'char' => 's',
            'tinytext' => 's',
            'text' => 's',
            'mediumtext' => 's',
            'longtext' => 's',
            'json' => 's',
            'uuid' => 's',
            'date' => 's',
            'time' => 's',
            'year' => 's',
            'datetime' => 's',
            'timestamp' => 's',
            'point' => 's',
            'linestring' => 's',
            'polygon' => 's',
            'geometry' => 's',
            'multipint' => 's',
            'multilinestring' => 's',
            'multipolygon' => 's',
            'geometrycollection' => 's',
            'unknown' => 's',
            'enum' => 's',
            'set' => 's'
        );

        $colmns = $this->viewColumns($tble);

        foreach ($tpd AS $key => $val) {
            $tpk[] = $key;
        }
        foreach ($colmns AS $col) {
            if (in_array($col->type, $tpk)) {
                if ($col->name === $ncol) {
                    continue;
                }
                $cname[] = '$' . $col->name;
                $ctype[] = $tpd[$col->type];
            }
        }
        foreach ($colmns AS $colid) {
            if (in_array($colid->type, $tpk)) {
                if ($colid->name !== $ncol) {
                    continue;
                }
                $idname[] = '$' . $colid->name;
                $idtype[] = $tpd[$colid->type];
            }
        }
        $idtypes = implode('', $idtype);
        $cnames = implode(', ', $cname);
        $ctypes = implode('', $ctype);
        $bindp = $ctypes . $idtypes;

        $vfile = 'qtmp.php';
        if (file_exists($vfile)) {
            unlink($vfile);
        }
        $content = '<?php' . "\n";
        $content .= '//This is temporal file only for add new row' . "\n";
        $content .= "if (isset(\$_POST['editrow'])) { \r\n";
        $content .= $scpt . "\r\n";
        $content .= "\$query=\"UPDATE `$tble` SET " . $ecols . " WHERE " . $ncol . " = ? \";" . "\r\n";
        $content .= '$stmt = $this->connection->prepare($query);' . "\r\n";
        $content .= '$stmt->bind_param("' . $bindp . '",' . $cnames . ', $id);' . "\n";
        $content .= '$stmt->execute();' . "\n";
        $content .= '$stmt->close();' . "\n";
        $content .= '$_SESSION["success"] = "The data was updated correctly.";' . "\n";
        $content .= "echo \"<script>
window.onload = function() {
    location.href = '" . $this->pgname . '?cms=table_crud&w=list&tbl=' . $tble . "';
}
</script>\";" . "\n";
        $content .= "} \r\n";
        $content .= "?> \n";

        file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);
        include_once 'qtmp.php';
    }

    public function updateData($tble) {
        $ncol = $this->getID($tble);
        $result = $this->getAllData($tble);

        while ($info = mysqli_fetch_field($result)) {
            if ($info->name == $ncol) {
                continue;
            }
            $postnames[] = '$' . $info->name . ' = $_POST["' . $info->name . '"]; ' . "\r\n";
            $varnames[] = $info->name . " = ?";
        }

        $scpt = implode("", $postnames);
        $ecols = implode(", ", $varnames);

        $colmns = $this->viewColumns($tble);

        foreach ($colmns AS $col) {
            if ($col->name === $ncol) {
                continue;
            }
            if (in_array($col->type, $this->tpi)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'i';
            } elseif (in_array($col->type, $this->tpb)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'b';
            } elseif (in_array($col->type, $this->tpd)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 'd';
            } elseif (in_array($col->type, $this->tps)) {
                $cname[] = '$' . $col->name;
                $ctype[] = 's';
            }
        }
        $cnames = implode(', ', $cname);
        $ctypes = implode('', $ctype);

        $rvfile = 'qtmp.php';
        if (file_exists($rvfile)) {
            unlink($rvfile);
        }

        $content = '<?php' . "\n";
        $content .= '//This is temporal file only for add new row' . "\n";
        $content .= "if (isset(\$_POST['editrow'])) { \r\n";
        $content .= $scpt . "\r\n";
        $content .= '$query = "UPDATE ' . $tble . ' SET ' . $ecols . ' WHERE ' . $ncol . ' = ?";' . "\r\n";
        $content .= "\$updated = \$this->connection->prepare(\$sql);
\$updated->bind_param('" . $ctypes . "i', " . $cnames . ", \$id );
\$updated->execute();
\$updated->close();" . "\n";
        $content .= "}" . "\n";
        $content .= "?> \n";

        file_put_contents($rvfile, $content, FILE_APPEND | LOCK_EX);
        include_once 'qtmp.php';
    }

    public function inputQEdit($tble, $id) {
        $columns = $this->viewColumns($tble);
        $ncol = $this->getID($tble);
        $resultq = $this->selectData("SELECT * FROM table_column_settings WHERE table_name='$tble'");
        $rowcq = $resultq->num_rows;
        $r = 0;
        if ($rowcq > $r) {
            echo '<form class="row form-horizontal" role="form" id="edit_' . $tble . '" method="POST" enctype="multipart/form-data">' . "\n";
            while ($rqu = $resultq->fetch_array()) {

                $qresult = $this->selectData("SELECT * FROM $tble WHERE $ncol = '$id' ");
                $row = $qresult->fetch_assoc();

                $c_nm = $rqu['col_name'];
                $c_tp = $rqu['col_type'];
                $i_tp = $rqu['input_type'];
                $c_jo = $rqu['joins'];
                $c_tb = $rqu['j_table'];
                $c_id = $rqu['j_id'];
                $c_vl = $rqu['j_value'];

                $cdta = $row[$c_nm];

                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                $itpi = ['int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'bit', 'float', 'double', 'decimal'];
                $itpc = ['time', 'year'];
                $itpd = ['date', 'datetime', 'timestamp'];
                $itpv = ['varchar', 'char'];
                $itpt = ['text', 'tinytext', 'mediumtext', 'longtext', 'json', 'point', 'linestring', 'polygon', 'geometry', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection', 'binary', 'varbinary', 'tinyblob', 'blob', 'mediumblob', 'longblob'];
                $itpe = ['enum', 'set'];

                if (in_array($c_tp, $itpi)) {
                    if ($i_tp === 3) {

                        echo '
			<div class="form-group">
        <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
        <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >';

                        $qres = $this->getAllData($c_tb);

                        while ($rqj = $qres->fetch_array()) {
                            if ($cdta == $rqj[$c_id]) {
                                echo '<option value="' . $rqj[$c_id] . '" selected>' . $rqj[$c_vl] . '</option>';
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
                } elseif (in_array($c_tp, $itpc)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                } elseif (in_array($c_tp, $itpd)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                    echo '<script type="text/javascript">
                                        $(document).ready(function (){
                                            $("#' . $c_nm . '").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#' . $c_nm . '").datepicker("setDate", new Date());
                                        });
                                    </script>' . "\n";
                } elseif (in_array($c_tp, $itpv)) {
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
                } elseif (in_array($c_tp, $itpt)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                } elseif (in_array($c_tp, $itpe)) {
                    // ----------------------
                    $options = $this->get_enum_values($tble, $c_nm);
                    //
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                    foreach ($options as $option) {
                        $soption = '<option value="' . $option . '"';
                        if ($cdta === $option) {
                            $soption .= ' selected';
                        }
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
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>
    </div>' . "\n";
            echo '</form>' . "\n";
        } else {
            echo '<form role="form" id="edit_' . $tble . '" method="POST">' . "\n";
            foreach ($columns as $finfo) {

                $qresult = $this->selectData("select * from $tble where $ncol = '$id' ");
                $row = $qresult->fetch_assoc();
                if ($finfo->name === $ncol) {
                    continue;
                }
                $c_nm = $finfo->name;
                $c_tp = $finfo->type;

                $cdta = $row[$c_nm];

                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                $itpi = array('int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'bit', 'float', 'double', 'decimal');
                $itpc = array('time', 'year');
                $itpd = array('date', 'datetime', 'timestamp');
                $itpv = array('varchar', 'char');
                $itpt = array('text', 'tinytext', 'mediumtext', 'longtext', 'json', 'point', 'linestring', 'polygon', 'geometry', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection', 'binary', 'varbinary', 'tinyblob', 'blob', 'mediumblob', 'longblob');
                $itpe = array('enum', 'set');

                if (in_array($c_tp, $itpi)) {

                    echo '<div class="form-group">
				<label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label> <input type="text"
					class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"
					value="' . $cdta . '">
			</div>
			' . "\n";
                } elseif (in_array($c_tp, $itpc)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                } elseif (in_array($c_tp, $itpd)) {
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
                if (in_array($c_tp, $itpv)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" value="' . $cdta . '">
                  </div>' . "\n";
                }
                if (in_array($c_tp, $itpt)) {
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">' . $cdta . '</textarea>
                  </div>' . "\n";
                }
                if (in_array($c_tp, $itpe)) {
                    // ----------------------

                    $options = $this->get_enum_values($tble, $c_nm);
                    //
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
                       <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                    foreach ($options as $option) {
                        $soption = '<option value="' . $option . '"';
                        if ($cdta === $option) {
                            $soption .= ' selected';
                        }
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
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>
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

        $vfile = 'qtmp.php';
        if (file_exists($vfile)) {
            unlink($vfile);
        }

        $content = '<?php' . "\n";
        $content .= "if(isset(\$_POST['editrow'])){" . "\n\n";
        $content .= $ptadds . "\n";
        $content .= '$sql = "UPDATE $tble SET ' . $pnames . ' WHERE $ncol=' . $id . '";' . "\n";
        $content .= "if (\$this->connection->query(\$sql) === TRUE) {
        \$_SESSION['success'] = \"New record created successfully\";
    } else {
       \$_SESSION['error'] = \"Error: \" . \$sql . \"<br>\" . \$this->connection->error;
    }" . "\n";
        $content .= " }" . "\n";
        $content .= "?> \n";

        file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);
        include_once 'ftmp.php';
    }

    // deleterow
    public function deleteData($tble, $id) {
        $ncol = $this->getID($tble);
        $qresult = $this->selectData("select * from $tble where $ncol = '$id' ");
        echo '<form class="row form-horizontal" role="form" id="delete_' . $tble . '" method="POST">' . "\n";
        $row = $qresult->fetch_assoc();
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
        <button type = "submit" id="deleterow" name="deleterow" class="btn btn-primary"><span class = "fas fa-trash-alt"></span> Delete</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

    // adduery
    public function addQuery($tble) {
        $qresult = $this->getAllData($tble);
        echo '<form class="row form-horizontal" role="form" method="post" id="query_' . $tble . '">' . "\n";
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
        <button type = "submit" id="addqueries" name="addqueries" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Add queries</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

    // addpost
    public function addpost($tble) {
        $result = $this->getAllData($tble);
        $r = 0;
        $postnames = array();
        while ($result->field_count > $r) {
            $info = $result->fetch_field();
            if ($info->name != $this->getID($tble)) {
                $postnames[] = '$' . $info->name . ' = $_POST["' . $info->name . '"]; ' . "\r\n";
            }
            $r = $r + 1;
        }
        return implode("", $postnames);
    }

    // updatedata
    public function updateInfo($tble) {
        $result = $this->getAllData($tble);
        $varnames = array();
        $r = 0;
        while ($result->field_count > $r) {
            $name = $result->fetch_field();

            if ($name->name != $this->getID($tble)) {
                $varnames[] = $name->name . " = '$" . $name->name . "'";
            }
            $r = $r + 1;
        }
        return implode(", ", $varnames);
    }

    // ifmpty
    public function ifMpty($tble) {
        $result = $this->getAllData($tble);
        $checkd = array();
        $r = 0;
        while ($result->field_count > $r) {
            $info = $result->fetch_field();
            if ($info->name != $this->getID($tble)) {
                $checkd[] = '!empty($' . $info->name . ')';
            }

            $r = $r + 1;
        }
        return implode(" && ", $checkd);
    }

    // addttl
    public function addTtl($tble) {
        $result = $this->getAllData($tble);
        $checkd = array();
        $r = 0;
        while ($result->field_count > $r) {
            $info = $result->fetch_field();
            if ($info->name != $this->getID($tble)) {
                $checkd[] = '`' . $info->name . '`';
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    // addtpost
    public function addTPost($tble) {
        $result = $this->getAllData($tble);
        $checkd = array();
        $r = 0;
        while ($result->field_count > $r) {
            $info = $result->fetch_field();
            if ($info->name != $this->getID($tble)) {
                $checkd[] = "'$" . $info->name . "'";
            }
            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    // ifempty
    public function ifEmpty($tble) {
        $result = $this->getAllData($tble);
        $checkd = array();
        $r = 0;
        if ($result->field_count > $r) {
            while ($info = $result->fetch_field()) {
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
        $result = $this->selectData("select * from $tble where $ncol = '$id' ");
        if (!$result) {
            return 'ERROR:' . mysqli_error();
        } else {
            $i = 0;
            $ttle = str_replace("_", " ", $tble);
            echo '<form class="row form-horizontal" role="form" method="POST" enctype="multipart/form-data">
    <fieldset>

        <!-- Form Name -->

        <legend>' . ucfirst($ttle) . '</legend>';

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            while ($i < $result->field_count) {
                $meta = $result->fetch_field();
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
                <button type="button" id="editrow" name="editrow" class="btn btn-primary"><span class="fas fa-edit"></span> Edit</button>
            </div>
        </div>';
            echo '</fieldset>
</form>';
            mysqli_free_result($result);
        }
    }

    // add colm
    public function addColm($tble) {
        $result = $this->getAllData($tble);

        if (!$result) {
            return 'ERROR:' . mysqli_error();
        } else {
            $i = 0;
            echo '<form class="form-horizontal">
    <fieldset>

        <!-- Form Name -->
        <legend>' . $tble . '</legend>';
            if ($result->field_count > $i) {
                while ($meta = $result->fetch_field()) {
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
        $result = $this->getAllData($tble);
        $varnames = array();
        $r = 0;
        if ($result->field_count > $r) {
            while ($name = $result->fetch_field()) {
                $varnames[] = $name->name . ': $' . $name->name;
            }
            echo implode(", ", $varnames);
        }
    }

    public function supdateD($tble) {
        $result = $this->getAllData($tble);
        $varnames = array();
        $r = 0;
        if ($result->field_count > $r) {
            while ($info = $result->fetch_field()) {
                $varnames[] = $info->name . ':' . $info->name;
            }
            echo implode(", ", $varnames);
        }
    }

    public function addReq($tble) {
        $result = $this->getAllData($tble);
        $r = 0;
        $varnames = '';
        if ($result->field_count > $r) {
            while ($info = $result->fetch_field()) {
                if ($info->name != $this->getID($tble)) {
                    $varnames = '$' . $info->name . ' = mysqli_real_escape_string($conn,$_REQUEST["' . $info->name . '"]); ' . "\n\r";
                }
                return $varnames;
            }
        }
    }

    public function addReqch($tble) {
        $result = $this->getAllData($tble);
        $checkd = array();
        $r = 0;
        if ($result->field_count > $r) {
            while ($info = $result->fetch_field()) {
                if ($info->name != $this->getID($tble)) {
                    $checkd[] = "' " . $info->name . " : $" . $info->name . " '";
                }
            }
            return implode(" , ", $checkd);
        }
    }

    public function addvTtl($tble) {
        $result = $this->getAllData($tble);
        $checkd = array();
        $r = 0;
        if ($result->field_count > $r) {
            while ($info = $result->fetch_field()) {
                if ($info->name != $this->getID($tble)) {
                    $checkd[] = "'$" . $info->name . "'";
                }
                return implode(" , ", $checkd);
            }
        }
    }

    public function sValues($tble) {
        $result = $this->getAllData($tble);
        $r = 0;
        if ($result->field_count > $r) {
            while ($info = $result->fetch_field()) {
                if ($info->name != $this->getID($tble)) {
                    $checkd = 'var ' . $info->name . ' = $("#' . $info->name . '").val();' . "\n";
                }
                echo implode(" ", $checkd);
            }
        }
    }

    public function searchData($tble, $col, $str) {

        $total_pages = $this->connection->query("SELECT * FROM $tble")->num_rows;

        $colmns = $this->viewColumns($tble);

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

        $num_results_on_page = 10;

        if ($stmt = $this->connection->prepare("SELECT * FROM $tble WHERE (`$col` LIKE '%" . $str . "%') LIMIT ?,?")) {

            $calc_page = ($page - 1) * $num_results_on_page;
            $stmt->bind_param('ii', $calc_page, $num_results_on_page);
            $stmt->execute();

            $result = $stmt->get_result();

            echo '
	<table class="table">
			<thead>
				<tr><th></th>' . "\n";
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
                <a id="editrow" name="editrow" class="btn btn-success" href="index.php?w=edit&tbl=' . $tble . '&id=' . $row[0] . '">Editar</a>
                <a id="deleterow" name="deleterow" class="btn btn-danger" href="index.php?w=delete&tbl=' . $tble . '&id=' . $row[0] . '">Borrar</a>
                </td>' . "\n";
                foreach ($colmns as $colmn) {
                    $fd = $row[$colmn->name];
                    $resultq = $this->connection->query("SELECT * FROM table_queries WHERE name_table='$tble' AND col_name='$colmn->name' AND input_type IS NOT NULL");
                    $resv = $resultq->num_rows;
                    $r = 0;
                    if ($resv > $r) {
                        $trow = $resultq->fetch_assoc();
                        $tb = $trow['j_table'];
                        $id = $trow['j_id'];
                        $val = $trow['j_value'];
                        $tow = $this->connection->query("SELECT * FROM $tb WHERE $id='$fd'")->fetch_assoc();

                        echo '<td><a class="goto" href="search.php?w=find&tbl=' . $tb . '&id=' . $fd . '">' . $tow[$val] . '</a></td>' . "\n";
                    } else {
                        echo '<td>' . $row[$colmn->name] . '</td>' . "\n";
                    }
                }


                echo '</tr>' . "\n";
            }
            echo '</tbody>
		</table>' . "\n";

            if (ceil($total_pages / $num_results_on_page) > 0) {
                ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mx-auto">
                        <?php if ($page > 1) { ?>
                            <li class="prev"><a href="search.php?page=<?php echo $page - 1 ?>">Anterior</a></li>
                        <?php } ?>

                        <?php if ($page > 3) { ?>
                            <li class="start"><a href="search.php?page=1">1</a></li>
                            <li class="dots">...</li>
                        <?php } ?>

                        <?php if ($page - 2 > 0) { ?>
                            <li class="page"><a href="search.php?page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a></li>
                        <?php } ?>
                        <?php if ($page - 1 > 0) { ?>
                            <li class="page"><a href="search.php?page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a></li>
                        <?php } ?>

                        <li class="currentpage"><a href="search.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

                        <?php if ($page + 1 < ceil($total_pages / $num_results_on_page) + 1) { ?>
                            <li class="page"><a href="search.php?page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a></li>
                        <?php } ?>
                        <?php if ($page + 2 < ceil($total_pages / $num_results_on_page) + 1) { ?>
                            <li class="page"><a href="search.php?page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a></li>
                        <?php } ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page) - 2) { ?>
                            <li class="dots">...</li>
                            <li class="end"><a
                                    href="search.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
                            <?php } ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page)) { ?>
                            <li class="next"><a href="search.php?page=<?php echo $page + 1 ?>">Siguiente</a></li>
                        <?php } ?>
                    </ul>
                </nav>
                <?php
            }
            $stmt->close();
        }
    }
}
?>
