<?php
session_start();
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
    $check = new CheckValidUser();
    $level = new AccessLevel();
} else {
    header('Location: ../installer/install.php');
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $spg = $conn->prepare("SELECT * FROM page WHERE id=?");
    $spg->bind_param("i", $id);
    $spg->execute();
    $rs = $spg->get_result();
    $nm = $rs->num_rows;
    if ($nm > 0) {
        $row = $rs->fetch_assoc();
        $bid = $row['id'];
        $title = $row['title'];
        $plink = $row['link'];
        $keyword = $row['keyword'];
        $classification = $row['classification'];
        $description = $row['description'];
        $cont = $row['type'];
        $menu = $row['menu'];
        $content = $row['content'];
        $style = $row['style'];
        $prnt = $row['parent'];
        $lng = $row['language'];
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
                <link href="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link rel="stylesheet" type="text/css" href="<?php echo SITE_PATH; ?>assets/css/font-awesome.min.css" />
                <script>
               jQuery.htmlPrefilter = function( html ) {
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
                        left:100%; top:-7px;
                }
                .dropdown-menu .dropdown-submenu-left{ 
                        right:100%; left:auto;
                }

                .dropdown-menu > li:hover{ background-color: #f1f1f1 }
                .dropdown-menu > li:hover > .dropdown-submenu{
                        display: block;
                }
        }	
        /* ============ desktop view .end// ============ */

        /* ============ small devices ============ */
        @media (max-width: 991px) {

        .dropdown-menu .dropdown-submenu{
                        margin-left:0.7rem; margin-right:0.7rem; margin-bottom: .5rem;
        }

        }	
        /* ============ small devices .end// ============ */

        </style>
        <script src="http://localhost:130/assets/js/menu.js" type="text/javascript"></script>
                <style>
        <?php
        echo decodeContent($style) . "\n";
        ?>
                </style>
            </head>
            <body>
                <?php
                require '../elements/menu.php';
                ?>
                <div class='container'>
                    <?php
                    echo decodeContent($content) . "\n";
                    ?>
                </div>
                <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
                <script src="<?php echo SITE_PATH; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                <script src="<?php echo SITE_PATH; ?>assets/js/popper.min.js" type="text/javascript"></script> 
            </body>
        </html>
        <?php
    } else {
        header('Location: dashboard.php?cms=pagelist');
        exit();
    }
} else {
    header('Location: dashboard.php?cms=pagelist');
    exit();
}
?>
