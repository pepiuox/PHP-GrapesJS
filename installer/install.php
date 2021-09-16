<?php
session_start();

if (isset($_SESSION['PathInstall'])) {
    $base = $_SESSION['PathInstall'];
} else {
    $folder = basename(dirname(__DIR__));
    $base = "http://" . $_SERVER['HTTP_HOST'] . '/' . $folder . '/';
}

$rname = $_SERVER["REQUEST_URI"];
$definefiles = '../config/define.php';
$file = '../config/dbconnection.php';

if (!file_exists($file)) {

    if (isset($_GET['step']) && !empty($_GET['step'])) {
        $step = $_GET['step'];
    } else {
        $_SESSION['StepInstall'] = 1;
        header("Location: install.php?step=1");
    }

    if (isset($_SESSION['DBConnected']) && !empty($_SESSION['DBConnected'])) {
        if ($_SESSION['DBConnected'] === 'Connected') {
            $conn = new mysqli($_SESSION['DBHOST'], $_SESSION['DBUSER'], $_SESSION['DBPASSWORD'], $_SESSION['DBNAME']);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            require 'installUser.php';
        }
    }


// Firts step
// Check Database
    if (isset($_POST['check'])) {
        $db_host = $_POST['host'];
        $db_user = $_POST['user'];
        $db_password = $_POST['password'];
        $db_name = $_POST['dbname'];

        $_SESSION['DBHOST'] = $db_host;
        $_SESSION['DBUSER'] = $db_user;
        $_SESSION['DBPASSWORD'] = $db_password;
        $_SESSION['DBNAME'] = $db_name;

        $conf = new mysqli($db_host, $db_user, $db_password, $db_name);

        /* If connection fails for some reason */
        if ($conf->connect_error) {
            $_SESSION['ErrorMessage'] = "The database has not been created yet, do you want to create it?";
            $_SESSION['StepInstall'] = 2;
            header("Location: install.php?step=2");
            exit();
        } else {
            $_SESSION['SuccessMessage'] = "The database exists, now you need to import the data tables.";
            $_SESSION['StepInstall'] = 3;
            $_SESSION['DBConnected'] = 'Connected';
            header("Location: install.php?step=3");
            exit();
        }
    }

// Back to first step
    if (isset($_POST['init'])) {
        $_SESSION['StepInstall'] = 1;
        header("Location: install.php?step=1");
    }

// Second step
// Create your DataBase
    if (isset($_POST['createdb'])) {
        $createdb = $_POST['cdbn'];

        $conn = new mysqli($_SESSION['DBHOST'], $_SESSION['DBUSER'], $_SESSION['DBPASSWORD']);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if ($createdb === 'yes') {

            // Create database
            $sql = "CREATE DATABASE " . $_SESSION['DBNAME'];
            if ($conn->query($sql) === TRUE) {
                $_SESSION['SuccessMessage'] = "Database created successfully";
                $_SESSION['StepInstall'] = 3;
                $_SESSION['DBConnected'] = 'Connected';
                header("Location: install.php?step=3");
                exit();
            } else {
                $_SESSION['ErrorMessage'] = "Error creating database: " . $conn->error;
            }
        }
        $conn->close();
    }

// Third step
// Import tables to Database
    if (isset($_POST['install'])) {
        // Name of the file
        $filename = 'sql/page.sql';

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
                $conn->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $conn->error . '<br /><br />');
                // Reset temp variable to empty
                $templine = '';
            }
        }
        $_SESSION['SuccessMessage'] = "Tables imported successfully";
        $conn->close();
        $_SESSION['StepInstall'] = 4;
        header("Location: install.php?step=4");
        exit();
    }

// Fourth step
// Define configuration for the website
    if (isset($_POST['definefile'])) {
        if (file_exists($definefiles)) {
            unlink($definefiles);
        }

        $valueCount = count($_POST["config_name"]);

        for ($i = 0; $i < $valueCount; $i++) {

            $conn->query("UPDATE `configuration` SET  `config_value` =  '{$_POST['config_value'][$i]}'   WHERE `config_name` = '{$_POST['config_name'][$i]}' ");
        }

        $_SESSION['AlertMessage'] = "The definitions are up to date.";

        $rvals = $conn->query("SELECT config_name, config_value FROM configuration");

        while ($rowt = $rvals->fetch_array()) {
            $values = $rowt['config_value'];
            $names = $rowt['config_name'];
            $vars[] = "define('" . $names . "', '" . $values . "');" . "\n";
        }

        if (!file_exists($definefiles)) {
            $def = fopen($definefiles, 'w');
            if (!$def) {
                $_SESSION['ErrorMessage'] = 'Error creating the file ' . $definefiles;
            }

            $ndef = '<?php' . "\n";
            $ndef .= implode(' ', $vars) . "\n";
            $ndef .= '?>' . "\n";
            file_put_contents($definefiles, $ndef, FILE_APPEND | LOCK_EX);

            $_SESSION['SuccessMessage'] = "The configuration definitions file has been created ";

            $_SESSION['StepInstall'] = 5;
            header("Location: install.php?step=5");
            exit();
        }

        $conn->close();
    }
