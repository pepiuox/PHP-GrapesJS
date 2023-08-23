<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = protect($_GET['w']);
} else {
    ?>
    <meta http-equiv="Refresh" content="0; url='dashboard.php?cms=themes&w=list'" />
    <?php
}
if ($w == "list") {
    ?>
    <div class="container">
        <div class="row pt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th><a id="addrow" name="addrow" title="Add" class="btn btn-primary" href="dashboard.php?cms=theme_template&amp;w=add&amp;tbl=themes">Add <i class="fa fa-plus-square"></i></a></th>
                        <th>Page</th>
                        <th>Theme name</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM themes LEFT JOIN page ON themes.id_page = page.id");
                    $numr = $result->num_rows;
                    if ($numr > 0) {
                        while ($prow = $result->fetch_array()) {
                            echo '<tr>
                        <td><!--Button -->
                            <a id="edittemplate" name="edittemplate" title="Edit Template" class="btn btn-success" href="dashboard.php?cms=theme_template&amp;w=list"><i class="fas fa-edit"></i></a>
                            <a id="editoption" name="editoption" title="Edit Option" class="btn btn-primary" href="dashboard.php?cms=themes&amp;w=options&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-edit"></i></a>
                        </td>
                        <td>' . $prow['title'] . '</td>
                        <td>' . $prow['theme_name'] . '</td>
                        
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
        <meta http-equiv="Refresh" content="0; url='dashboard.php?cms=themes&w=list'" />
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
                                $container = $_POST['container'];
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
    location.href = 'dashboard.php?cms=themes&w=options#theme_settings';
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
                                <?php enum_values('theme_settings', 'container', $ts['container']); ?>
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
                                $primary = $_POST['primary'];
                                $secondary = $_POST['secondary'];
                                $info = $_POST['info'];
                                $light = $_POST['light'];
                                $dark = $_POST['dark'];
                                $success = $_POST['success'];
                                $warning = $_POST['warning'];
                                $danger = $_POST['danger'];
                                $custom = $_POST['custom'];
                                $custom_light = $_POST['custom_light'];
                                $custom_dark = $_POST['custom_dark'];

                                $sql = "UPDATE theme_palette SET primary_color = '$primary', secondary_color = '$secondary', info_color = '$info', light_color = '$light', dark_color = '$dark', success_color = '$success', warning_color = '$warning', danger_color = '$danger', custom_color = '$custom', custom_light_color = '$custom_light', custom_dark_color = '$custom_dark' WHERE idtp='$id' ";
                                if ($conn->query($sql) === TRUE) {
                                    echo "The data was updated correctly.";

                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard.php?cms=themes&w=options#theme_palette';
}
</script>";
                                } else {
                                    echo "Error updating data: " . $conn->error;
                                }
                            }
                            $rtp = $conn->query("SELECT * FROM theme_palette WHERE idtp='$id'");
                            $tp = $rtp->fetch_assoc();
                            ?> 
                            <form class="row form-horizontal" role="form" id="edit_theme_palette" method="POST">
                                <div class="form-group">
                                    <label for="primary" class ="control-label col-sm-3">Primary:</label>
                                    <input type="text" class="form-control" id="primary" name="primary" value="<?php echo $tp['primary_color']; ?>">
                                    <script>
                                        const primary = new CP(document.querySelector('#primary'));
                                        primary.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="secondary" class ="control-label col-sm-3">Secondary:</label>
                                    <input type="text" class="form-control" id="secondary" name="secondary" value="<?php echo $tp['secondary_color']; ?>">
                                    <script>
                                        const secondary = new CP(document.querySelector('#secondary'));
                                        secondary.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="info" class ="control-label col-sm-3">Info:</label>
                                    <input type="text" class="form-control" id="info" name="info" value="<?php echo $tp['info_color']; ?>">
                                    <script>
                                        const info = new CP(document.querySelector('#info'));
                                        info.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="light" class ="control-label col-sm-3">Light:</label>
                                    <input type="text" class="form-control" id="light" name="light" value="<?php echo $tp['light_color']; ?>">
                                    <script>
                                        const light = new CP(document.querySelector('#light'));
                                        light.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="dark" class ="control-label col-sm-3">Dark:</label>
                                    <input type="text" class="form-control" id="dark" name="dark" value="<?php echo $tp['dark_color']; ?>">
                                    <script>
                                        const dark = new CP(document.querySelector('#dark'));
                                        dark.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="success" class ="control-label col-sm-3">Success:</label>
                                    <input type="text" class="form-control" id="success" name="success" value="<?php echo $tp['success_color']; ?>">
                                    <script>
                                        const success = new CP(document.querySelector('#success'));
                                        success.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="warning" class ="control-label col-sm-3">Warning:</label>
                                    <input type="text" class="form-control" id="warning" name="warning" value="<?php echo $tp['warning_color']; ?>">
                                    <script>
                                        const warning = new CP(document.querySelector('#warning'));
                                        warning.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="danger" class ="control-label col-sm-3">Danger:</label>
                                    <input type="text" class="form-control" id="danger" name="danger" value="<?php echo $tp['danger_color']; ?>">
                                    <script>
                                        const danger = new CP(document.querySelector('#danger'));
                                        danger.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="custom" class ="control-label col-sm-3">Custom:</label>
                                    <input type="text" class="form-control" id="custom" name="custom" value="<?php echo $tp['custom_color']; ?>">
                                    <script>
                                        const custom = new CP(document.querySelector('#custom'));
                                        custom.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="custom_light" class ="control-label col-sm-3">Custom light:</label>
                                    <input type="text" class="form-control" id="custom_light" name="custom_light" value="<?php echo $tp['custom_light_color']; ?>">
                                    <script>
                                        const custom_light = new CP(document.querySelector('#custom_light'));
                                        custom_light.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="custom_dark" class ="control-label col-sm-3">Custom dark:</label>
                                    <input type="text" class="form-control" id="custom_dark" name="custom_dark" value="<?php echo $tp['custom_dark_color']; ?>">
                                    <script>
                                        const custom_dark = new CP(document.querySelector('#custom_dark'));
                                        custom_dark.on('change', function (r, g, b, a) {
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
    location.href = 'dashboard.php?cms=themes&w=options#theme_lead_font';
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
    location.href = 'dashboard.php?cms=themes&w=options#theme_headings_font';
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
    location.href = 'dashboard.php?cms=themes&w=options#theme_base_font';
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
                                $body = $_POST['body'];
                                $text = $_POST['text'];
                                $links = $_POST['links'];

                                $query = "UPDATE `theme_base_colors` SET body = '$body', text = '$text', links = '$links' WHERE idtbc='$id'";
                                if ($conn->query($query) === TRUE) {
                                    echo "The data was updated correctly.";

                                    echo "<script>
window.onload = function() {
    location.href = 'dashboard.php?cms=themes&w=options#theme_base_colors';
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
                                    <label for="body" class ="control-label col-sm-3">Body:</label>
                                    <input type="text" class="form-control" id="body" name="body" value="<?php echo$tbc['body']; ?>">
                                    <script>
                                        const body = new CP(document.querySelector('#body'));
                                        body.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>                                  
                                </div>
                                <div class="form-group">
                                    <label for="text" class ="control-label col-sm-3">Text:</label>
                                    <input type="text" class="form-control" id="text" name="text" value="<?php echo $tbc['text']; ?>">
                                    <script>
                                        const text = new CP(document.querySelector('#text'));
                                        text.on('change', function (r, g, b, a) {
                                        this.source.value = this.color(r, g, b, a);
                                        });
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="links" class ="control-label col-sm-3">Links:</label>
                                    <input type="text" class="form-control" id="links" name="links" value="<?php echo $tbc['links']; ?>">
                                    <script>
                                        const links = new CP(document.querySelector('#links'));
                                        links.on('change', function (r, g, b, a) {
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
