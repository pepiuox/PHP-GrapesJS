<?php

include_once '../config/dbconnection.php';
if (isset($_POST['nome']) && $_POST['nome'] != "") {
    $svlue = $_POST["nome"];

    function selrQuery($tble) {
        global $link;
        if (!$link) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tble;
        $qresult = $link->query($sql);

        echo '<div class="form-group">
              <label class="col-md-12 control-label" for="column_id">Select a value to relate</label>
              <div class="col-md-12"> 
                 <select id="column_id" name="column_id" class="form-control">' . "\n";
        while ($rinfo = $qresult->fetch_field()) {
            $rempp = str_replace("_", " ", $rinfo->name);
            echo '<option value="' . $rinfo->name . '">' . $rempp . '</option>' . "\n";
        }
        echo '   </select>
              </div>
              </div>' . "\n";
        return;
    }

    function selvQuery($tble) {
        global $link;
        if (!$link) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tble;
        $qresult = $link->query($sql);

        echo '<div class="form-group">
  <label class="col-md-12 control-label" for="column_value">Select a value for show</label>
  <div class="col-md-12">
    <select id="column_value" name="column_value" class="form-control">' . "\n";
        while ($vinfo = $qresult->fetch_field()) {
            $vempp = str_replace("_", " ", $vinfo->name);
            echo '<option value="' . $vinfo->name . '">' . $vempp . '</option>' . "\n";
        }
        echo '</select>
  </div>
</div>' . "\n";
        return;
    }

    echo selrQuery($svlue);
    echo selvQuery($svlue);
}


