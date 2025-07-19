<?php
session_start();
$alertpg = 'http://localhost:130/';
                
        function randHash($len=32) {
            return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
        }

        $nf = randHash(32) . '.php';
        $nu = randHash(30) . '.php';
        rename('install.php', $nf);
        rename('installUser.php', $nu);
                
$rname=$_SERVER["REQUEST_URI"]; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="refresh" content="5; url=http://localhost:130/signin/login" />
    <title>PHP GrapesJS</title>

    <link href="http://localhost:130/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost:130/assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <script src="http://localhost:130/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://localhost:130/assets/js/popper.min.js" type="text/javascript"></script>
    <script src="http://localhost:130/assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
</head>

<body>
    <div class="wrapper">
        <?php
        include "C:/Users/PePiuoX/Documents/Github/PHP-GrapesJS/core/elements/alerts.php";
        ?>
        <div class="container">
            <div class="row">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100"
                        aria-valuemin="0" aria-valuemax="100">100%</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
unset($_SESSION["FullSuccess"]);
unset($_SESSION["PathInstall"]);
unset($_SESSION["DBHOST"]);
unset($_SESSION["DBUSER"]);
unset($_SESSION["DBPASSWORD"]);
unset($_SESSION["DBNAME"]);
unset($_SESSION["SECURE_TOKEN"]);
unset($_SESSION["SECURE_HASH"]);
session_destroy();
?>
