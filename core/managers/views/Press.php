<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    if (isset($_GET['w']) && !empty($_GET['w'])) {
        $w = $_GET['w'];
        if ($w === 'list') {
?>
                    <div class="container"> 
                        <a class="btn btn-secondary" href='dashboard/press/add'>Nueva Publicacion</a> 
                        <h3>Lista de Publicaciones</h3> 
            <?php
            echo "<table class='table' border=1 cellpadding=0 cellspacing=0 >";
            echo "<thead>";
            echo "<tr class=title>";
            echo "<th><b>Galeria</b></th>";
            echo "<th><b>Título</b></th>";
            echo "<th><b>Sub Título</b></th>";
            echo "<th><b>Fecha de publicacion</b></th>";
            echo "<th><b>Tipo de publicacion</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $result = $conn->query("SELECT * FROM `press_gal`, `galleries` WHERE press_gal.galId=galleries.idGal") or trigger_error($conn->error);
            while ($row = $result->fetch_array()) {
                foreach ($row AS $key => $value) {
                    $row[$key] = $value;
                }
                echo "<tr>";
                echo "<td valign='top'>" . $row['gallery'] . "</td>";
                echo "<td valign='top'>" . $row['title'] . "</td>";
                echo "<td valign='top'>" . $row['subtitle'] . "</td>";
                echo "<td valign='top'>" . $row['printing_date'] . "</td>";
                echo "<td valign='top'>";
                if ($row['type_press'] == 0) {
                    echo "Entrevista";
                } else if ($row['type_press'] == 1) {
                    echo "Articulo";
                } else {
                    echo "Catalogo";
                }
                echo "</td>";
                echo "<td valign='top'><a href='dashboard/press/view&idPr={$row['idPr']}'>Vista</a></td><td valign='top'><a href='dashboard/press/edit&idPr={$row['idPr']}'>Editar</a></td><td><a href='dashboard/press/delete&idPr={$row['idPr']}'>Eliminar</a></td> ";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr class=title>";
            echo "<th><b>Galeria</b></th>";
            echo "<th><b>Título</b></th>";
            echo "<th><b>Sub Título</b></th>";
            echo "<th><b>Fecha de publicacion</b></th>";
            echo "<th><b>Tipo de publicacion</b></th>";
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
                $sql = "INSERT INTO `press_gal` ( `galId` ,  `image` , `title` ,  `subtitle` ,  `description` ,  `printing_date` ,  `type_press`  ) VALUES(  '{$_POST['galId']}' ,  '{$_POST['image']}',  '{$_POST['title']}' ,  '{$_POST['subtitle']}' ,  '{$_POST['description']}' ,  '{$_POST['printing_date']}' ,  '{$_POST['type_press']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Publicacion Agregada.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
                        <p>
                            <a class="btn btn-secondary" href='dashboard/press/list'>Retornar a la Lista</a> 
                        </p>
                        <h3>Agregar a Publicacion</h3> 
                        <form action='' method='POST'>  
                            <div class='col-md-6'><label class="form-label">Galeria:</label>
            <?php
            $sql1 = "SELECT * FROM galleries WHERE type = '3'";
            $query1 = $conn->query($sql1);
            ?>                    
                                <select class="form-select" name='galId' id='galId'>  
                                    <option>Selecciona una galeria</option>                        
            <?php while ($rs1 = mysqli_fetch_array($query1)) { ?>                                    
                                            <option value="<?php echo $rs1['id']; ?>"><?php echo $rs1['gallery']; ?></option>
                <?php
            }
                ?>
                                </select>
                            </div> 
                            <div class='col-md-6'><label class="form-label">Imagen:</label>
                                <script src="<?php echo SYST; ?>js/jquery.popupwindow.js" type="text/javascript"></script> 
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('#imageUpload').on('click', function (event) {
                                            event.preventDefault();
                                            $.popupWindow('elfinder/elfinder.html', {
                                                height: 420,
                                                width: 750
                                            });
                                        });
                                    });
                                    function processFile(file) {
                                        $('#picture').html('<img src="' + file + '" />');
                                        $('#image').val(file);
                                    }
                                </script> 
                                <div id="picture">
                                    <span>No hay imagen? Utilice el botón para seleccionar una!</span>
                                </div>
                                <div class="container">
                                    <input type="text" name='image' id='image' placeholder="Imagen Url" readonly />
                                    <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                                </div>                    
                            </div> 
                            <div class='col-md-6'>
                                <label class="form-label">Título:</label>
                                <input type="text" class="form-control" name='title' id='title'/>
                            </div> 
                            <div class='col-md-6'>
                                <label class="form-label">Sub Título:</label>
                                <input type="text" class="form-control" name='subtitle' id='subtitle'/>
                            </div> 
                            <div class="container">
                                <label class="form-label">Descripción:</label>
                                <textarea class="form-control" name='description' id='description'></textarea>
                            </div> 
                            <div class='col-md-6'>
                                <label class="form-label">Fecha de publicacion:</label>
                                <input type="text" class="form-control" name='printing_date' id='printing_date'/>
                            </div> 
                            <div class='col-md-6'>
                                <label class="form-label">Tipo de publicacion:</label>
                                <select class="form-select" name='type_press' id='type_press'>
            <?php
            $acti = array("Entrevista", "Articulo", "Catalogo");
            reset($acti);
            foreach ($acti as $key) {
                $val = $key;
            ?>     
                                            <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                <?php
            }
                ?>     
                                </select>
                            </div> 
                            <div class="container">
                                <input class="btn btn-primary" type='submit' value='Agregar Publicacion' />
                                <input type='hidden' value='1' name='submitted' />
                            </div> 
                        </form> 
                    </div>
            <?php
        } elseif ($w === 'edit') {
            ?> 
                    <div class="container"> 
            <?php
            if (isset($_GET['idPr']) && !empty($_GET['idPr'])) {
                $idPr = (int) $_GET['idPr'];
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `press_gal` WHERE `idPr` = '$idPr' "));
                if (isset($_POST['submitted'])) {
                    $sql = "UPDATE `press_gal` SET  `galId` =  '{$_POST['galId']}' ,  `image` =  '{$_POST['image']}' , `title` =  '{$_POST['title']}' ,  `subtitle` =  '{$_POST['subtitle']}' ,  `description` =  '{$_POST['description']}' ,  `printing_date` =  '{$_POST['printing_date']}' ,  `type_press` =  '{$_POST['type_press']}'   WHERE `idPr` = '$idPr' ";
                    $conn->query($sql) or die($conn->error);
                    echo "Publicacion actualizado.<br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
            ?>
                                <p>
                                    <a class="btn btn-secondary" href='dashboard/press/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/press/add'>Nueva Fila</a> 
                                </p>
                                <h3>Editar de Publicacion</h3> 
                                <form action='' method='POST'> 
                                    <div class='col-md-6'>
                                        <div class="container">
                                            <label class="form-label">Galeria:</label>
                                            <select class="form-select" name='galId' id='galId'> 
                <?php
                $sql1 = "SELECT * FROM galleries WHERE type='3'";
                $query1 = $conn->query($sql1);
                ?>  
                                                <option>-- Seleccione galeria --</option>                            
                <?php
                while ($rs1 = mysqli_fetch_array($query1)) {
                    if ($rs1['idGal'] == $row['galId']) {
                ?>     
                                                                <option value="<?php echo $rs1['idGal']; ?>" selected><?php echo $rs1['gallery']; ?></option>
                        <?php
                    } else {
                        ?>     
                                                                <option value="<?php echo $rs1['idGal']; ?>"><?php echo $rs1['gallery']; ?></option>
                        <?php
                    }
                }
                        ?>
                                            </select>
                                        </div> 
                                        <div class="container">
                                            <label class="form-label">Título:</label>
                                            <input type="text" class="form-control" name='title' id='title' value='<?php echo $row['title']; ?>' />
                                        </div> 
                                        <div class="container">
                                            <label class="form-label">Sub Título:</label>
                                            <input type="text" class="form-control" name='subtitle' id='subtitle' value='<?php echo $row['subtitle']; ?>' />
                                        </div> 
                                    </div> 
                                    <div class='col-md-6'><label class="form-label">Imagen:</label>
                                        <script src="<?php echo SYST; ?>js/jquery.popupwindow.js" type="text/javascript"></script> 
                                        <script type="text/javascript">
                                        $(document).ready(function () {
                                            $('#imageUpload').on('click', function (event) {
                                                event.preventDefault();
                                                $.popupWindow('elfinder/elfinder.html', {
                                                    height: 420,
                                                    width: 750
                                                });
                                            });
                                        });
                                        function processFile(file) {
                                            $('#picture').html('<img src="' + file + '" />');
                                            $('#image').val(file);
                                        }
                                        </script>
                                        <div id="picture">
                                            <img class="scale" src="<?php echo $row['image']; ?>" />                            
                                        </div>
                                        <div class="container">
                                            <input type="text" name='image' id='image' placeholder="Imagen Url" value='<?php echo $row['image']; ?>' readonly/>                            
                                            <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                                        </div>       
                                    </div>                         
                                    <div class="container">
                                        <label class="form-label">Descripción:</label>
                                        <textarea class="form-control" name='description' id='description'><?php echo $row['description']; ?></textarea>
                                    </div> 
                                    <script>
                                        CKEDITOR.replace('description', {
                                            filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserUploadUrl: 'elfinder/elfinder.html',
                                            filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                            imageUploadUrl: 'elfinder/elfinder.html'
                                        });
                                    </script>
                                    <div class='col-md-6'>
                                        <label class="form-label">Fecha de publicacion:</label>
                                        <input type="text" class="form-control" name='printing_date' id='printing_date' value='<?php echo $row['printing_date']; ?>' />
                                    </div> 
                                    <div class='col-md-6'>
                                        <label class="form-label">Tipo de publicacion:</label>
                                        <select class="form-select" name='type_press' id='type_press'>
                <?php
                $acti = array("Entrevista", "Articulo", "Catalogo");
                reset($acti);
                while (list($key, $val) = each($acti)) {
                    if ($key == $row['type_press']) {
                ?>     
                                                            <option value="<?php echo $key; ?>" selected><?php echo $val; ?></option>
                        <?php
                    } else {
                        ?>     
                                                            <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                        <?php
                    }
                }
                        ?>     
                                        </select>
                                    </div> 
                                    <div class="container">
                                        <input class="btn btn-secondary" type='submit' value='Editar Publicacion' />
                                        <input type='hidden' value='1' name='submitted' />
                                    </div> 
                                </form> 
            <?php } ?> 
                    </div>
            <?php
        } elseif ($w === 'delete') {
            ?> 
                    <div class="container"> 
                        <p>
                            <a class="btn btn-secondary" href='dashboard/press/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/press/add'>Nueva Fila</a> 
                        </p>
                        <h3>Eliminado de Publicacion</h3> 
            <?php
            $idPr = (int) $_GET['idPr'];
            $conn->query("DELETE FROM `press_gal` WHERE `idPr` = '$idPr' ");
            echo ($conn->affected_rows) ? "Publicacion Eliminada.<br /> " : "No se Elimino.<br /> ";
            ?> 
                    </div>
            <?php
        }
    }
}
            ?>
