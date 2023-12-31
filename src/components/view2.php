<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
$connfile = "config/dbconnection.php";
if (file_exists($connfile)) {
    $page = $_SERVER["PHP_SELF"];

    require_once "config/dbconnection.php";
    require_once "Autoload.php";
    $pages = new Routers();
    $login = new UsersClass();
    $visitor = new GetVisitor();
    $protocol =
        (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ||
        $_SERVER["SERVER_PORT"] == 443
            ? "https://"
            : "http://";
    $_SESSION["language"] = "";
    $initweb = $protocol . $_SERVER["HTTP_HOST"] . "/";
    $pg404 = $initweb . "404.php";
    $url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $escaped_url = htmlspecialchars($url, ENT_QUOTES, "UTF-8");
    $url_path = parse_url($escaped_url, PHP_URL_PATH);
    $basename = pathinfo($url_path, PATHINFO_BASENAME);
    $active = 1;
    $startpage = 1;
    $nm = "";

    if ($initweb === $url) {
        $spg = $conn->prepare(
            "SELECT * FROM page WHERE startpage = ? AND active = ? "
        );
        $spg->bind_param("ii", $startpage, $active);
        $spg->execute();
        $rs = $spg->get_result();
        $spg->close();
        $nm = $rs->num_rows;
        $rpx = $rs->fetch_assoc();
    } elseif (isset($_GET["page"]) && !empty($_GET["page"])) {
        $id = (int) $_GET["page"];
        $spg = $conn->prepare(
            "SELECT * FROM page WHERE id = ? AND active = ? "
        );
        $spg->bind_param("ii", $active, $id);
        $spg->execute();
        $rs = $spg->get_result();
        $spg->close();
        $nm = $rs->num_rows;

        if ($nm > 0) {
            $rpx = $rs->fetch_assoc();
            $namelink = $initweb . $rpx["link"];
        } else {
            header("Location: $namelink");
            exit;
        }
    } elseif (isset($basename) && !empty($basename)) {
        $spg = $conn->prepare(
            "SELECT * FROM page WHERE link = ? AND active = ? "
        );
        $spg->bind_param("si", $basename, $active);
        $spg->execute();
        $rs = $spg->get_result();
        $spg->close();
        $nm = $rs->num_rows;

        if ($nm > 0) {
            $rpx = $rs->fetch_assoc();
        } else {
            header("Location: $pg404");
            exit;
        }
    }

    if ($nm > 0) {

        $bid = $rpx["id"];
        $title = $rpx["title"];
        $plink = $rpx["link"];
        $keyword = $rpx["keyword"];
        $classification = $rpx["classification"];
        $description = $rpx["description"];
        $cont = $rpx["type"];
        $menu = $rpx["menu"];
        $content = $rpx["content"];
        $style = $rpx["style"];
        $lng = $rpx["language"];

        $visitor->pageViews($title);

        $language = $_SESSION["language"] = $lng;

        require_once "top.php";
        ?>

        </head>
        <body>
            <div id="wrapper"> 
                <?php require_once "menu.php"; ?>
                <div class='container-fluid' id="content-page">
                    <?php
                    $string = decodeContent($content);
                    if (!empty($content)) {
                        $string = str_replace("<body>", "", $string);
                        $string = str_replace("</body>", "", $string);
                    }
                    echo $string . "\n";
                    ?>
                </div>
            </div>  
            <?php require_once "footer.php"; ?>
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
                <?php require_once "metalink.php"; ?>     
                <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>" />           
                <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />           
                <meta name="classification" content="<?php echo SITE_CLASSIFICATION; ?>" />

                <title><?php echo SITE_NAME; ?></title>
                <link href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" type="text/css" href="<?php echo SITE_PATH; ?>assets/plugins/fontawesome/css/fontawesome.min.css" />
                <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                <script src="<?php echo SITE_PATH; ?>assets/plugins/popper/popper.min.js" type="text/javascript"></script> 
                <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
                <script>
                    jQuery.htmlPrefilter = function (html) {
                        return html;
                    };

                </script>
            </head>
            <body>
                <div class="wrapper"> 
                    <?php require_once "navbar.php"; ?>
                    <div class="container">
                        <div class="row">
                            <div  class="col-12 text-center">
                                <?php echo $initweb . " - " . SITE_PATH; ?>
                                <h3>This page not exits,  Start creating your first page of content </h3>
                                <a href="signin/login.php">Login</a> - <a href="admin/dashboard.php">Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php require_once "footer.php"; ?>
            </body>
        </html>
        <?php
    }
} else {
    $_SESSION["PathInstall"] = "http://{$_SERVER["HTTP_HOST"]}/";
    header("Location: installer/install.php");
    exit;
}
?>
