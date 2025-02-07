<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $_GET['w'];
    if ($w === 'list') {
        ?>
        <div class="container"> 
            <p>
                <a class="btn btn-secondary" href='dashboard/gallery/add'>Agregar Nueva galeria</a> 
            </p>
            <h3>Lista de galeria de medios </h3>
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
                    echo "<a class='button' href='dashboard/gallery/addi&id={$row['idGal']}'>Agregar Imagenes</a>";
                } else if ($row['type'] == 2) {
                    echo "<a class='button' href='dashboard/gallery/addm&id={$row['idGal']}'>Agregar Multimedia</a>";
                } else {
                    echo "<a class='button' href='dashboard/gallery/addp&id={$row['idGal']}'>Agregar Publicaciones</a>";
                }
                echo "</td>";
                echo "<td valign='top'>" . $row['name'] . "</td>";
                echo "<td valign='top'>" . $row['type_gallery'] . "</td>";
                echo "<td valign='top'>";
                if ($row['type'] == 1) {
                    echo "<a href='dashboard/gallery/listgi&gal={$row['idGal']}'>";
                } elseif ($row['type'] == 2) {
                    echo "<a href='dashboard/gallery/listgm&gal={$row['idGal']}'>";
                } else {
                    echo "<a href='dashboard/gallery/listgp&gal={$row['idGal']}'>";
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
                echo "<td valign='top'><a href='" . B_URL . "index.php?page={$row['id']}' target='_blank'>Vista</a></td><td valign='top'><a href='dashboard/gallery/edit&id={$row['idGal']}'>Editar</a></td><td><a href='dashboard/gallery/delete&id={$row['idGal']}'>Eliminar</a></td> ";
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
        <?php
    } elseif ($w === 'add') {
        ?>
        <div class="container">
            <?php
            if (isset($_POST['submitted'])) {
                $sql = "INSERT INTO `galleries` ( `gallery` ,  `name` ,  `type` ,  `description` ,  `pageId`,  `active`  ) VALUES(  '{$_POST['gallery']}' ,  '{$_POST['name']}' ,  '{$_POST['type']}' ,  '{$_POST['description']}' ,  '{$_POST['pageId']}' ,  '{$_POST['active']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Imagen Agregada.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
            <p>
                <a class="btn btn-secondary" href='dashboard/gallery/list'>Retornar a la Lista</a> 
            </p>
            <h3>Agregar una Galeria</h3> 
            <form action='' method='POST'> 
                <div class="container">
                    <label class="form-label">Nombre de galleria:</label>
                    <input type="text" class="form-control" name='gallery' id='gallery'/>
                </div>  
                <div class="container">
                    <label class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name='name' id='name'/>
                </div>                
                <div class="container">
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
                <div class="container"><label class="form-label">Descripción:</label>
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
                <div class="container"><label class="form-label">Página de visualización:</label>                    
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
                <div class="container"><label class="form-label">Activo:</label>
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
                <div class="container"><input class="btn btn-primary" type='submit' value='Agregar Galeria' /><input type='hidden' value='1' name='submitted' /></div> 
                <div class="clear"></div>
            </form> 
        </div>
        <?php
    } elseif ($w === 'edit') {
        ?>
        <div class="container">  
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
                    <a class="btn btn-secondary" href='dashboard/gallery/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/gallery/add'>Nueva Galeria</a> 
                </p>
                <h3>Editar <?php echo $row['name']; ?></h3> 

                <form action='' method='POST'> 
                    <div class="container">
                        <label class="form-label">Nombre de gallery:</label>
                        <input type="text" class="form-control" name='gallery' id='gallery' value='<?php echo $row['gallery']; ?>' />
                    </div>                                        
                    <div class="container">
                        <label class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name='name' id='name' value='<?php echo $row['name']; ?>'/>
                    </div> 
                    <div class="container">
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
                    <div class="container"><label class="form-label">Descripción:</label>
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
                    <div class="container"><label class="form-label">Página de visualización:</label>                    
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
                    <div class="container"><label class="form-label">Activo:</label>
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
                    <div class="container"><input class="btn btn-primary" type='submit' value='Editar Galeria' /><input type='hidden' value='1' name='submitted' /></div> 
                    <div class="clear"></div>
                </form> 
            <?php } ?> 
        </div>
        <?php
    } elseif ($w === 'view') {
        ?>
        <div class="container">
            <p>
                <a class="btn btn-secondary" href='dashboard/gallery/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/gallery/add'>Nueva Galeria</a> 
            </p>
            <link href="<?php echo B_URL; ?>dist/css/lightgallery.css" rel="stylesheet">
            <link href="<?php echo B_URL; ?>css/responsiveslides.css" rel="stylesheet">
            <script src="<?php echo B_URL; ?>js/jquery.min.js" type="text/javascript"></script>
            <script src="<?php echo B_URL; ?>js/responsiveslides.min.js" type="text/javascript"></script> 
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
            <script src="<?php echo B_URL; ?>dist/js/lightgallery.js"></script>
            <script src="<?php echo B_URL; ?>dist/js/lg-fullscreen.js"></script>
            <script src="<?php echo B_URL; ?>dist/js/lg-thumbnail.js"></script>
            <script src="<?php echo B_URL; ?>dist/js/lg-video.js"></script>
            <script src="<?php echo B_URL; ?>dist/js/lg-autoplay.js"></script>
            <script src="<?php echo B_URL; ?>dist/js/lg-zoom.js"></script>
            <script src="<?php echo B_URL; ?>dist/js/lg-hash.js"></script>
            <script src="<?php echo B_URL; ?>dist/js/lg-pager.js"></script>
            <script src="<?php echo B_URL; ?>lib/jquery.mousewheel.min.js"></script>
        </div>
        <?php
    } elseif ($w === 'delete') {
        ?>
        <div class="container"> 
            <p>
                <a class="btn btn-secondary" href='dashboard/gallery/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/gallery/add'>Nueva Galeria</a> 
            </p>
            <h3>Eliminado de galleries</h3> 

            <?php
            $id = (int) $_GET['id'];
            $conn->query("DELETE FROM `galleries` WHERE `idGal` = '$id' ");
            echo "Galelria Eliminada.<br /> ";
            header("Location: dashboard/gallery/list");
            ?> 
        </div>
        <?php
    } elseif ($w === 'listgi') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['gal']) && !empty($_GET['gal'])) {
                $id = (int) $_GET['gal'];
                $rowg = mysqli_fetch_array($conn->query("SELECT * FROM `galleries` WHERE `idGal` = '$id' "));
            }
            ?>
            <p>
                <a class="btn btn-secondary" href='newimage.php?id=<?php echo $rowg['idGal']; ?>'>Agregar Nueva Imagen</a> 
            </p>
            <h3>Lista de Imagenes de <?php echo $rowg['name']; ?></h3>
            <style>
                #sort
                {
                    border-collapse: collapse;
                    width: 98%;
                }
                #sort a{
                    cursor: pointer;
                }
                #sort thead tr
                {
                    border-bottom: 1px solid #ccc;
                }

                #sort tr
                {
                    vertical-align: middle !important;
                    line-height: 2rem !important;
                    margin-bottom: 3px;
                }

                #sort th, #sort td
                {
                    border: 1px solid #ddd;
                    padding: 10px;
                    white-space: nowrap;
                }
            </style>
            <script>
                            $(document).ready(function () {
                                sortable_tables.sorting_field_table();
                            });
                            $('table#sort tbody').sortable({
                                items: ">tr",
                                appendTo: "parent",
                                opacity: 1,
                                containment: "document",
                                placeholder: "placeholder-style",
                                cursor: "move",
                                delay: 150,
                                update: function (event, ui) {
                                    $(this).children().each(function (index) {
                                        $(this).find('tr').last().html(index + 1);
                                    });
                                }
                            });
                            var sortable_tables =
                                    {
                                        sorting_field_table: function ()
                                        {
                                            $('table#sort tbody').sortable({
                                                helper: sortable_tables.fixWidthHelper
                                            }).disableSelection();
                                        },

                                        fixWidthHelper: function (e, ui) {
                                            ui.children().each(function () {
                                                $(this).width($(this).width());
                                            });
                                            return ui;
                                        }
                                    };
                            function saveOrderImg() {
                                var selectedLanguage = new Array();
                                $('#sort tr').each(function () {
                                    selectedLanguage.push($(this).attr("id"));
                                });
                                document.getElementById("img_order").value = selectedLanguage;
                            }
                            $('table#sort tr:last').index() + 1;
            </script>
            <?php
            if (isset($_POST["submitOrd"])) {
                $id_ary = explode(",", $_POST["img_order"]);
                for ($i = 1; $i < count($id_ary); $i++) {
                    $conn->query("UPDATE image_gal SET sort='$i' WHERE id='$id_ary[$i]' AND galId='$id' ");
                }
            }
            ?>
            <form action='' method='POST'> 
                <input type = "hidden" name="img_order" id="img_order" /> 
                <?php
                echo "<table class='table' id='sort' border=1 cellpadding=0 cellspacing=0 > \n";
                echo "<thead> \n";
                echo "<tr class=title> \n";
                echo "<th><b>Orden</b></th> \n";
                echo "<th><b>Imagen</b></th>  \n";
                echo "<th></th><th></th> \n";
                echo "</tr> \n";
                echo "</thead> \n";
                echo "<tbody> \n";
                $result = $conn->query("SELECT * FROM `image_gal` WHERE galId='$id' ORDER BY sort") or trigger_error($conn->error);
                while ($row = $result->fetch_array()) {
                    foreach ($row AS $key => $value) {
                        $row[$key] = stripslashes($value);
                    }
                    echo "<tr id=" . $row['id'] . "> \n";
                    echo "<td valign='top' style='height:110px'>" . $row['sort'] . "</td> \n";
                    echo "<td valign='top' style='height:110px'><img src='" . $row['image'] . "' /></td> \n";
                    echo "<td valign='top' style='height:110px'><a href='editImg.php?id={$row['id']}'>Editar</a></td><td style='height:110px'><a href='deleteImg.php?id={$row['id']}'>Eliminar</a></td> \n";
                    echo "</tr> \n";
                }
                echo "</tbody> \n";
                echo "<tfoot> \n";
                echo "<tr class=title> \n";
                echo "<th><b>Orden</b></th> \n";
                echo "<th><b>Imagen</b></th> \n";
                echo "<th></th><th></th> \n";
                echo "</tr> \n";
                echo "</tfoot> \n";
                echo "</table> \n";
                ?> 
                <input type="submit" class="btnSave" name="submitOrd" value="Guardar Orden" onClick="saveOrderImg();" />
            </form>
        </div>
        <?php
    } elseif ($w === 'addi') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `galleries` WHERE `idGal` = '$id' "));
                if (isset($_POST['submitted'])) {
                    $sql = "INSERT INTO `image_gal` ( `galId` ,  `image` ,  `caption_en` ,  `link`   ) VALUES(  '{$id}' ,  '{$_POST['image']}' ,  '{$_POST['caption_en']}' ,  '{$_POST['link']}'   ) ";
                    $conn->query($sql) or die($conn->error);
                    echo "Fila Agregada.";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                ?>
                <p>
                    <a class="btn btn-secondary" href='listGal.php'>Retornar a Galerias</a> - <a class="btn btn-secondary"  href='listImg.php'>Retornar a Imagenes</a> 
                </p>
                <h3>Agregar Imagenes a <?php echo $row['gallery']; ?></h3> 
                <form action='' method='POST'>                 
                    <div class='col-md-6'><label class="form-label">Image:</label>
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
                    <div class="col-md-6">
                        <div class="container"><label class="form-label">Descripción:</label><textarea class="form-control" name='caption_en' id='caption_en'></textarea></div> 
                        <div class="container"><label class="form-label">Link:</label><input type="text" class="form-control" name='link' id='link'/></div>                         
                    </div>
                    <div class="container"><input class="btn btn-primary" type='submit' value='Agregar Imagen' /><input type='hidden' value='1' name='submitted' /></div>                      
                </form> 
            <?php } ?>
        </div>
        <?php
    } elseif ($w === 'listgm') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['gal']) && !empty($_GET['gal'])) {
                $id = (int) $_GET['gal'];
                $rowg = mysqli_fetch_array($conn->query("SELECT * FROM `galleries` WHERE `idGal` = '$id' "));
            }
            ?>
            <p>
                <a class="btn btn-secondary" href='dashboard/gallery/addm&id=<?php echo $rowg['idGal']; ?>'>Agregar Nuevo Multimedia</a> 
            </p>
            <h3>Lista de Multimedia de <?php echo $rowg['name']; ?></h3>
            <style>
                #sort
                {
                    border-collapse: collapse;
                    width: 98%;
                }
                #sort a{
                    cursor: pointer;
                }
                #sort thead tr
                {
                    border-bottom: 1px solid #ccc;
                }

                #sort tr
                {
                    vertical-align: middle !important;
                    line-height: 2rem !important;
                    margin-bottom: 3px;
                }
                #sort th, #sort td
                {
                    border: 1px solid #ddd;
                    padding: 10px;
                    white-space: nowrap;
                }
            </style>
            <script>
                $(document).ready(function () {
                    sortable_tables.sorting_field_table();
                });
                $('table#sort tbody').sortable({
                    items: ">tr",
                    appendTo: "parent",
                    opacity: 1,
                    containment: "document",
                    placeholder: "placeholder-style",
                    cursor: "move",
                    delay: 150,
                    update: function (event, ui) {
                        $(this).children().each(function (index) {
                            $(this).find('tr').last().html(index + 1);
                        });
                    }
                });
                var sortable_tables =
                        {
                            sorting_field_table: function ()
                            {
                                $('table#sort tbody').sortable({
                                    helper: sortable_tables.fixWidthHelper
                                }).disableSelection();
                            },

                            fixWidthHelper: function (e, ui) {
                                ui.children().each(function () {
                                    $(this).width($(this).width());
                                });
                                return ui;
                            }
                        };
                function saveOrderImg() {
                    var selectedLanguage = new Array();
                    $('#sort tr').each(function () {
                        selectedLanguage.push($(this).attr("id"));
                    });
                    document.getElementById("img_order").value = selectedLanguage;
                }
                $('table#sort tr:last').index() + 1;
            </script>
            <?php
            if (isset($_POST["submitOrd"])) {
                $id_ary = explode(",", $_POST["img_order"]);
                for ($i = 1; $i < count($id_ary); $i++) {
                    $conn->query("UPDATE multimedia_gal SET sort='$i' WHERE id='$id_ary[$i]' AND galId='$id' ");
                }
            }
            ?>
            <form action='' method='POST'> 
                <input type = "hidden" name="img_order" id="img_order" /> 
                <?php
                echo "<table class='table' id='sort' border=1 cellpadding=0 cellspacing=0 > \n";
                echo "<thead>";
                echo "<tr class=title>";
                echo "<th><b>Orden</b></th>";
                echo "<th><b>Nombre</b></th>";
                echo "<th><b>Origen</b></th>";
                echo "<th><b>Id Video</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $result = $conn->query("SELECT * FROM multimedia_gal WHERE galId='$id' ORDER BY sort") or trigger_error($conn->error);
                while ($row = $result->fetch_array()) {
                    foreach ($row AS $key => $value) {
                        $row[$key] = stripslashes($value);
                    }
                    echo "<tr id=" . $row['id'] . "> \n";
                    echo "<td valign='top'>" . $row['sort'] . "</td>";
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
                    echo "<td valign='top'><a href='editMedia.php?id={$row['id']}'>Editar</a></td><td><a href='deleteMedia.php?id={$row['id']}'>Eliminar</a></td> ";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr class=title>";
                echo "<th><b>Orden</b></th>";
                echo "<th><b>Nombre</b></th>";
                echo "<th><b>Origen</b></th>";
                echo "<th><b>Id Video</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</tfoot>";
                echo "</table>";
                ?> 
                <input type="submit" class="btnSave" name="submitOrd" value="Guardar Orden" onClick="saveOrderImg();" />
            </form>
        </div>            
        <?php
    } elseif ($w === 'addm') {
        ?>
        <div class="container">
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `galleries` WHERE `idGal` = '$id' "));
                if (isset($_POST['submitted'])) {
                    $sql = "INSERT INTO `multimedia_gal` ( `galId` ,  `name` ,  `image` ,  `description` ,  `source` ,  `idlink`  ) VALUES(  '{$id}' ,  '{$_POST['name']}' ,  '{$_POST['image']}' ,  '{$_POST['description']}' ,  '{$_POST['source']}' ,  '{$_POST['idlink']}'  ) ";
                    $conn->query($sql) or die($conn->error);
                    echo "Fila Agregada.<br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                ?>
                <a class="btn btn-secondary" href='dashboard/gallery/listgm'>Retornar a la Lista</a> 
                <h3>Agregar Multimedia a <?php echo $row['gallery']; ?></h3> 
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
                        <div class="container">
                            <input type="text" name='image' id='image' placeholder="Imagen Url" readonly />
                            <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                        </div>                        
                    </div> 
                    <div class='col-md-6'>
                        <div class="container"><label class="form-label">Título:</label>
                            <input type="text" class="form-control" name='name' id='name'/>
                        </div> 
                        <div class="container"><label class="form-label">Descripción:</label>
                            <textarea class="form-control" name='description' id='description'></textarea>
                        </div> 
                        <div class="container"><label class="form-label">Origen:</label>
                            <select class="form-select" name='source' id='source'>
                                <?php
                                $acti = array("youtube", "vimeo");
                                reset($acti);
                                while (list($key, $val) = each($acti)) {
                                    ?>     
                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                    <?php
                                }
                                ?>     
                            </select>
                        </div> 
                        <div class="container"><label class="form-label">Id Video:</label>
                            <input type="text" class="form-control" name='idlink' id='idlink'/>
                        </div>    
                    </div>
                    <div class="container"><input class="btn btn-primary" type='submit' value='Agregar Multimedia' /><input type='hidden' value='1' name='submitted' /></div>                         
                </form> 
                <?php
            }
            ?>
        </div>           
        <?php
    } elseif ($w === 'listgp') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['gal']) && !empty($_GET['gal'])) {
                $id = (int) $_GET['gal'];
                $rowg = mysqli_fetch_array($conn->query("SELECT * FROM `galleries` WHERE `idGal` = '$id' "));
            }
            ?>
            <p>
                <a class="btn btn-secondary" href='dashboard/gallery/addp&id=<?php echo $rowg['idGal']; ?>'>Agregar Nuevo Publicacion</a> 
            </p>
            <h3>Lista de Publicaciones de <?php echo $rowg['name']; ?></h3>
            <style>
                #sort
                {
                    border-collapse: collapse;
                    width: 98%;
                }
                #sort a{
                    cursor: pointer;
                }
                #sort thead tr
                {
                    border-bottom: 1px solid #ccc;
                }

                #sort tr
                {
                    vertical-align: middle !important;
                    line-height: 2rem !important;
                    margin-bottom: 3px;
                }
                #sort th, #sort td
                {
                    border: 1px solid #ddd;
                    padding: 10px;
                    white-space: nowrap;
                }
            </style>
            <script>
                $(document).ready(function () {
                    sortable_tables.sorting_field_table();
                });
                $('table#sort tbody').sortable({
                    items: ">tr",
                    appendTo: "parent",
                    opacity: 1,
                    containment: "document",
                    placeholder: "placeholder-style",
                    cursor: "move",
                    delay: 150,
                    update: function (event, ui) {
                        $(this).children().each(function (index) {
                            $(this).find('tr').last().html(index + 1);
                        });
                    }
                });
                var sortable_tables =
                        {
                            sorting_field_table: function ()
                            {
                                $('table#sort tbody').sortable({
                                    helper: sortable_tables.fixWidthHelper
                                }).disableSelection();
                            },

                            fixWidthHelper: function (e, ui) {
                                ui.children().each(function () {
                                    $(this).width($(this).width());
                                });
                                return ui;
                            }
                        };
                function saveOrderImg() {
                    var selectedLanguage = new Array();
                    $('#sort tr').each(function () {
                        selectedLanguage.push($(this).attr("id"));
                    });
                    document.getElementById("img_order").value = selectedLanguage;
                }
                $('table#sort tr:last').index() + 1;
            </script>
            <?php
            if (isset($_POST["submitOrd"])) {
                $id_ary = explode(",", $_POST["img_order"]);
                for ($i = 1; $i < count($id_ary); $i++) {
                    $conn->query("UPDATE press_gal SET sort='$i' WHERE id='$id_ary[$i]' AND galId='$id' ");
                }
            }
            ?>
            <form action='' method='POST'> 
                <input type = "hidden" name="img_order" id="img_order" /> 
                <?php
                echo "<table class='table' id='sort' border=1 cellpadding=0 cellspacing=0 > \n";
                echo "<thead>";
                echo "<tr class=title>";
                echo "<th><b>Orden</b></th>";
                echo "<th><b>Título</b></th>";
                echo "<th><b>Sub Título</b></th>";
                echo "<th><b>Fecha de publicacion</b></th>";
                echo "<th><b>Tipo de publicacion</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $result = $conn->query("SELECT * FROM `press_gal` WHERE galId='$id'") or trigger_error($conn->error);
                while ($row = $result->fetch_array()) {
                    foreach ($row AS $key => $value) {
                        $row[$key] = stripslashes($value);
                    }
                    echo "<tr id=" . $row['id'] . "> \n";
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
                    echo "<td valign='top'><a href='viewPress.php?idPr={$row['idPr']}'>Vista</a></td><td valign='top'><a href='editPress.php?idPr={$row['idPr']}'>Editar</a></td><td><a href='deletePress.php?idPr={$row['idPr']}'>Eliminar</a></td> ";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr class=title>";
                echo "<th><b>Orden</b></th>";
                echo "<th><b>Título</b></th>";
                echo "<th><b>Sub Título</b></th>";
                echo "<th><b>Fecha de publicacion</b></th>";
                echo "<th><b>Tipo de publicacion</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</tfoot>";
                echo "</table>";
                ?> 
                <input type="submit" class="btnSave" name="submitOrd" value="Guardar Orden" onClick="saveOrderImg();" />
            </form>
        </div>            
        <?php
    } elseif ($w === 'addp') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `galleries` WHERE `idGal` = '$id' "));
                if (isset($_POST['submitted'])) {
                    $sql = "INSERT INTO `press_gal` ( `galId` ,  `image`,  `title` ,  `subtitle` ,  `description` ,  `printing_date` ,  `type_press`  ) VALUES(  '$id' ,  '{$_POST['image']}' , '{$_POST['title']}' ,  '{$_POST['subtitle']}' ,  '{$_POST['description']}' ,  '{$_POST['printing_date']}' ,  '{$_POST['type_press']}'  ) ";
                    $conn->query($sql) or die($conn->error);
                    echo "Fila Agregada.<br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                ?>
                <p>
                    <a class="btn btn-secondary" href='dashboard/gallery/listgp'>Retornar a la Lista</a> 
                </p>
                <h3>Agregar a Publicación</h3> 
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
                        <div class="container">
                            <input type="text" name='image' id='image' placeholder="Imagen Url" readonly />
                            <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                        </div>
                    </div> 
                    <div class='col-md-6'><label class="form-label">Título:</label><input type="text" class="form-control" name='title' id='title'/></div> 
                    <div class='col-md-6'><label class="form-label">SubTítulo:</label><input type="text" class="form-control" name='subtitle' id='subtitle'/></div> 
                    <div class="container"><label class="form-label">Descripción:</label><textarea class="form-control" name='description' id='description'></textarea></div> 
                    <div class='col-md-6'><label class="form-label">Fecha de publicación:</label><input type="text" class="form-control" name='printing_date' id='printing_date'/></div> 
                    <div class='col-md-6'><label class="form-label">Tipo de publicación:</label>                    
                        <select class="form-select" name='type_press' id='type_press'>
                            <?php
                            $acti = array("Entrevista", "Articulo", "Catalogo");
                            reset($acti);
                            while (list($key, $val) = each($acti)) {
                                ?>     
                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                <?php
                            }
                            ?>     
                        </select>
                    </div> 
                    <div class="container"><input class="btn btn-primary" type='submit' value='Agregar Publicacion' /><input type='hidden' value='1' name='submitted' /></div> 

                </form> 
                <?php
            }
            ?>
        </div>           
        <?php
    }
}
?>
