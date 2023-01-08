<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = protect($_GET['w']);
} else {
     ?>
    <meta http-equiv="Refresh" content="0; url='dashboard.php?cms=plugins&w=list'" />
    <?php
   
}

if ($w == "list") {
    $tble = 'plugins_app';
    $titl = ucfirst(str_replace("_", " ", $tble));
    ?>
    <div class="container">
        <div class="row pt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th><a id="addrow" name="addrow" title="Agregar" class="btn btn-primary" href="dashboard.php?cms=plugins&w=add">Add <i class="fas fa-plus-square"></i></a></th>
                        <th>Id</th>
                        <th>Plugins</th>
                        <th>Plugins Opts</th>
                        <th>Script</th>
                        <th>Css</th>
                        <th>Buttons</th>
                        <th>Plugins script</th>
                        <th>Plugins css</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $mopt = $conn->query("SELECT * FROM $tble");
                    while ($mnop = $mopt->fetch_array()) {
                        echo '
                        <tr>
                            <td><!--Button -->
                                <a id="editrow" name="editrow" title="Edit" class="btn btn-success" href="dashboard.php?cms=plugins&w=edit&id=' . $mnop['id'] . '"><i class="fas fa-edit"></i></a>
                                <a id="deleterow" name="deleterow" title="Delete" class="btn btn-danger" href="dashboard.php?cms=crud&w=delete&id=' . $mnop['id'] . '"><i class="fas fa-trash-alt"></i></a>
                            </td>
                            <td>' . $mnop['id'] . '</td>
                            <td>' . $mnop['plugins'] . '</td>
                            <td>' . $mnop['plugins_opts'] . '</td>
                            <td>' . $mnop['script'] . '</td>
                            <td>' . $mnop['css'] . '</td>
                            <td>' . $mnop['buttons'] . '</td>
                            <td>' . $mnop['plugins_script'] . '</td>
                            <td>' . $mnop['plugins_css'] . '</td>
                        </tr>' . "\n";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} elseif ($w == 'add') {
    $tble = 'plugins_app';
    if (isset($_POST['addrow'])) {
        $plugins = $_POST['plugins'];
        $plugins_opts = $_POST['plugins_opts'];
        $script = $_POST['script'];
        $css = $_POST['css'];
        $buttons = $_POST['buttons'];
        $plugins_script = $_POST['plugins_script'];
        $plugins_css = $_POST['plugins_css'];

        $sql = "INSERT INTO plugins_app (plugins, plugins_opts, script, css, buttons, plugins_script, plugins_css) "
                . "VALUES ('$plugins', '$plugins_opts', '$script', '$css', '$buttons', '$plugins_script', '$plugins_css')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = 'The data was added correctly';
            header('Location: dashboard.php?cms=plugins&w=list');
            exit();
        } else {
            $_SESSION['error'] = 'Error: ' . $conn->error;
        }

        $conn->close();
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=plugins&w=list">Back to List </a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Add Menu Options </h2>
            </div>
            <div class="card py-3">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row pt-3">
                            <form method="post" class="form-horizontal" role="form" id="add_plugins_app" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="plugins">Plugins:</label>
                                    <input type="text" class="form-control" id="plugins" name="plugins">
                                </div>
                                <div class="form-group">
                                    <label for="plugins_opts">Plugins Opts:</label>
                                    <input type="text" class="form-control" id="plugins_opts" name="plugins_opts">
                                </div>
                                <div class="form-group">
                                    <label for="script">Script:</label>
                                    <input type="text" class="form-control" id="script" name="script">
                                </div>
                                <div class="form-group">
                                    <label for="css">Css:</label>
                                    <input type="text" class="form-control" id="css" name="css">
                                </div>
                                <div class="form-group">
                                    <label for="buttons">Buttons:</label>
                                    <input type="text" class="form-control" id="buttons" name="buttons">
                                </div>
                                <div class="form-group">
                                    <label for="plugins_script">Plugins script:</label>
                                    <input type="text" class="form-control" id="plugins_script" name="plugins_script">
                                </div>
                                <div class="form-group">
                                    <label for="plugins_css">Plugins css:</label>
                                    <input type="text" class="form-control" id="plugins_css" name="plugins_css">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square" onclick="dVals();"></span> Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} elseif ($w == "edit") {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    }
    $tble = 'plugins_app';

    if (isset($_POST['editrow'])) {
        $plugins = $_POST["plugins"];
        $plugins_opts = $_POST["plugins_opts"];
        $script = $_POST["script"];
        $css = $_POST["css"];
        $buttons = $_POST["buttons"];
        $plugins_script = $_POST["plugins_script"];
        $plugins_css = $_POST["plugins_css"];

        $query = "UPDATE `$tble` SET plugins = '$plugins', plugins_opts = '$plugins_opts', script = '$script', css = '$css', buttons = '$buttons', plugins_script = '$plugins_script', plugins_css = '$plugins_css' WHERE id=$id ";
        if ($conn->query($query) === TRUE) {
            $_SESSION["success"] = "The data was updated correctly.";
            header("Location: dashboard.php?cms=plugins&w=list");
            exit();
        } else {
            $_SESSION["error"] = "Error updating data: " . $conn->error;
        }
    }

    $mopt = $conn->query("SELECT * FROM $tble WHERE id='$id'")->fetch_assoc();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=plugins&w=list">Back to List </a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Edit Menu Options </h2>
            </div>
            <div class="card py-3">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row pt-3">
                            <form role="form" id="add_plugins_app" method="POST">
                                <div class="form-group">
                                    <label for="plugins" class ="control-label col-sm-3">Plugins:</label>
                                    <input type="text" class="form-control" id="plugins" name="plugins" value="<?php echo $mopt['plugins']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="plugins_opts" class ="control-label col-sm-3">Plugins Opts:</label>
                                    <input type="text" class="form-control" id="plugins_opts" name="plugins_opts" value="<?php echo $mopt['plugins_opts']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="script" class ="control-label col-sm-3">Script:</label>
                                    <input type="text" class="form-control" id="script" name="script" value="<?php echo $mopt['script']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="css" class ="control-label col-sm-3">Css:</label>
                                    <input type="text" class="form-control" id="css" name="css" value="<?php echo $mopt['css']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="buttons" class ="control-label col-sm-3">Buttons:</label>
                                    <input type="text" class="form-control" id="buttons" name="buttons" value="<?php echo $mopt['buttons']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="plugins_script" class ="control-label col-sm-3">Plugins script:</label>
                                    <input type="text" class="form-control" id="plugins_script" name="plugins_script" value="<?php echo $mopt['plugins_script']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="plugins_css" class ="control-label col-sm-3">Plugins css:</label>
                                    <input type="text" class="form-control" id="plugins_css" name="plugins_css" value="<?php echo $mopt['plugins_css']; ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} elseif ($w == "delete") {
    $tble = 'plugins_app';
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="dashboard.php?cms=plugins&w=list">List Menu Option</a>
            </div>
            <div class="col-md-9">
                <center>
                    <h2 class="text-primary">Delete info</h2>
                    <h4 class="text-primary">Are you sure you want to delete data?</h4>
                </center>
                <hr>
            </div>
        </div>
        <div class="card py-3">
            <div class="card-body">
                <div class="col-md-12">
                    <?php
                    if (isset($_GET["id"])) {
                        $id = $_GET["id"];
                    }
                    if (isset($_POST["deleterow"])) {

                        if ($conn->query("DELETE FROM $tble WHERE id='$id'") === TRUE) {
                            $_SESSION['success'] = "Record deleted successfully";
                            header('Location: dashboard.php?cms=plugins&w=list');
                            exit();
                        } else {
                            $_SESSION['error'] = "Error deleting record";
                        }
                    }
                    ?>
                    <form method="post">
                        <div class="form-group">
                            <button type="submit" id="deleterow" name="deleterow" class="btn btn-primary"><span class="fas fa-trash-alt"></span> Delete</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
