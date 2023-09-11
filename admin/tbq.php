<?php

include_once '../config/dbconnection.php';
if (isset($_POST['tbname']) && $_POST['tbname'] != "") {
    $tbname = $_POST["tbname"];
    $tid = $_POST["tid"];
    $tvl = $_POST["tvl"];

    function selrQuery($tble, $tid = '') {
        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tble;
        $qresult = $conn->query($sql);

        echo '<div class="form-group">
              <label class="col-md-12 control-label" for="column_id">Select a value to relate</label>
              <div class="col-md-12"> 
                 <select id="column_id" name="column_id" class="form-select">' . "\n";
        while ($rinfo = $qresult->fetch_field()) {
            $rempp = str_replace("_", " ", $rinfo->name);
            if ($rinfo->name == $tid) {
                echo '<option value="' . $rinfo->name . '" selected>' . $rempp . '</option>' . "\n";
            } else {
                echo '<option value="' . $rinfo->name . '">' . $rempp . '</option>' . "\n";
            }
        }
        echo '   </select>
              </div>
              </div>' . "\n";
    }

    function selvQuery($tble, $tvl = '') {
        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tble;
        $qresult = $conn->query($sql);

        echo '<div class="form-group">
  <label class="col-md-12 control-label" for="column_value">Select a value for show</label>
  <div class="col-md-12">
    <select id="column_value" name="column_value" class="form-select">' . "\n";
        while ($vinfo = $qresult->fetch_field()) {
            $vempp = str_replace("_", " ", $vinfo->name);
            echo '<option value="' . $vinfo->name . '">' . $vempp . '</option>' . "\n";
            if ($vinfo->name == $tvl) {
                echo '<option value="' . $vinfo->name . '" selected>' . $vempp . '</option>' . "\n";
            } else {
                echo '<option value="' . $vinfo->name . '">' . $vempp . '</option>' . "\n";
            }
        }
        echo '</select>
  </div>
</div>' . "\n";
    }

    echo selrQuery($tbname, $tid);
    echo selvQuery($tbname, $tvl);
}


