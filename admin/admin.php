<?php
$form = new Form();

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers() {
    global $conn;
    $q = "SELECT username, email, level, timestamp FROM uverify ORDER BY level DESC";
    $result = $conn->query($q);
    /* Error occurred, return given name by default */
    $num_rows = $result->num_rows;

    if (!$result || ($num_rows < 0)) {
        echo "Information display error";
        return;
    }
    if ($num_rows == 0) {
        echo "Empty database table";
        return;
    }
    /* Display table contents */
    echo "<table class='table' id='display'>";
    echo "<tr class='title'><td colspan='2'>Username</td>"
    . "<td>Level</td>"
    . "<td colspan='2'>Email</td>"
    . "<td colspan='2'>Last activity</td></tr>";

    while ($fecth_rows = $result->fetch_array()) {
        $uname = $fecth_rows["username"];
        $ulevel = $fecth_rows["level"];
        $email = $fecth_rows["email"];
        $time = $fecth_rows["timestamp"];

        echo "<tr><td colspan='2'>" . $uname . "</td><td>" . $ulevel . "</td><td colspan='2'>" . $email . "</td><td colspan='2'>" . $time . "</td></tr>";
    }
    echo "</table>";
}

/**
 * displayBannedUsers - Displays the banned users
 * database table in a nicely formatted html table.
 */
function displayBannedUsers() {
    global $conn;
    $q = "SELECT user_id,banned_timestamp FROM banned_users ORDER BY user_id";
    $result = $conn->query($q);
    /* Error occurred, return given name by default */
    $num_rows = $result->num_rows;
    if (!$result || ($num_rows < 0)) {
        echo " Error de visualización de información";
        return;
    }
    if ($num_rows == 0) {
        echo "<p class='col-12'>There are no banned users.</p>";
        return;
    }
    /* Display table contents */
    echo "<div class='table-responsive'>";
    echo "<table class='table table-sm' id='display'>";
    echo "<tr class='title'><tr colspan='2'>Usuario</td><td colspan='2'>Tiempo Prohibido</td></tr>";
    while ($fecth_rows = $result->fetch_array()) {
        $uname = $fecth_rows["user_id"];
        $time = $fecth_rows["banned_timestamp"];

        echo "<tr><td colspan='2'>" . $uname . "</td><td colspan='2'>" . $time . "</td></tr>";
    }
    echo "</table>";
    echo "</div>";
}

/**
 * Administrator is viewing page, so display all
 * forms.
 */
?>
<div class="container-fluid"> 
    <div class="row my-2 py-2">                           
        <div class="col-md-12">           
            <h4>Registered users:</h4>
            <?php
            displayUsers();
            ?>
        </div>  
        <div class="col-md-12">
            <div><br /></div>
            <hr>
            <br />
        </div>
    </div>
</div>

<div class="container-fluid"> 
    <div class="row my-2 py-2">    
        <div class="col-md-12">

            <?php
            /**
             * The user is already logged in, not allowed to register.
             */
            if (isset($_SESSION['regsuccess'])) {
                /* Registration was successful */
                if ($_SESSION['regsuccess']) {
                    echo "<h4>Registrado!</h4>";
                    if (EMAIL_WELCOME) {
                        echo "<p>Agrega el usuario: <b>" . $_SESSION['reguname'] . "</b>, Se le ha enviado un correo electr�nico de confirmación que debe llegar en breve. Por favor, confirme su registro antes de continuar.<br />Volver a <a href='../'>Principal</a>"
                        . "<a href='admin.php'>Agregar nuevo usuario</a>";
                    } else {
                        echo "<p>Agrega el usuario: <b>" . $_SESSION['reguname'] . "</b>, su información se ha añadido a la base de datos, "
                        . "usted puede ahora <a href=\"../index.php\">acceder</a>.</p>";
                    }
                }
                /* Registration failed */ else {
                    echo "<h4>Registracion Fallida</h4>";
                    echo "<p>Lo sentimos, pero ha habido un error y el registro para el Usuario <b>" . $_SESSION['reguname'] . "</b>, "
                    . "No se pudo completar. <br /> Por favor, inténtelo de nuevo en un momento posterior.</p>";
                }
                unset($_SESSION['regsuccess']);
                unset($_SESSION['reguname']);
            }
            /**
             * The user has not filled out the registration form yet.
             * Below is the page with the sign-up form, the names
             * of the input fields are important and should not
             * be changed.
             */ else {
                ?>                                      

                <h4>Add New User </h4>                                
                <?php
                if ($form->num_errors > 0) {
                    echo "<div><font size=\"2\" color=\"#ff0000\">" . $form->num_errors . " error(es) encontrados</font></div>";
                }
                ?>
                <form action="../process.php" method="POST">
                    <div class="row mb-3">

                        <label class="col-sm-3 col-form-label">Name:</label> 
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" type="text" name="name" maxlength="30" value="<?php echo $form->value("name"); ?>"><?php echo $form->error("name"); ?>
                        </div>

                    </div>
                    <div class="row mb-3">

                        <label class="col-sm-3 col-form-label">Username:</label> 
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"><?php echo $form->error("user"); ?>
                        </div>

                    </div>
                    <div class="row mb-3">

                        <label class="col-sm-3 col-form-label">Password:</label> 
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"><?php echo $form->error("pass"); ?>
                        </div>

                    </div>
                    <div class="row mb-3">

                        <label class="col-sm-3 col-form-label">Email:</label> 
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" type="text" name="email" maxlength="50" value="<?php echo $form->value("email"); ?>"><?php echo $form->error("email"); ?>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <input type="hidden" name="subjoin" value="1"><input class="button" type="submit" value="Add user!">
                        </div>
                    </div>
                </form>
                <?php
            }
            ?>

        </div>
        <?php
        /**
         * Update User Valid
         */
        ?>     
    </div>
