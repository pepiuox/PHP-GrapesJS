<?php
include 'conn.php';
if (isset($_POST['edit'])) {
    $edit = $_POST['edit'];

    if ($edit == 1) {
        echo '
        <form method="post">
            <div class="form-group row">
                <div class="col-8">
                    <h4> Edit your setting for DB</h4>
                </div>
            </div>
            <div class="form-group row">
                <label for="host" class="col-4 col-form-label">Database Host</label> 
                <div class="col-8">
                    <input id="host" name="host" type="text" class="form-control" value="' . DBHOST . '">
                </div>
            </div>
            <div class="form-group row">
                <label for="user" class="col-4 col-form-label">Database Username</label> 
                <div class="col-8">
                    <input id="user" name="user" type="text" class="form-control" value="' . DBUSER . '">
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-4 col-form-label">Database Password</label> 
                <div class="col-8">
                    <input id="password" name="password" type="text" class="form-control" value="' . DBPASS . '">
                </div>
            </div> 
            <div class="form-group row">
                <label for="dbname" class="col-4 col-form-label">Database Name</label> 
                <div class="col-8">
                    <input id="dbname" name="dbname" type="text" class="form-control" value="' . DBNAME . '">
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-4 col-8">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>';
    }
}
?>
