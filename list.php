<?php
require 'conn.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <title>Content Editor</title>
        <link href="<?php echo $base; ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" data-type="keditor-style"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>css/font-awesome.min.css" data-type="keditor-style" />
    </head>
    <body>
        <?php
        //require 'navbar.php';
        ?>
        <div class='container'>
            <div class="row pt-4">               
                <div class="col-md-6">
                    <a class="btn btn-primary" href="add.php"><i class="fa fa-file-o" aria-hidden="true"></i> Add New Page</a>
                </div>
                <div class="col-md-6">                    
                    <a class="btn btn-primary" href="settings.php"><i class="fa fa-gear" aria-hidden="true"></i> Edit Settings</a>         
                </div>               
            </div>
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
        <script src="plugins/jquery-3.5.1/jquery-3.5.1.min.js" type="text/javascript"></script>
        <script src="plugins/bootstrap-4.4.1/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="plugins/popper/popper.min.js" type="text/javascript"></script>
    </body>
</html>