</div>
<div class="container-fluid"> 
    <div class="row my-2 py-2">   
        <div class="col-md-12">
            <hr>
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#invuser" data-toggle="tab">Validate and Invalidate User</a></li>
                        <li class="nav-item"><a class="nav-link" href="#upduser" data-toggle="tab">Update User Level</a></li>
                        <li class="nav-item"><a class="nav-link" href="#deluser" data-toggle="tab">Delete User</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="invuser">
                            <h4>Validate and Invalidate User</h4>
                            <?php echo $form->error("valuser"); ?>
                            <form action="adminprocess.php" method="POST">
                                <p><b>Username:</b><br /> <input type="text" name="valuser" maxlength="30" value="<?php echo $form->value("valuser"); ?>"></p>
                                <p><b>Validate and Invalidate:</b><br />
                                    <select name="updvalid">
                                        <option value="1">Validate</option>
                                        <option value="0">Invalidate</option>                                                                                
                                    </select>
                                </p>
                                <input type="hidden" name="subupdvalid" value="1">
                                <input class="button" type="submit" value="Change state">
                            </form>
                        </div>
                        <div class="tab-pane" id="upduser">
                            <h4>Update User Level </h4>
                            <?php echo $form->error("upduser"); ?>
                            <form action="adminprocess.php" method="POST">
                                <p><b>Username:</b><br /> <input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>"></p>
                                <p><b>Level:</b><br />
                                    <select name="updlevel">
                                        <option value = "1"> Seller </option>
                                        <option value = "2"> Vendor Supervisor </option>
                                        <option value = "3"> Agent </option>
                                        <option value = "4"> Member Agent </option>
                                        <option value = "5"> Agent Supervisor </option>
                                        <option value = "7"> Manager </option>
                                        <option value = "9"> Administrator </option> 
                                    </select>
                                </p>
                                <input type="hidden" name="subupdlevel" value="1">
                                <input class="button" type="submit" value="Level">
                            </form>  
                        </div>
                        <div class="tab-pane" id="deluser">
                            <h4>Delete User</h4>
                            <?php echo $form->error("deluser"); ?>
                            <form action="adminprocess.php" method="POST">
                                <p><b>Username:</b><br /> <input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>"></p>
                                <input type="hidden" name="subdeluser" value="1">
                                <input class="button" type="submit" value="Delete User">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid"> 
    <div class="row my-2 py-2">  
        <div class="col-md-12">
            <hr>
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#deiuser" data-toggle="tab">Delete Inactive Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="#banuser" data-toggle="tab">Banned User</a></li>
                        <li class="nav-item"><a class="nav-link" href="#rebuser" data-toggle="tab">Remove Banned Users</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="deiuser">
                            <h4>Delete Inactive Users </h4>
                            <p>
                                This will remove all users (non-administrators), who have not logged into the site within a certain period of time. The days spent inactive are specified. </p>
                            <form action="adminprocess.php" method="POST">
                                <p><b>Dias:</b><br />
                                    <select name="inactdays">
                                        <option value="3">3</option>
                                        <option value="7">7</option>
                                        <option value="14">14</option>
                                        <option value="30">30</option>
                                        <option value="100">100</option>
                                        <option value="365">365</option>
                                    </select>
                                </p>
                                <input type="hidden" name="subdelinact" value="1">
                                <input class="button" type="submit" value="Eliminar todos inactivos">
                            </form>
                        </div>
                        <div class="tab-pane" id="banuser">
                            <h4>Banned User </h4><?php echo $form->error("banuser"); ?>
                            <p>Prohibit access to the system of a user. <br />
                                If you only want to restrict, or it is recommended to deactivate it in any case. </p>
                            <form action="adminprocess.php" method="POST">
                                <p><b>Username:</b><br /> <input type="text" name="banuser" maxlength="30" value="<?php echo $form->value("banuser"); ?>"></p>
                                <input type="hidden" name="subbanuser" value="1">
                                <input class="button" type="submit" value="Banned User">
                            </form>
                        </div>
                        <div class="tab-pane" id="rebuser">
                            <h4>Table of prohibited users for system :</h4>
                            <?php
                            displayBannedUsers();
                            ?>
                            <h4>Remove Banned Users </h4><?php echo $form->error("delbanuser"); ?>
                            <form action="adminprocess.php" method="POST">
                                <p><b>Username:</b><br /> <input type="text" name="delbanuser" maxlength="30" value="<?php echo $form->value("delbanuser"); ?>"></p>
                                <input type="hidden" name="subdelbanned" value="1">
                                <input class="button" type="submit" value="Eliminar usuario prohibido">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> 
</div>

