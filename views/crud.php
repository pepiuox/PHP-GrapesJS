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
        } else {
            
        }
    }
    ?>
    <script>
        $(function () {
            $("#selecttb").change(function () {
                var selecttb = $(this).val();
                var value = selecttb;
                //var path=$(location).attr('href');     
                value = value.replace("_", " ");
                var url = 'dashboard.php?cms=crud&w=list&tbl=' + selecttb;
                $('#fttl').text('Form ' + value);
                window.location.replace(url);
            });
        });
    </script>
    <?php if (!empty($_SESSION['SuccessMessage'])) { ?>
        <div class="container">
            <div class="row pt-2">
                <div class="alert alert-success alert-container" id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['SuccessMessage']) ?></center></strong>
                    <?php unset($_SESSION['SuccessMessage']); ?>
                    <meta http-equiv="refresh" content="5;URL=index.php" />
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if (!empty($_SESSION['AlertMessage'])) { ?>
        <div class="container">
            <div class="row pt-2">
                <div class="alert alert-success alert-container" id="alert">
                    <strong><center><?php echo htmlentities($_SESSION['AlertMessage']) ?></center></strong> 
                    <meta http-equiv="refresh" content="4;URL=profile.php" />
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-6">
                <h3 id="fttl">List of system tables </h3>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select id="selecttb" name="selecttb" class="form-control">
                        <option value="">Select a Table </option>
                        <?php
                        foreach ($tableNames as $tname) {
                            $remp = str_replace("_", " ", $tname);
                            echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                        }
                        ?>
                    </select>
                </div>
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

            $c->updateScript($tble, $id);
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
                        echo "Record deleted successfully";
                    } else {
                        echo "Error deleting record";
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