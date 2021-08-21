<?php
include '../classes/session.php';
$mypage = $_SERVER['PHP_SELF'];
$page = $mypage;

include '../top.php';
include 'header.php';
if ($session->logged_in) {
    ?> 
    <div class="container">   
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class='col-md-12'>       
                        <?php
                        if ($session->isAdmin()) {

                            if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {

                                $valueCount = count($_POST["type_name"]);
                                for ($i = 0; $i < $valueCount; $i++) {

                                    $conn->query("UPDATE `config` SET  `value` =  '{$_POST['value'][$i]}'   WHERE `type_name` = '{$_POST['type_name'][$i]}' ");
                                }
                                if ($conn === true) {
                                    $definefiles = '../config/define.php';
                                    unlink($definefiles);
                                    $result = $conn->query("SELECT config_name, config_value FROM configuration");

                                    while ($rowt = $result->fetch_array()) {
                                        $values = $rowt['config_value'];
                                        $names = $rowt['config_name'];
                                        $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
                                    }
                                    
                                    if (!file_exists($definefiles)) {
                                        $ndef = '<?php' . "\n";
                                        $ndef .= implode(' ', $vars) . "\n";
                                        $ndef .= '?>' . "\n";
                                        file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
                                    }
                                }
                            }
                            ?>
                            <h3>Administrar configuracion</h3> 
                            <form action='' method='POST'> 
                                <?php
                                echo "<table class='table table-striped table-sm'>";
                                echo "<thead>";
                                echo "<tr class=title>";
                                echo "<th><b>Config / Nombre</b></th>";
                                echo "<th><b>Config / Valor</b></th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                $result = $conn->query("SELECT * FROM `config`") or trigger_error($conn->error);
                                while ($row = $result->fetch_array()) {
                                    foreach ($row AS $key => $value) {
                                        $row[$key] = stripslashes($value);
                                    }
                                    echo "<tr>";
                                    echo "<td valign='top'><input type='text' name='type_name[]' id='type_name' value='" . $row['type_name'] . "' readonly /></td>";
                                    echo "<td valign='top'><input type='text' name='value[]' id='value' value='" . $row['value'] . "' /></td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "<tfoot>";
                                echo "<tr class=title>";
                                echo "<th><b>Config / Nombre</b></th>";
                                echo "<th><b>Config / Valor</b></th>";
                                echo "</tr>";
                                echo "</tfoot>";
                                echo "</table>";
                                ?>
                                <div class='col-md-12'><input class="button" type='submit' value='Editar configuracion' /><input type='hidden' value='1' name='submitted' /></div> 
                            </form> 
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    header("location: http://$bUrl/");
}
include '../footer.php';
?> 
