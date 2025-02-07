<?php

if ($w == "list") {
    ?>
    <div class="container">
        <div class="row pt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th><a id="addrow" name="addrow" title="Add" class="btn btn-primary" href="dashboard/theme_template&amp;w=add&amp;tbl=themes">Add <i class="fa fa-plus-square"></i></a></th>
                        <th>Theme name</th>
                        <th>Theme bootstrap</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM themes");
                    $numr = $result->num_rows;
                    if ($numr > 0) {
                        while ($prow = $result->fetch_array()) {
                            echo '<tr>
                        <td><!--Button -->
                            <a id="edittemplate" name="edittemplate" title="Edit Template" class="btn btn-success" href="dashboard/theme_template&amp;w=list"><i class="fas fa-edit"></i> List</a>
                            <a id="editoption" name="editoption" title="Edit Option" class="btn btn-primary" href="dashboard/themes&amp;w=options&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-edit"></i> More options</a>
                        </td>
                        <td>' . $prow['theme_name'] . '</td>
                        <td>' . $prow['theme_bootstrap'] . '</td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} elseif ($w == "options") {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = protect($_GET['id']);
    } else {
        ?>
        <meta http-equiv="Refresh" content="0; url='dashboard/themes/list'" />
        <?php
    }
    ?> 
    <!-- Color-Picker -->
    <script src="<?php echo SITE_PATH; ?>assets/plugins/color-picker/js/index.min.js" type="text/javascript"></script>
    <div class="container">                   
        <div class="row">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="theme_settings-tab" data-bs-toggle="tab" data-bs-target="#theme_settings" type="button" role="tab" aria-controls="theme_settings" aria-selected="true">Theme Settings</button>
                    <button class="nav-link" id="theme_palette-tab" data-bs-toggle="tab" data-bs-target="#theme_palette" type="button" role="tab" aria-controls="theme_palette" aria-selected="false">Theme Palette</button>               
                    <button class="nav-link" id="theme_lead_font-tab" data-bs-toggle="tab" data-bs-target="#theme_lead_font" type="button" role="tab" aria-controls="theme_lead_font" aria-selected="false">Theme Lead Font</button>
                    <button class="nav-link" id="theme_headings_font-tab" data-bs-toggle="tab" data-bs-target="#theme_headings_font" type="button" role="tab" aria-controls="theme_headings_font" aria-selected="false">Theme Headings Font</button>
                    <button class="nav-link" id="theme_base_font-tab" data-bs-toggle="tab" data-bs-target="#theme_base_font" type="button" role="tab" aria-controls="theme_base_font" aria-selected="false">Theme Base Font</button>
                    <button class="nav-link" id="theme_base_colors-tab" data-bs-toggle="tab" data-bs-target="#theme_base_colors" type="button" role="tab" aria-controls="theme_base_colors" aria-selected="false">Theme Base Colors</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="theme_settings" role="tabpanel" aria-labelledby="theme_settings-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="py-5 text-primary">Theme Settings</h3>
                            <?php
//This is temporal file only for add new row
                            if (isset($_POST['theme_settings'])) {
                                $container = $_POST["container"];
                                $spacer = $_POST['spacer'];
                                $radius = $_POST['radius'];
                                $radius_sm = $_POST['radius_sm'];
                                $radius_lg = $_POST['radius_lg'];
                                $font_size = $_POST['font_size'];

                                $query = "UPDATE `theme_settings` SET container = '$container', spacer = '$spacer', radius = '$radius', radius_sm = '$radius_sm', radius_lg = '$radius_lg', font_size = '$font_size' WHERE idts='$id' ";
                                if ($conn->query($query) === TRUE) {
                                    echo "The data was updated correctly.";

                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard/themes/options&id=" . $id . "';
}
</script>";
                                } else {
                                    echo "Error updating data: " . $conn->error;
                                }
                            }
                            $rts = $conn->query("SELECT * FROM theme_settings WHERE idts='$id'");
                            $ts = $rts->fetch_assoc();
                            ?> 
                            <form class="row form-horizontal" role="form" id="add_theme_settings" method="POST">
                                <?php enum_values('theme_settings', "container", $ts["container"]); ?>
                                <?php enum_values('theme_settings', 'spacer', $ts['spacer']); ?>                            
                                <div class="form-group">
                                    <label for="radius" class ="control-label col-sm-3">Radius:</label>
                                    <input type="text" class="form-control" id="radius" name="radius" value="<?php echo $ts['radius']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="radius_sm" class ="control-label col-sm-3">Radius sm:</label>
                                    <input type="text" class="form-control" id="radius_sm" name="radius_sm" value="<?php echo $ts['radius_sm']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="radius_lg" class ="control-label col-sm-3">Radius lg:</label>
                                    <input type="text" class="form-control" id="radius_lg" name="radius_lg" value="<?php echo $ts['radius_lg']; ?>">
                                </div>
                                <?php enum_values('theme_settings', 'font_size', $ts['font_size']); ?>
                                <div class="form-group">
                                    <button type="submit" id="theme_settings" name="theme_settings" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="theme_palette" role="tabpanel" aria-labelledby="theme_palette-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="py-5 text-primary">Theme Palette</h3>
                            <?php
//This is temporal file only for add new row
                            if (isset($_POST['theme_palette'])) {
                                $primary = $_POST['primary_color'];
                                $secondary = $_POST['secondary_color'];
                                $info = $_POST['info_color'];
                                $light = $_POST['light_color'];
                                $dark = $_POST['dark_color'];
                                $success = $_POST['success_color'];
                                $warning = $_POST['warning_color'];
                                $danger = $_POST['danger_color'];
                                $custom = $_POST['custom_color'];
                                $custom_light = $_POST['custom_light_color'];
                                $custom_dark = $_POST['custom_dark_color'];

                                $sql = "UPDATE theme_palette SET primary_color = ?, secondary_color = ', info_color = ?, light_color = ?, dark_color = ?, success_color = ?, warning_color = ?, danger_color = ?, custom_color = ?, custom_light_color = ?, custom_dark_color = ? WHERE idtp = ? ";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssssssssssss", $primary, $secondary, $info, $light, $dark, $success, $warning, $danger, $custom, $custom_light, $custom_dark, $id);
                                if ($stmt->error) {
                                    echo "FAILURE!!! " . $stmt->error;
                                } else {
                                    echo "The data was updated correctly.";
                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard/themes/options&id=" . $id . "';
}
</script>";
                                }
                            }
                            $sql = "SELECT * FROM theme_palette WHERE idtp=?";
                            $rtp = $conn->prepare($sql);
                            $rtp->bind_param("s", $id);
                            $rtp->execute();
                            $result = $rtp->get_result(); // get the mysqli result
                            $tp = $result->fetch_assoc();
                            ?> 
                            <form class="row form-horizontal" role="form" id="edit_theme_palette" method="POST">
                                <div class="form-group">
                                    <label for="primary_color" class ="control-label col-sm-3">Primary:</label>
                                    <input type="text" class="form-control" id="primary_color" name="primary_color" value="<?php echo $tp['primary_color']; ?>">
                                    <script>
                                        const primary_color = new CP(document.querySelector('#primary_color'));
                                        primary_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="secondary_color" class ="control-label col-sm-3">Secondary:</label>
                                    <input type="text" class="form-control" id="secondary_color" name="secondary_color" value="<?php echo $tp['secondary_color']; ?>">
                                    <script>
                                        const secondary_color = new CP(document.querySelector('#secondary_color'));
                                        secondary_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="info_color" class ="control-label col-sm-3">Info:</label>
                                    <input type="text" class="form-control" id="info_color" name="info_color" value="<?php echo $tp['info_color']; ?>">
                                    <script>
                                        const info_color = new CP(document.querySelector('#info_color'));
                                        info_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="light_color" class ="control-label col-sm-3">Light:</label>
                                    <input type="text" class="form-control" id="light_color" name="light_color" value="<?php echo $tp['light_color']; ?>">
                                    <script>
                                        const light_color = new CP(document.querySelector('#light_color'));
                                        light_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="dark_color" class ="control-label col-sm-3">Dark:</label>
                                    <input type="text" class="form-control" id="dark_color" name="dark_color" value="<?php echo $tp['dark_color']; ?>">
                                    <script>
                                        const dark_color = new CP(document.querySelector('#dark_color'));
                                        dark_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="success_color" class ="control-label col-sm-3">Success:</label>
                                    <input type="text" class="form-control" id="success_color" name="success_color" value="<?php echo $tp['success_color']; ?>">
                                    <script>
                                        const success_color = new CP(document.querySelector('#success_color'));
                                        success_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="warning_color" class ="control-label col-sm-3">Warning:</label>
                                    <input type="text" class="form-control" id="warning_color" name="warning_color" value="<?php echo $tp['warning_color']; ?>">
                                    <script>
                                        const warning_color = new CP(document.querySelector('#warning_color'));
                                        warning_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="danger_color" class ="control-label col-sm-3">Danger:</label>
                                    <input type="text" class="form-control" id="danger_color" name="danger_color" value="<?php echo $tp['danger_color']; ?>">
                                    <script>
                                        const danger_color = new CP(document.querySelector('#danger_color'));
                                        danger_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="custom_color" class ="control-label col-sm-3">Custom:</label>
                                    <input type="text" class="form-control" id="custom_color" name="custom_color" value="<?php echo $tp['custom_color']; ?>">
                                    <script>
                                        const custom_color = new CP(document.querySelector('#custom_color'));
                                        custom_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="custom_light_color" class ="control-label col-sm-3">Custom light:</label>
                                    <input type="text" class="form-control" id="custom_light_color" name="custom_light_color" value="<?php echo $tp['custom_light_color']; ?>">
                                    <script>
                                        const custom_light_color = new CP(document.querySelector('#custom_light_color'));
                                        custom_light_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="custom_dark_color" class ="control-label col-sm-3">Custom dark:</label>
                                    <input type="text" class="form-control" id="custom_dark_color" name="custom_dark_color" value="<?php echo $tp['custom_dark_color']; ?>">
                                    <script>
                                        const custom_dark_color = new CP(document.querySelector('#custom_dark_color'));
                                        custom_dark_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="theme_palette" name="theme_palette" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="theme_lead_font" role="tabpanel" aria-labelledby="theme_lead_font-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="py-5 text-primary">Theme Lead Font</h3>
                            <?php
