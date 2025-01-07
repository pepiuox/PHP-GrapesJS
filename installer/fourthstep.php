<?php
// Third step
// Import tables to Database
if (isset($_POST['install'])) {
// Name of the file
    $filename = 'sql/page.sql';

// Temporary variable, used to store current query
    $templine = '';
// Read in entire file
    $lines = file($filename);
// Loop through each line
    foreach ($lines as $line) {
// Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '') {
            continue;
        }
// Add this line to the current segment
        $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';') {
// Perform the query
            $conn->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $conn->error . '<br /><br />');
// Reset temp variable to empty
            $templine = '';
        }
    }
    $_SESSION['SuccessMessage'] = "Tables imported successfully";
    $conn->close();
    $_SESSION['StepInstall'] = 4;
    header("Location: install.php?step=4");
    exit();
}
?>
<div class="alert alert-success" role="alert">
<h5>Your DB is connected to <?php echo $_SESSION['DBNAME']; ?></h5>
</div>
<div class="mb-3">
<div class="alert alert-primary text-center" role="alert">
<h3>3.- Third step</h3>
</div>
<h4> Install tables in your database.</h4>
<div class="progress">
<div class="progress-bar" role="progressbar" style="width: 55%;" aria-valuenow="55"
aria-valuemin="0" aria-valuemax="100">55%</div>
</div>
</div>
<h4>This step creates the tables in your database </h4>
<div class="mb-3">
<button class="btn btn-info" type="submit" name="install" id="install">Install
tables</button>
</div>