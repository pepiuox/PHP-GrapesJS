<?php
require 'conn.php';
if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {

    $valueCount = count($_POST["type_name"]);
    for ($i = 0; $i < $valueCount; $i++) {
        $conn->query("UPDATE `config` SET  `value` =  '{$_POST['value'][$i]}'   WHERE `type_name` = '{$_POST['type_name'][$i]}' ");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Editor Settings</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"/>
    </head>
    <body>
        <div class="container">
            <div class="row pt-4">              
                <div class="col-md-6">
                    <a class="btn btn-primary" href="list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                </div>
                <div class="col-md-6">                    
                    <a class="btn btn-primary" href="settings.php"><i class="fa fa-gear" aria-hidden="true"></i> Edit Settings</a>         
                </div>               
            </div>
            <div class="row">
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
                        $result = $conn->query("SELECT * FROM `config`") or trigger_error($conn->error);
                        while ($row = $result->fetch_array()) {
                            echo "<tr>";
                            echo "<td valign='top'><input type='text' name='type_name[]' id='type_name' value='" . $row['type_name'] . "' readonly /></td>";
                            echo "<td valign='top'><input type='text' name='value[]' id='value' value='" . $row['value'] . "' /></td>";
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
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/popper.min.js" type="text/javascript"></script>
    </body>
</html>