//This is temporal file only for add new row
                            if (isset($_POST['theme_lead_font'])) {
                                $size = $_POST['size'];
                                $weight = $_POST['weight'];

                                $query = "UPDATE `theme_lead_font` SET size = '$size', weight = '$weight' WHERE idtlf='$id' ";
                                if ($conn->query($query) === TRUE) {
                                    echo "The data was updated correctly.";

                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard/themes/options&id=" . $id . "';
}
</script>";
                                } else {
                                    echo "Error updating data: " . $conn->error;
                                }
                            }
                            $rtlf = $conn->query("SELECT * FROM theme_lead_font WHERE idtlf='$id'");
                            $tlf = $rtlf->fetch_assoc();
                            ?>
                            <form class="row form-horizontal" role="form" id="add_theme_lead_font" method="POST">
                                <div class="form-group">
                                    <label for="size" class ="control-label col-sm-3">Size:</label>
                                    <input type="text" class="form-control" id="size" name="size" value="<?php echo $tlf['size']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="weight" class ="control-label col-sm-3">Weight:</label>
                                    <input type="text" class="form-control" id="weight" name="weight" value="<?php echo $tlf['weight']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="theme_lead_font" name="theme_lead_font" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="theme_headings_font" role="tabpanel" aria-labelledby="theme_headings_font-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="py-5 text-primary">Theme Headings Font</h3>
                            <?php
