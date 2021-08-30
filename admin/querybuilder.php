<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../config/checkfile.php';
require '../config/dbconnection.php';
require 'autoload.php';
$level = new AccessLevel();
$login = new UserClass();

if ($login->isLoggedIn() === true) {
    ob_start();

    extract($_POST);

    $path = basename($_SERVER['REQUEST_URI']);
    $file = basename($path);

    $fileName = basename($_SERVER['PHP_SELF']);

    if ($file == $fileName) {
        header('Location: querybuilder.php?w=select');
    }

    function protect($string) {
        $protection = htmlspecialchars(trim($string), ENT_QUOTES);
        return $protection;
    }

    $w = protect($_GET['w']);
    $c = new MyCRUD();
}
include '../elements/header.php';
?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    if ($login->isLoggedIn() === true) {
        if ($w == "select") {
            if ($result = $conn->query("SELECT * FROM table_config")) {
                $total_found = mysqli_num_rows($result);

                if ($total_found > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $tableNames = explode(',', $row['table_name']);
                }
            }
            ?>
            <script>
                $(function () {
                    $("#selecttb").change(function () {
                        var selecttb = $(this).val();
                        var value = selecttb.replace("_", " ");
                        var url = 'querybuilder.php?w=add&tbl=' + selecttb;
                        $('#fttl').text('Form ' + value);
                        window.location.replace(url);
                    });
                });
            </script>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3 id="fttl">Form</h3>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="selecttb">Select Table</label> <select
                                id="selecttb" name="selecttb" class="form-control">
                                <option value="">Select Table</option>
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
        } elseif ($w == "add") {
            ?>
            <div class="container">
                <div class="row">
                    <?php
                    $tble = protect($_GET['tbl']);

                    $vfile = 'qtmp.php';
                    if (file_exists($vfile)) {
                        unlink($vfile);
                    }

                    $query = "SELECT name_table FROM table_queries WHERE name_table = '{$tble}'";

                    if ($result = $conn->query($query)) {
                        // Return the number of rows in result set
                        $rowcount = mysqli_num_rows($result);
                        $r = 0;
                        if ($rowcount > $r) {

                            echo '<script>
                    window.location.href = "querybuilder.php?w=editor&tbl=' . $tble . '";
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
                                    $addq[] = "('" . $tble . "', '" . $finfo->name . "', '" . $finfo->type . "')" . "\n";
                                }
                            }
                            $dq .= implode(", ", $addq);
                            $dq .= '";';

                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $redir = '/querybuilder.php?w=editor&tbl=' . $tble;

                            $vfile = 'qtmp.php';
                            $myfile = fopen("$vfile", "w") or die("Unable to open file!");
                            $content = '<?php' . "\n";
                            $content .= '//This is temporal file only for add new row' . "\n";
                            $content .= "if(isset(\$_POST['addtable'])){";
                            $content .= $dq . "\n";
                            $content .= 'if($conn->query($query) === TRUE){' . "\r\n";
                            $content .= 'echo "Record added successfully";' . "\r\n";
                            $content .= '} else{
                                echo "Error added record: " . $conn->error;
                                }
                                }' . "\n";
                            $content .= "?> \n";
                            fwrite($myfile, $content);
                            fclose($myfile);

                            include 'qtmp.php';
                            echo '<form method="POST" role="form" class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Add table for query builder</legend>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="addtable"></label>
  <div class="col-md-4">
    <button id="addtable" name="addtable" class="btn btn-info" href="http://' . $host . $uri . $redir . '">Add table</button>
  </div>
</div>

</fieldset>
</form>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
            // end record
        } elseif ($w == "editor") {
            ?>

            <div class="container">


                <h2 class="text-primary">Add query for column</h2>

                <?php
                $tble = protect($_GET['tbl']);
                $sql1 = "SHOW COLUMNS FROM " . $tble;
                $result1 = $conn->query($sql1);
                $row = mysqli_fetch_array($result1);
                $ncol = $row[0];

                $sql = "SELECT * FROM table_queries WHERE name_table='{$tble}'";

                $qresult = $conn->query($sql);
                $count = mysqli_num_rows($qresult);
                $q = 1;
                ?>
                <?php
                echo '<form method="POST" role="form" id="query_' . $tble . '">' . "\n";

                while ($finfo = $qresult->fetch_assoc()) {
                    $remp = str_replace("_", " ", $finfo['col_name']);
                    $column = $finfo['col_name'];
                    echo '<style>
                #vtable-' . $finfo['tque_Id'] . '{
                    display: none;
                }
            </style>' . "\n";
                    echo '<script type="text/javascript">
                            $(document).ready(function () {
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
                          <button type="button" id="btsel_' . $finfo['tque_Id'] . '" name="btsel_' . $finfo['tque_Id'] . '" class="btn btn-secondary" data-toggle="modal" data-target="#Modal1">
                          Add Colmn Id and Value 
                          </button>
                          </div>' . "\n";

                    echo '<div class="form-group" id="text_' . $finfo['tque_Id'] . '">
                            <label for="' . $finfo['col_name'] . '">Query for ' . ucfirst($remp) . ':</label>
                            <textarea type="text" class="form-control" id="' . $finfo['col_name'] . '" name="' . $finfo['col_name'] . '">' . $finfo['query'] . '</textarea>
                          </div>' . "\n";
                }
                echo '<div class="form-group">
                        <button type = "submit" id="updatequeries" name="updatequeries" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span>Agregar Consultas</button>
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
                $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
                $content = '<?php' . "\n";
                $content .= '//This is temporal file only for add new row' . "\n";
                $content .= 'if (isset($_POST["updatequeries"])) {' . "\n";
                $content .= queries($tble);
                $content .= 'echo "Record added successfully";' . "\r\n";
                $content .= 'header("Location: querybuilder.php?w=editor&tbl=' . $tble . '");' . "\r\n";
                $content .= "} \r\n";
                $content .= "?> \n";

                fwrite($mfile, $content);
                fclose($mfile);

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

            <?php
            echo '<div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="Modal1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Select a column to relate</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">' . "\n";

            $idw = '';
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#table').change(function () {
                        var nome = this.value;
                        $.ajax({
                            type: 'POST',
                            url: 'tbq.php',
                            async: true,
                            cache: false,
                            data: 'nome=' + nome,
                            dataType: 'html',
                            success: function (data) {
                                $("#seltables").html(data);
                            }
                        });
                    });
                });
            </script>
            <?php
            echo ' <form class="form-horizontal" method="POST">
                                <fieldset>' . "\n";
            ?>
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
                <div id="seltables" name="seltables"></div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <button type="submit" id="submitrv" name="submitrv"
                            class="btn btn-warning">Save</button>
                </div>
            </div>
            <?php
            echo "</fieldset>
	</form>" . "\n";
            ?>

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
    header("Location: login.php");
}
?>
</body>
</html>