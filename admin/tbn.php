<?php

include_once 'db.php';

if (!empty($_POST['snname'])) {

    $snname = $_POST['snname'];
    $stble = $_POST['stble'];

    function selvAsn($tbl, $sname) {
        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tbl;
        $qresult = $conn->query($sql);
        echo '<div class="form-group">';
        while ($vinfo = $qresult->fetch_field()) {
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
