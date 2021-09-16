<?php
$p = new Protect();
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $p->secureStr($_GET['w']);
}
$c = new MyCRUD();

if ($w == "list") {
    $tble = 'menu_options';
    $titl = ucfirst(str_replace("_", " ", $tble));
    ?>
    <div class="container">
        <div class="row pt-3">
            <div class="col-md-12">
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

    $tble = 'menu_options';
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=menu&w=list">Back to List</a>
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
    $tble = 'menu_options';
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="btn btn-secondary"
                   href="dashboard.php?cms=menu&w=list">Back to List </a>
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
    $tble = 'menu_options';
    $ncol = $c->getID($tble);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="dashboard.php?cms=menu&w=list">List</a>
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
