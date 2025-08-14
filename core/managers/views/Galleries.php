<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    if (isset($_GET['w']) && !empty($_GET['w'])) {
        $w = $_GET['w'];
        if ($w === 'list') {
?>         
                    <div class="container">
                        <div class="row"> 
                            <p>
                                <a class="btn btn-secondary" href='dashboard/galleries/add'>Agregar Nueva galleries</a> 
                            </p>
                            <h3>Lista de galleries </h3>
                            <style>
                                table a{
                                    cursor: pointer;
                                }
                            </style>
            <?php
            echo "<table class='table' border=1 cellpadding=0 cellspacing=0 >";
            echo "<thead>";
            echo "<tr class=name>";
            echo "<th><b>Galeria</b></th>";
            echo "<th><b>Nombre</b></th>";
            echo "<th><b>Tipo</b></th>";
            echo "<th><b>Contenido</b></th>";
            echo "<th><b>Página</b></th>";
            echo "<th><b>Activo</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $result = $conn->query("SELECT * FROM `galleries` LEFT JOIN `type_gallery` ON galleries.type=type_gallery.idTG  LEFT JOIN (SELECT id, title AS npage FROM pages)`page`  ON galleries.pageID=page.id") or trigger_error($conn->error);
            while ($row = $result->fetch_array()) {
                foreach ($row AS $key => $value) {
                    $row[$key] = stripslashes($value);
                }
                echo "<tr>";
                echo "<td valign='top'>" . $row['gallery'] . "<br />";
                if ($row['type'] == 1) {
                    echo "<a class='button' href='newimage.php?id={$row['idGal']}'>Agregar Imagenes</a>";
                } else if ($row['type'] == 2) {
                    echo "<a class='button' href='newmultimedia.php?id={$row['idGal']}'>Agregar Multimedia</a>";
                } else {
                    echo "<a class='button' href='newpubli.php?id={$row['idGal']}'>Agregar Publicaciones</a>";
                }
                echo "</td>";
                echo "<td valign='top'>" . $row['name'] . "</td>";
                echo "<td valign='top'>" . $row['type_gallery'] . "</td>";
                echo "<td valign='top'>";
                if ($row['type'] == 1) {
                    echo "<a href='listGalImg.php?gal={$row['idGal']}'>";
                } elseif ($row['type'] == 2) {
                    echo "<a href='listGalMedia.php?gal={$row['idGal']}'>";
                } else {
                    echo "<a href='listGalPress.php?gal={$row['idGal']}'>";
                }
                echo "Ver contenido</a></td>";
                echo "<td valign='top'>" . $row['npage'] . "</td>";
                echo "<td valign='top'>";
                if ($row['active'] == 1) {
                    echo 'Si';
                } else {
                    echo 'No';
                }
                echo "</td>";
                echo "<td valign='top'><a href='" . B_URL . "index.php?page={$row['id']}' target='_blank'>Vista</a></td><td valign='top'><a href='editGal.php?id={$row['idGal']}'>Editar</a></td><td><a href='deleteGal.php?id={$row['idGal']}'>Eliminar</a></td> ";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr class=name>";
            echo "<th><b>Galeria</b></th>";
            echo "<th><b>Nombre</b></th>";
            echo "<th><b>Tipo</b></th>";
            echo "<th><b>Contenido</b></th>";
            echo "<th><b>Página</b></th>";
            echo "<th><b>Activo</b></th>";
            echo "<th></th><th></th><th></th>";
            echo "</tr>";
            echo "</tfoot>";
            echo "</table>";
            ?> 
                        </div> 
                    </div>
            <?php
        } elseif ($w === 'add') {
            ?> 
                    <div class="container">
                        <div class="row">
            <?php
            if (isset($_POST['submitted'])) {
                $sql = "INSERT INTO `galleries` ( `gallery` ,  `name` ,  `type` ,  `description` ,  `pageId`,  `active`  ) VALUES(  '{$_POST['gallery']}' ,  '{$_POST['name']}' ,  '{$_POST['type']}' ,  '{$_POST['description']}' ,  '{$_POST['pageId']}' ,  '{$_POST['active']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Imagen Agregada.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
                            <p>
                                <a class="btn btn-secondary" href='dashboard/galleries/list'>Retornar a la Lista</a> 
                            </p>
                            <h3>Agregar una Galeria</h3> 
                            <form action='' method='POST'> 
                                <div class='col-md-6'>
                                    <label class="form-label">Nombre de galleria:</label>
                                    <input type="text" class="form-control" name='gallery' id='gallery'/>
                                </div>  
                                <div class='col-md-6'>
                                    <label class="form-label">Nombre:</label>
                                    <input type="text" class="form-control" name='name' id='name'/>
                                </div>                
                                <div class='col-md-6'>
                                    <label class="form-label">Tipo de galeria:</label>                   
            <?php
            $stpg = "SELECT * FROM type_gallery";
            $querg = $conn->query($stpg);
            ?> 
                                    <select class="form-select" name='type' id='type'>                             
                                        <option>-- Selecciona tipo -- </option>
            <?php
            while ($tpg = mysqli_fetch_array($querg)) {
            ?>     
                                                <option value="<?php echo $tpg['idTG']; ?>"><?php echo $tpg['type_gallery']; ?></option>
                <?php
            }
                ?>
                                    </select>
                                </div> 
                                <div class="container">
                                    <label class="form-label">Descripción:</label>
                                    <textarea class="form-control" name='description' id='description'></textarea>
                                    <script>
                                        CKEDITOR.replace('description', {
                                            filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserUploadUrl: 'elfinder/elfinder.html',
                                            filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                            imageUploadUrl: 'elfinder/elfinder.html'
                                        });
                                    </script>
                                </div>           
                                <div class='col-md-6'>
                                    <label class="form-label">Página de visualización:</label>                    
            <?php
            $sqp1 = "SELECT * FROM pages";
            $queryp1 = $conn->query($sqp1);
            ?> 
                                    <select class="form-select" name='pageId' id='pageId'>                            
                                        <option>Selecciona una página </option>
            <?php
            while ($rp1 = mysqli_fetch_array($queryp1)) {
            ?>     
                                                <option value="<?php echo $rp1['id']; ?>"><?php echo $rp1['title']; ?></option>
                <?php
            }
                ?>
                                    </select>
                                </div> 
                                <div class='col-md-6'>
                                    <label class="form-label">Activo:</label>
                                    <select class="form-select" name='active' id='active'>
            <?php
            $acti = array("No", "Si");
            reset($acti);
            while (list($key, $val) = each($acti)) {
            ?>     
                                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                <?php
            }
                ?>     
                                    </select>
                                </div> 
                                <div class="container"
                                     ><input class="btn btn-primary" type='submit' value='Agregar Galeria' />
                                    <input type='hidden' value='1' name='submitted' />
                                </div> 
                            </form> 
                        </div>
                    </div>
            <?php
        } elseif ($w === 'edit') {
            ?> 
                    <div class="container">  
                        <div class="row">
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                if (isset($_POST['submitted'])) {
                    $sql = "UPDATE `galleries` SET  `gallery` =  '{$_POST['gallery']}' ,  `name` =  '{$_POST['name']}' ,  `type` =  '{$_POST['type']}' ,  `description` =  '{$_POST['description']}' ,  `pageId` =  '{$_POST['pageId']}' ,  `active` =  '{$_POST['active']}'   WHERE `idGal` = '$id' ";
                    $conn->query($sql) or die($conn->error);
                    echo "Galeria Editada.<br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `galleries` WHERE `idGal` = '$id' "));
            ?>
                                    <p>
                                        <a class="btn btn-secondary" href='dashboard/galleries/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/galleries/add'>Nueva Galeria</a> 
                                    </p>
                                    <h3>Editar <?php echo $row['name']; ?></h3> 
                                    <form action='' method='POST'> 
                                        <div class='col-md-6'>
                                            <label class="form-label">Nombre de gallery:</label>
                                            <input type="text" class="form-control" name='gallery' id='gallery' value='<?php echo $row['gallery']; ?>' />
                                        </div>                                        
                                        <div class='col-md-6'>
                                            <label class="form-label">Nombre:</label>
                                            <input type="text" class="form-control" name='name' id='name' value='<?php echo $row['name']; ?>'/>
                                        </div> 
                                        <div class='col-md-6'>
                                            <label class="form-label">Tipo de galeria:</label>                   
                <?php
                $stpg = "SELECT * FROM type_gallery";
                $querg = $conn->query($stpg);
                ?> 
                                            <select class="form-select" name='type' id='type'>                             
                                                <option>-- Selecciona tipo -- </option>
                <?php
                while ($tpg = mysqli_fetch_array($querg)) {
                    if ($tpg['idTG'] == $row['type']) {
                ?>     
                                                                <option value="<?php echo $tpg['idTG']; ?>" selected><?php echo $tpg['type_gallery']; ?></option>
                        <?php
                    } else {
                        ?>
                                                                <option value="<?php echo $tpg['idTG']; ?>"><?php echo $tpg['type_gallery']; ?></option>
                        <?php
                    }
                }
                        ?>
                                            </select>
                                        </div>
                                        <div class="container">
                                            <label class="form-label">Descripción:</label>
                                            <textarea class="form-control" name='description' id='description'><?php echo $row['description']; ?></textarea> 
                                            <script>
                                                CKEDITOR.replace('description', {
                                                    filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                                    filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                                    filebrowserUploadUrl: 'elfinder/elfinder.html',
                                                    filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                                    imageUploadUrl: 'elfinder/elfinder.html'
                                                });
                                            </script>
                                        </div> 
                                        <div class='col-md-6'>
                                            <label class="form-label">Página de visualización:</label>                    
                <?php
                $sqp1 = "SELECT * FROM pages";
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
                                        <div class='col-md-6'>
                                            <label class="form-label">Activo:</label>
                                            <select class="form-select" name='active' id='active'>
                <?php
                $acti = array("No", "Si");

                reset($acti);

                while (list($key, $val) = each($acti)) {
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
                                        <div class="container">
                                            <input class="btn btn-primary" type='submit' value='Editar Galeria' />
                                            <input type='hidden' value='1' name='submitted' />
                                        </div> 
                                    </form> 
            <?php } ?> 
                        </div>
                    </div>
            <?php
        } elseif ($w === 'view') {
            ?>
                    <div class="container">
                        <div class="row">
                            <p>
                                <a class="btn btn-secondary" href='dashboard/galleries/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/galleries/add'>Nueva Galeria</a> 
                            </p>
                            <link href="<?php echo SITE_PATH; ?>assets/dist/css/lightgallery.css" rel="stylesheet">
                            <link href="<?php echo SITE_PATH; ?>assets/css/responsiveslides.css" rel="stylesheet">
                            <script src="<?php echo SITE_PATH; ?>assets/js/jquery.min.js" type="text/javascript"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/js/responsiveslides.min.js" type="text/javascript"></script> 
                            <style>
                                .imagegal{
                                    width: 480px;
                                    text-align: center;
                                }
                                @media only screen and (max-width: 480px)  {
                                    .imagegal{
                                        width: 100%;
                                    }
                                }
                            </style> 
                            <div class="imagegal">
                                <div id="lightgallery">
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];

                $result = $conn->query("SELECT id galId, image FROM `image_gal` WHERE `galId` = '$id' ") or trigger_error($conn->error);

                while ($row = $result->fetch_array()) {
            ?>
                                                    <a href="<?php echo $row['image']; ?>"><img class="scale" src="<?php echo $row['image']; ?>" /></a>
                    <?php
                }
            }
                    ?>                
                                </div> 
                            </div>
                            <script type="text/javascript">
                                            $(document).ready(function () {
                                                $("#lightgallery").lightGallery();
                                                $("#lightgallery").responsiveSlides();
                                            });
                            </script>
                            <script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lightgallery.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lg-fullscreen.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lg-thumbnail.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lg-video.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lg-autoplay.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lg-zoom.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lg-hash.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/dist/js/lg-pager.js"></script>
                            <script src="<?php echo SITE_PATH; ?>assets/lib/jquery.mousewheel.min.js"></script>
                        </div>
                    </div>
            <?php
        } elseif ($w === 'delete') {
            ?>  
                    <div class="container">
                        <div class="row">
                            <p>
                                <a class="btn btn-secondary" href='dashboard/galleries/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/galleries/add'>Nueva Galeria</a> 
                            </p>
                            <h3>Eliminado de galleries</h3> 
            <?php
            $id = (int) $_GET['id'];
            $conn->query("DELETE FROM `galleries` WHERE `idGal` = '$id' ");
            echo "Galelria Eliminada.<br /> ";
            header("Location: dashboard/galleries/list");
            ?> 
                        </div>
                    </div>
            <?php
        }
    }
}
            ?>
