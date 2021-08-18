<?php
if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {

    $valueCount = count($_POST["type_name"]);
    for ($i = 0; $i < $valueCount; $i++) {
        $conn->query("UPDATE configuration SET  `config_value` =  '{$_POST['value'][$i]}'   WHERE `config_name` = '{$_POST['type_name'][$i]}' ");
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
    header("Refresh:0");
    exit();
}
?>
<div class="container">            
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 py-4">
                    <div id="resp"></div>
                    <h3>Manage settings</h3> 
                    <form action='' method='POST'> 
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
                            echo "<td valign='top'><label name='type_name[]' id='type_name'>" . $row['config_name'] . " :</label></td>";
                            echo "<td valign='top'><input type='text' name='value[]' id='value' value='" . $row['config_value'] . "' /></td>";
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