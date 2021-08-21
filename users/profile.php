<?php
if (!isset($_SESSION)) {
    session_start();
}
require '../config/dbconnection.php';
require 'Autoload.php';

$login = new UserClass();
$check = new CheckValidUser();
$level = new AccessLevel();
if (isset($_GET['user']) && !empty($_GET['user'])) {
    $user = $_GET['user'];
} else {
    $user = '';
}
?>
<?php include '../elements/header.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
    <?php
    if ($login->isLoggedIn() === true) {
        ?>
        <div class="wrapper">
            <!-- Navbar -->
            <?php include '../elements/navbar.php'; ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="index.php" class="brand-link">
                    <?php
                    $logo = IMG_PAGE;
                    if (file_exists($logo)) {
                        ?>
                        <img src="<?php echo $logo; ?>" alt="<?php echo SITE_NAME; ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
                        <span class="brand-text font-weight-light"><?php echo SITE_NAME; ?></span>
                        <?php
                    } else {
                        echo SITE_NAME;
                    }
                    ?>
                </a>
                <!-- Sidebar -->
                <div class="sidebar">                   
                    <!-- Sidebar Menu -->
                    <?php include '../elements/sidenav.php'; ?>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <?php
                                if ($user == 'pinfo') {
                                    $vpages = 'Personal user information';
                                } elseif ($user == 'sphra') {
                                    $vpages = 'Security phrase';
                                } elseif ($user == 'chpass') {
                                    $vpages = 'Change of password';
                                } elseif ($user == 'chpin') {
                                    $vpages = 'Security PIN change ';
                                } else {
                                    $vpages = 'Profile';
                                }
                                ?>
                                <h1 class="m-0 text-dark"><?php echo $vpages; ?></h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="<?php echo $base; ?>">Home</a></li>
                                    <li class="breadcrumb-item active"><?php echo $vpages; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php include '../elements/alerts.php'; ?>
                    <?php
                    if ($user == 'pinfo') {
                        include 'personalInfo.php';
                    } elseif ($user == 'chpass') {
                        include 'changePassword.php';
                    } elseif ($user == 'chpin') {
                        include 'changePIN.php';
                    } elseif ($user == 'sphra') {
                        include 'securePhrase.php';
                    } else {
                        ?>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 mr-auto">
                                    <div class="card card-primary card-outline">
                                        <div class="card-body">
                                            <a name="about">
                                                <h2>Connected</h2>
                                            </a>
                                            <div class="descText text-center">
                                                <h4>Wellcome <?php echo USERS_FULLNAMES; ?></h4>
                                                <hr class="colorgraph">
                                                <p>
                                                    You are already connected.
                                                </p>                                             
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3">

                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle"
                                                     src="<?php echo $base; ?>upload/<?php echo USERS_AVATARS; ?>"
                                                     alt="User profile picture">
                                            </div>

                                            <h3 class="profile-username text-center"><?php echo USERS_NAMES; ?></h3>

                                            <p class="text-muted text-center">Software Engineer</p>

                                            <ul class="list-group list-group-unbordered mb-3">
                                                <li class="list-group-item">
                                                    <b>Followers</b> <a class="float-right">1,322</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Following</b> <a class="float-right">543</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Friends</b> <a class="float-right">13,287</a>
                                                </li>
                                            </ul>

                                            <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                    <!-- About Me Box -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">About Me</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <strong><i class="fas fa-book mr-1"></i> Education</strong>

                                            <p class="text-muted">
                                                B.S. in Computer Science from the University of Tennessee at Knoxville
                                            </p>

                                            <hr>

                                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                            <p class="text-muted">Malibu, California</p>

                                            <hr>

                                            <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                                            <p class="text-muted">
                                                <span class="tag tag-danger">UI Design</span>
                                                <span class="tag tag-success">Coding</span>
                                                <span class="tag tag-info">Javascript</span>
                                                <span class="tag tag-warning">PHP</span>
                                                <span class="tag tag-primary">Node.js</span>
                                            </p>

                                            <hr>

                                            <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                                            </ul>
                                        </div><!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="active tab-pane" id="activity">
                                                    <!-- Post -->
                                                    <div class="post">
                                                        <div class="user-block">
                                                            <img class="img-circle img-bordered-sm" src="<?php echo $base; ?>dist/img/user1-128x128.jpg" alt="user image">
                                                            <span class="username">
                                                                <a href="#">Jonathan Burke Jr.</a>
                                                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                            </span>
                                                            <span class="description">Shared publicly - 7:30 PM today</span>
                                                        </div>
                                                        <!-- /.user-block -->
                                                        <p>
                                                            Lorem ipsum represents a long-held tradition for designers,
                                                            typographers and the like. Some people hate it and argue for
                                                            its demise, but others ignore the hate as they create awesome
                                                            tools to help create filler text for everyone from bacon lovers
                                                            to Charlie Sheen fans.
                                                        </p>

                                                        <p>
                                                            <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                                            <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                                            <span class="float-right">
                                                                <a href="#" class="link-black text-sm">
                                                                    <i class="far fa-comments mr-1"></i> Comments (5)
                                                                </a>
                                                            </span>
                                                        </p>

                                                        <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                                    </div>
                                                    <!-- /.post -->

                                                    <!-- Post -->
                                                    <div class="post clearfix">
                                                        <div class="user-block">
                                                            <img class="img-circle img-bordered-sm" src="<?php echo $base; ?>dist/img/user7-128x128.jpg" alt="User Image">
                                                            <span class="username">
                                                                <a href="#">Sarah Ross</a>
                                                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                            </span>
                                                            <span class="description">Sent you a message - 3 days ago</span>
                                                        </div>
                                                        <!-- /.user-block -->
                                                        <p>
                                                            Lorem ipsum represents a long-held tradition for designers,
                                                            typographers and the like. Some people hate it and argue for
                                                            its demise, but others ignore the hate as they create awesome
                                                            tools to help create filler text for everyone from bacon lovers
                                                            to Charlie Sheen fans.
                                                        </p>

                                                        <form class="form-horizontal">
                                                            <div class="input-group input-group-sm mb-0">
                                                                <input class="form-control form-control-sm" placeholder="Response">
                                                                <div class="input-group-append">
                                                                    <button type="submit" class="btn btn-danger">Send</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.post -->

                                                    <!-- Post -->
                                                    <div class="post">
                                                        <div class="user-block">
                                                            <img class="img-circle img-bordered-sm" src="<?php echo $base; ?>dist/img/user6-128x128.jpg" alt="User Image">
                                                            <span class="username">
                                                                <a href="#">Adam Jones</a>
                                                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                                            </span>
                                                            <span class="description">Posted 5 photos - 5 days ago</span>
                                                        </div>
                                                        <!-- /.user-block -->
                                                        <div class="row mb-3">
                                                            <div class="col-sm-6">
                                                                <img class="img-fluid" src="<?php echo $base; ?>dist/img/photo1.png" alt="Photo">
                                                            </div>
                                                            <!-- /.col -->
                                                            <div class="col-sm-6">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <img class="img-fluid mb-3" src="<?php echo $base; ?>dist/img/photo2.png" alt="Photo">
                                                                        <img class="img-fluid" src="<?php echo $base; ?>dist/img/photo3.jpg" alt="Photo">
                                                                    </div>
                                                                    <!-- /.col -->
                                                                    <div class="col-sm-6">
                                                                        <img class="img-fluid mb-3" src="<?php echo $base; ?>dist/img/photo4.jpg" alt="Photo">
                                                                        <img class="img-fluid" src="<?php echo $base; ?>dist/img/photo1.png" alt="Photo">
                                                                    </div>
                                                                    <!-- /.col -->
                                                                </div>
                                                                <!-- /.row -->
                                                            </div>
                                                            <!-- /.col -->
                                                        </div>
                                                        <!-- /.row -->

                                                        <p>
                                                            <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                                            <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                                            <span class="float-right">
                                                                <a href="#" class="link-black text-sm">
                                                                    <i class="far fa-comments mr-1"></i> Comments (5)
                                                                </a>
                                                            </span>
                                                        </p>

                                                        <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                                    </div>
                                                    <!-- /.post -->
                                                </div>
                                                <!-- /.tab-pane -->
                                                <div class="tab-pane" id="timeline">
                                                    <!-- The timeline -->
                                                    <div class="timeline timeline-inverse">
                                                        <!-- timeline time label -->
                                                        <div class="time-label">
                                                            <span class="bg-danger">
                                                                10 Feb. 2014
                                                            </span>
                                                        </div>
                                                        <!-- /.timeline-label -->
                                                        <!-- timeline item -->
                                                        <div>
                                                            <i class="fas fa-envelope bg-primary"></i>

                                                            <div class="timeline-item">
                                                                <span class="time"><i class="far fa-clock"></i> 12:05</span>

                                                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                                                <div class="timeline-body">
                                                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                                    quora plaxo ideeli hulu weebly balihoo...
                                                                </div>
                                                                <div class="timeline-footer">
                                                                    <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END timeline item -->
                                                        <!-- timeline item -->
                                                        <div>
                                                            <i class="fas fa-user bg-info"></i>

                                                            <div class="timeline-item">
                                                                <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                                                                <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend request
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <!-- END timeline item -->
                                                        <!-- timeline item -->
                                                        <div>
                                                            <i class="fas fa-comments bg-warning"></i>

                                                            <div class="timeline-item">
                                                                <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                                                                <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                                                                <div class="timeline-body">
                                                                    Take me to your leader!
                                                                    Switzerland is small and neutral!
                                                                    We are more like Germany, ambitious and misunderstood!
                                                                </div>
                                                                <div class="timeline-footer">
                                                                    <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END timeline item -->
                                                        <!-- timeline time label -->
                                                        <div class="time-label">
                                                            <span class="bg-success">
                                                                3 Jan. 2014
                                                            </span>
                                                        </div>
                                                        <!-- /.timeline-label -->
                                                        <!-- timeline item -->
                                                        <div>
                                                            <i class="fas fa-camera bg-purple"></i>

                                                            <div class="timeline-item">
                                                                <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                                                                <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                                                                <div class="timeline-body">
                                                                    <img src="https://placehold.it/150x100" alt="...">
                                                                    <img src="https://placehold.it/150x100" alt="...">
                                                                    <img src="https://placehold.it/150x100" alt="...">
                                                                    <img src="https://placehold.it/150x100" alt="...">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END timeline item -->
                                                        <div>
                                                            <i class="far fa-clock bg-gray"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.tab-pane -->

                                                <div class="tab-pane" id="settings">
                                                    <form class="form-horizontal">
                                                        <div class="form-group row">
                                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                                            <div class="col-sm-10">
                                                                <input type="email" class="form-control" id="inputName" placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                            <div class="col-sm-10">
                                                                <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="inputName2" placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                                                            <div class="col-sm-10">
                                                                <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="offset-sm-2 col-sm-10">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="offset-sm-2 col-sm-10">
                                                                <button type="submit" class="btn btn-danger">Submit</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.tab-pane -->
                                            </div>
                                            <!-- /.tab-content -->
                                        </div><!-- /.card-body -->
                                    </div>
                                    <!-- /.nav-tabs-custom -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div><!-- /.container-fluid -->
                    <?php }
                    ?>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <?php
            include '../elements/footprint.php';
            ?>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->
        <?php
    } else {
        header('Location: ../index.php');
    }
    require '../elements/footer.php';
    ?>

</body>
</html>
