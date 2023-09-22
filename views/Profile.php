<div class="w-100"> 
    <h3>Editar de Perfil Visual</h3> 
    <?php
    $row = mysqli_fetch_array($conn->query("SELECT * FROM `my_info` WHERE active = '1' AND `idPro` = '1' "));
    if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {
        foreach ($row AS $key => $value) {
            $row[$key] = stripslashes($value);
        }
        $fn = $_POST['first_name'];
        $ln = $_POST['last_name'];
        $ag = $_POST['age'];
        $gd = $_POST['gender'];
        $dn = $_POST['description_en'];
        $ds = $_POST['description_es'];
        $im = $_POST['image'];
        $ac = $_POST['active'];
        $sql = "UPDATE `my_info` SET  `first_name` =  '$fn' ,  `last_name` =  '$ln' ,  `age` =  '$ag' ,  `gender` =  '$gd', `description_en` =  '$dn', `description_es` =  '$ds', `image` =  '$im', `active` =  '$ac'  WHERE idPro = '1' ";
        $conn->query($sql);
        if ($database->connect_errno) {
            printf("Error en actualizar: %s\n", $database->connect_error);
            exit();
        } else {
            echo 'Se edito el perfil';
        }
        echo '<meta http-equiv="refresh" content="0">';
    }
    ?>                      
    <form action='' method='POST'>                     
        <div class='col-md-6'>
            <label class="form-label">Nombres:</label>
            <input type="text" class="form-control" name='first_name' id='first_name' value='<?php echo $row['first_name']; ?>' />
            <label class="form-label">Apellidos:</label>
            <input type="text" class="form-control" name='last_name' id='last_name' value='<?php echo $row['last_name']; ?>' />
            <label class="form-label">Edad:</label>
            <input type="text" class="form-control" name='age' id='age' value='<?php echo $row['age']; ?>' />
            <label class="form-label">Genero:</label>
            <select class="form-select" name='gender' id='gender'>
                <?php
                $aage = array("Mujer", "Varon");
                foreach ($aage as $key => $val) {
                    if ($row['gender'] == $key) {
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
            <label class="form-label">Activar:</label>
            <select class="form-select" name='active' id='active'>
                <?php
                $acti = array("No", "Si");
                foreach ($acti as $key => $val) {
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
        <div class='col-md-6'>
            <label class="form-label">Imagen:</label>                                 
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
            <div class='w-100'>
                <img class="img-fluid" src="<?php echo $row['image']; ?>" />                            
            </div>
            <div class='w-100'>
                <input type="text" name='image' id='image' placeholder="Imagen Url" value='<?php echo $row['image']; ?>' readonly/>                            
                <input type="button" id="imageUpload" value='Seleccionar Imagen' />
            </div>                    
        </div> 
        <div class='w-100'><label class="form-label">Descripción personal / Ingles:</label>                                                               
            <textarea class="form-control" name='description_en' id='description_en'><?php echo $row['description_en']; ?></textarea>
            <script>
                CKEDITOR.replace('description_en', {
                    filebrowserBrowseUrl: 'elFinder/elfinder.html'
                });
            </script>
        </div> 
        <div class='w-100'><label class="form-label">Descripción personal / Español:</label>                                                               
            <textarea class="form-control" name='description_es' id='description_es'><?php echo $row['description_es']; ?></textarea>
            <script>
                CKEDITOR.replace('description_es', {
                    filebrowserBrowseUrl: 'elFinder/elfinder.html'
                });
            </script>
        </div> 
        <div class='w-100'>
            <input class="btn btn-secondary" type='submit' value='Editar Perfil' /><input type='hidden' value='1' name='submitted' />
        </div> 
    </form>   
</div>            
