<?php
session_start();

$file = '../config/dbconnection.php';
$nclose = '';
$base = $_SESSION['PathInstall'];

$folder = basename(dirname(__FILE__));

if (isset($_POST['submit'])) {

    $handle = fopen($file, 'w') or die('Cannot open file:  ' . $file);
    $actual = file_get_contents($file);

    $db_host = $_POST['host'];
    $db_user = $_POST['user'];
    $db_password = $_POST['password'];
    $db_name = $_POST['dbname'];
    $createdb = $_POST['cdbn'];
    if ($createdb === 'yes') {

        $mkdb = new mysqli($db_host, $db_user, $db_password);
        // Check connection
        if ($mkdb->connect_error) {
            die("Connection failed: " . $mkdb->connect_error);
        }

        // Create database
        $sql = "CREATE DATABASE " . $db_name;
        if ($mkdb->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $mkdb->error;
        }

        $mkdb->close();
        // Name of the file
        $filename = 'sql/page.sql';
        $mktbs = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($mktbs->connect_errno) {
            echo "Failed to connect to MySQL: " . $mktbs->connect_errno;
            echo "<br/>Error: " . $mktbs->connect_error;
        }

        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }
            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $mktbs->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $mktbs->error . '<br /><br />');
                // Reset temp variable to empty
                $templine = '';
            }
        }
        echo "Tables imported successfully";
        $mktbs->close();
    }
    $filecontent = '';
    $filecontent .= '<?php' . "\n\n";
    $filecontent .= "define('DBHOST', '" . $db_host . "');" . "\n";
    $filecontent .= "define('DBUSER', '" . $db_user . "');" . "\n";
    $filecontent .= "define('DBPASS', '" . $db_password . "');" . "\n";
    $filecontent .= "define('DBNAME', '" . $db_name . "');" . "\n";
    $filecontent .= '$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);' . "\n";
    $filecontent .= "
    /* If connection fails for some reason */
    if (\$conn->connect_error) {
        die('Error, Database connection failed: (' . \$conn->connect_errno . ') ' . \$conn->connect_error);
    }" . "\n";
    if (!empty($base)) {
        $filecontent .= "\$base = '" . $base . "/';" . "\n";
    } else {
        $filecontent .= "\$base = 'http://'.\$_SERVER['HTTP_HOST'].'/" . "\n";
    }

    $filecontent .= "require 'function.php';" . "\n";
    $filecontent .= "require 'define.php';
    ?>
    ";
    file_put_contents($file, $filecontent);

    $confdb = new mysqli($db_host, $db_user, $db_password);
    // Check connection
    if ($confdb->connect_error) {
        die("Connection failed: " . $confdb->connect_error);
    }
    $result = $confdb->query("SELECT config_name, config_value FROM configuration");

    while ($rowt = $result->fetch_array()) {
        $values = $rowt['config_value'];
        $names = $rowt['config_name'];
        $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
    }

//return implode(' ', $vars) . "\n";

    $definefiles = '../config/define.php';
    if (!file_exists($definefiles)) {
        $ndef = '<?php' . "\n";
        $ndef .= implode(' ', $vars) . "\n";
        $ndef .= '?>' . "\n";
        file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);
    }

    header('Location: ../configuration.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Content Editor</title>

        <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" data-type="keditor-style"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>css/font-awesome.min.css" data-type="keditor-style" />
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 py-4">
                    <div id="resp"></div>
                    <?php
                    if (file_exists($file)) {
                        ?>
                        <div class="modal fade in" id="myModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" >
                                        <h5 class="modal-title">File already exists</h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span> </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>The configuration file already exists.</p>
                                        <button class="btn btn-primary" name="edit" id="edit">Edit DB config file</button>
                                        <button class="btn btn-secondary" name="check" id="check">Check DB connection</button> 
                                        <button class="btn btn-info" name="install" id="install">Install tables</button>
                                    </div>
                                    <div class="modal-footer"> <a href="../list.php" class="btn btn-primary" name="edit" id="edit">Go to page list</a> <button type="button" name="close" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button> </div>
                                </div>
                            </div>
                        </div>
                    <?php } else {
                        ?>     
                        <form method="post">
                            <div class="form-group row">
                                <div class="col-8">
                                    <h2> Save your setting for DB</h2>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="host" class="col-4 col-form-label">Database Host</label> 
                                <div class="col-8">
                                    <input id="host" name="host" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="user" class="col-4 col-form-label">Database Username</label> 
                                <div class="col-8">
                                    <input id="user" name="user" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-4 col-form-label">Database Password</label> 
                                <div class="col-8">
                                    <input id="password" name="password" type="text" class="form-control">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <label for="dbname" class="col-4 col-form-label">Database Name</label> 
                                <div class="col-8">
                                    <input id="dbname" name="dbname" type="text" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <h5>This option creates the database with the tables</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="yes" id="cdbn" name="cdbn">
                                <label class="form-check-label" for="flexCheckDisabled">
                                    You have a database or do you need create one
                                </label>
                            </div>

                            <div class="form-group row">
                                <div class="offset-4 col-8">
                                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>

        <script src="<?php echo $base; ?>js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo $base; ?>js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo $base; ?>js/popper.min.js" type="text/javascript"></script>   
        <script type="text/javascript">
            $(window).on('load', function () {
                $('#myModal').modal('show');
            });
            $('#edit').click(function () {
                $('#myModal').modal('toggle');
                var edit = 1;
                $.ajax({
                    type: 'POST',
                    url: 'editconf.php',
                    data: {edit: edit}
                }).done(function (rsp) {
                    $('#resp').html(rsp);
                });
            });
            $('#check').click(function () {
                $('#myModal').modal('toggle');
                var check = 1;
                $.ajax({
                    type: 'POST',
                    url: 'checkconf.php',
                    data: {check: check}
                }).done(function (rsp) {
                    $('#resp').html(rsp);
                });
            });
            $('#install').click(function () {
                var install = 1;
                $.ajax({
                    type: 'POST',
                    url: 'installtables.php',
                    data: {install: install}
                }).done(function (rsp) {
                    $('#resp').html(rsp);
                });
            });
        </script>
    </body>
</html>