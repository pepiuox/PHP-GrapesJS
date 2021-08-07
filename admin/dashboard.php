<?php
session_start();
$file = '../config/dbconnection.php';
if (file_exists($file)) {
    require '../config/dbconnection.php';
} else {
    header('Location: install.php');
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
        <style>
            
            .line {
                width: 100%;
                height: 1px;
                border-bottom: 1px dashed #ddd;
                margin: 40px 0;
            }

            /* ---------------------------------------------------
                SIDEBAR STYLE
            ----------------------------------------------------- */

            .wrapper {
                display: flex;
                width: 100%;
                align-items: stretch;
            }

            #sidebar {
                min-width: 250px;
                max-width: 250px;
                background: #7386D5;
                color: #fff;
                transition: all 0.3s;
            }

            #sidebar.active {
                margin-left: -250px;
            }

            #sidebar .sidebar-header {
                padding: 20px;
                background: #6d7fcc;
            }

            #sidebar ul.components {
                padding: 20px 0;
                border-bottom: 1px solid #47748b;
            }

            #sidebar ul p {
                color: #fff;
                padding: 10px;
            }

            #sidebar ul li a {
                padding: 10px;
                font-size: 1.1em;
                display: block;
            }

            #sidebar ul li a:hover {
                color: #7386D5;
                background: #fff;
            }

            #sidebar ul li.active>a,
            a[aria-expanded="true"] {
                color: #fff;
                background: #6d7fcc;
            }

            a[data-toggle="collapse"] {
                position: relative;
            }

            .dropdown-toggle::after {
                display: block;
                position: absolute;
                top: 50%;
                right: 20px;
                transform: translateY(-50%);
            }

            ul ul a {
                font-size: 0.9em !important;
                padding-left: 30px !important;
                background: #6d7fcc;
            }

            ul.CTAs {
                padding: 20px;
            }

            ul.CTAs a {
                text-align: center;
                font-size: 0.9em !important;
                display: block;
                border-radius: 5px;
                margin-bottom: 5px;
            }

            a.download {
                background: #fff;
                color: #7386D5;
            }

            a.article,
            a.article:hover {
                background: #6d7fcc !important;
                color: #fff !important;
            }

            /* ---------------------------------------------------
                CONTENT STYLE
            ----------------------------------------------------- */

            #content {
                width: 100%;
                padding: 20px;
                min-height: 100vh;
                transition: all 0.3s;
            }

            /* ---------------------------------------------------
                MEDIAQUERIES
            ----------------------------------------------------- */

            @media (max-width: 768px) {
                #sidebar {
                    margin-left: -250px;
                }
                #sidebar.active {
                    margin-left: 0;
                }
                #sidebarCollapse span {
                    display: none;
                }
            }
        </style>

    </head>
    <body>
        <div class="wrapper">
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>BBBOOTSTRAP</h3>
                    <hr>
                </div>
                <ul class="list-unstyled components">
                    <p>MENUS</p>
                    <li> <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dashboard</a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li> <a href="#">Dashboard1</a> </li>
                            <li> <a href="#">Dashboard2</a> </li>
                            <li> <a href="#">Dashboard3</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#">Users</a> </li>
                    <li> <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Subscribers</a>
                        <ul class="collapse list-unstyled" id="pageSubmenu">
                            <li> <a href="#">Active</a> </li>
                            <li> <a href="#">Idle</a> </li>
                            <li> <a href="#">Non Active</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#">Timeline</a> </li>
                    <li> <a href="#">Live Chat</a> </li>
                    <li> <a href="#">Likes</a> </li>
                    <li> <a href="#">Comments</a> </li>
                </ul>
                <ul class="list-unstyled CTAs">
                    <li> <a href="#" class="download">Subscribe</a> </li>
                </ul>
            </nav>
            <div class="content">
                <nav class="navbar navbar-expand-lg navbar-light bg-light"> <button type="button" id="sidebarCollapse" class="btn btn-info"> <i class="fa fa-align-justify"></i> </button> <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item active"> <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item"> <a class="nav-link" href="#">Features</a> </li>
                            <li class="nav-item"> <a class="nav-link" href="#">Pricing</a> </li>
                            <li class="nav-item"> <a class="nav-link" href="#">Contact</a> </li>
                        </ul>
                    </div>
                </nav>
                <div class="content-wrapper">
                    <h2>Collapsible Sidebar Using Bootstrap 4</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Aliquam nulla facilisi cras fermentum odio eu. Eget nulla facilisi etiam dignissim diam quis enim. Et netus et malesuada fames ac turpis egestas integer eget. Tortor at risus viverra adipiscing at in tellus. Cras adipiscing enim eu turpis. Malesuada nunc vel risus commodo viverra. Enim nulla aliquet porttitor lacus luctus accumsan tortor posuere. Ac placerat vestibulum lectus mauris ultrices eros. In arcu cursus euismod quis viverra nibh cras. Non quam lacus suspendisse faucibus interdum posuere lorem ipsum. In fermentum posuere urna nec tincidunt praesent semper. Ultricies mi quis hendrerit dolor. Sit amet luctus venenatis lectus magna fringilla urna porttitor. Praesent tristique magna sit amet purus gravida quis. Enim nunc faucibus a pellentesque sit amet porttitor.
                        Amet justo donec enim diam vulputate. Aliquet eget sit amet tellus cras. Tincidunt arcu non sodales neque. Semper auctor neque vitae tempus quam. In massa tempor nec feugiat nisl pretium fusce id. Fames ac turpis egestas integer eget aliquet. Proin sagittis nisl rhoncus mattis rhoncus urna neque viverra. Ut sem nulla pharetra diam. Vitae tempus quam pellentesque nec nam aliquam sem. Eget duis at tellus at urna condimentum mattis. Tellus orci ac auctor augue mauris. Eget sit amet tellus cras adipiscing enim eu turpis. Nam aliquam sem et tortor. Ac tincidunt vitae semper quis lectus. Mollis nunc sed id semper risus in hendrerit. Tincidunt id aliquet risus feugiat. Massa eget egestas purus viverra accumsan in nisl.
                        Quis enim lobortis scelerisque fermentum. Ut diam quam nulla porttitor massa. Nunc sed id semper risus in. Mattis pellentesque id nibh tortor. Ac orci phasellus egestas tellus rutrum. Congue nisi vitae suscipit tellus mauris a diam. Facilisis volutpat est velit egestas. Quam viverra orci sagittis eu volutpat odio. Etiam dignissim diam quis enim lobortis. Sollicitudin nibh sit amet commodo nulla facilisi nullam vehicula. Sit amet luctus venenatis lectus. Mi eget mauris pharetra et ultrices neque. Sit amet cursus sit amet dictum sit amet. Eget lorem dolor sed viverra. Neque egestas congue quisque egestas diam. Vestibulum lectus mauris ultrices eros in cursus turpis. Et leo duis ut diam quam nulla. Egestas sed tempus urna et pharetra. Aliquam sem et tortor consequat id. Sollicitudin tempor id eu nisl nunc mi.
                        .</p>
                    <div class="line"></div>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="<?php echo $base; ?>js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo $base; ?>js/bootstrap.min.js" type="text/javascript"></script>        
        <script src="<?php echo $base; ?>js/popper.min.js" type="text/javascript"></script>

        <!-- Core theme JS-->
        <script>
          $(document).ready(function(){
    $("#sidebarCollapse").on('click', function(){
        $("#sidebar").toggleClass('active');
    });
    $("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});

});
        </script>
    </body>
</html>

