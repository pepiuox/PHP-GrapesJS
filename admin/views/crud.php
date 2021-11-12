
<?php
$p = new Protect();
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $p->secureStr($_GET['w']);
}

$c = new MyCRUD();
if ($w == "select") {

    if ($result = $c->wQueries("SELECT * FROM table_config")) {
        $total_found = $result->num_rows;

        if ($total_found > 0) {
            $row = $result->fetch_assoc();
            $tableNames = explode(',', $row['table_name']);
        } 
    }
    ?>
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-6">
                <h3 id="fttl">List of system tables </h3>
            </div>
            <div class="col-md-6">
                <form method="post">
                    <div class="form-group">
                        <label class="control-label" for="selecttb">Select Table</label> <select
                            id="selecttb" name="selecttb" class="form-control">
                            <option value="">Select Table</option>
                            <?php
                            if (!empty($tableNames)) {
                                foreach ($tableNames as $tname) {
                                    $remp = str_replace("_", " ", $tname);
                                    echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                                }
                            }
                            ?>
                        </select>
                        <script>
                            let select =  document.querySelector('#selecttb');
                            let result = document.querySelector('#fttl');
                            select.addEventListener('change', function () {                                        
                                let nvalue = this.value.replace("_", " ");
                                let url = 'dashboard.php?cms=crud&w=list&tbl=' + this.value;
                                result.textContent='Form ' + nvalue;
                                window.location.replace(url);
                            });
                        </script>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
} elseif ($w == "list") {
    $tble = $p->secureStr($_GET['tbl']);
    $titl = ucfirst(str_replace("_", " ", $tble));
    ?>
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-3">
                <a class="btn btn-secondary" href="dashboard.php?cms=crud&w=select">Select a Table </a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Data List of <?php echo $titl; ?></h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">		
        <?php
        $fichero = 'frtmp.php';
        if (file_exists($fichero)) {
            unlink($fichero);
        }

        echo $c->getDatalist($tble);
        ?>           
    </div>
    <?php
} elseif ($w == 'add') {

    $tble = $p->secureStr($_GET['tbl']);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=crud&w=list&tbl=<?php echo $tble; ?>">Back to List</a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Add Data </h2>
            </div>
        </div>
        <div class="col-md-12">
            <?php
            $c->addData($tble);
            ?>                        
        </div>
        <div class="row">
            <div id="dataTable"></div>
        </div>
    </div>
    </div>

    <?php
} elseif ($w == "edit") {
    $tble = $p->secureStr($_GET['tbl']);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=crud&w=list&tbl=<?php echo $tble; ?>">Back to List </a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Edit Data </h2>
            </div>
        </div>
        <div class="col-md-12">
            <?php
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
            }

            $c->updateScript($tble);
            include 'updatetmp.php';
            $c->inputQEdit($tble, $id);
            ?>             
        </div>
    </div>
    <?php
} elseif ($w == "delete") {
    $tble = $p->secureStr($_GET['tbl']);
    $ncol = $c->getID($tble);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="dashboard.php?cms=crud&w=list&tbl=<?php echo $tble; ?>">List</a>
            </div>
            <div class="col-md-9">

                <center>
                    <h2 class="text-primary">Delete info</h2>
                    <h4 class="text-primary">Are you sure you want to delete data?</h4>
                </center>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                if (isset($_GET["id"])) {
                    $id = $_GET["id"];
                }

                if (isset($_POST["deleterow"])) {

                    if ($c->wQueries("DELETE FROM $tble WHERE $ncol='$id'") === TRUE) {
                        $_SESSION['success'] = "Record deleted successfully";
                        
                    } else {
                        $_SESSION['error'] = "Error deleting record";
                    }
                }

                $c->deleteData($tble, $id);
                ?>
            </div>
        </div>
    </div>

    <?php
}
?>
