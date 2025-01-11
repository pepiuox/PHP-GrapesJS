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
        require_once 'top.php';
?>
        
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
        $pfile = URL . $pages->ExistsPage() . '.php';
        if(file_exists($pfile)){
             include_once '..' . $pages->ExistsPage() . '.php';
        }else{
            include_once '404.php';
        }
        ?>

        <?php
    }
} else {
    $_SESSION['PathInstall'] = "http://{$_SERVER['HTTP_HOST']}/";
    header('Location: installer/install.php');
    exit();
}
        ?>
