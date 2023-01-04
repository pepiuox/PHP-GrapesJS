<div class='container-fluid'>            
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
                    $presult = $conn->query("SELECT * FROM page");
                    $pnumr = $presult->num_rows;
                    if ($pnumr > 0) {
                        while ($prow = $presult->fetch_array()) {
                            echo '<tr><td>';
                            echo '<a href="' . SITE_PATH . $prow['link'] . '" target="_blank"><i class="fas fa-eye" aria-hidden="true"></i></a>';
                            echo '</td><td>' . "\n";
                            echo $prow['title'];
                            echo '</td><td>' . "\n";
                            echo clean_string($prow['link']);
                            echo '</td><td>' . "\n";
                            vwparent($prow['parent']);
                            echo '</td><td>' . "\n";
                            vwaction($prow['active']);
                            echo '</td><td>' . "\n";
                            echo '<a href="dashboard.php?cms=editpage&id=' . $prow['id'] . '"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                            echo '</td><td>' . "\n";
                            echo '<a href="builder.php?id=' . $prow['id'] . '"><i class="fas fa-cog" aria-hidden="true"></i></i></a>';
                            echo '</td><td>' . "\n";
                            echo '<a href="dashboard.php?cms=deletepage&id=' . $prow['id'] . '"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
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
