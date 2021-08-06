<?php
require '../config/conn.php';
if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {

    $valueCount = count($_POST["type_name"]);
    for ($i = 0; $i < $valueCount; $i++) {
        $conn->query("UPDATE `config` SET  `value` =  '{$_POST['value'][$i]}'   WHERE `type_name` = '{$_POST['type_name'][$i]}' ");
    }

    $define = $conn->query("SELECT * FROM config");
    while ($def = $define->fetch_array()) {
        $type_name = $def['type_name'];
        $value = $def['value'];
        $vars[] = "define('" . $type_name . "', '" . $value . "');" . "\n";
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
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Editor Settings</title>
        <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>css/font-awesome.min.css"/>
    </head>
    <body>
        <!-- start menu -->                     
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <div class="menu-logo">
                    <div class="navbar-brand">
                        <a class="navbar-logo" href="<?php echo $base; ?>">
                            <?php echo SITE_NAME; ?> 
                        </a>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span> 
                </button>
                <div id="navbarNavDropdown" class="navbar-collapse collapse
                     justify-content-end">
                    <ul class="navbar-nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="btn btn-success" href="../list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="../add.php"><i class="fa fa-file-o" aria-hidden="true"></i> Add New Page</a>
                        </li>                         
                    </ul>   
                </div>
            </div>
        </nav>
        <!<!-- end menu -->
        <div class="container">            
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
        <script src="<?php echo $base; ?>js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo $base; ?>js/bootstrap.min.js" type="text/javascript"></script>        
        <script src="<?php echo $base; ?>js/popper.min.js" type="text/javascript"></script>
    </body>
</html>