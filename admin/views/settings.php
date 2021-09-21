<?php
if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {

    $valueCount = count($_POST["config_name"]);
    for ($i = 0; $i < $valueCount; $i++) {
        $conn->query("UPDATE configuration SET  `config_value` =  '{$_POST['config_value'][$i]}'   WHERE `config_name` = '{$_POST['config_name'][$i]}' ");
    }

    $define = $conn->query("SELECT * FROM configuration");
    while ($rowt = $define->fetch_array()) {
        $values = $rowt['config_value'];
        $names = $rowt['config_name'];
        $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
    }

    $definefiles = '../config/define.php';

    if (!file_exists($definefiles)) {
        $ndef = '<?php' . "\n";
        $ndef .= implode(" ", $vars);
        $ndef .= '?>' . "\n";
        file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
    } else {
        unlink($definefiles);
        $ndef = '<?php' . "\n";
        $ndef .= implode(" ", $vars);
        $ndef .= '?>' . "\n";
        file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
    }

}
?>
<div class="container">            
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 py-4">
                    <div id="resp"></div>
                    <h3>Manage settings</h3> 
                    <form method='POST'> 
                        <?php
                        echo "<table class='table'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Config / Name</th>";
                        echo "<th>Config / Value</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        $result = $conn->query("SELECT * FROM `configuration`") or trigger_error($conn->error);
                        while ($row = $result->fetch_array()) {
                            echo "<tr>";
                            echo "<td valign='top'><input type='text' name='config_name[]' id='config_name' value='" . $row['config_name'] . "' readonly/></td>";
                            echo "<td valign='top'><input type='text' name='config_value[]' id='config_value' value='" . $row['config_value'] . "' /></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "<tfoot>";
                        echo "<tr>";
                        echo "<th>Config / Name</b></th>";
                        echo "<th><b>Config / Value</b></th>";
                        echo "</tr>";
                        echo "</tfoot>";
                        echo "</table>";
                        ?>
                        <div class='col-md-12'><input class="button" type='submit' value='Edit settings' /><input type='hidden' value='1' name='submitted' /></div> 
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>