// Fifth step
// // check if exists user in table 
    if (isset($_POST['verifyuser'])) {

        $newuser = new installUser();
    }
// Create user name for admin access
    if (isset($_POST['register'])) {

        $newuser = new installUser();
    }
// Sixth step
// Create file for connection
    if (isset($_POST['createfile'])) {

        fopen($file, 'w') or die('Cannot open file:  ' . $file);

        $filecontent = '';
        $filecontent .= '<?php' . "\n\n";
        $filecontent .= "include 'error_report.php';" . "\n";
        $filecontent .= "define('DBHOST', '" . $_SESSION['DBHOST'] . "');" . "\n";
        $filecontent .= "define('DBUSER', '" . $_SESSION['DBUSER'] . "');" . "\n";
        $filecontent .= "define('DBPASS', '" . $_SESSION['DBPASSWORD'] . "');" . "\n";
        $filecontent .= "define('DBNAME', '" . $_SESSION['DBNAME'] . "');" . "\n\n";
        $filecontent .= '$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);' . "\n";
        $filecontent .= "
    /* If connection fails for some reason */
    if (\$conn->connect_error) {
        die('Error, Database connection failed: (' . \$conn->connect_errno . ') ' . \$conn->connect_error);
    }" . "\n";

        $filecontent .= "\$conn->set_charset('utf8mb4');" . "\n";
        $filecontent .= "require 'function.php';" . "\n";
        $filecontent .= "require 'define.php'";

        $filecontent .= "
        if (!empty(SITE_PATH)) {
            \$base = SITE_PATH;
        } else {" . "\n";
        if (!empty($base)) {
            $filecontent .= "\$base = '" . $base . "';" . "\n";
        } else {
            $filecontent .= "\$base = 'http://'.\$_SERVER['HTTP_HOST'].'" . "\n";
        }
        $filecontent .= "}" . "\n";

        $filecontent .= "\$fname = basename(\$_SERVER['SCRIPT_FILENAME'], '.php');" . "\n";
        $filecontent .= "\$rname = \$fname . '.php';" . "\n";
        $filecontent .= "\$alertpg = \$_SERVER['REQUEST_URI'];" . "\n\n";
        $filecontent .= "    
    ?>
    ";
        file_put_contents($file, $filecontent, FILE_APPEND | LOCK_EX);

        if (file_exists($file)) {
            $_SESSION['SuccessMessage'] = "Configuration file created successfully, installation is complete. ";
            $_SESSION['AlertMessage'] = "Now you will be redirected to the home page . ";
            $finalstep = 'finalstep.php';
            $file_handle = fopen($finalstep, 'w');
            $lastcontent = '';
            $lastcontent .= '<?php' . "\n";
            $lastcontent .= 'session_start();' . "\n";
            $lastcontent .= "
        function randHash(\$len = 32) {
            return substr(md5(openssl_random_pseudo_bytes(20)), -\$len);
        }

        \$nf = randHash(32) . '.php';
        \$nu = randHash(30) . '.php';
        rename('install.php', \$nf);
        rename('installUser.php', \$nu);
        ?>
            " . "\n";
            $lastcontent .= '
            <?php 
            $rname = $_SERVER["REQUEST_URI"]; 
            ?>
        <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="refresh" content="5; url=../signin/login.php" />
        <title>PHP GrapesJS</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />  
        <link href="../css/all.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <?php
        include "../elements/alerts.php";
        ?>
        <div class="container">
            <div class="row">
                <div class="progress">
                    <div class="progress-bar" role = "progressbar" style = "width: 100%;" aria-valuenow = "100" aria-valuemin = "0" aria-valuemax = "100">95%</div>
                </div>
             </div>
         </div>
        <script src="../js/jquery.min.js" type="text/javascript"></script>
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/popper.min.js" type="text/javascript"></script>   
    </body>
</html>  
<?php
unset($_SESSION["FullSuccess"]);
unset($_SESSION["PathInstall"]);
unset($_SESSION["DBHOST"]);
unset($_SESSION["DBUSER"]);
unset($_SESSION["DBPASSWORD"]);
unset($_SESSION["DBNAME"]);
session_destroy();
?>
        ';
            file_put_contents($finalstep, $lastcontent, FILE_APPEND | LOCK_EX);
            header('Location: finalstep.php');
            exit();
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>PHP GrapesJS</title>

            <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />  
            <link href="../css/all.min.css" rel="stylesheet" type="text/css"/>
        </head>
        <body>
            <?php include '../elements/alerts.php'; ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 py-4">
                        <div id="resp"></div>

                        <div class="card mx-auto col-6">
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <h2>PHP GrapesJS</h2>
                                        <h4>You are about to install PHP GrapesJS.</h4>
                                        <p>We recommend that you follow the steps carefully and verify that everything is correctly installed.
                                        </p>
                                    </div>
                                    <hr>
                                    <?php
                                    if ($step == 1 && $_SESSION['StepInstall'] == 1) {
                                        ?>
                                        <div class="mb-3">
                                            <div class="alert alert-primary text-center" role="alert">
                                                <h3>1.- First step</h3>
                                            </div>
                                            <h4> Save your setting for DB</h4>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 5%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="host" class="col-form-label">Database Host</label> 
                                            <input id="host" name="host" type="text" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="user" class="col-form-label">Database Username</label> 
                                            <input id="user" name="user" type="text" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="col-form-label">Database Password</label> 
                                            <input id="password" name="password" type="text" class="form-control">
                                        </div> 
                                        <div class="mb-3">
                                            <label for="dbname" class="col-form-label">Database Name</label> 
                                            <input id="dbname" name="dbname" type="text" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary" name="check" id="check">Check DB connection</button> 
                                        </div>
                                        <hr>
                                        <div class="mb-3">
                                            <p>
                                                If you have already created the database, check the connection and continue with the installation of tables in the third step, otherwise create the table in the second step. 
                                            </p>
                                        </div>
                                        <?php
                                    } elseif ($step == 2 || $_SESSION['StepInstall'] == 2) {
                                        ?>
                                        <div class="alert alert-danger" role="alert">
                                            <h5>You don't have the <?php echo $_SESSION['DBNAME']; ?> database installed </h5>
                                        </div>
                                        <div class="mb-3">
                                            <div class="alert alert-primary text-center" role="alert">
                                                <h3>2.- Second step</h3>
                                            </div>
                                            <h4> Create you database.</h4>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 15%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">15%</div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <h4>This option creates the database</h4>
                                            <p>
                                                <strong> Server Host : <span class="text-primary"><?php echo $_SESSION['DBHOST']; ?></span></strong><br/>                                        
                                                <strong> Server User : <span class="text-primary"><?php echo $_SESSION['DBUSER']; ?></span></strong><br/>                                       
                                                <strong> Server Password : <span class="text-primary"> <?php echo $_SESSION['DBPASSWORD']; ?></span></strong><br/>                                        
                                                <strong> DataBase Name : <span class="text-danger"> <?php echo $_SESSION['DBNAME']; ?></span></strong>
                                            </p>                                        
                                        </div>
                                        <div class="mb-3">
                                            <p>
                                                If everything is correct, check the box and create the database.
                                            </p>
                                            <div class="form-check">

                                                <input class="form-check-input" type="checkbox" value="yes" id="cdbn" name="cdbn">
                                                <label class="form-check-label" for="flexCheckDisabled">
                                                    Confirm the creation of the database.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button name="init" type="submit" class="btn btn-success"><i class="fas fa-arrow-left"></i> Start steps </button>
                                            <button name="createdb" type="submit" class="btn btn-primary">Create database </button>
                                        </div>

                                    <?php } elseif ($step == 3 || $_SESSION['StepInstall'] == 3) {
                                        ?>
                                        <div class="alert alert-success" role="alert">
                                            <h5>Your DB is connected to <?php echo $_SESSION['DBNAME']; ?></h5>
                                        </div>
                                        <div class="mb-3">
                                            <div class="alert alert-primary text-center" role="alert">
                                                <h3>3.- Third step</h3>
                                            </div>
                                            <h4> Install tables in your database.</h4>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 55%;" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">55%</div>
                                            </div>
                                        </div>
                                        <h4>This step creates the tables in your database </h4>

                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit" name="install" id="install">Install tables</button>
                                        </div>
                                        <?php
                                    } elseif ($step == 4 || $_SESSION['StepInstall'] == 4) {
                                        $conn = new mysqli($_SESSION['DBHOST'], $_SESSION['DBUSER'], $_SESSION['DBPASSWORD'], $_SESSION['DBNAME']);
                                        // Check connection
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }
                                        ?>
                                        <div class="alert alert-success" role="alert">
                                            <h5>Create configuration for your web site</h5>
                                        </div>
                                        <div class="mb-3">
                                            <div class="alert alert-primary text-center" role="alert">
                                                <h3>4.- Fourth step</h3>
                                            </div>
                                            <h4>Define the website values .</h4>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                            </div>
                                        </div>
                                        <h4>Define values for the configuration</h4> 

                                        <?php
                                        echo "<table class='table table-striped table-sm'>";
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th><b>Config / Nombre</b></th>";
                                        echo "<th><b>Config / Valor</b></th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        $result = $conn->query("SELECT * FROM `configuration`") or trigger_error($conn->error);
                                        while ($row = $result->fetch_array()) {
                                            foreach ($row AS $key => $value) {
                                                $row[$key] = stripslashes($value);
                                            }
                                            echo "<tr>";
                                            echo "<td valign='top'><input type='text' name='config_name[]' id='config_name' value='" . $row['config_name'] . "' readonly /></td>";
                                            echo "<td valign='top'><input type='text' name='config_value[]' id='config_value' value='" . $row['config_value'] . "' /></td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";
                                        echo "<tfoot>";
                                        echo "</tfoot>";
                                        echo "</table>";
                                        ?>

                                        <div class="mb-3">
                                            <button class="btn btn-info" type="submit" name="definefile" id="definefile">Define website</button>
                                        </div>
                                        <?php
                                        $conn->close();
                                    } elseif ($step == 5 || $_SESSION['StepInstall'] == 5) {
                                        ?>
                                        <div class="alert alert-success" role="alert">
                                            <h5>Admin registration </h5>
                                        </div>
                                        <div class="mb-3">
                                            <div class="alert alert-primary text-center" role="alert">
                                                <h3>5.- Fifth step</h3>

                                            </div>
                                            <h4>Remember the data entered for the user.</h4>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 86%;" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100">86%</div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Firstname">
                                            <span class="input-group-text fas fa-id-card"></span>
                                        </div>                    
                                        <div class="input-group mb-3">
                                            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Lastname">                          
                                            <span class="input-group-text far fa-id-card"></span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                                            <span class="input-group-text fas fa-user"></span>
                                        </div>                    
                                        <div class="input-group mb-3">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email">                          
                                            <span class="input-group-text fas fa-envelope"></span>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">                                            
                                            <span class="input-group-text fas fa-lock"></span>                                                
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="password" name="password2" id="password2" class="form-control" placeholder="Retype password">
                                            <span class="input-group-text fas fa-lock"></span>
                                        </div>
                                        <div class="mb-3">
                                            <div class="icheck-primary">
                                                <input type="checkbox" id="agreeTerms" name="agreeTerms" value="agree">
                                                <label for="agreeTerms">
                                                    I agree to the <a href="#">terms</a>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="mb-3">
                                            <h5 class="text-danger">We recommend you check if there are users with administration levels. </h5>
                                            <button class = "btn btn-danger" type = "submit" name = "verifyuser" id = "install">Verify user admin</button>
                                            <button type="submit" name="register" class="btn btn-primary btn-block">Register user</button>
                                        </div>
                                        <!-- /.col -->
                                        <?php
                                    } elseif ($step == 6 || $_SESSION['StepInstall'] == 6) {
                                        ?>
                                        <div class = "alert alert-success" role = "alert">
                                            <h5>Create file configuration</h5>
                                        </div>
                                        <div class = "mb-3">
                                            <div class = "alert alert-primary text-center" role = "alert">
                                                <h3>6.- Sixth step</h3>
                                            </div>
                                            <h4>System installation is nearing completion .</h4>
                                            <div class = "progress">
                                                <div class = "progress-bar" role = "progressbar" style = "width: 95%;" aria-valuenow = "95" aria-valuemin = "0" aria-valuemax = "100">95%</div>
                                            </div>
                                        </div>

                                        <div class = "mb-3">
                                            <h4>This is the final step to start editing your website. </h4>
                                            <button class = "btn btn-info" type = "submit" name = "createfile" id = "install">Create configuration</button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>

            <script src="../js/jquery.min.js" type="text/javascript"></script>
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>
            <script src="../js/popper.min.js" type="text/javascript"></script>   

        </body>
    </html>
    <?php
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <title>PHP GrapesJS</title>

            <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />  
            <link href="../css/all.min.css" rel="stylesheet" type="text/css"/>
        </head>
        <body class="bg-dark">
            <div class="container py-4">
                <div class="row">

                    <div class="card">
                        <div class="card-body text-center">
                            <h3>PHP GrapesJS is already installed</h3>
                            <p>
                                <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/signin/login.php'; ?>" target="_self" class="btn btn-info">Go to homepage</a> 
                            </p>                           
                        </div>
                    </div>
                </div>
            </div>
            <script src="../js/jquery.min.js" type="text/javascript"></script>
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>
            <script src="../js/popper.min.js" type="text/javascript"></script>   
        </body>
    </html>
    <?php
}
?>
