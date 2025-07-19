<?php
if (!isset($_SESSION)) {
    session_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require_once '../config/dbconnection.php';
    require_once 'Autoload.php';

    $path = basename($_SERVER['REQUEST_URI']);
    $file = basename($path);

    $fileName = basename($_SERVER['PHP_SELF']);


    if ($file == $fileName) {
        header("Location: $fileName?view=select");
    }
    if (isset($_GET['view'])) {
        $view = protect($_GET['view']);
    } else {
        header("Location: $fileName?view=select");
    }
    if (!empty($_GET["tbl"])) {
        $tbl = protect($_GET["tbl"]);
        $tble = ucfirst(str_replace('_', ' ', $tbl));

        if (substr($tbl, -1) == 's') {
            $coln = substr($tbl, 0, -1);
            $ucoln = ucfirst(substr($tbl, 0, -1));
        } else {
            $coln = $tbl;
            $ucoln = ucfirst($tbl);
        }

        $sql = "SELECT * FROM $tbl";
        $result = $conn->query($sql);

        $cname = array();
        $i = 0;
        while ($result->field_count > $i) {
            $nam = $result->fetch_field();
            if ($i === 0) {
                $idcol = $nam->name;
                $whre = $nam->name . " ='$" . $nam->name . "'";
                $vpost = "$" . $nam->name . " = \$_POST['" . $nam->name . "'];";
            }

            $cnames[] = $nam->name;
            $uposts[] = "$" . $nam->name . "= \$_POST['" . $nam->name . "'];" . "\n";
            if ($i != 0) {
                $varc[] = $nam->name . ' :""';
                $cols[] = $nam->name;
                $varnames[] = "'$" . $nam->name . "'";
                $upnames[] = $nam->name . " ='$" . $nam->name . "'";
                $cposts[] = "$" . $nam->name . "= \$_POST['" . $nam->name . "'];" . "\n";
            }
            $i = $i + 1;
        }

        // start joins
        $ths = array();
        $qnames = array();
        $num = $result->field_count;
        if ($num > 0) {
            while ($nam = $result->fetch_field()) {
                $qnames[] = $nam->name;
            }
            foreach ($qnames as $qname) {
                $ths[] = '<th>' . $qname . '</th>' . "\n";
            }
        } else {
            echo 'This table if it has columns or content.' . '<br>';
        }

        function stringUf($cname) {
            return ucfirst(str_replace('_', ' ', $cname));
        }

        function getJoin($tbl, $cname) {
            global $conn;
            $joinquery = $conn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
            if ($joinquery->num_rows > 0) {
                $resjoin = $joinquery->fetch_assoc();
                $coln = $resjoin['j_value'];
                if (!empty($coln)) {
                    return $coln;
                } else {
                    return $cname;
                }
            } else {
                return $cname;
            }
        }

        function getSelect($tbl, $cname) {
            global $conn;
            $joinquery = $conn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
            if ($joinquery->num_rows > 0) {
                $resjoin = $joinquery->fetch_assoc();
                $coln = $resjoin['j_value'];
                if (!empty($coln)) {
                    return $coln;
                } else {
                    return $cname;
                }
            } else {
                return $cname;
            }
        }

        function optsel($jtable, $jid, $jvalue) {
            global $conn;
            $sels = $conn->query("SELECT * FROM $jtable");
            while ($sel = $sels->fetch_array()) {
                echo '<option value="' . $sel[$jid] . '">' . $sel[$jvalue] . '</option>';
            }
        }

        function inpSel($tbl, $cname) {
            global $conn;
            $qsl = $conn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
            while ($row = $qsl->fetch_array()) {
                if (!empty($row['joins'])) {
                    $jtable = $row['j_table'];
                    $jvalue = $row['j_value'];
                    $jid = $row['j_id'];
                    echo'<select class="form-control" id="' . $cname . '" name="' . $cname . '" v-model="newDato.' . $cname . '">';
                    optsel($jtable, $jid, $jvalue);
                    echo '</select>' . "\n";
                } else {
                    echo '<input type="text"  class="form-control" id="' . $cname . '" name="' . $cname . '" placeholder="' . stringUf($cname) . '" v-model="newDato.' . $cname . '">';
                }
            }
        }

        function upSel($tbl, $cname) {
            global $conn;
            $qsl = $conn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
            while ($row = $qsl->fetch_array()) {
                if (!empty($row['joins'])) {
                    $jtable = $row['j_table'];
                    $jvalue = $row['j_value'];
                    $jid = $row['j_id'];
                    echo'<select class="form-control" id="' . $cname . '" name="' . $cname . '" v-model="clickedDato.' . $cname . '">';
                    optsel($jtable, $jid, $jvalue);
                    echo '</select>' . "\n";
                } else {
                    echo '<input type="text"  class="form-control" id="' . $cname . '" name="' . $cname . '" placeholder="' . stringUf($cname) . '" v-model="clickedDato.' . $cname . '">';
                }
            }
        }

        $colcs = array();
        $ljoins = array();
        $sql1 = "SELECT * FROM table_column_settings WHERE table_name='$tbl'";
        $result1 = $conn->query($sql1);
        $ntq = $result1->num_rows;
        if ($ntq > 0) {
            while ($qtb = $result1->fetch_array()) {
                $colcs[] = getJoin($tbl, $qtb['col_name']);
                if (empty($qtb['joins'])) {
                    continue;
                } else {
                    $ljoins[] = $qtb['joins'] . ' ' . $qtb['j_table'] . ' ON ' . $tbl . '.' . $qtb['col_name'] . ' = ' . $qtb['j_table'] . '.' . $qtb['j_id'];
                }
            }
        }

        $rjoin = implode(" ", $ljoins);
        $joinquery = $conn->query("SELECT * FROM $tbl $rjoin");

// end joins

        $col = implode(" , ", $cols);
        $varname = implode(" , ", $varnames);
        $upname = implode(" , ", $upnames);
        $cli = implode(" , ", $varc);
        $upost = implode(" ", $uposts);
        $cpost = implode(" ", $cposts);

        // make file app.js
        $appfile = 'app.js';
        $myapp = fopen("$appfile", "w") or die("Unable to open file!");
        $appcontent = 'var app = new Vue({
                el: "#app",
                data: {
                    showmodaladd: false,
                    showmodaledit: false,
                    showmodaldelete: false,
                    successmessage: "",
                    errormessage: "",
                    datos: [],
                    newDato: {' . $cli . '},
                    clickedDato: {}

                },
                mounted: function () {
                    console.log("mounted");
                    this.getAllDatos();
                },
                methods: {
                    getAllDatos: function () {
                        axios.get("app.php?action=read")
                                .then(function (response) {
                                    // console.log(response);
                                    if (response.data.error) {
                                        app.errormessage = response.data.message;
                                    } else {
                                        app.datos = response.data.datos;
                                    }
                                });

                    },
                    saveDato: function () {
                        // console.log(app.newDato);
                        var formData = app.toformData(app.newDato);
                        axios.post("app.php?action=create", formData)
                                .then(function (response) {

                                    // app.newDato={' . $cli . '};

                                    if (response.data.error == true) {
                                        app.errormessage = response.data.message;
                                    } else {
                                        app.successmessage = response.data.message;
                                        app.getAllDatos();
                                    }
                                });
                    },
                    selectDato: function (dato) {
                        app.clickedDato = dato;
                    },
                    updateDato: function (dato) {
                        var formData = app.toformData(app.clickedDato);
                        axios.post("app.php?action=update", formData)
                                .then(function (response) {

                                    app.clickedDato = {};

                                    if (response.data.error) {
                                        app.errormessage = response.data.error;
                                    } else {
                                        app.successmessage = response.data.message;
                                        app.getAllDatos();
                                    }
                                });
                    },
                    deleteDato: function (dato) {
                        var formData = app.toformData(app.clickedDato);
                        axios.post("app.php?action=delete", formData)
                                .then(function (response) {

                                    app.clickedDato = {};

                                    if (response.data.error) {
                                        app.errormessage = response.data.message;
                                    } else {
                                        app.successmessage = response.data.message;
                                        app.getAllDatos();
                                    }
                                });
                    },
                    toformData: function (obj) {
                        var form_data = new FormData();
                        for (var key in obj) {
                            form_data.append(key, obj[key]);
                        }
                        return form_data;
                    },

                    clearMessage: function () {
                        app.successmessage = "";
                        app.errormessage = "";
                    }
                }

            });';
        fwrite($myapp, $appcontent);
        fclose($myapp);
        // make file app.php
        $apifile = 'app.php';
        $myapi = fopen("$apifile", "w") or die("Unable to open file!");
        $apicontent = '
