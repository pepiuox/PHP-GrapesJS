<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $_GET['w'];
    if ($w === 'list') {
        ?>
        <div class="container"> 
            <p>
                <a class='button' href='dashboard/profiles/add'>Nuevo perfil</a> 
            </p>
            <h3>Lista de perfiles</h3> 
            <?php
            echo "<table class='table' border=1 cellpadding=0 cellspacing=0 >";
            echo "<thead>";
            echo "<tr class=title>";
            echo "<th><b>IdPro</b></th>";
            echo "<th><b>IdUser</b></th>";
            echo "<th><b>First Name</b></th>";
            echo "<th><b>Last Name</b></th>";
            echo "<th><b>Age</b></th>";
            echo "<th><b>Gender</b></th>";
            echo "<th><b>Description EN</b></th>";
            echo "<th><b>Description ES</b></th>";
            echo "<th><b>Image</b></th>";
            echo "<th><b>Active</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $result = $conn->query("SELECT * FROM `my_info`") or trigger_error($conn->error);
            while ($row = $result->fetch_array()) {
                foreach ($row AS $key => $value) {
                    $row[$key] = stripslashes($value);
                }
                echo "<tr>";
                echo "<td valign='top'>" . $row['idPro'] . "</td>";
                echo "<td valign='top'>" . $row['idUser'] . "</td>";
                echo "<td valign='top'>" . $row['first_name'] . "</td>";
                echo "<td valign='top'>" . $row['last_name'] . "</td>";
                echo "<td valign='top'>" . $row['age'] . "</td>";
                echo "<td valign='top'>" . $row['gender'] . "</td>";
                echo "<td valign='top'>" . $row['description_en'] . "</td>";
                echo "<td valign='top'>" . $row['description_es'] . "</td>";
                echo "<td valign='top'>" . $row['image'] . "</td>";
                echo "<td valign='top'>" . $row['active'] . "</td>";
                echo "<td valign='top'><a href='dashboard/profiles/view&id={$row['idPro']}'>Vista</a></td><td valign='top'><a href='dashboard/profiles/edit&id={$row['idPro']}'>Editar</a></td><td><a href='dashboard/profiles/delete&id={$row['idPro']}'>Eliminar</a></td> ";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr class=title>";
            echo "<th><b>IdPro</b></th>";
            echo "<th><b>IdUser</b></th>";
            echo "<th><b>First Name</b></th>";
            echo "<th><b>Last Name</b></th>";
            echo "<th><b>Age</b></th>";
            echo "<th><b>Gender</b></th>";
            echo "<th><b>Description EN</b></th>";
            echo "<th><b>Description ES</b></th>";
            echo "<th><b>Image</b></th>";
            echo "<th><b>Active</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</tfoot>";
            echo "</table>";
            ?> 
        </div>
        <?php
    } elseif ($w === 'add') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_POST['submitted'])) {
                $sql = "INSERT INTO `my_info` ( `first_name` ,  `last_name` ,  `age` ,  `gender` ,  `description_en` ,  `description_es` ,  `image` ,  `active`  ) VALUES( ,  '{$_POST['first_name']}' ,  '{$_POST['last_name']}' ,  '{$_POST['age']}' ,  '{$_POST['gender']}' ,  '{$_POST['description']}' ,  '{$_POST['image']}' ,  '{$_POST['active']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Fila Agregada.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
            <p>
                <a class='button' href='dashboard/profiles/list'>Retornar a la Lista</a> 
            </p>
            <h3>Agregar a Perfil</h3> 
            <form action='' method='POST'>                 
                <div class='col-md-6'>
                    <label class="form-label">Nombres:</label><br /><input type="text" class="form-control" name='first_name' id='first_name'/>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Apellidos:</label><br /><input type="text" class="form-control" name='last_name' id='last_name'/>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Edad:</label><br /><input type="text" class="form-control" name='age' id='age'/>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Genero:</label><br /><input type="text" class="form-control" name='gender' id='gender'/>
                </div> 
                <div class="container">
                    <label class="form-label">Descripcion Ingles:</label>
                    <textarea class="form-control" name='description_en' id='description_en'></textarea>
                    <script>
                        CKEDITOR.replace('description_en', {
                            filebrowserBrowseUrl: 'elFinder/elfinder.html'
                        });
                    </script>
                </div> 
                <div class="container">
                    <label class="form-label">Descripcion Español:</label>
                    <textarea class="form-control" name='description_es' id='description_es'></textarea>
                    <script>
                        CKEDITOR.replace('description_es', {
                            filebrowserBrowseUrl: 'elFinder/elfinder.html'
                        });
                    </script>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Imagen:</label>
                    <input type="text" class="form-control" name='image' id='image'/>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Activo:</label>
                    <select class="form-select" name='active' id='active'>
                        <?php
                        $acti = array("No", "Si");
                        foreach ($acti as $key => $val) {
                            ?>     
                            <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                            <?php
                        }
                        ?>     
                    </select>
                </div> 
                <div class="container">
                    <input class="btn btn-primary" type='submit' value='Agregar Fila' />
                    <input type='hidden' value='1' name='submitted' />
                </div> 
            </form> 
        </div>
    <?php } elseif ($w === 'edit') {
        ?>
        <div class="container">
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $idPro = (int) $_GET['id'];
                if (isset($_POST['submitted'])) {
                    $sql = "UPDATE `my_info` SET  `idUser` =  '{$_POST['idUser']}' ,  `first_name` =  '{$_POST['first_name']}' ,  `last_name` =  '{$_POST['last_name']}' ,  `age` =  '{$_POST['age']}' ,  `gender` =  '{$_POST['gender']}' ,  `description_en` =  '{$_POST['description_en']}' ,  `description_es` =  '{$_POST['description_es']}' ,  `image` =  '{$_POST['image']}' ,  `active` =  '{$_POST['active']}'   WHERE `idPro` = '$idPro' ";
                    $conn->query($sql) or die($conn->error);
                    echo ($conn->affected_rows) ? "Fila Editada.<br />" : "No se hizo Cambios. <br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                $row = mysqli_fetch_assoc($conn->query("SELECT * FROM `my_info` WHERE `idPro` = '$idPro' "));
                ?>
                <p>
                    <a class='button' href='dashboard/profiles/list'>Retornar a la Lista</a> - <a class='button' href='dashboard/profiles/add'>Nuevo perfil</a> 
                </p>
                <h3>Editar de profile</h3> 
                <form action='' method='POST'>                     
                    <div class='col-md-6'>
                        <label class="form-label">First Name:</label>
                        <input type="text" class="form-control" name='first_name' id='first_name' value='<?php echo $row['first_name']; ?>' />
                    </div> 
                    <div class='col-md-6'>
                        <label class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name='last_name' id='last_name' value='<?php echo $row['last_name']; ?>' />
                    </div> 
                    <div class='col-md-6'>
                        <label class="form-label">Age:</label>
                        <input type="text" class="form-control" name='age' id='age' value='<?php echo $row['age']; ?>' />
                    </div> 
                    <div class='col-md-6'>
                        <label class="form-label">Gender:</label>
                        <input type="text" class="form-control" name='gender' id='gender' value='<?php echo $row['gender']; ?>' />
                    </div> 
                    <div class="container">
                        <label class="form-label">Description EN:</label>
                        <textarea class="form-control" name='description_en' id='description_en'><?php echo $row['description_en']; ?></textarea>
                        <script>
                            CKEDITOR.replace('description_en', {
                                filebrowserBrowseUrl: 'elFinder/elfinder.html'
                            });
                        </script>
                    </div>
                    <div class="container">
                        <label class="form-label">Description ES:</label>
                        <textarea class="form-control" name='description_es' id='description_es'><?php echo $row['description_es']; ?></textarea>
                        <script>
                            CKEDITOR.replace('description_es', {
                                filebrowserBrowseUrl: 'elFinder/elfinder.html'
                            });
                        </script>
                    </div>  
                    <div class='col-md-6'>
                        <label class="form-label">Image:</label>
                        <input type="text" class="form-control" name='image' id='image' value='<?php echo $row['image']; ?>' />
                    </div> 
                    <div class='col-md-6'>
                        <label class="form-label">Active:</label>
                        <input type="text" class="form-control" name='active' id='active' value='<?php echo $row['active']; ?>' />
                    </div> 
                    <div class="container">
                        <input class="btn btn-primary" type='submit' value='Editar Fila' />
                        <input type='hidden' value='1' name='submitted' />
                    </div> 
                </form> 
            <?php } ?> 
        </div> 
    <?php } elseif ($w === 'view') {
        ?>
        <div class="container">
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $idPro = (int) $_GET['id'];
            }
            $row = mysqli_fetch_assoc($conn->query("SELECT * FROM `my_info` WHERE `idPro` = '$idPro' "));
            ?>
            <p>
                <a class='button' href='dashboard/profiles/list'>Retornar a la Lista</a> - <a class='button' href='dashboard/profiles/add'>Nuevo perfil</a> 
            </p>
            <h3>Vista de profile</h3> 
            <div class="container">                 
                <div class='col-md-6'>
                    <label class="form-label">First Name:</label><?php echo $row['first_name']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Last Name:</label><?php echo $row['last_name']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Age:</label><?php echo $row['age']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Gender:</label><?php echo $row['gender']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Description:</label><?php echo $row['description_en']; ?>
                </div>
                <div class='col-md-6'>
                    <label class="form-label">Description:</label><?php echo $row['description_es']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Image:</label><?php echo $row['image']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Active:</label><?php echo $row['active']; ?>
                </div> 
            </div> 
        </div
        </div>
    <?php } elseif ($w === 'delete') {
        ?>
        <div class="container">
            <p>
                <a class='button' href='dashboard/profiles/list'>Retornar a la Lista</a> - <a class='button' href='dashboard/profiles/add'>Nuevo perfil</a> 
            </p>
            <h3>Eliminado de profile</h3> 
            <?php
            $idPro = (int) $_GET['id'];
            $conn->query("DELETE FROM `my_info` WHERE `idPro` = '$idPro' ");
            echo ($conn->affected_rows) ? "Fila Eliminada.<br /> " : "No se Elimino.<br /> ";
            ?> 
        </div>
        <?php
    }
}
?>
