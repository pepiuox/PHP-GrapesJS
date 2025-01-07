<?php
session_start();

$folder = basename(dirname(__DIR__));
$local = 'localhost';
$host = $_SERVER['HTTP_HOST'];
if (str_contains($host, $local)) {
    $sitenm = str_replace(":", "_", $host);
} else {
    $sitenm = str_replace(".", "_", $host);
}

$laststep = 'finalstep.php';
if (file_exists($laststep)) {
    unlink($laststep);
}
if (isset($_SESSION['PathInstall'])) {
//$_SESSION['PathInstall'] = "http://{$_SERVER['HTTP_HOST']}/";
    $siteinstall = $_SESSION['PathInstall'];
} else {
    $siteinstall = "http://" . $host . '/' . $folder . '/';
}

$rname = $_SERVER["REQUEST_URI"];
$alertpg = $rname;
// $dom = '../config/domain.php';
$file = '../config/dbconnection.php';
$serverfile = '../config/server.php';
$definefiles = '../config/define.php';
if (isset($_SESSION['DBConnected']) && !empty($_SESSION['DBConnected'])) {
    if ($_SESSION['DBConnected'] === 'Connected') {
        $conn = new mysqli($_SESSION['DBHOST'], $_SESSION['DBUSER'], $_SESSION['DBPASSWORD'], $_SESSION['DBNAME']);
// Check connection
        require_once 'installUser.php';
    }
}
if (!file_exists($file)) {

    if (isset($_GET['step']) && !empty($_GET['step'])) {
        $step = $_GET['step'];

        if ($step === 1) {
            $_SESSION['DBConnected'] = '';
        }
    } else {
        $_SESSION['StepInstall'] = 1;
        header("Location: install.php?step=1");
    }
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>PHP GrapesJS</title>

        <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/plugins/adminlte/css/adminlte.min.css" rel="stylesheet" type="text/css" />
        <script src="../assets/plugins/adminlte/js/adminlte.min.js" type="text/javascript"></script>
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
        <p>We recommend that you follow the steps carefully and verify that everything is correctly installed.</p>
        </div>
        <hr>
    <?php
    if ($step == 1 && $_SESSION['StepInstall'] == 1) {
        include 'firststep.php';
    } elseif ($step == 2 || $_SESSION['StepInstall'] == 2) {
       include 'secondstep.php';
       } elseif ($step == 3 || $_SESSION['StepInstall'] == 3) {
    include 'thirdstep.php';
    } elseif ($step == 4 || $_SESSION['StepInstall'] == 4) {
        $conn = new mysqli($_SESSION['DBHOST'], $_SESSION['DBUSER'], $_SESSION['DBPASSWORD'], $_SESSION['DBNAME']);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $result = $conn->query("SELECT * FROM `site_configuration` WHERE `ID_Site` = '1'") or trigger_error($conn->error);
        $confs = $result->fetch_assoc();
        include 'fourthstep.php';
        $conn->close();
    } elseif ($step == 5 || $_SESSION['StepInstall'] == 5) {
        include 'fifthstep.php';
    } elseif ($step == 6 || $_SESSION['StepInstall'] == 6) {
        include 'sithstep.php';
    } elseif ($step == 7 || $_SESSION['StepInstall'] == 7) {
        include 'seventhstep.php';
    }
        ?>
        </form>
        </div>
        </div>
        </div>
        </div>
        </div>

        <script src="../assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/js/popper.min.js" type="text/javascript"></script>

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

        <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
        </head>

        <body class="bg-dark">
        <div class="container py-4">
        <div class="row">

        <div class="card">
        <div class="card-body text-center">
        <h3>PHP GrapesJS is already installed</h3>
        <p>
        <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/signin/login.php'; ?>" target="_self"
        class="btn btn-info">Go to homepage</a>
        </p>
        </div>
        </div>
        </div>
        </div>
        <script src="../assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/js/popper.min.js" type="text/javascript"></script>
        </body>

        </html>
    <?php
}
    ?>
