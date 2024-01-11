<?php
$p = new Protect();
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $p->secureStr($_GET['w']);
}
$set = new tableSettings();
$c = new MyCRUD();

if ($w == "select") {

    if ($result = $c->selectData("SELECT * FROM table_config")) {
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
                <h3>List of system tables </h3>
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
                            let select = document.querySelector('#selecttb');
                            select.addEventListener('change', function () {
                                let url = 'dashboard.php?cms=table_crud&w=list&tbl=' + this.value;
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

    $cdata = $set->tblSettings($tble);
    $data = json_decode($cdata, true);
    $list = $data['table_list'];
    ?>
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-3">
                <a class="btn btn-secondary" href="dashboard.php?cms=table_crud&w=select">Select a Table </a>
                <a class="btn btn-success" href="dashboard.php?cms=table_builder&w=editor&tbl=<?php echo $tble; ?>">Edit query Table </a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Data List of <?php echo $titl; ?></h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <?php
            $fichero = 'ftmp.php';
            if (file_exists($fichero)) {
                unlink($fichero);
            }
            if ($set->checkList($list) === true) {
                echo $c->getDatalist($tble);
            } else {
                echo '<h3>You do not have permissions to view the content list.</h3>';
            }
            ?>           
        </div>
    </div>
    <?php
} elseif ($w == 'add') {
    $tble = $p->secureStr($_GET['tbl']);
    $titl = ucfirst(str_replace("_", " ", $tble));

    $cdata = $set->tblSettings($tble);
    $data = json_decode($cdata, true);
    $add = $data['table_add'];
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=table_crud&w=list&tbl=<?php echo $tble; ?>">Back to List</a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Add Data in <?php echo $titl; ?></h2>
            </div>
        </div>
        <div class="col-md-12">
            <?php
            if ($set->checkAdd($add) === true) {
                $c->addData($tble);
            } else {
                echo '<h3>You do not have permissions to add data content.</h3>';
            }
            ?>                        
        </div>      
    </div>
    </div>

    <?php
} elseif ($w == "edit") {
    $tble = $p->secureStr($_GET['tbl']);
    $titl = ucfirst(str_replace("_", " ", $tble));

    $cdata = $set->tblSettings($tble);
    $data = json_decode($cdata, true);
    $update = $data['table_update'];

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=table_crud&w=list&tbl=<?php echo $tble; ?>">Back to List </a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Edit data from <?php echo $titl; ?></h2>
            </div>
        </div>
        <div class="col-md-12">
            <?php
            if ($set->checkUpdate($update) === true) {
                $c->updateScript($tble);
                include 'updatetmp.php';
                $c->inputQEdit($tble, $id);
            } else {
                echo '<h3>You do not have permissions to edit/update data content.</h3>';
            }
            ?>             
        </div>
    </div>
    <?php
} elseif ($w == "delete") {
    $tble = $p->secureStr($_GET['tbl']);
    $titl = ucfirst(str_replace("_", " ", $tble));

    $cdata = $set->tblSettings($tble);
    $data = json_decode($cdata, true);
    $delete = $data['table_delete'];

    $ncol = $c->getID($tble);
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary" href="dashboard.php?cms=table_crud&w=list&tbl=<?php echo $tble; ?>">Back to List </a>
            </div>
            <div class="col-md-9">
                <h2 class="text-primary">Delete data from <?php echo $titl; ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($set->checkDelete($delete) === true) {
                    if (isset($_POST["deleterow"])) {

                        if ($c->selectData("DELETE FROM $tble WHERE $ncol='$id'") === TRUE) {
                            $_SESSION['success'] = "Record deleted successfully";
                        } else {
                            $_SESSION['error'] = "Error deleting record";
                        }
                    }

                    $c->deleteData($tble, $id);
                } else {
                    echo '<h3>You do not have permissions to delete this data content.</h3>';
                }
                ?>
            </div>
        </div>
    </div>

    <?php
}
?>