//This is temporal file only for add new row
                            if (isset($_POST['theme_headings_font'])) {
                                $family = $_POST['family'];
                                $weight = $_POST['weight'];
                                $line_weight = $_POST['line_weight'];

                                $query = "UPDATE `theme_headings_font` SET family = '$family', weight = '$weight', line_weight = '$line_weight' WHERE idthf='$id' ";
                                if ($conn->query($query) === TRUE) {
                                    echo "The data was updated correctly.";

                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard/themes/options&id=" . $id . "';
}
</script>";
                                } else {
                                    echo "Error updating data: " . $conn->error;
                                }
                            }
                            $rthf = $conn->query("SELECT * FROM theme_headings_font WHERE idthf='$id'");
                            $thf = $rthf->fetch_assoc();
                            ?> 
                            <form class="row form-horizontal" role="form" id="add_theme_headings_font" method="POST">
                                <div class="form-group">
                                    <label for="family" class ="control-label col-sm-3">Family:</label>
                                    <input type="text" class="form-control" id="family" name="family" value="<?php echo $thf['family']; ?>">
                                </div>
                                <?php enum_values('theme_headings_font', 'weight', $thf['weight']); ?>
                                <div class="form-group">
                                    <label for="line_weight" class ="control-label col-sm-3">Line weight:</label>
                                    <input type="text" class="form-control" id="line_weight" name="line_weight" value="<?php echo $thf['line_weight']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="theme_headings_font" name="theme_headings_font" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="theme_base_font" role="tabpanel" aria-labelledby="theme_base_font-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="py-5 text-primary">Theme Base Font</h3>
                            <?php
                            if (isset($_POST['theme_base_font'])) {
                                $family = $_POST['family'];
                                $size = $_POST['size'];
                                $weight = $_POST['weight'];
                                $line_height = $_POST['line_height'];

                                $query = "UPDATE `theme_base_font` SET family = '$family', size = '$size', weight = '$weight', line_height = '$line_height' WHERE idtbf='$id' ";
                                if ($conn->query($query) === TRUE) {
                                    echo "The data was updated correctly.";

                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard/themes/options&id=" . $id . "';
}
</script>";
                                } else {
                                    echo "Error updating data: " . $conn->error;
                                }
                            }
                            $rtbf = $conn->query("SELECT * FROM theme_base_font WHERE idtbf='$id'");
                            $tbf = $rtbf->fetch_assoc();
                            ?>
                            <form class="row form-horizontal" role="form" id="add_theme_base_font" method="POST">
                                <div class="form-group">
                                    <label for="family" class ="control-label col-sm-3">Family:</label>
                                    <input type="text" class="form-control" id="family" name="family" value="<?php echo $tbf['family']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="size" class ="control-label col-sm-3">Size:</label>
                                    <input type="text" class="form-control" id="size" name="size" value="<?php echo $tbf['size']; ?>">
                                </div>
                                <?php enum_values('theme_base_font', 'weight', $tbf['weight']); ?>
                                <div class="form-group">
                                    <label for="line_height" class ="control-label col-sm-3">Line height:</label>
                                    <input type="text" class="form-control" id="line_height" name="line_height" value="<?php echo $tbf['line_height']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="theme_base_font" name="theme_base_font" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>
                                </div>
                            </form> 
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="theme_base_colors" role="tabpanel" aria-labelledby="theme_base_colors-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="py-5 text-primary">Theme Base Colors</h3>
                            <?php
                            if (isset($_POST['theme_base_colors'])) {
                                $_POST = array_map('stripslashes', $_POST);

                                //collect form data
                                extract($_POST);

                                //$body_color = $_POST['body_color'];
                                //$text_color = $_POST['text_color'];
                                //$links_color = $_POST['links_color'];

                                $query = "UPDATE `theme_base_colors` SET body_color = '$body_color', text_color = '$text_color', links_color = '$links_color' WHERE idtbc='$id'";
                                if ($conn->query($query) === TRUE) {
                                    echo "The data was updated correctly.";

                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard/themes/options&id=" . $id . "';
}
</script>";
                                } else {
                                    echo "Error updating data: " . $conn->error;
                                }
                            }
                            $rtbc = $conn->query("SELECT * FROM theme_base_colors WHERE idtbc='$id'");
                            $tbc = $rtbc->fetch_assoc();
                            ?> 
                            <form class="row form-horizontal" role="form" id="add_theme_base_colors" method="POST">
                                <div class="form-group">
                                    <label for="body_color" class ="control-label col-sm-3">Body:</label>
                                    <input type="text" class="form-control" id="body_color" name="body_color" value="<?php echo $tbc['body_color']; ?>">
                                    <script>
                                        const body_color = new CP(document.querySelector('#body_color'));
                                        body_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>                                  
                                </div>
                                <div class="form-group">
                                    <label for="text" class ="control-label col-sm-3">Text:</label>
                                    <input type="text" class="form-control" id="text_color" name="text_color" value="<?php echo $tbc['text_color']; ?>">
                                    <script>
                                        const text_color = new CP(document.querySelector('#text_color'));
                                        text_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="links" class ="control-label col-sm-3">Links:</label>
                                    <input type="text" class="form-control" id="links_color" name="links_color" value="<?php echo $tbc['links_color']; ?>">
                                    <script>
                                        const links_color = new CP(document.querySelector('#links_color'));
                                        links_color.on('change', function (r, g, b, a) {
                                            this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="theme_base_colors" name="theme_base_colors" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
