<?php
$_SESSION['language'] = '';
$initweb = 'http://' . $_SERVER['HTTP_HOST'] . '/';
$url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
$url_path = parse_url($escaped_url, PHP_URL_PATH);
$basename = pathinfo($url_path, PATHINFO_BASENAME);
$active = 1;
$startpage = 1;
$nm = '';

if ($initweb === $url) {
    $spg = $conn->prepare("SELECT * FROM page WHERE startpage = ? AND active = ? ");
    $spg->bind_param("ii", $startpage, $active);
    $spg->execute();
    $rs = $spg->get_result();
    $nm = $rs->num_rows;
    $rpx = $rs->fetch_assoc();
} elseif (isset($_GET['page']) && !empty($_GET['page'])) {
    $id = (int) $_GET['page'];
    $spg = $conn->prepare("SELECT * FROM page WHERE id = ? AND active = ? ");
    $spg->bind_param("ii", $active, $id);
    $spg->execute();
    $rs = $spg->get_result();
    $rpx = $rs->fetch_assoc();
    $namelink = $base . $rpx['link'];
    header("Location: $namelink");
    exit();
} elseif (isset($basename) && !empty($basename)) {
    $spg = $conn->prepare("SELECT * FROM page WHERE link = ? AND active = ? ");
    $spg->bind_param("si", $basename, $active);
    $spg->execute();
    $rs = $spg->get_result();
    $nm = $rs->num_rows;

    if ($nm > 0) {
        $rpx = $rs->fetch_assoc();
    } else {
        $spg = $conn->prepare("SELECT * FROM page WHERE startpage = ? AND active = ? ");
        $spg->bind_param("ii", $startpage, $active);
        $spg->execute();
        $rs = $spg->get_result();
        $nm = $rs->num_rows;
        $rpx = $rs->fetch_assoc();
    }
}

if ($nm > 0) {
    $bid = $rpx['id'];
    $title = $rpx['title'];
    $plink = $rpx['link'];
    $keyword = $rpx['keyword'];
    $classification = $rpx['classification'];
    $description = $rpx['description'];
    $cont = $rpx['type'];
    $menu = $rpx['menu'];
    $content = $rpx['content'];
    $style = $rpx['style'];
    $prnt = $rpx['parent'];
    $lng = $rpx['language'];

    $language = $_SESSION["language"] = $lng;
    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
            <?php if (!empty($description)) { ?>
                <meta name="description" content="<?php echo $description; ?>" />
            <?php } else { ?>
                <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>" />
                <?php
            }
            if (!empty($keyword)) {
                ?>
                <meta name="keywords" content="<?php echo $keyword; ?>" />
            <?php } else { ?>
                <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />
                <?php
            }
            if (!empty($classification)) {
                ?>
                <meta name="classification" content="<?php echo $classification; ?>" />
            <?php } else { ?>
                <meta name="classification" content="<?php echo SITE_CLASSIFICATION; ?>" />
            <?php } ?>
            <title><?php echo $title; ?></title>
            <link href="<?php echo $base; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/plugins/font-awesome/css/font-awesome.min.css" />
            <style>
    <?php
    echo decodeContent($style);
    ?>
            </style>
        </head>
        <body>
            <div class="wrapper"> 
                <?php
                require 'menu.php';
                ?>
                <div class='container-fluid'>
                    <?php
                    echo decodeContent($content) . "\n";
                    ?>
                </div>
            </div>
            <script src="<?php echo $base; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>assets/js/popper.min.js" type="text/javascript"></script> 
        </body>
    </html>
    <?php
} else {
    ?>
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>        
            <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>" />           
            <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />           
            <meta name="classification" content="<?php echo SITE_CLASSIFICATION; ?>" />

            <title><?php echo SITE_NAME; ?></title>
            <link href="<?php echo $base; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/font-awesome/css/font-awesome.min.css" />

        </head>
        <body>
            <div class="wrapper"> 
                <?php
                require 'navbar.php';
                ?>
                <div class='container'>
                    <div class="row">
                        <div  class="col-12 text-center">
                            <?php echo $initweb . ' - ' . $base; ?>
                            <h3> Start creating your first page of content </h3>
                            <a href="signin/login.php">Login</a> - <a href="admin/dashboard.php">Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?php echo $base; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?php echo $base; ?>assets/js/popper.min.js" type="text/javascript"></script> 
        </body>
    </html>
    <?php
}
?>

