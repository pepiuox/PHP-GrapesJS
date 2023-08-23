<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = protect($_GET['w']);
} else {
    ?>
    <meta http-equiv="Refresh" content="0; url='dashboard.php?cms=theme_template&w=list'" />
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
                        <th>Theme</th>
                        <th>Base default</th>
                        <th>Active theme</th>
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
                            <a id="editrow" name="editrow" title="Edit" class="btn btn-success" href="dashboard.php?cms=theme_template&amp;w=edit&amp;tbl=theme_template&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-edit"></i></a>
                            <a id="deleterow" name="deleterow" title="Delete" class="btn btn-danger" href="dashboard.php?cms=theme_template&amp;w=delete&amp;tbl=theme_template&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-trash-alt"></i></a>
                        </td>
                        <td>' . $prow['title'] . '</td>
                        <td>' . $prow['theme_name'] . '</td>
                            <td>' . $prow['theme'] . '</td>
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

    $log_directory = '../themes';
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

    if (isset($_POST['addtheme'])) {
        $idtheme = uniqid(rand(), false);
        $id_page = $_POST['id_page'];
        $theme_name = $_POST['theme_name'];
        $theme = $_POST['theme'];
        $base_default = $_POST['base_default'];
        $active_theme = $_POST['active_theme'];

        $stmt = $conn->prepare("INSERT INTO themes (theme_id, id_page, theme_name, theme, base_default, active_theme) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("s1ssss", $idtheme, $theme_name, $theme, $base_default, $active_theme);
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
                    <label for="id_page">Page:</label>

                    <select id="id_page" name="id_page" class="form-select" aria-label="select">
                        <option>Select theme</option>
                        <?php
                        $pages = $conn->query("SELECT id, title FROM page");
                        while ($npg = $pages->fetch_array()) {
                            echo '<option value="' . $npg['id'] . '">' . $npg['title'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="theme_name">Theme name:</label>
                    <input type="text" class="form-control" id="theme_name" name="theme_name">
                </div>
                <div class="form-group">
                    <label for="theme">Select </label>
                    <select name="theme" id="theme" class="form-select" aria-label="select">
                        <option>Select theme</option>
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
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
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
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        ?>

        <script>
            function componentFromStr(numStr, percent) {
                var num = Math.max(0, parseInt(numStr, 10));
                return percent ?
                        Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
            }

            function rgbToHex(rgb) {
                var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
                var result, r, g, b, hex = "";
                if ((result = rgbRegex.exec(rgb))) {
                    r = componentFromStr(result[1], result[2]);
                    g = componentFromStr(result[3], result[4]);
                    b = componentFromStr(result[5], result[6]);

                    hex = "0x" + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1);
                }
                return hex;
            }

            document.body.innerHTML = rgbToHex("rgb(255,255,200)");
        </script>

        <?php
    }
}
?>
