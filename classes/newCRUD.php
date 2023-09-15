<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../config/dbconnection.php';

class newCRUD {

    protected $connection;
    private $tbl;
    public $pgname;

    public function __construct() {
        global $conn;
        $this->tbl = $_SESSION['table'];
        $this->pgname = $_SERVER['PHP_SELF'];
        $this->connection = $conn;
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

// Get id table
    public function getID($tble) {
        if ($result = $this->getAllData($tble)) {
            $result->field_seek(0);
            $finfo = $result->fetch_field();
            return $finfo->name;
        }
    }

    public function viewColumns($tble) {
        $hostDB = DBHOST;
        $userDB = DBUSER;
        $passDB = DBPASS;
        $baseDB = DBNAME;
        try {
            $dbDdata = new PDO("mysql:host=$hostDB;dbname=$baseDB", $userDB, $passDB);
        } catch (Exception $e) {
            echo "Something happened to the database: " . $e->getMessage();
        }
        return $dbDdata->query("SELECT COLUMN_NAME AS name, DATA_TYPE AS type
            FROM information_schema.columns WHERE
            table_schema = '$baseDB'
            AND table_name = '$tble'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function listDatatype($tble) {
        $nDB = DBNAME;
        $dsql = "SELECT COLUMN_NAME AS name, DATA_TYPE AS type FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$nDB' AND TABLE_NAME = '$tble'";
        $dresult = $this->selectData($dsql);
//$columnArr = array();
        $cnm = array();
        while ($row = $dresult->fetch_assoc()) {
            $cnm[] = $row['type'];
        }
        return $cnm;
    }

    public function showDatatype($tble) {
        $nDB = DBNAME;
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$nDB' AND TABLE_NAME = '$tble'";
        $result = $this->selectData($sql);
        $columnArr = array();
        while ($row = $result->fetch_assoc()) {
            $columnArr[] = $row['DATA_TYPE'];
        }
        return $columnArr;
    }

    public function viewColmnNames($tble) {
        $sql = "SHOW COLUMNS FROM $tble";
        $result = $this->connection->query($sql);
        $cnm = array();
        while ($row = $result->fetch_array()) {
            $cnm[] = $row['Field'];
        }
        return $cnm;
    }

    public function viewColumnData($tble) {
        $hostDB = DBHOST;
        $userDB = DBUSER;
        $passDB = DBPASS;
        $baseDB = DBNAME;
        try {
            $dbDdata = new PDO("mysql:host=$hostDB;dbname=$baseDB", $userDB, $passDB);
        } catch (Exception $e) {
            echo "Something happened to the database: " . $e->getMessage();
        }
        return $dbDdata->query("SELECT COLUMN_NAME AS name, DATA_TYPE AS type
            FROM information_schema.columns WHERE
            table_schema = '$baseDB'
            AND table_name = '$tble'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDatalist($tble) {
        $cnm = $this->viewColmnName($tble);

        echo '<table class="table">' . "\n";
        echo '<thead>' . "\n";
        echo '<tr>' . "\n";
        foreach ($cnm as $tnms) {
            $tremp = ucfirst(str_replace("_", " ", $tnms));
            $remp = str_replace(" id", " ", $tremp);
            echo '<th>' . $remp . '</th>' . "\n";
        }
        echo '</tr>' . "\n";
        echo '</thead>' . "\n";
        echo '<tbody>' . "\n";
        $result1 = $this->getAllData($tble);

        while ($td = $result1->fetch_array()) {
            echo '<tr>';
            foreach ($cnm as $tnms) {
                echo '<td>' . $td[$tnms] . '</td>' . "\n";
            }
            echo '</tr>' . "\n";
        }
        echo '</tbody>' . "\n";
        echo '</table>' . "\n";
    }

    public function joinCols($tble) {

        $columns = $this->viewColumnData($tble);
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
                $c_qr = $rqu['query'];

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
                       <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                        $qres = $this->getAllData($c_tb);

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
                        echo "<script>$('.custom-file-input').on('change',function(e){
                            e.preventDefault();
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
                    $row = $iresult->fetch_array();
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
//
                    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

                    $options = $enum_list;
                    foreach ($options as $option) {
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
                    $row = $iresult->fetch_array();
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
//
                    echo '<div class="form-group">
                       <label for="' . $dtpe->name . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $dtpe->name . '" name="' . $dtpe->name . '" >' . "\n";

                    $options = $enum_list;
                    foreach ($options as $option) {
                        $soption = '<option value="' . $option . '"';
                        $soption .= ($default_value === $option) ? ' selected' : '';
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

    public function addData($tble) {
        $vname = array();
        $pname = array();
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
            $pname[] = "'$" . $finfo->name . "'";
            $ptadd[] = "$" . $finfo->name . " = \$_POST['" . $finfo->name . "'];" . "\n";
        }

        $nvls = implode(", ", $nvl);
        $vnames = implode(", ", $vname);
        $pnames = implode(", ", $pname);
        $ptadds = implode(" ", $ptadd);
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
            'binary' => 'b',
            'varbinary' => 'b',
            'tinyblob' => 'b',
            'blob' => 'b',
            'mediumblob' => 'b',
            'longblob' => 'b',
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
                if ($col->name === $colID) {
                    continue;
                }
                $cname[] = '$' . $col->name;
                $ctype[] = $tpd[$col->type];
            }
        }
        $cnames = implode(', ', $cname);
        $ctypes = implode('', $ctype);

        /*
          i - integer
          d - double
          s - string
          b - BLOB
         */

        $vfile = 'tmp_crud.php';
        if (file_exists($vfile)) {
            unlink($vfile);
        }
        $content = '<?php' . "\n";
        $content .= '//This is a temporary file to add data to the table.' . "\n";
        $content .= "if(isset(\$_POST['addrow'])){" . "\n";
        $content .= $ptadds . "\n";
        $content .= '$sql = "INSERT INTO ' . $tble . ' (' . $vnames . ')' . "\n";
        $content .= 'VALUES (' . $nvls . ')";' . "\n";
        $content .= "\$stmt = \$conn->prepare(\$sql);" . "\n";
        $content .= '$stmt->bind_param("' . $ctypes . '", ' . $cnames . ');' . "\n";
        $content .= '$stmt->execute();' . "\n";
        $content .= '$stmt->close();' . "\n";
        $content .= "\$_SESSION['success'] = 'The data was added correctly';
header('Location: " . $this->pgname . "?cms=table_crud&w=list&tbl=" . $tble . "');
\$conn->close();" . "\n";
        $content .= "} \n";
        $content .= "?> \n";

        file_put_contents($vfile, $content, FILE_APPEND | LOCK_EX);

        include 'tmp_crud.php';

        echo '<form class="row form-horizontal" role="form" id="add_' . $tble . '" method="post" enctype="multipart/form-data">' . "\n";

        $this->joinCols($tble);

        echo '<div class="form-group">
        <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square></span> Add</button>
    </div>' . "\n";
        echo '</form>' . "\n";
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
            'binary' => 'b',
            'varbinary' => 'b',
            'tinyblob' => 'b',
            'blob' => 'b',
            'mediumblob' => 'b',
            'longblob' => 'b',
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

        $vfile = 'updatetmp.php';
        if (file_exists($vfile)) {
            unlink($vfile);
        }
        $content = '<?php' . "\n";
        $content .= '//This is temporal file only for add new row' . "\n";
        $content .= "if (isset(\$_POST['editrow'])) { \r\n";
        $content .= $scpt . "\r\n";
        $content .= "\$query=\"UPDATE `$tble` SET " . $ecols . " WHERE " . $ncol . " = ? \";" . "\r\n";
        $content .= '$stmt = $conn->prepare($query);' . "\r\n";
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
    }

    public function inputQEdit($tble, $id) {
        $columns = $this->viewColumns($tble);
        $ncol = $this->getID($tble);
        $resultq = $this->selectData("SELECT * FROM table_queries WHERE name_table='$tble'");
        $rowcq = $resultq->num_rows;
        $r = 0;
        if ($rowcq > $r) {
            echo '<form class = "row form-horizontal" role = "form" id = "edit_' . $tble . '" method = "POST" enctype = "multipart/form-data">' . "\n";
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

                if ($c_tp === 'int' || $c_tp === 'tinyint' || $c_tp === 'smallint' || $c_tp === 'mediumint' || $c_tp === 'bigint' || $c_tp === 'bit' || $c_tp === 'float' || $c_tp === 'double' || $c_tp === 'decimal') {
                    if ($i_tp === 3) {

                        echo '
<div class = "form-group">
<label for = "' . $c_nm . '" class = "control-label col-sm-3">' . $frmp . ':</label>
<select class = "form-select" id = "' . $c_nm . '" name = "' . $c_nm . '" >';

                        $qres = $this->getAllData($c_tb);

                        while ($rqj = $qres->fetch_array()) {
                            if ($cdta == $rqj[$c_id]) {
                                echo '<option value = "' . $rqj[$c_id] . '" selected>' . $rqj[$c_vl] . '</option>';
                            } else {
                                echo '<option value = "' . $rqj[$c_id] . '">' . $rqj[$c_vl] . '</option>';
                            }
                        }

                        echo '</select>';
                        echo '</div>';
                    } else {
                        echo '<div class = "form-group">
<label for = "' . $c_nm . '" class = "control-label col-sm-3">' . $frmp . ':</label> <input type = "text"
class = "form-control" id = "' . $c_nm . '" name = "' . $c_nm . '"
value = "' . $cdta . '">
</div>
' . "\n";
                    }
                }
                if ($c_tp === 'time' || $c_tp === 'year') {
                    echo '<div class = "form-group">
<label for = "' . $c_nm . '" class = "control-label col-sm-3">' . $frmp . ':</label>
<input type = "text" class = "form-control" id = "' . $c_nm . '" name = "' . $c_nm . '" value = "' . $cdta . '">
</div>' . "\n";
                }
                if ($c_tp === 'date' || $c_tp === 'datetime' || $c_tp === 'timestamp') {
                    echo '<div class = "form-group">
<label for = "' . $c_nm . '" class = "control-label col-sm-3">' . $frmp . ':</label>
<input type = "text" data-date-format = "dd/mm/yyyy" class = "form-control" id = "' . $c_nm . '" name = "' . $c_nm . '" value = "' . $cdta . '">
</div>' . "\n";
                    echo '<script type = "text/javascript">
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
            <?php echo $preview; ?>
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
                    $iresult = $this->selectData($isql);
                    $row = $iresult->fetch_array();
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
//
                    echo '<div class="form-group">
    <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
    <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

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

                    $iresult = $this->selectData($isql);
                    $row = $iresult->fetch_array();
                    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
                    $default_value = '';
                    //
                    echo '<div class="form-group">
        <label for="' . $c_nm . '" class ="control-label col-sm-3">' . $frmp . ':</label>
        <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

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

        $vfile = 'ftmp.php';

        $content = '<?php
            ' . "\n";
        $content .= "if(isset(\$_POST['editrow'])){" . "\n\n";
        $content .= $ptadds . "\n";
        $content .= '$sql = "UPDATE $tble SET ' . $pnames . ' WHERE $ncol=' . $id . '";
            ' . "\n";
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
        echo '<form class = "row form-horizontal" role = "form" id = "delete_' . $tble . '" method = "POST">' . "\n";
        $row = $qresult->fetch_assoc();
        while ($finfo = $qresult->fetch_field()) {
            $cdta = $row[$finfo->name];
            if ($finfo->name == $ncol) {
                continue;
            } else {
                $remp = str_replace("_", " ", $finfo->name);
                echo '<div class = "form-group">
            <label for = "' . $finfo->name . '">' . ucfirst($remp) . ':</label>
            <input type = "text" class = "form-control" id = "' . $finfo->name . '" name = "' . $finfo->name . '" value = "' . $cdta . '" readonly>
            </div>' . "\n";
            }
        }
        echo '<div class = "form-group">
            <button type = "submit" id = "deleterow" name = "deleterow" class = "btn btn-primary"><span class = "fas fa-trash-alt"></span> Delete</button>
            </div>' . "\n";
        echo '</form>' . "\n";
    }
}

$id = 2;
$tble = 'page';
$_SESSION['table'] = '';
$nc = new newCRUD();

//$stmt->bind_param();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Test </title>
    </head>
    <body>
        <?php
       echo implode(', ', $nc->listDatatype($tble));
        
        ?>
    </body>
</html>

