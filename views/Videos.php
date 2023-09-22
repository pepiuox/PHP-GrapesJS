<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $_GET['w'];

    if ($w === 'list') {
        ?>
        <div class='w-100'> 
            <p>
                <a class='button' href='dashboard.php?cms=videos&w=add'>Nuevo Video</a> 
            </p>
            <h3>Lista de videos</h3> 

            <?php
            echo '<table class="table" border=1 cellpadding=0 cellspacing=0 >' . "\n";
            echo "<thead>";
            echo '<tr class="title">';
            echo "<th><b>Paginá</b></th>";
            echo "<th><b>Título</b></th>";
            echo "<th><b>Imagen</b></th>";
            echo "<th><b>Origen</b></th>";
            echo "<th><b>Id Video</b></th>";
            echo "<th><b>Activo</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $result = $conn->query("SELECT * FROM `videos` LEFT JOIN (SELECT id, title AS tp FROM page) `page` ON videos.idVd=page.id") or trigger_error($conn->error);
            while ($row = $result->fetch_array()) {
                foreach ($row AS $key => $value) {
                    $row[$key] = $value;
                }
                echo "<tr>";
                echo "<td valign='top'>" . $row['tp'] . "</td>";
                echo "<td valign='top'>" . $row['title'] . "</td>";
                echo "<td valign='top'><img src='" . $row['image'] . "' width='120px'/></td>";
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
                echo "<td valign='top'>";
                if ($row['active'] == 1) {
                    echo 'Si';
                } else {
                    echo 'No';
                }
                echo "</td>";
                echo "<td valign='top'><a href='" . B_URL . "index.php?page={$row['idVd']}' target='_blank'>Vista</a></td><td valign='top'><a href='dashboard.php?cms=videos&w=edit&idVd={$row['idVd']}'>Editar</a></td><td><a href='dashboard.php?cms=videos&w=delete&idVd={$row['idVd']}'>Eliminar</a></td> ";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo '<tr class="title">';
            echo "<th><b>Página</b></th>";
            echo "<th><b>Título</b></th>";
            echo "<th><b>Imagen</b></th>";
            echo "<th><b>Origen</b></th>";
            echo "<th><b>Id Video</b></th>";
            echo "<th><b>Activo</b></th>";
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
                $sql = "INSERT INTO `videos` ( `pageId` ,  `title` ,  `image` ,  `description_en` ,  `description_es`,  `source` ,  `idlink` ,  `active`  ) VALUES(  '{$_POST['pageId']}' ,  '{$_POST['title']}' ,  '{$_POST['image']}' ,  '{$_POST['description_en']}',  '{$_POST['description_es']}' ,  '{$_POST['source']}' ,  '{$_POST['idlink']}' ,  '{$_POST['active']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Video Agregada.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
            <p>
                <a class='button' href='dashboard.php?cms=videos&w=list'>Retornar a la Lista</a> 
            </p>
            <h3>Agregar Video</h3> 

            <form action='' method='POST'> 
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
                    <div class='w-100'>
                        <input type="text" name='image' id='image' placeholder="Imagen Url" readonly />
                        <input type="submit" id="imageUpload" value='Seleccionar Imagen' />
                    </div>                        
                </div> 
                <div class='col-md-6'>
                    <div class='w-100'><label class="form-label">Paginá:</label>
                        <?php
                        $stp1 = "SELECT * FROM page";
                        $quertp1 = $conn->query($stp1);
                        ?> 
                        <select class="form-select" name='pageId' id='pageId'/>                             
                        <option>-- Selecciona Página -- </option>
                        <?php
                        while ($tp1 = mysqli_fetch_array($quertp1)) {
                            ?>     
                            <option value="<?php echo $tp1['id']; ?>"><?php echo $tp1['title']; ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div> 
                    <div class='w-100'>
                        <label class="form-label">Título:</label>
                        <input type="text" class="form-control" name='title' id='title'/>
                    </div>                 
                    <div class='w-100'>
                        <label class="form-label">Descripción EN:</label>
                        <textarea class="form-control" name='description_en' id='description_en'></textarea>
                    </div> 
                    <div class='w-100'>
                        <label class="form-label">Descripción ES:</label>
                        <textarea class="form-control" name='description_es' id='description_es'></textarea>
                    </div> 
                    <div class='w-100'>
                        <label class="form-label">Origen:</label>
                        <select class="form-select" name='source' id='source'>
                            <?php
                            $vacti = array("youtube", "vimeo", "daylimotion");
                            reset($vacti);
                            foreach ($vacti as $key) {
                                $val = $key;
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
                    <div class='w-100'>
                        <label class="form-label">Activo:</label>
                        <select class="form-select" name='active' id='active'>
                            <?php
                            $acti = array("No", "Si");
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
                </div> 
                <div class='w-100'>
                    <input class="btn btn-primary" type='submit' value='Agregar Video' />
                    <input type='hidden' value='1' name='submitted' />
                </div> 
            </form> 
        </div>
        <?php
    } elseif ($w === 'edit') {
        ?> 
        <div class='w-100'> 
            <?php
            if (isset($_GET['idVd']) && !empty($_GET['idVd'])) {
                $idVd = (int) $_GET['idVd'];
                if (isset($_POST['submitted'])) {
                    $sql = "UPDATE `videos` SET  `pageId` =  '{$_POST['pageId']}' ,  `title` =  '{$_POST['title']}' ,  `image` =  '{$_POST['image']}' ,  `description_en` =  '{$_POST['description_en']}',  `description_es` =  '{$_POST['description_es']}' ,  `source` =  '{$_POST['source']}' ,  `idlink` =  '{$_POST['idlink']}' ,  `active` =  '{$_POST['active']}'   WHERE `idVd` = '$idVd' ";
                    $conn->query($sql) or die($conn->error);
                    echo "Video Editado.<br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `videos` WHERE `idVd` = '$idVd' "));
                ?>
                <a class='button' href='dashboard.php?cms=videos&w=list'>Retornar a la Lista</a> - <a class='button' href='dashboard.php?cms=videos&w=add'>Nueva Video</a> 
                <h3>Editar de video</h3> 
                <form action='' method='POST'> 
                    <div class='col-md-6'>
                        <label class="form-label">Imagen de página:</label>  
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
                        <div class='w-100'>
                            <input type="text" name='image' id='image' placeholder="Imagen Url" value='<?php echo $row['image']; ?>' readonly/>                            
                            <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                        </div>                       
                    </div> 
                    <div class='col-md-6'>
                        <div class='w-100'><label class="form-label">Paginá:</label>
                            <?php
                            $sqp1 = "SELECT * FROM page";
                            $queryp1 = $conn->query($sqp1);
                            ?> 
                            <select class="form-select" name='pageId' id='pageId'>                            
                                <option>Selecciona una página </option>
                                <?php
                                while ($rp1 = mysqli_fetch_array($queryp1)) {
                                    if ($rp1['id'] == $row['pageId']) {
                                        ?>     
                                        <option value="<?php echo $rp1['id']; ?>" selected><?php echo $rp1['title']; ?></option>
                                        <?php
                                    } else {
                                        ?>     
                                        <option value="<?php echo $rp1['id']; ?>"><?php echo $rp1['title']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div> 
                        <div class='w-100'>
                            <label class="form-label">Título:</label>
                            <input type="text" class="form-control" name='title' id='title' value='<?php echo $row['title']; ?>' />
                        </div>                    
                        <div class='w-100'>
                            <label class="form-label">Descripción EN:</label>
                            <textarea class="form-control" name='description_en' id='description_en'><?php echo $row['description_en']; ?></textarea>
                        </div> 
                        <div class='w-100'>
                            <label class="form-label">Descripción ES:</label>
                            <textarea class="form-control" name='description_es' id='description_es'><?php echo $row['description_es']; ?></textarea>
                        </div> 
                        <div class='w-100'>
                            <label class="form-label">Origen:</label>
                            <select class="form-select" name='source' id='source'>
                                <?php
                                $vacti = array("youtube", "vimeo", "daylimotion");
                                reset($vacti);
                                foreach ($vacti as $key) {
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
                        <div class='w-100'>
                            <label class="form-label">Activo:</label>
                            <select class="form-select" name='active' id='active'>
                                <?php
                                $acti = array("No", "Si");

                                reset($acti);

                                foreach ($acti as $key) {
                                    $val = $key;
                                    if ($row['active'] == $key) {
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
                    </div>
                    <div class='w-100'>
                        <input class='button' type='submit' value='Editar Video' />
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
            if (isset($_GET['idVd']) && !empty($_GET['idVd'])) {
                $idVd = (int) $_GET['idVd'];
            }
            $row = mysqli_fetch_array($conn->query("SELECT * FROM `videos` WHERE `idVd` = '$idVd' "));
            ?>
            <p>
                <a class='button' href='dashboard.php?cms=videos&w=list'>Retornar a la Lista</a> - <a class='button' href='dashboard.php?cms=videos&w=add'>Nueva Video</a> 
            </p>
            <h3>Vista de videos</h3> 
            <div class='w-100'> 
                <div class='col-md-6'>
                    <label class="form-label">Paginá:</label><?php echo $row['pageId']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Título:</label><?php echo $row['title']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Imagen:</label><?php echo $row['image']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Descripción:</label><?php echo $row['description']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Origen:</label><?php echo $row['source']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Id Video:</label><?php echo $row['idlink']; ?>
                </div> 
                <div class='col-md-6'>
                    <label class="form-label">Activo:</label><?php echo $row['active']; ?>
                </div> 
            </div> 
        </div>
        <?php
    } elseif ($w === 'delete') {
        ?> 

        <div class='w-100'>
            <p>
                <a class='button' href='dashboard.php?cms=videos&w=list'>Retornar a la Lista</a> - <a class='button' href='dashboard.php?cms=videos&w=add'>Nueva video</a> 
            </p>
            <h3>Eliminado el video</h3> 

            <?php
            $idVd = (int) $_GET['idVd'];
            $conn->query("DELETE FROM `videos` WHERE `idVd` = '$idVd' ");
            echo "Video Eliminado.<br /> ";
            ?> 

            <?php
        }
    }
    
