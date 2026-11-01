<?php

include_once 'db.php';

if (!empty($_POST['snname'])) {

    $snname = $_POST['snname'];
    $stble = $_POST['stble'];

    function selvAsn($tbl, $sname) {
        global $conn;
        if (!($conn instanceof PDO)) {
            die('Error: Could not connect to the database.');
        }

        $tbl = \PepiuoX\Security\SqlIdentifier::assert($tbl);
        $sql = "SELECT * FROM " . $tbl;
        $qresult = $conn->query($sql);
        echo '<div class="form-group">';
        /* ✅ PDO: getColumnMeta() reemplaza mysqli_result::fetch_field() */
        for ($c = 0; $c < $qresult->columnCount(); $c++) {
            $vinfo = (object) $qresult->getColumnMeta($c);
            if ($vinfo->name == $sname) {
                echo '
                <label class="col-md-12 control-label" for="column_as">There is the <b>' . $sname . '</b> column in the <b>' . $tbl . '</b> table, you need to configure with another name</label>
                <div class="col-md-12">
                    <input type="text" class="form-control" id="column_as" name="column_as" >               
                </div>
                </div>' . "\n";
            } if ($vinfo->name == !$sname) {
                echo '<div class="col-md-12">Is correct the ' . $sname . ' column.</div>' . "\n";
            }
        }
        echo '</div>' . "\n";
    }

    selvAsn($stble, $snname);
}
