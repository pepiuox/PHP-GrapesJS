<?php
// Sixth step
// Create file for connection
    if (isset($_POST['createfile'])) {
// Create file for server connection

        $svcontent = '';
        $svcontent .= '<?php' . "\n\n";
        $svcontent .= "return [
    'default-connection' => 'cms',
    'connections' => [
        'cms' => [
            'server' => '" . $_SESSION['DBHOST'] . "',
            'database' => '" . $_SESSION['DBNAME'] . "',
            'username' => '" . $_SESSION['DBUSER'] . "',
            'password' => '" . $_SESSION['DBPASSWORD'] . "',
            'charset' => 'utf8',
            'port' => '3306',
        ],// use different connection for another DB in this app, and change values.
         'ecommerce' => [
            'server' => 'localhost',
            'database' => 'ecommerce',
            'username' => 'user',
            'password' => 'password',
            'charset' => 'utf8',
            'port' => '3306',
        ],
    ],
];
?>";

file_put_contents($serverfile, $svcontent, FILE_APPEND | LOCK_EX);

$filecontent = '';
$filecontent .= '<?php' . "\n\n";
        $filecontent .= "include 'error_report.php';
include 'Database.php';
\$link = new Database();
\$conn = \$link->MysqliConnection();
require_once 'Routers.php';
require_once 'function.php';
include_once 'define.php';" . "\n\n";

        $filecontent .= '$protocol =
        (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
        $_SERVER["SERVER_PORT"] == 443
            ? "https://"
            : "http://";' . "\n\n";

        $filecontent .= "
        if (!empty(SITE_PATH)) {
            \$siteinstall = SITE_PATH;
        } else {" . "\n";
        if (!empty($siteinstall)) {
            $filecontent .= "\$base = \$protocol.\$_SERVER['HTTP_HOST'];" . "\n";
        } else {
            $filecontent .= "\$base = \$protocol.\$_SERVER['HTTP_HOST'].'" . $folder . "\n";
        }
        $filecontent .= "}" . "\n";
        $filecontent .= "\$fname = basename(\$_SERVER['SCRIPT_FILENAME'], '.php');" . "\n";
        $filecontent .= "\$rname = \$fname.'.php';" . "\n";
        $filecontent .= "\$alertpg = \$_SERVER['REQUEST_URI'];" . "\n\n";
        $filecontent .= "?>";
file_put_contents($file, $filecontent, FILE_APPEND | LOCK_EX);

if (file_exists($file)) {
$_SESSION['SuccessMessage'] = "Configuration file created successfully, installation is complete. ";
$_SESSION['AlertMessage'] = "Now you will be redirected to the home page . ";
$finalstep = 'finalstep.php';

$lastcontent = '';
$lastcontent .= '<?php' . "\n\n";
            $lastcontent .= 'session_start();' . "\n";
            $lastcontent .= "
            \$alertpg ='../signin/login.php';" . "\n";
            $lastcontent .= "
        function randHash(\$len = 32) {
            return substr(md5(openssl_random_pseudo_bytes(20)), -\$len);
        }

        \$nf = randHash(32) . '.php';
        \$nu = randHash(30) . '.php';
        rename('install.php', \$nf);
        rename('installUser.php', \$nu);
        
            " . "\n";
            $lastcontent .= '           
            $rname = $_SERVER["REQUEST_URI"]; 
            ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="refresh" content="5; url=../signin/login.php" />
    <title>PHP GrapesJS</title>

    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php
        include "../elements/alerts.php";
        ?>
    <div class="container">
        <div class="row">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0"
                    aria-valuemax="100">100%</div>
            </div>
        </div>
    </div>
    <script src="../assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../assets/js/popper.min.js" type="text/javascript"></script>
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
<div class="alert alert-success" role="alert">
    <h5>Create file configuration</h5>
</div>
<div class="mb-3">
    <div class="alert alert-primary text-center" role="alert">
        <h3>6.- Sixth step</h3>
    </div>
    <h4>System installation is nearing completion .</h4>
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 95%;" aria-valuenow="95" aria-valuemin="0"
            aria-valuemax="100">95%</div>
    </div>
</div>

<div class="mb-3">
    <h4>This is the final step to start editing your website. </h4>
    <button class="btn btn-info" type="submit" name="createfile" id="createfile">Create
        configuration</button>
</div>