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
                        <th><a id="addrow" name="addrow" title="Add" class="btn btn-primary" href="dashboard.php?cms=themes&amp;w=add&amp;tbl=themes">Add <i class="fa fa-plus-square"></i></a></th>
                        <th>Page</th>
                        <th>Theme name</th>
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
                            <a id="editrow" name="editrow" title="Edit" class="btn btn-success" href="dashboard.php?cms=themes&amp;w=edit&amp;tbl=themes&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-edit"></i></a>
                            <a id="deleterow" name="deleterow" title="Delete" class="btn btn-danger" href="dashboard.php?cms=themes&amp;w=delete&amp;tbl=themes&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-trash-alt"></i></a>
                        </td>
                        <td>' . $prow['title'] . '</td>
                        <td>' . $prow['theme_name'] . '</td>
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
        $themename = $_POST['theme'];
        $default = 'Yes';
        $idtheme = uniqid(rand(), false);
        $stmt = $conn->prepare("INSERT INTO themes (theme_id, theme_name, base_default) VALUES (?,?,?)");
        $stmt->bind_param("sss", $idtheme, $themename, $default);
        $stmt->execute();
        $stmt->close();
    }
    ?>
    <div class="container">
        <div class="row pt-3">
            <form role="form" id="add_theme_options" method="POST">
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
                    <input type="submit" id="addtheme" name="addtheme" class="btn btn-primary" value="Add theme">
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
