<?php
// Firts step
// Check Database
if (isset($_POST['check'])) {
    $db_host = $_POST['host'];
    $db_user = $_POST['user'];
    $db_password = $_POST['password'];
    $db_name = $_POST['dbname'];

    $_SESSION['DBHOST'] = $db_host;
    $_SESSION['DBUSER'] = $db_user;
    $_SESSION['DBPASSWORD'] = $db_password;
    $_SESSION['DBNAME'] = $db_name;

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    /* If connection fails for some reason */
    if ($conn->connect_error) {
        $_SESSION['ErrorMessage'] = "The database has not been created yet, do you want to create it?";
        $_SESSION['StepInstall'] = 2;
        header("Location: install.php?step=2");
        exit();
    } else {
        $_SESSION['SuccessMessage'] = "The database exists, now you need to import the data tables.";
        $_SESSION['StepInstall'] = 3;
        $_SESSION['DBConnected'] = 'Connected';
        header("Location: install.php?step=3");
        exit();
    }
}

// Back to first step
if (isset($_POST['init'])) {
    $_SESSION['StepInstall'] = 1;
    header("Location: install.php?step=1");
}
?>
<div class="mb-3">
<div class="alert alert-primary text-center" role="alert">
<h3>1.- First step</h3>
</div>
<h4> Save your setting for DB</h4>
<div class="progress">
<div class="progress-bar" role="progressbar" style="width: 5%;" aria-valuenow="0"
aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
</div>
<div class="mb-3">
<p>If you have a primary domain and others pointing to the same domain, but you do not want to do multiple installations and upload multiple files, you can use this option.</p>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
  Is it a multi domain installation?
  </label>
</div>
<hr class="border border-primary border-3 opacity-75">
<div class="mb-3">
<label for="host" class="col-form-label">Database Host</label>
<input id="host" name="host" type="text" class="form-control">
</div>
<div class="mb-3">
<label for="dbname" class="col-form-label">Database Name</label>
<input id="dbname" name="dbname" type="text" class="form-control">
</div>
<div class="mb-3">
<label for="user" class="col-form-label">Database Username</label>
<input id="user" name="user" type="text" class="form-control">
</div>
<div class="mb-3">
<label for="password" class="col-form-label">Database Password</label>
<input id="password" name="password" type="text" class="form-control">
</div>
<div class="mb-3">
<button class="btn btn-primary" name="check" id="check">Check DB connection</button>
</div>
<hr>
<div class="mb-3">
<p>
If you have already created the database, check the connection and continue with the
installation of tables in the third step, otherwise create the table in the second
step.
</p>
</div>