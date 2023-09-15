<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
        $w = $_GET['w'];

        if ($w === 'list') {
            ?>
          
                    <div class='w-100'> 
                        <p>
                            <a class="btn btn-secondary" href='dashboard.php?cms=images&w=add'>Agregar Nueva Imagen</a> 
                        </p>
                        <h3>Lista de Imagenes en Galerias</h3>

                        <?php
                        echo "<table class='table' border=1 cellpadding=0 cellspacing=0 > \n";
                        echo "<thead> \n";
                        echo "<tr class=title> \n";
                        echo "<th><b>Galeria</b></th> \n";
                        echo "<th><b>Imagen</b></th>  \n";
                        echo "<th></th><th></th><th></th> \n";
                        echo "</tr> \n";
                        echo "</thead> \n";
                        echo "<tbody> \n";
                        $result = $database->query("SELECT * FROM `image_gal` LEFT JOIN galleries ON image_gal.galId=galleries.idGal") or trigger_error($database->error);
                        while ($row = $result->fetch_array()) {
                            foreach ($row AS $key => $value) {
                                $row[$key] = $value;
                            }
                            echo "<tr> \n";
                            echo "<td valign='top' style='height:110px'>" . $row['gallery'] . "</td> \n";
                            echo "<td valign='top' style='height:110px'><img src='" . $row['image'] . "' style='height:110px' /></td> \n";
                            echo "<td valign='top' style='height:110px'><a href='dashboard.php?cms=images&w=view&id={$row['id']}'>Vista</a></td><td valign='top' style='height:110px'><a href='dashboard.php?cms=images&w=edit&id={$row['id']}'>Editar</a></td><td style='height:110px'><a href='dashboard.php?cms=images&w=delete&id={$row['id']}'>Eliminar</a></td> \n";
                            echo "</tr> \n";
                        }
                        echo "</tbody> \n";
                        echo "<tfoot> \n";
                        echo "<tr class=title> \n";
                        echo "<th><b>Galeria</b></th> \n";
                        echo "<th><b>Imagen</b></th> \n";
                        echo "<th></th><th></th><th></th> \n";
                        echo "</tr> \n";
                        echo "</tfoot> \n";
                        echo "</table> \n";
                        ?> 
                    </div>

                
            <?php
        } elseif ($w === 'add') {
            ?> 
           
                    <div class='w-100'> 
                        <?php
                        if (isset($_POST['submitted'])) {
                            $sql = "INSERT INTO `image_gal` ( `galId` ,  `image` ,  `caption_en` ,`caption_es` ,`paypal_code`,  `link`   ) VALUES(  '{$_POST['galId']}' ,  '{$_POST['image']}' ,  '{$_POST['caption_en']}',  '{$_POST['caption_es']}' ,'{$_POST['paypal_code']}',  '{$_POST['link']}'   ) ";
                            $database->query($sql) or die($database->error);
                            echo "Imagen Agregada.<br />\n";
                            echo '<meta http-equiv="refresh" content="0">';
                        }
                        ?>
                        <p>
                            <a class="btn btn-secondary" href='dashboard.php?cms=images&w=list'>Retornar a la Lista</a> 
                        </p>
                        <h3>Agregar una Imagen</h3> 

                        <form action='' method='POST'> 
                            <div class="col-md-6">
                                <div class='w-100'><label class="form-label">Galeria:</label>               
                                    <?php
                                    $sql1 = "SELECT * FROM galleries WHERE type = '1'";
                                    $query1 = $database->query($sql1);
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
                                <div class='w-100'><label class="form-label">Codigo Paypal:</label><textarea class="form-control" name='paypal_code' id='paypal_code'></textarea></div>
                                <div class='w-100'><label class="form-label">Link:</label><input type="text" class="form-control" name='link' id='link'/></div>                     
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
                                    <img class="scale" src="" />                        
                                </div>
                                <span>No hay imagen? Utilice el botón para seleccionar una!</span>
                                <div class="w-100">
                                    <input type="text" name='image' id='image' placeholder="Imagen Url" readonly />
                                    <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                                </div>                       
                            </div>                     
                            <div class='w-100'><label class="form-label">Descripción EN:</label><textarea class="form-control" name='caption_en' id='caption_en'></textarea>
                                <script>
                                    CKEDITOR.replace('caption_en', {
                                        filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                        filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                        filebrowserUploadUrl: 'elfinder/elfinder.html',
                                        filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                        imageUploadUrl: 'elfinder/elfinder.html'
                                    });
                                </script>
                            </div> 
                            <div class='w-100'><label class="form-label">Descripción ES:</label><textarea class="form-control" name='caption_es' id='caption_es'></textarea>
                                <script>
                                    CKEDITOR.replace('caption_es', {
                                        filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                        filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                        filebrowserUploadUrl: 'elfinder/elfinder.html',
                                        filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                        imageUploadUrl: 'elfinder/elfinder.html'
                                    });
                                </script>
                            </div>
                            <div class='w-100'><input class="btn btn-primary" type='submit' value='Agregar Imagen' /><input type='hidden' value='1' name='submitted' /></div> 

                        </form> 
                    </div>

                
            <?php
        } elseif ($w === 'edit') {
            ?> 
           
                    <div class='w-100'> 
                        <?php
                        if (isset($_GET['id']) && !empty($_GET['id'])) {
                            $id = (int) $_GET['id'];
                            if (isset($_POST['submitted'])) {
                                $sql = "UPDATE `image_gal` SET  `galId` =  '{$_POST['galId']}' ,  `image` =  '{$_POST['image']}' ,  `caption_en` =  '{$_POST['caption_en']}' ,  `caption_es` =  '{$_POST['caption_es']}', `paypal_code` =  '{$_POST['paypal_code']}', `link` =  '{$_POST['link']}'  WHERE `id` = '$id' ";
                                $database->query($sql) or die($database->error);
                                printf("Imagen Editada.\n", $database->affected_rows);
                                echo '<meta http-equiv="refresh" content="0">';
                            }
                            $row = mysqli_fetch_array($database->query("SELECT * FROM `image_gal` WHERE `id` = '$id' "));
                            ?>
                            <p>
                                <a class="btn btn-secondary" href='dashboard.php?cms=images&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=images&w=add'>Nueva Imagen</a> 
                            </p>
                            <h3>Editar de Imagen para galeria</h3> 

                            <form action='' method='POST'> 
                                <div class="col-md-6">   
                                    <div class='w-100'><label class="form-label">Galeria:</label>
                                        <select class="form-select" name='galId' id='galId'> 
                                            <?php
                                            $sql1 = "SELECT * FROM galleries";
                                            $query1 = $database->query($sql1);
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
                                    <div class='w-100'><label class="form-label">Codigo Paypal:</label>
<textarea class="form-control" name='paypal_code' id='paypal_code'><?php echo $row['paypal_code']; ?></textarea></div>
                                    <div class='w-100'><label class="form-label">Link:</label>
<input type="text" class="form-control" name='link' id='link' value='<?php echo $row['link']; ?>' /></div>                         
                                </div>
                                <div class='col-md-6'><label class="form-label">Imagen de página:</label>
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
                                    <div id="w-100">
                                        <img class="img-fluid" src="<?php echo $row['image']; ?>" />                            
                                    </div>
                                    <span>Selecciona una imagen si desea cambiar la imagen? Utilice el botón para seleccionar una!</span>
                                    <div class="w-100">
                                        <input type="text" name='image' id='image' value='<?php echo $row['image']; ?>' readonly />
                                        <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                                    </div>                        
                                </div>   

                                <div class='w-100'><label class="form-label">Descripción EN:</label><textarea class="form-control" name='caption_en' id='caption_en'><?php echo $row['caption_en']; ?></textarea>                                                          
                                    <script>
                                        CKEDITOR.replace('caption_en', {
                                            filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserUploadUrl: 'elfinder/elfinder.html',
                                            filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                            imageUploadUrl: 'elfinder/elfinder.html'
                                        });
                                    </script>
                                </div> 
                                <div class='w-100'><label class="form-label">Descripción ES:</label><textarea class="form-control" name='caption_es' id='caption_es'><?php echo $row['caption_es']; ?></textarea>
                                    <script>
                                        CKEDITOR.replace('caption_es', {
                                            filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserUploadUrl: 'elfinder/elfinder.html',
                                            filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                            imageUploadUrl: 'elfinder/elfinder.html'
                                        });
                                    </script>
                                </div>
                                <div class='w-100'><input class="btn btn-primary" type='submit' value='Editar Imagen' /><input type='hidden' value='1' name='submitted' /></div> 

                            </form> 

                        <?php } ?> 
                    </div>

                
            <?php
        } elseif($w ==='view'){
            ?>
 
 <div class='w-100'> 
                <?php
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = (int) $_GET['id'];
                }
                $row = mysqli_fetch_array($database->query("SELECT * FROM `image_gal` LEFT JOIN galleries ON image_gal.galId=galleries.idGal WHERE `id` = '$id' "));
                ?>
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=images&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=images&w=add'>Nueva Imagen</a> 
                </p>
                <h3>Vista de imagenes</h3> 

                <div class='w-100'>
<div class="row">
                    <div class='col-md-6'>
                        <label class="form-label">Galeria : <?php echo $row['gallery']; ?></label>                                  
                    </div>
                    <div class='w-100'>
                        <label class="form-label">Imagen:</label>
                        <img class="img-fluid" src="<?php echo $row['image']; ?>"/>
                    </div>                 
                    <div class='col-md-6'>
                        <label class="form-label">Descripción:</label>
                        <p>
                            <?php echo $row['caption_en']; ?>    
                        </p>
                    </div> 
</div> 
                </div> 
            </div>
 
<?php
        }elseif ($w === 'delete') {
            ?> 
           
                    <div class='w-100'> 
                        <p>
                            <a class="btn btn-secondary" href='dashboard.php?cms=images&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=images&w=add'>Nueva Fila</a> 
                        </p>
                        <h3>Eliminado de image_gal</h3> 

                        <?php
                        $id = (int) $_GET['id'];
                        $database->query("DELETE FROM `image_gal` WHERE `id` = '$id' ");
                        echo "Imagen Eliminada.<br /> ";
                        ?> 

                    </div>
               
            <?php
        }
    }
  