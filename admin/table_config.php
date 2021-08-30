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

    $myTable = '';

    extract($_POST);
    $check_exist_qry = "SELECT * FROM table_config";
    $run_qry = $conn->query($check_exist_qry);
    $total_found = $run_qry->num_rows;
    if ($total_found > 0) {
        $my_value = $run_qry->fetch_assoc();
        $myTable = explode(',', $my_value['table_name']);
    }

    if (isset($submit)) {
        $all_table_value = implode(",", $_POST['tables']);
        if ($total_found > 0) {
            // update
            $upd_qry = "UPDATE table_config SET table_name='" . $all_table_value . "'";
            $conn->query($upd_qry);
            header("Location: table_config.php");
        } else {
            // insert
            $ins_qry = "INSERT INTO table_config(table_name) VALUES('" . $all_table_value . "')";
            $conn->query($ins_qry);
            header("Location: table_config.php");
        }
    }
}
include 'top.php';
?>
</head>
<body>

    <?php
    include 'header.php';
    if ($login->isLoggedIn() === true) {
        ?>
        <div class="container">

            <form class="form-horizontal" method="post" action="">
                <div class="col_md_12">
                    <?php
                    if ($result = $conn->query("SELECT DATABASE()")) {
                        $row = $result->fetch_row();
                        printf("<h6>Default database is %s </h6>.\n", $row[0]);
                        $result->close();
                    }
                    ?>
                    <h3 class="col-md-4 control-label" for="checkboxes">Tables you want to view :</h3>
                </div>
                <div class="col-md-4">
                    <div class="form-group">

                        <?php
                        $i = 0;
                        $x = 0;

                        $sql = "SHOW TABLES FROM $row[0]";
                        $result = $conn->query($sql);
                        $arrayCount = 0;
                        while ($row = mysqli_fetch_row($result)) {
                            $tableNames[$arrayCount] = $row[0];
                            $arrayCount++; // only do this to make sure it starts at index 0
                        }
                        foreach ($tableNames as $tname) {
                            $remp = str_replace("_", " ", $tname);
                            echo '<div class="checkbox">' . "\n";
                            echo '<label for="checkboxes-' . $i++ . '">';
                            echo '<input type="checkbox" id="checkboxes-' . $x++ . '" name="tables[]" value="' . $tname . '" ';
                            if (in_array($tname, $myTable)) {
                                echo "checked";
                            }
                            echo '> ';
                            echo ucfirst($remp) . '</label>' . "\n";
                            echo '</div>' . "\n";
                        }
                        ?>
                    </div>

                    <div class="form-group">
                        <button type="submit" id="submit" name="submit"
                                class="btn btn-primary">
                            <span class="glyphicon glyphicon-plus"></span> Visualizar Tablas
                        </button>
                    </div>
                </div>
            </form>
        </div>


        <?php
    } else {
        header("Location: ../signin/login.php");
    }
    ?>
</body>
</html>