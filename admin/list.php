<?php
session_start();
$file = '../config/dbconnection.php';
if (file_exists($file)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
    $check = new CheckValidUser();
} else {
    header('Location: install.php');
}
include '../elements/header.php';
?>
</head>
<body>
    <?php
    if ($login->isLoggedIn() === true) {
        ?>
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
                            <a class="btn btn-primary" href="add.php"><i class="fa fa-file-o" aria-hidden="true"></i> Add New Page</a>
                        </li> 
                        <li class="nav-item">
                            <a class="btn btn-secondary" href="admin/settings.php"><i class="fa fa-gear" aria-hidden="true"></i> Edit Settings</a> 
                        </li>
                    </ul>   
                </div>
            </div>
        </nav>
        <!<!-- end menu -->

        <div class='container'>            
            <div class="row">                
                <div class="col-md-12 py-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>View</th>
                                <th>Title</th>
                                <th>Link</th>
                                <th>Parent</th>
                                <th>Active</th>
                                <th>Edit</th>
                                <th>Build</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM page");
                            $numr = $result->num_rows;
                            if ($numr > 0) {
                                while ($row = $result->fetch_array()) {
                                    echo '<tr><td>';
                                    echo '<a href="view.php?id=' . $row['id'] . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                    echo '</td><td>' . "\n";
                                    echo $row['title'];
                                    echo '</td><td>' . "\n";
                                    echo clean_string($row['link']);
                                    echo '</td><td>' . "\n";
                                    vwparent($row['parent']);
                                    echo '</td><td>' . "\n";
                                    vwaction($row['active']);
                                    echo '</td><td>' . "\n";
                                    echo '<a href="edit.php?id=' . $row['id'] . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                                    echo '</td><td>' . "\n";
                                    echo '<a href="builder.php?id=' . $row['id'] . '"><i class="fa fa-cog" aria-hidden="true"></i></i></a>';
                                    echo '</td><td>' . "\n";
                                    echo '<a href="delete.php?id=' . $row['id'] . '"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
                                    echo '</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8" rowspan="1" style="vertical-align: top;">';
                                echo "<h3>You haven't created a page yet.</h3>";
                                echo '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
    } else {
        header('Location: ../index.php');
    }
    require '../elements/footer.php';
    ?>
</body>
</html>
