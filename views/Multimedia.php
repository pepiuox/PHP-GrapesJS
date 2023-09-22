<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $_GET['w'];
    if ($w === 'list') {
        ?>
        <div class='w-100'> 
            <p>
                <a class='button' href='dashboard.php?cms=multimedia&w=add'>Agregar Nueva Multimedia</a> 
            </p>
            <h3>Lista de Multimedia</h3>
            <?php
            echo "<table class='table' border=1 cellpadding=0 cellspacing=0 >";
            echo "<thead>";
            echo "<tr class=title>";
            echo "<th><b>Galeria</b></th>";
            echo "<th><b>Nombre</b></th>";
            echo "<th><b>Origen</b></th>";
            echo "<th><b>Id Video</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $result = $conn->query("SELECT * FROM multimedia_gal LEFT JOIN galleries ON multimedia_gal.galId=galleries.idGal") or trigger_error($conn->error);
            while ($row = $result->fetch_array()) {
                foreach ($row AS $key => $value) {
                    $row[$key] = $value;
                }
                echo "<tr>";
                echo "<td valign='top'>" . $row['gallery'] . "</td>";
                echo "<td valign='top'>" . $row['name'] . "</td>";
                echo "<td valign='top'>";
                if ($row['source'] == 0) {
                    echo 'youtube';
                } elseif ($row['source'] == 1) {
                    echo 'vimeo';
                } else {
                    echo 'daylimotion';
                }
                echo "</td>";
                echo "<td valign='top'>" . $row['idlink'] . "</td>";
                echo "<td valign='top'><a href='dashboard.php?cms=multimedia&w=view&id={$row['id']}'>Vista</a></td><td valign='top'><a href='dashboard.php?cms=multimedia&w=edit&id={$row['id']}'>Editar</a></td><td><a href='dashboard.php?cms=multimedia&w=delete&id={$row['id']}'>Eliminar</a></td> ";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr class=title>";
            echo "<th><b>Galeria</b></th>";
            echo "<th><b>Nombre</b></th>";
            echo "<th><b>Origen</b></th>";
            echo "<th><b>Id Video</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</tfoot>";
            echo "</table>";
            ?> 
        </div>
        <?php
    } elseif ($w === 'add') {
        ?> 
        <div class='w-100'>
            <?php
            if (isset($_POST['submitted'])) {
                $sql = "INSERT INTO `multimedia_gal` ( `galId` ,  `name` ,  `image` ,  `description_en`,  `description_es` ,  `source` ,  `idlink`  ) VALUES(  '{$_POST['galId']}' ,  '{$_POST['name']}' ,  '{$_POST['image']}' ,  '{$_POST['description_en']}',  '{$_POST['description_es']}' ,  '{$_POST['source']}' ,  '{$_POST['idlink']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Multimedia Agregada.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
            <p>
                <a class="btn btn-secondary" href='dashboard.php?cms=multimedia&w=list'>Retornar a la Lista</a> 
            </p>
            <h3>Agregar a Multimedia</h3> 
            <form action='' method='POST'> 
                <div class='col-md-6'><label class="form-label">Galeria:</label>
                    <?php
                    $sql1 = "SELECT * FROM galleries WHERE type = '2'";
                    $query1 = $conn->query($sql1);
                    ?>                    
                    <select class="form-select" name='galId' id='galId'>  
                        <option>Selecciona una galeria</option>                        
                        <?php while ($rs1 = mysqli_fetch_array($query1)) { ?>                                    
                            <option value="<?php echo $rs1['idGal']; ?>"><?php echo $rs1['gallery']; ?></option>
                            <?php
                        }
                        ?>
                    </select>   
                </div> 
                <div class='col-md-6'><label class="form-label">Título:</label><input type="text" class="form-control" name='name' id='name'/></div> 
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
                    <div class="w-100">
                        <input type="text" name='image' id='image' placeholder="Imagen Url" readonly />
                        <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                    </div>                   
                </div> 
                <div class='col-md-6'>
                    <div class='w-100'><label class="form-label">Descripción EN:</label>
                        <textarea class="form-control" name='description_en' id='description_en'></textarea>
                    </div> 
                    <div class='w-100'><label class="form-label">Descripción ES:</label>
                        <textarea class="form-control" name='description_es' id='description_es'></textarea>
                    </div> 
                    <div class='w-100'><label class="form-label">Origen:</label>
                        <select class="form-select" name='source' id='source'>
                            <?php
                            $acti = array("youtube", "vimeo", "daylimotion");
                            reset($acti);
                            while (list($key, $val) = each($acti)) {
                                ?>     
                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                <?php
                            }
                            ?>     
                        </select>
                    </div> 
                    <div class='w-100'><label class="form-label">Id Video:</label>
                        <input type="text" class="form-control" name='idlink' id='idlink'/>
                    </div>    
                </div>
                <div class='w-100'><input class="btn btn-primary" type='submit' value='Agregar Multimedia' /><input type='hidden' value='1' name='submitted' /></div> 
            </form> 
        </div>
        <?php
    } elseif ($w === 'edit') {
        ?> 
        <div class='w-100'>
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `multimedia_gal` WHERE `id` = '$id' "));
                if (isset($_POST['submitted'])) {
                    $sql = "UPDATE `multimedia_gal` SET  `galId` =  '{$_POST['galId']}' ,  `name` =  '{$_POST['name']}' ,  `image` =  '{$_POST['image']}' ,  `description_en` =  '{$_POST['description_en']}' ,,  `description_es` =  '{$_POST['description_es']}'  `source` =  '{$_POST['source']}' ,  `idlink` =  '{$_POST['idlink']}' WHERE `id` = '$id' ";
                    $conn->query($sql) or die($conn->error);
                    echo '<meta http-equiv="refresh" content="0">';
                }
                ?>
                <a class="btn btn-secondary" href='dashboard.php?cms=multimedia&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=multimedia&w=add'>Nuevo Multimedia</a> 
                <h3>Editar de multimedia</h3> 
                <form action='' method='POST'> 
                    <div class='col-md-6'><label class="form-label">Galeria:</label>
                        <select class="form-select" name='galId' id='galId'> 
                            <?php
                            $sql1 = "SELECT * FROM galleries WHERE type='2'";
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
                    <div class='col-md-6'>                        
                        <label class="form-label">Título:</label><input type="text" class="form-control" name='name' id='name' value='<?php echo $row['name']; ?>' />
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
                        <span>No hay imagen? Utilice el botón para seleccionar una!</span>
                        <div class="w-100">
                            <input type="text" name='image' id='image' placeholder="Imagen Url" value='<?php echo $row['image']; ?>' readonly />
                            <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                        </div>
                    </div>                    
                    <div class='col-md-6'>    
                        <div class='w-100'>
                            <label class="form-label">Descripción EN:</label>
                            <textarea class="form-control" name='description_en' id='description_en'><?php echo $row['description_en']; ?></textarea>
                        </div> 
                        <div class='w-100'>
                            <label class="form-label">Descripción ES:</label>
                            <textarea class="form-control" name='description_en' id='description_es'><?php echo $row['description_es']; ?></textarea>
                        </div> 
                        <div class='w-100'>
                            <label class="form-label">Origen:</label>                        
                            <select class="form-select" name='source' id='source'>
                                <?php
                                $acti = array("youtube", "vimeo", "daylimotion");
                                reset($acti);
                                foreach ($acti as $key) {
                                    $val = $key;
                                    if ($row['source'] == $key) {
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
                        <div class='w-100'>
                            <label class="form-label">Id Video:</label>
                            <input type="text" class="form-control" name='idlink' id='idlink' value='<?php echo $row['idlink']; ?>' />
                        </div> 
                    </div>
                    <div class='w-100'>
                        <input class="btn btn-primary" type='submit' value='Editar Fila' />
                        <input type='hidden' value='1' name='submitted' />
                    </div> 
                </form> 
            <?php } ?> 
        </div>
        <?php
    } elseif ($w === 'view') {
        ?>
        <div class='w-100'>
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
            }
            $row = mysqli_fetch_array($conn->query("SELECT * FROM multimedia_gal LEFT JOIN galleries ON multimedia_gal.galId=galleries.idGal WHERE `id` = '$id' "));
            ?>
            <a class="btn btn-secondary" href='dashboard.php?cms=multimedia&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=multimedia&w=add'>Nueva Fila</a> 
            <h3>Vista de multimedia_gal</h3> 
            <div class='w-100'> 
                <div class='col-md-6'>
                    <label class="form-label">Galeria:</label>
                    <?php echo $row['gallery']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Título:</label>
                    <?php echo $row['name']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Imagen:</label>
                    <img src="<?php echo $row['image']; ?>" width="220" /><
                    /div> 
                    <div class='col-md-6'>
                        <label class="form-label">Descripción:</label><?php echo $row['description']; ?>
                    </div> 
                    <div class='col-md-6'>
                        <label class="form-label">Origen:</label>
                        <?php
                        if ($row['source'] == 0) {
                            echo 'youtube';
                        } elseif ($row['source'] == 1) {
                            echo 'vimeo';
                        } else {
                            echo 'daylimotion';
                        }
                        ?>
                    </div> 
                    <div class='col-md-6'>
                        <label class="form-label">Id Video:</label>
                        <?php echo $row['idlink']; ?>
                    </div>                 
                </div> 
            </div>
            <?php
        } elseif ($w === 'delete') {
            ?> 
            <div class='w-100'> 
                <p>
                    <a class='button' href='dashboard.php?cms=multimedia&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=multimedia&w=add'>Nuevo multimedia</a> 
                </p>
                <h3>Eliminado de Multimedia</h3> 
                <?php
                $id = (int) $_GET['id'];
                $conn->query("DELETE FROM `multimedia_gal` WHERE `id` = '$id' ");
                echo ($database->affected_rows) ? "Fila Eliminada.<br /> " : "No se Elimino.<br /> ";
                ?> 
            </div>
            <?php
        }
    }
    ?>
