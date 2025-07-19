<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    
      $log_directory = URL.'/build/themes';
      $results_array = array();

        if (is_dir($log_directory)) {
            if ($handle = opendir($log_directory)) {
                //Notice the parentheses I added:
                while (($file = readdir($handle)) !== FALSE) {
                    $results_array[] = $file;
                }
                closedir($handle);
            }
        }
              
if ($w == "list") {
       
    ?>
    <div class="container">
        <div class="row pt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th><a id="addrow" name="addrow" title="Add" class="btn btn-primary" href="../theme_template/add/themes">Add <i class="fa fa-plus-square"></i></a></th>                      
                        <th>Theme name</th>
                        <th>Theme bootstrap</th>
                        <th>Base default</th>
                        <th>Active theme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM themes ");
                    $numr = $result->num_rows;
                    if ($numr > 0) {
                        while ($prow = $result->fetch_array()) {
                            echo '<tr>
                        <td><!--Button -->
                            <a id="editrow" name="editrow" title="Edit" class="btn btn-success" href="../theme_template/edit/theme_template/' . $prow['theme_id'] . '"><i class="fas fa-edit"></i></a>
                            <a id="deleterow" name="deleterow" title="Delete" class="btn btn-danger" href="../theme_template/delete/theme_template/' . $prow['theme_id'] . '"><i class="fas fa-trash-alt"></i></a>
                        </td>                        
                        <td>' . $prow['theme_name'] . '</td>
                            <td>' . $prow['theme_bootstrap'] . '</td>
                        <td>' . $prow['base_default'] . '</td>
                        <td>' . $prow['active_theme'] . '</td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} elseif ($w == "add") {

    if (isset($_POST['addtheme'])) {
        $idtheme = uniqid(rand(), false);

        $theme_name = $_POST['theme_name'];
        $theme = $_POST['theme'];
        $base_default = $_POST['base_default'];
        $active_theme = $_POST['active_theme'];

        $stmt = $conn->prepare("INSERT INTO themes (theme_id, theme_name, theme, base_default, active_theme) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $idtheme, $theme_name, $theme, $base_default, $active_theme);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO theme_base_colors (idtbc) VALUES (?)");
        $stmt->bind_param("s", $idtheme);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO theme_base_font (idtbf) VALUES (?)");
        $stmt->bind_param("s", $idtheme);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO theme_headings_font (idthf) VALUES (?)");
        $stmt->bind_param("s", $idtheme);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO theme_lead_font (idtlf) VALUES (?)");
        $stmt->bind_param("s", $idtheme);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO theme_palette (idtp) VALUES (?)");
        $stmt->bind_param("s", $idtheme);
        $stmt->execute();

        $stmt = $conn->prepare("INSERT INTO theme_settings (idts) VALUES (?)");
        $stmt->bind_param("s", $idtheme);
        $stmt->execute();
        $stmt->close();
    }
    ?>
    <div class="container">
        <div class="row pt-3">

            <form method="post" class="row form-horizontal" role="form" id="add_themes" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="theme_name">Theme name:</label>
                    <input type="text" class="form-control" id="theme_name" name="theme_name">
                </div>
                <div class="form-group">
                    <label for="theme_bootstrap">Select theme bootstrap </label>
                    <select name="theme_bootstrap" id="theme_bootstrap" class="form-select" aria-label="select">
                        <option>Select theme bootstrap</option>
                        <?php
                        //Output findings
                        foreach ($results_array as $value) {
                            if ($value === '.' || $value === '..') {
                                continue;
                            }
                            echo '<option value="' . $value . '">' . ucfirst($value) . '</option>';
                        }
                        ?>
                    </select> 
                </div>
                <div class="form-group">
                    <label for="base_default">Base default:</label>
                    <select type="text" class="form-select" id="base_default" name="base_default" >
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="active_theme">Active theme:</label>
                    <select type="text" class="form-select" id="active_theme" name="active_theme" >
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>                    
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" id="addtheme" name="addtheme" class="btn btn-primary"><span class="fas fa-plus-square"></span> Add theme</button>
                </div>
            </form>
        </div>
    </div>
    <?php
} elseif ($w == "edit") {
    if (isset($id) && !empty($id)) {
        
if(is_numeric($id)=== TRUE){
    
//This is temporal file only for add new row
        if (isset($_POST['editrow'])) {

            $theme_name = $_POST["theme_name"];
            $theme_bootstrap = $_POST["theme_bootstrap"];
            $base_default = $_POST["base_default"];
            $active_theme = $_POST["active_theme"];

            $query = "UPDATE themes SET theme_name = '$theme_name', theme_bootstrap = '$theme_bootstrap', base_default = '$base_default', active_theme = '$active_theme' WHERE theme_id='$id' ";
            if ($conn->query($query) === TRUE) {
                $_SESSION["success"] = "The data was updated correctly.";
 echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/theme_template/list">' . "\n";
                
            } else {
                $_SESSION["error"] = "Error updating data: " . $conn->error;
            }
        }
        $rtt = $conn->query("SELECT * FROM themes WHERE theme_id='$id'");
        $tt = $rtt->fetch_assoc();
        ?> 
        <div class="container">
            <div class="row">
                <form role="form" id="edit_themes" method="POST">

                    <div class="form-group">
                        <label for="theme_name" class ="control-label col-sm-3">Theme name:</label>
                        <input type="text" class="form-control" id="theme_name" name="theme_name" value="<?php echo $tt['theme_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="theme_bootstrap" class ="control-label col-sm-3">Theme bootstrap:</label>
                        <select name="theme_bootstrap" id="theme_bootstrap" class="form-select" aria-label="select">
                            <option>Select theme bootstrap</option>
                            <?php
                            $thmbt = $tt['theme_bootstrap'];
                            //Output findings
                            foreach ($results_array as $value) {
                                if ($value === '.' || $value === '..') {
                                    continue;
                                }
                                if ($value == $thmbt) {
                                    echo '<option value="' . $value . '" selected>' . ucfirst($value) . '</option>';
                                } else {
                                    echo '<option value="' . $value . '">' . ucfirst($value) . '</option>';
                                }
                            }
                            ?>
                        </select> 
                    </div>
                    <?php enum_values('themes', 'base_default', $tt['base_default']); ?>
                    <?php enum_values('themes', 'active_theme', $tt['active_theme']); ?>

                    <div class="form-group">
                        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        }
    }else{
        echo '<meta http-equiv="refresh" content="0;url='.SITE_PATH.'admin/dashboard/theme_template/list">' . "\n";
    }
} elseif ($w == "delete") {
    
}
}
?>
