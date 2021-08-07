<?php
session_start();
$file = '../config/dbconnection.php';
if (file_exists($file)) {
    require 'config/dbconnection.php';
} else {
    header('Location: install.php');
}
$hta = '../.htaccess';

if (isset($_POST['submit'])) {
    $ppath = $_POST['ppath'];
    $uripath = $_POST['uripath'];
    if ($ppath === 'yes') {
        if (!empty($uripath)) {
            $upath = "RewriteCond %{REQUEST_URI} !/" . $uripath . "/.* [NC]" . "\n";
        } else {
            $upath = "";
            $alert = '<div class="alert alert-danger" role="alert">
            The field is empty, it is necessary to put a folder name
            </div>';
        }
    }
    $filecontent = "<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    RewriteCond %{REQUEST_FILENAME} !-f    
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME}/index.html !-f
    RewriteCond %{REQUEST_FILENAME}/index.php !-f" . "\n";
    $filecontent .= $upath . "\n";
    $filecontent .= "RewriteRule (.*?)index\.php/*(.*) /$1$2 [R=301,NE,L]
          
</IfModule>";
    file_put_contents($hta, $filecontent);
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <title>Content Editor</title>
        <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>css/font-awesome.min.css" />
    </head>
    <body>
        <!-- start menu -->                     
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <div class="menu-logo">
                    <div class="navbar-brand">
                        <a class="navbar-logo" href="<?php echo $base; ?>">
                            <?php echo SITE_NAME; ?> 
                        </a>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span> 
                </button>
                <div id="navbarNavDropdown" class="navbar-collapse collapse
                     justify-content-end">
                    <ul class="navbar-nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="btn btn-success" href="list.php"><i class="fa fa-list" aria-hidden="true"></i> View Page List</a>
                        </li>                        
                        <li class="nav-item">
                            <a class="btn btn-secondary" href="settings.php"><i class="fa fa-gear" aria-hidden="true"></i> Edit Settings</a> 
                        </li>
                    </ul>   
                </div>
            </div>
        </nav>
        <!<!-- end menu -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 py-4">
                    <div id="resp"></div>

                    <form method="post">
                        <div class="form-group row">
                            <div class="col-8">
                                <h3>Creates your beauty url with .htaccess</h3>
                            </div>
                        </div>
                        <h5>This option creates the database with the tables</h5>
                        <div class="form-group row">
                            <label for="ppath" class="col-4 col-form-label">You want to create a path for your folder on your system</label> 
                            <div class="col-8">
                                <input id="ppath" name="ppath" type="checkbox" value="yes" class="form-control mx-2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="uripath" class="col-4 col-form-label">Uri Path</label> 
                            <div class="col-8">
                                <input id="uripath" name="uripath" type="text" class="form-control">
                            </div>
                        </div>
                        <?php
                        if (isset($alert)) {
                            echo $alert;
                        }
                        ?>
                        <div class="form-group row">
                            <div class="offset-4 col-8">
                                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="<?php echo $base; ?>js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo $base; ?>js/bootstrap.min.js" type="text/javascript"></script>        
        <script src="<?php echo $base; ?>js/popper.min.js" type="text/javascript"></script>
    </body>
</html>