<?php
include "db.php";
$res = array("error" => false);

$action = "read";

if (isset($_GET["action"])) {
    $action = $_GET["action"];
}

if ($action == "read") {';

        if ($ntq > 0) {
            $apicontent .= '$result = $conn->query("SELECT * FROM ' . $tbl . ' ' . $rjoin . '");
        $datos = array();
            while ($row = $result->fetch_assoc()) {
                array_push($datos, $row);
            }

            $res["datos"] = $datos;               
        ';
        } else {
            $apicontent .= ' $result = $conn->query("SELECT * FROM `' . $tbl . '`");
        $datos = array();
        while ($row = $result->fetch_assoc()) {
            array_push($datos, $row);
        }

        $res["datos"] = $datos;
    ';
        }
        $apicontent .= '
    
}
// Create form

if ($action == "create") {

    ' . $cpost . '

    $result = $conn->query("INSERT INTO ' . $tbl . '(' . $col . ') VALUES (' . $varname . ')");

    if ($result) {
        $res["message"] = "dato agregado exitosamente";
    } else {
        $res["error"] = true;
        $res["message"] = "dato no se agrego exitosamente";
    }

    // $res["datos"] =$datos;
}
// end of create form
// update form

if ($action == "update") {
    ' . $upost . '

    $result = $conn->query("UPDATE ' . $tbl . ' SET ' . $upname . ' WHERE ' . $whre . ' ");

    if ($result) {
        $res["message"] = "dato actualizado con éxito";
    } else {
        $res["error"] = true;
        $res["error"] = "dato no se actualizo con éxito";
    }
}

