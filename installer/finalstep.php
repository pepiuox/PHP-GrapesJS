<?php
session_start();

        function randHash($len = 32) {
            return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
        }

        $nf = randHash(32) . '.php';
        $nu = randHash(30) . '.php';
        rename('install.php', $nf);
        rename('installUser.php', $nu);
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
unset($_SESSION["PathInstall"]);
unset($_SESSION["DBHOST"]);
unset($_SESSION["DBUSER"]);
unset($_SESSION["DBPASSWORD"]);
unset($_SESSION["DBNAME"]);
session_destroy();
?>
        