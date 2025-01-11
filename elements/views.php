<?php
$connfile = URL . '/config/dbconnection.php';
if (file_exists($connfile)) {
    $page = $_SERVER['PHP_SELF'];

    require_once URL . '/config/dbconnection.php';
    require_once URL . '/controller/UserClass.php';
    require_once URL . '/controller/GetVisitor.php';

    $pages = new Routers();
    $login = new UserClass();
    $visitor = new GetVisitor();

    $_SESSION["language"] = "";
    $menu = "";
    $pages->routePages();
    if ($pages->GoPage() === true) {
        $rpx = $pages->ContentPage();
        $bid = $rpx['id'];
        $title = $rpx['title'];
        $link = $rpx['link'];
        $keyword = $rpx['keyword'];
        $classification = $rpx['classification'];
        $description = $rpx['description'];
        $cont = $rpx['type'];
        $menu = $rpx['menu'];
        $content = $rpx['content'];
        $style = $rpx['style'];
        $prnt = $rpx['parent'];
        $lng = $rpx['language'];

        $visitor->pageViews($title);

        $language = $_SESSION["language"] = $lng;
?>
        <!doctype html>
        <html lang="en">
        <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
        <?php
        require_once 'metalink.php';

        if (!empty($description)) {
        ?>
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
        <link href="<?php echo SITE_PATH; ?>assets/css/theme.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="<?php echo SITE_PATH; ?>assets/plugins/font-awesome/css/font-awesome.min.css" />
        <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo SITE_PATH; ?>assets/js/popper.min.js" type="text/javascript"></script> 
        <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
        <script>
        jQuery.htmlPrefilter = function (html) {
        return html;
        };

        </script>
        <style type="text/css">
        .dropdown:hover >.dropdown-menu{
        display: block !important;
        }

        .dropdown-submenu:hover > .dropdown-menu{
        display: block !important;
        left: 100%;
        margin-top: -37px;
        }

        .dropdown-item{
        font-size: small; /* 13px */
        }

        .dropdown-toggle::after{
        font-size: var(--font-md);
        margin-bottom: -2px;
        }

        .dropdown-menu li a.active{
        color:#fff;
        }

        .custom-toggle-arrow{
        font-size: 18px;
        margin-top: 1px;
        line-height: 12px;
        }

        .dropdown-hover-all .dropdown-menu, .dropdown-hover>.dropdown-menu.dropend {
        margin-left: -1px !important
        }

        .dropdown-menu li {
        position: relative;
        }

        .dropdown-menu .dropdown-submenu {
        display: none;
        position: absolute;
        left: 100%;
        top: -7px;
        }

        .dropdown-menu .dropdown-submenu-left {
        right: 100%;
        left: auto;
        }

        .dropdown-menu > li:hover > .dropdown-submenu {
        display: block;
        }

        .dropdown-hover:hover>.dropdown-menu {
        display: inline-block;
        }

        .dropdown-hover>.dropdown-toggle:active {
        /*Without this, clicking will make it sticky*/
        pointer-events: none;
        }
        /* ============ desktop view ============ */
        @media all and (min-width: 992px) {

        .dropdown-menu li{
        position: relative;
        }
        .dropdown-menu .dropdown-submenu{
        display: none;
        position: absolute;
        left:100%;
        top:-7px;
        }
        .dropdown-menu .dropdown-submenu-left{
        right:100%;
        left:auto;
        }

        .dropdown-menu > li:hover{
        background-color: #f1f1f1
        }
        .dropdown-menu > li:hover > .dropdown-submenu{
        display: block;
        }
        }
        /* ============ desktop view .end// ============ */

        /* ============ small devices ============ */
        @media (max-width: 991px) {

        .dropdown-menu .dropdown-submenu{
        margin-left:0.7rem;
        margin-right:0.7rem;
        margin-bottom: .5rem;
        }

        }
        /* ============ small devices .end// ============ */

        </style>
        <script src="<?php echo SITE_PATH; ?>assets/js/menu.js" type="text/javascript"></script>
        <style>
        #wrapper, .container-fluid{
        margin:0;
        padding:0;
        }

        <?php
        echo decodeContent($style) . "\n";
        ?>
        </style>
        </head>
        <body>
        <div id="wrapper"> 
        <?php
        require_once 'menu.php';
        ?>
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
        <?php
        require_once 'footer.php';
        ?>
        </body>
        </html>
        <?php
    } else {
        include_once '..' . $pages->ExistsPage() . '.php';
        ?>

        <?php
    }
} else {
    $_SESSION['PathInstall'] = "http://{$_SERVER['HTTP_HOST']}/";
    header('Location: installer/install.php');
    exit();
}
        ?>