// end of update form

if ($action == "delete") {
    ' . $vpost . '

    $result = $conn->query("DELETE FROM `' . $tbl . '` WHERE ' . $whre . ' ");

    if ($result) {
        $res["message"] = "dato borrado exitosamente";
    } else {
        $res["error"] = true;
        $res["message"] = "dato no se borro exitosamente";
    }
    // $res["datos"] =$datos;
}

$conn->close();
header("content-type:application/json");
echo json_encode($res);
die();
?>
';
        fwrite($myapi, $apicontent);
        fclose($myapi);
    }
    ?>
   <?php include '../elements/header.php';
            ?>
            <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
            <script src="../assets/js/axios.min.js"></script>
            <style type="text/css">

                .my-form{
                    padding: 60px;
                }
                .head{
                    text-align: right;
                    color: red;
                    font-weight: bolder;
                    padding: 10px;
                    font-size: 18px;
                }
                .head span{
                    text-align: left
                }
                .head i:hover{
                    cursor: pointer;
                    transform: rotate(180deg);
                    transition: transform linear 260ms;

                }
                .my-btn button{
                    float: right
                }
                .buttons button{
                    float: right;
                    margin-right: 8px;
                }
                .fade-enter-active, .fade-leave-active {
                    transition: opacity .5s;
                    /*transform: translateY(50%);
                      transition:transform ease-in-out 500ms;*/ 
                }
                .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
                    opacity: 0;
                    /* transform: translateY(0%);*/
                }
                .bounce-enter-active {
                    animation: bounce-in .5s;
                }
                .bounce-leave-active {
                    animation: bounce-in .5s reverse;
                }
                @keyframes bounce-in {
                    0% {
                        transform: scale(0);
                    }
                    50% {
                        transform: scale(1.3);
                    }
                    100% {
                        transform: scale(1);
                    }
                }
            </style>
        </head>
        <body>
            <?php
            if ($view === "select") {
                if ($result = $conn->query("SELECT * FROM table_config")) {
                    $total_found = mysqli_num_rows($result);

                    if ($total_found > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $tableNames = explode(',', $row['table_name']);
                    }
                }
                ?>
                <div class="container">
                    <div class="row py-3">                    
                        <div class="col-md-6">
                            <h3 id="fttl">Select a table from your database</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <script>
                                    $(function () {
                                        $("#selecttb").change(function () {
                                            var selecttb = $(this).val();
                                            //var path = $(location).attr('href');                        
                                            var url = 'vuejs_crud.php?view=crud&tbl=' + selecttb;
                                            $('#fttl').text('Table ' + selecttb);
                                            window.location.replace(url);
                                        });
                                    });
                                </script>
                                <label class="control-label" for="selecttb">Select Table</label>
                                <select id="selecttb" name="selecttb" class="form-control">
                                    <option value="">Elige una Tabla</option>
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
                /* View data in the selected table */
            } elseif ($view == "crud") {
                ?>
                <div class="container-fluid" id="app"> 
                    <!-- row for messages -->
                    <div class="row py-1">
                        <div class="w-100">
                            <div class="alert alert-success" role="alert" v-if="successmessage">
                                <h4 class="alert-heading" >{{successmessage}}</h4> 
                            </div>
                            <div class="alert alert-danger" role="alert" v-if="errormessage">
                                <h4 class="alert-heading" >{{errormessage}}</h4> 
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-2">
                            <a class="btn btn-secondary" href="index.php?view=select">Select a table</a>
                        </div>
                        <div class="col-md-8">
                            <h5>List of <?php echo $tble; ?></h5>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary " @click="showmodaladd=true" data-toggle="modal" data-target="#addModal">Agregar nuevo <i class="fa fa-plus" aria-hidden="true"></i></button>
                        </div>
                    </div> <hr>
                    <!-- row for table content -->
                    <div class="row">
                        <table class="table table-sm">
                            <thead class="table-info">
                                <tr>
                                    <th scope="col">Actions</th>
                                    <?php
                                    foreach ($cnames as $cname) {
                                        $remp = str_replace("_", " ", $cname);
                                        echo '<th scope="col">' . ucfirst(str_replace(' id', '', $remp)) . '</th>' . "\n";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="dato in datos">
                                    <td>
                                        <button type="button" class="btn btn-info"  @click="showmodaledit = true; selectDato(dato)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> &nbsp; 
                                        <button type="button" class="btn btn-danger" @click="showmodaldelete= true; selectDato(dato)" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    </td>
                                    <?php
                                    foreach ($cnames as $cname) {
                                        echo '<td scope="row">{{dato.' . getJoin($tbl, $cname) . '}}</td>' . "\n";
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table> 
                        <!-- add modal -->
                        <transition name="fade">
                            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" v-if="showmodaladd">
                                        <div class="modal-header bg-info">
                                            <h5 class="modal-title">Add <i class="fa fa-plus" aria-hidden="true"></i></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i class="la la-times "  @click="showmodaladd= false"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">                                        
                                            <form method="post" class="my-form" action="javascript:void(0)">
                                                <?php
                                                foreach ($cnames as $key => $cname) {
                                                    if ($key == 0) {
                                                        continue;
                                                    } else {
                                                        $cinp = ucfirst(str_replace('_', ' ', $cname));
                                                        echo '<div class="form-group row">
                                <label for="' . $cname . '" class="col-sm-3 col-form-label">' . $cinp . '</label>
                                <div class="col-sm-9">' . "\n";
                                                        if ($ntq > 0) {
                                                            inpSel($tbl, $cname);
                                                        } else {
                                                            echo '<input type="text"  class="form-control" id="' . $cname . '" name="' . $cname . '" placeholder="' . $cinp . '" v-model="newDato.' . $cname . '">';
                                                        }
                                                        echo '</div></div>';
                                                    }
                                                }
                                                ?>
                                                <div class="col-sm-9">
                                                    <button type="button" class="btn btn-info"  @click="showmodaladd = false; saveDato()">Add</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition>
                        <!-- end of add modal -->
                        <!-- edit modal -->
                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content" v-if="showmodaledit">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title">Update <i class="fa fa-pencil-square-o" aria-hidden="true"></i></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i class="la la-times "  @click="showmodaledit= false"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">                                    
                                        <form method="post" class="my-form">                                       
                                            <?php
                                            foreach ($cnames as $key => $cname) {
                                                if ($key == 0) {
                                                    continue;
                                                } else {
                                                    $cinp = ucfirst(str_replace('_', ' ', $cname));
                                                    echo '<div class="form-group row">
                                                <label for="' . $cname . '" class="col-sm-3 col-form-label">' . $cinp . '</label>
                                                <div class="col-sm-9">';
                                                    if ($ntq > 0) {
                                                        upSel($tbl, $cname);
                                                    } else {
                                                        echo '<input type="text"  class="form-control" id="' . $cname . '" name="' . $cname . '" placeholder="' . $cinp . '" v-model="clickedDato.' . $cname . '">';
                                                    }
                                                    echo ' </div>
                                                </div>' . "\n";
                                                }
                                            }
                                            ?>
                                            <div class="form-group row">
                                                <div class="col-sm-9">
                                                    <button type="button" class="btn btn-info"  @click="showmodaledit = false;updateDato(dato) ">
                                                        <i class="fas fa-pencil-alt"></i>
                                                        Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end of edit modal -->
                        <!-- Delete modal -->
                        <transition name="bounce">
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" v-if="showmodaldelete">
                                        <div class="modal-header  bg-info">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i class="la la-times "  @click="showmodaldelete= false"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">                               
                                            <p class="text-center">You're going to delete id {{clickedDato.<?php echo $idcol; ?>}}</p>
                                            <br>
                                            <div class="buttons container">
                                                <p></p>
                                                <button type="button" class="btn btn-success" @click="showmodaldelete = false; deleteDato(dato)">Yes</button> &nbsp;&nbsp;&nbsp;
                                                <button type="button" class="btn btn-info" @click="showmodaldelete = false">No</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </transition>
                        <!-- end of Delete modal -->
                    </div>
                </div>

                <br>
                <script src="app.js" type="text/javascript"></script>
            <?php } ?>
         <?php
    } else {
        header('Location: ../signin/login.php');
        exit;
    }
    include '../elements/footer.php';
    ?>
</body>
</html>
