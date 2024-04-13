<?php
session_start();

        function randHash($len = 32) {
            return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
        }

        $nf = randHash(32) . '.php';
        $nu = randHash(30) . '.php';
        rename('install.php', $nf);
        rename('installUser.php', $nu);
        
            
           
            $rname = $_SERVER["REQUEST_URI"]; 
            ?>
        <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="refresh" content="5; url=../signin/login.php" />
        <title>PHP GrapesJS</title>

        <link href="http://localhost:130/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />  
        <link href="http://localhost:130/assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <?php
        include "C:/Users/pepiu/Documents/Github/PHP-GrapesJS/core/elements/alerts.php";
        ?>
        <div class="container">
            <div class="row">
                <div class="progress">
                    <div class="progress-bar" role = "progressbar" style = "width: 100%;" aria-valuenow = "100" aria-valuemin = "0" aria-valuemax = "100">95%</div>
                </div>
             </div>
         </div>
        <script src="http://localhost:130/assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
        <script src="http://localhost:130/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="http://localhost:130/assets/js/popper.min.js" type="text/javascript"></script>   
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
        