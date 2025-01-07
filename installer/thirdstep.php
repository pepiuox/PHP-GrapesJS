<?php
// Second step
// Create your DataBase
if (isset($_POST['createdb'])) {
    $createdb = $_POST['cdbn'];

    $conn = new mysqli($_SESSION['DBHOST'], $_SESSION['DBUSER'], $_SESSION['DBPASSWORD']);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if ($createdb === 'yes') {

// Create database
        $sql = "CREATE DATABASE " . $_SESSION['DBNAME'];
        if ($conn->query($sql) === TRUE) {
            $_SESSION['SuccessMessage'] = "Database created successfully";
            $_SESSION['StepInstall'] = 3;
            $_SESSION['DBConnected'] = 'Connected';
            header("Location: install.php?step=3");
            exit();
        } else {
            $_SESSION['ErrorMessage'] = "Error creating database: " . $conn->error;
        }
    }
    $conn->close();
}
?>
<div class="alert alert-danger" role="alert">
<h5>You don't have the <?php echo $_SESSION['DBNAME']; ?> database installed </h5>
</div>
<div class="mb-3">
<div class="alert alert-primary text-center" role="alert">
<h3>2.- Second step</h3>
</div>
<h4> Create you database.</h4>
<div class="progress">
<div class="progress-bar" role="progressbar" style="width: 15%;" aria-valuenow="15"
aria-valuemin="0" aria-valuemax="100">15%</div>
</div>
</div>

<div class="mb-3">
<h4>This option creates the database</h4>
<p>
<strong> Server Host : <span class="text-primary">
<?php echo $_SESSION['DBHOST']; ?></span></strong><br />
<strong> Server User : <span class="text-primary">
<?php echo $_SESSION['DBUSER']; ?></span></strong><br />
<strong> Server Password : <span class="text-primary">
<?php echo $_SESSION['DBPASSWORD']; ?></span></strong><br />
<strong> Database Name : <span class="text-danger">
<?php echo $_SESSION['DBNAME']; ?></span></strong>
</p>
</div>
<div class="mb-3">
<p>
If everything is correct, check the box and create the database.
</p>
<div class="form-check">

<input class="form-check-input" type="checkbox" value="yes" id="cdbn" name="cdbn">
<label class="form-check-label" for="flexCheckDisabled">
Confirm the creation of the database.
</label>
</div>
</div>
<div class="mb-3">
<button name="init" type="submit" class="btn btn-success"><i
class="fas fa-arrow-left"></i> Start steps </button>
<button name="createdb" type="submit" class="btn btn-primary">Create database </button>
</div>