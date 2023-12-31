<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $_GET['w'];
    if ($w === 'list') {
        ?>
        <div class="container">
            <div class="row">
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=pages&w=add'>Agregar Nueva Página</a> 
                </p>
                <h3>Lista de páginas </h3>
                <?php
                echo '<table class="table" border=1 cellpadding=0 cellspacing=0 >' . "\n";
                echo "<thead>" . "\n";
                echo '<tr class="title">';
                echo "<th><b>Título</b></th>" . "\n";
                echo "<th><b>Tipo</b></th>" . "\n";
                echo "<th><b>Inicio</b></th>" . "\n";
                echo "<th><b>Padre</b></th>" . "\n";
                echo "<th><b>Sub Paginas</b></th>" . "\n";
                echo "<th><b>Activo</b></th>";
                echo "<th></th><th></th><th></th><th></th>" . "\n";
                echo "</tr>" . "\n";
                echo "</thead>" . "\n";
                echo "<tbody>" . "\n";
                $result = $conn->query("SELECT * FROM page LEFT JOIN (SELECT id AS idt, type_page FROM type_page) type_page ON page.type = type_page.idt") or trigger_error($conn->error);
                while ($row = $result->fetch_array()) {
                    echo "<tr>" . "\n";
                    echo "<td valign='top'><b>" . $row['title'] . "</b> - <a class='button' href='dashboard.php?cms=pages&w=addsubp&id={$row['id']}'>Agregar Sub Página</a></td>" . "\n";
                    echo "<td valign='top'>" . $row['type_page'] . "</td>" . "\n";
                    echo "<td valign='top'>" . "\n";
                    if ($row['startpage'] == 1) {
                        echo 'Si';
                    } else {
                        echo 'No';
                    }
                    echo "</td>";
                    $parnt = $row['parent'];

                    $rest = $conn->query("SELECT * FROM `page` WHERE `id` = '$parnt' ");
                    if ($rest->num_rows > 0) {
                        $rowp = $rest->fetch_array();
                        if ($parnt == $rowp['id']) {
                            echo "<td valign='top'><b>" . $rowp['title'] . "</b></td>";
                        }
                    } else {
                        echo "<td valign='top'>Página principal</td>";
                    }
                    echo "<td valign='top'>";
                    if ($row['sort'] == 1) {
                        echo "<a href='dashboard.php?cms=pages&w=subp&id={$row['id']}'>Subpaginas</a>";
                    }
                    echo "</td>";
                    echo "<td valign='top'>";
                    if ($row['active'] == 1) {
                        echo 'Si';
                    } else {
                        echo 'No';
                    }
                    echo "</td>" . "\n";
                    echo "<td valign='top'><a href='" . B_URL . "index.php?page={$row['id']}' target='_blank'>Vista</a></td><td valign='top'><a href='builder.php?id={$row['id']}'>Diseñar</a></td><td valign='top'><a href='dashboard.php?cms=pages&w=edit&id={$row['id']}'>Editar</a></td><td><a href='deletePage.php?id={$row['id']}'>Eliminar</a></td> " . "\n";
                    echo "</tr>" . "\n";
                }
                echo "</tbody>" . "\n";
                echo "<tfoot>" . "\n";
                echo '<tr class="title">';
                echo "<th><b>Título</b></th>" . "\n";
                echo "<th><b>Tipo</b></th>" . "\n";
                echo "<th><b>Inicio</b></th>" . "\n";
                echo "<th><b>Padre</b></th>" . "\n";
                echo "<th><b>Sub Paginas</b></th>" . "\n";
                echo "<th><b>Activo</b></th>";
                echo "<th></th><th></th><th></th><th></th>" . "\n";
                echo "</tr>" . "\n";
                echo "</tfoot>" . "\n";
                echo "</table>" . "\n";
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
                    if ($_POST['startpage'] === 1) {
                        //$qr = mysqli_fetch_array($conn->query("SELECT * FROM page WHERE startpage = '1' "));

                        $st = "UPDATE page SET startpage = '0' WHERE startpage = '1'";
                        $conn->query($st) or die($conn->error);
                    }
                    $posrt = $_POST['parent'];
                    if ($posrt > 0) {
                        $st = "UPDATE page SET sort = '1' WHERE id = '$posrt'";
                    }
                    $sql = "INSERT INTO page ( language , title ,  link ,  image ,  type ,  menu ,  content ,  startpage ,  parent ,  sort ,  active  ) VALUES( '{$_POST['language']}' , '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$_POST['startpage']}' ,  '{$_POST['parent']}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                    $conn->query($sql) or die($conn->error);
                    $lastId = $database->insert_id;
                    echo "Página Agregada.";
                    if ($conn->query($sql) === TRUE) {
                        $sql1 = "INSERT INTO blocks ( blockId ,  active ,  pageId  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['active']}' ,  '$lastId'  ) ";
                        $conn->query($sql1) or die($conn->error);
                        echo "Bloque Agregado.<br />";
                    }
                    if ($_POST['copypage'] === 1) {
                        if ($_POST['language'] == 1) {
                            $sql = "INSERT INTO page ( language , title ,  link ,  image ,  type ,  menu ,  content ,  startpage ,  parent ,  sort ,  active  ) VALUES( '2' , '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$_POST['startpage']}' ,  '{$_POST['parent']}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                            $conn->query($sql) or die($conn->error);
                            $lastId1 = $database->insert_id;
                            echo "Página Copiada.";
                            $sql1 = "INSERT INTO blocks ( blockId ,  active ,  pageId  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['activeb']}' ,  '$lastId1'  ) ";
                            $conn->query($sql1) or die($conn->error);
                            echo "Bloque Agregado.<br />";
                        } else {
                            $sql = "INSERT INTO page ( language , title ,  link ,  image ,  type ,  menu ,  content ,  startpage ,  parent ,  sort ,  active  ) VALUES( '1' , '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$_POST['startpage']}' ,  '{$_POST['parent']}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                            $conn->query($sql) or die($conn->error);
                            $lastId1 = $database->insert_id;
                            echo "Página Copiada.";
                            $sql1 = "INSERT INTO blocks ( blockId ,  active ,  pageId  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['activeb']}' ,  '$lastId1'  ) ";
                            $conn->query($sql1) or die($conn->error);
                            echo "Bloque Agregado.<br />";
                        }
                    }
                    echo '<meta http-equiv="refresh" content="0">';
                }
                ?>
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=pages&w=list'>Retornar a la Lista</a> 
                </p>
                <h3>Agregar una página</h3> 
                <form action='' method='POST'> 
                    <div class="row">
                        <div class='col-md-6'>
                            <label class="form-label">Título:</label>
                            <input type="text" class="form-control" name='title' id='title'tabindex='1' autofocus/>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#title').keyup(function () {
                                        var titl = $('#title').val().split(" ").join("-").toLowerCase();
                                        $("#link").val(titl);
                                    });
                                });
                            </script> 
                        </div> 
                        <div class='col-md-6'>
                            <label class="form-label">Link de página:</label>
                            <input type="text" class="form-control" name='link' id='link'/>
                        </div>   
                    </div>               
                    <div class="row">
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
                            <div id="picture">
                                <span>No hay imagen? Utilice el botón para seleccionar una!</span>
                            </div>
                            <div class="container">
                                <input type="text" name='image' id='image' placeholder="Imagen Url" readonly />
                                <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                            </div>                        
                        </div>                  
                        <div class='col-md-6'>
                            <div class="container">
                                <label class="form-label">Idioma de página:</label>
                                <?php
                                $sqpl = "SELECT * FROM language";
                                $querypl = $conn->query($sqpl);
                                ?> 
                                <select class="form-select" name='language' id='language'>                                                        
                                    <?php
                                    while ($rpl = mysqli_fetch_array($querypl)) {
                                        ?>     
                                        <option value="<?php echo $rpl['id']; ?>"><?php echo $rpl['version']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="container">
                                <label class="form-label">Copiar página:</label>
                                <select class="form-select" name='copypage' id='copypage'>
                                    <?php
                                    $spti = array("No", "Si");
                                    foreach ($spti as $key => $val) {
                                        ?>     
                                        <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                        <?php
                                    }
                                    ?>     
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de página:</label> 
                            <script type="text/javascript">
                                function getval(sel) {
                                    var value = $('#type option:selected').val();
                                    if (value > 0) {
                                        $("#chng").show();
                                    } else {
                                        $("#chng").hide();
                                    }
                                }
                            </script>
                            <?php
                            $stp1 = "SELECT * FROM type_page";
                            $quertp1 = $conn->query($stp1);
                            ?> 
                            <select class="form-select" name='type' id='type'/>                             

                            <?php
                            while ($tp1 = mysqli_fetch_array($quertp1)) {
                                ?>     
                                <option value="<?php echo $tp1['id']; ?>"><?php echo $tp1['type_page']; ?></option>
                                <?php
                            }
                            ?>
                            </select>   
                        </div>
                        <div class='col-md-6'>
                            <label class="form-label">Tipo de menu:</label>
                            <?php
                            $sqpm = "SELECT * FROM type_menu";
                            $querypm = $conn->query($sqpm);
                            ?> 
                            <select class="form-select" name='menu' id='menu'>                            
                                <?php
                                while ($rpm = mysqli_fetch_array($querypm)) {
                                    ?>     
                                    <option value="<?php echo $rpm['id']; ?>"><?php echo $rpm['type_menu']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div> 
                    </div>
                    <div class='row'>
                        <div class="container">
                            <div id='chng'>
                                <label class="form-label">Contenido y/o Descripción:</label>
                                <textarea class="form-control" name='content' id='content'></textarea>
                                <script>
                                    CKEDITOR.replace('content', {
                                        filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                        filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                        filebrowserUploadUrl: 'elfinder/elfinder.html',
                                        filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                        imageUploadUrl: 'elfinder/elfinder.html'
                                    });
                                </script>
                            </div>
                        </div>                 
                        <div class='col-md-6'><label class="form-label">Página de inicio:</label>
                            <select class="form-select" name='startpage' id='startpage'>
                                <?php
                                $spi = array("No", "Si");

                                foreach ($spi as $key => $val) {
                                    ?>     
                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                    <?php
                                }
                                ?>     
                            </select> 
                        </div>
                        <div class='col-md-6'><label class="form-label">Página padre:</label>
                            <?php
                            $sqp1 = "SELECT * FROM page";
                            $queryp1 = $conn->query($sqp1);
                            ?> 
                            <select class="form-select" name='parent' id='parent'>                            
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
                        <div class='col-md-6'><label class="form-label">Tiene páginas hijo?:</label>
                            <select class="form-select" name='sort' id='sort'>
                                <?php
                                $opti = array("No", "Si");

                                foreach ($opti as $key => $val) {
                                    ?>     
                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                    <?php
                                }
                                ?>                                                  
                            </select>
                        </div> 
                        <div class='col-md-6'><label class="form-label">Página Activa:</label>
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
                        <div class="col-md-6"><label class="form-label">Tipo de Bloque:</label>
                            <?php
                            $stpb = "SELECT * FROM type_blocks";
                            $quertpb = $conn->query($stpb);
                            ?> 
                            <select class="form-select" name='blockId' id='blockId'/>                                     
                            <?php
                            while ($tpb = mysqli_fetch_array($quertpb)) {
                                ?>     
                                <option value="<?php echo $tpb['id']; ?>"><?php echo $tpb['type_block']; ?></option>
                                <?php
                            }
                            ?>
                            </select>  
                        </div> 
                        <div class="col-md-6"><label class="form-label">Bloque Activo:</label>
                            <select class="form-select" name='activeb' id='activeb'>
                                <?php
                                $actib = array("No", "Si");

                                foreach ($actib as $key => $val) {
                                    ?>     
                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                    <?php
                                }
                                ?>     
                            </select>
                        </div> 
                        <div class="container"><input class="btn btn-primary" type='submit' value='Agregar Página' /><input type='hidden' value='1' name='submitted' /></div> 
                    </div>
                </form> 
            </div>
        </div>
        <?php
    } elseif ($w === 'edit') {
        ?> 
        <div class="container"> 
            <div class='row'>
                <?php
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = (int) $_GET['id'];
                    $row = mysqli_fetch_array($conn->query("SELECT * FROM `page` LEFT JOIN (SELECT idB, blockId, active AS actB, pageId FROM blocks)`blocks` ON page.id = blocks.pageId WHERE `id` = '$id' "));

                    $idBl = $row['idB'];
                    if (isset($_POST['submitted'])) {
                        if ($_POST['startpage'] == 1) {
                            //$qr = mysqli_fetch_array($conn->query("SELECT * FROM `page` WHERE `startpage` = '1' "));

                            $st = "UPDATE `page` SET `startpage` = '0' WHERE `startpage` = '1'";
                            $conn->query($st) or die($conn->error);
                        }
                        $posrt = $_POST['parent'];
                        if ($posrt > 0) {
                            $st = "UPDATE `page` SET `sort` = '1' WHERE `id` = '$posrt'";
                        }
                        $sql = "UPDATE `page` SET `language` =  '{$_POST['language']}' , `title` =  '{$_POST['title']}' ,  `link` =  '{$_POST['link']}' ,  `image` =  '{$_POST['image']}' ,  `type` =  '{$_POST['type']}' ,  `menu` =  '{$_POST['menu']}' ,  `content` =  '{$_POST['content']}' ,  `startpage` =  '{$_POST['startpage']}' ,  `parent` =  '{$_POST['parent']}' ,  `sort` =  '{$_POST['sort']}' ,  `active` =  '{$_POST['active']}'   WHERE `id` = '$id' ";
                        $conn->query($sql) or die($conn->error);
                        echo "Página actualizada.<br />";
                        if ($_POST['copypage'] === 1) {
                            if ($_POST['language'] == 1) {
                                $sql = "INSERT INTO `page` ( `language` , `title` ,  `link` ,  `image` ,  `type` ,  `menu` ,  `content` ,  `startpage` ,  `parent` ,  `sort` ,  `active`  ) VALUES( '2' , '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$_POST['startpage']}' ,  '{$_POST['parent']}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                                $conn->query($sql) or die($conn->error);
                                $lastId = $database->insert_id;
                                echo "Página Copiada.";
                                $sql1 = "INSERT INTO `blocks` ( `blockId` ,  `active` ,  `pageId`  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['activeb']}' ,  '$lastId  ) ";
                                $conn->query($sql1) or die($conn->error);
                                echo "Bloque Agregado.<br />";
                            } else {
                                $sql = "INSERT INTO `page` ( `language` , `title` ,  `link` ,  `image` ,  `type` ,  `menu` ,  `content` ,  `startpage` ,  `parent` ,  `sort` ,  `active`  ) VALUES( '1' , '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$_POST['startpage']}' ,  '{$_POST['parent']}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                                $conn->query($sql) or die($conn->error);
                                $lastId = $database->insert_id;
                                echo "Página Copiada.";
                                $sql1 = "INSERT INTO `blocks` ( `blockId` ,  `active` ,  `pageId`  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['activeb']}' ,  '$lastId  ) ";
                                $conn->query($sql1) or die($conn->error);
                                echo "Bloque Agregado.<br />";
                            }
                        }
                        $sql1 = "UPDATE `blocks` SET  `blockId` =  '{$_POST['blockId']}' ,  `active` =  '{$_POST['activeb']}' WHERE  `idB` =  '$idBl'";
                        $conn->query($sql1) or die($conn->error);
                        echo "Bloque actualizado.<br />";
                        echo '<meta http-equiv="refresh" content="0">';
                    }
                    ?>
                    <p>
                        <a class="btn btn-secondary" href='dashboard.php?cms=pages&w=list'>Retornar a la Lista de Páginas</a> - <a class="btn btn-secondary" href='dashboard.php?cms=pages&w=add'>Nueva Página</a> 
                    </p>
                    <h3>Editar <?php echo $row['title']; ?></h3> 
                    <form action='' method='POST'> 
                        <div class="row">
                            <div class='col-md-6'>
                                <label class="form-label">Título:</label>
                                <input type="text" class="form-control" name='title' id='title' value='<?php echo $row['title']; ?>' />
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('#title').keyup(function () {
                                            var titl = $('#title').val().split(" ").join("-").toLowerCase();
                                            $("#link").val(titl);
                                        });
                                    });
                                </script> 
                            </div> 
                            <div class='col-md-6'>
                                <label class="form-label">Link de página:</label>
                                <input type="text" class="form-control" name='link' id='link' value='<?php echo $row['link']; ?>' />
                            </div>                     
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
                                <div class="container">
                                    <img class="img-fluid" src="<?php echo $row['image']; ?>" />                            
                                </div>
                                <div class="container">
                                    <input type="text" name='image' id='image' placeholder="Imagen Url" value='<?php echo $row['image']; ?>' readonly/>                            
                                    <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                                </div>                       
                            </div>     
                            <div class='col-md-6'>
                                <div class="container">
                                    <label class="form-label">Lenguage:</label>
                                    <select class="form-select" name='language' id='language'>                                                             
                                        <?php
                                        $sqpl = "SELECT * FROM language";
                                        $quertl = $conn->query($sqpl);
                                        while ($tpl = mysqli_fetch_array($quertl)) {
                                            if ($tpl['id'] == $row['language']) {
                                                ?>     
                                                <option value="<?php echo $tpl['id']; ?>" selected><?php echo $tpl['version']; ?></option>
                                                <?php
                                            } else {
                                                ?>     
                                                <option value="<?php echo $tpl['id']; ?>"><?php echo $tpl['version']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select> 
                                </div>     
                                <div class="container">
                                    <label class="form-label">Copiar página:</label>
                                    <select class="form-select" name='copypage' id='copypage'>
                                        <?php
                                        $spti = array("No", "Si");
                                        foreach ($spti as $key => $val) {
                                            ?>
                                            <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                            <?php
                                        }
                                        ?>     
                                    </select> 
                                </div>
                                <div class="container">
                                    <label class="form-label">Tipo de página:</label>    
                                    <script type="text/javascript">
                                        function getval(sel) {
                                            var value = $('#type option:selected').val();
                                            if (value > 0) {
                                                $("#chng").show();
                                            } else {
                                                $("#chng").hide();
                                            }
                                        }
                                    </script>
                                    <?php
                                    $stp1 = "SELECT * FROM type_page";
                                    $quertp1 = $conn->query($stp1);
                                    ?> 
                                    <select class="form-select" name='type' id='type' onchange="getval(this);">                             
                                        <option>-- Selecciona tipo -- </option>
                                        <?php
                                        while ($tp1 = mysqli_fetch_array($quertp1)) {
                                            if ($tp1['id'] == $row['type']) {
                                                ?>     
                                                <option value="<?php echo $tp1['id']; ?>" selected><?php echo $tp1['type_page']; ?></option>
                                                <?php
                                            } else {
                                                ?>     
                                                <option value="<?php echo $tp1['id']; ?>"><?php echo $tp1['type_page']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>   
                                </div>
                                <div class="container">
                                    <label class="form-label">Tipo de menu:</label>
                                    <select class="form-select" name='menu' id='menu'>                             
                                        <option> -- Selecciona menu -- </option>
                                        <?php
                                        $sqpm = "SELECT * FROM type_menu";
                                        $quertm = $conn->query($sqpm);
                                        while ($tpm = mysqli_fetch_array($quertm)) {
                                            if ($tpm['id'] == $row['menu']) {
                                                ?>     
                                                <option value="<?php echo $tpm['id']; ?>" selected><?php echo $tpm['type_menu']; ?></option>
                                                <?php
                                            } else {
                                                ?>     
                                                <option value="<?php echo $tpm['id']; ?>"><?php echo $tpm['type_menu']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select> 
                                </div>
                            </div>
                            <div class='col-md-12'>
                                <div id='chng'>
                                    <label class="form-label">Contenido y/o Descripción:</label>
                                    <textarea class="form-control" name='content' id='content'><?php echo decodeContent($row['content']); ?></textarea>
                                    <script>
                                        CKEDITOR.replace('content', {
                                            filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                            filebrowserUploadUrl: 'elfinder/elfinder.html',
                                            filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                            imageUploadUrl: 'elfinder/elfinder.html'
                                        });
                                    </script>
                                </div>
                            </div>                     
                            <div class='col-md-6'><label class="form-label">Página de inicio:</label>
                                <select class="form-select" name='startpage' id='startpage'>                            
                                    <?php
                                    $spgi = array("No", "Si");

                                    foreach ($spgi as $key => $val) {

                                        if ($row['startpage'] == $key) {
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
                                </select> </div> 
                            <div class='col-md-6'><label class="form-label">Página padre:</label>
                                <?php
                                $sqp1 = "SELECT * FROM page";
                                $queryp1 = $conn->query($sqp1);
                                ?> 
                                <select class="form-select" name='parent' id='parent'>                            
                                    <option>Selecciona una página </option>
                                    <?php
                                    while ($rp1 = mysqli_fetch_array($queryp1)) {
                                        if ($rp1['id'] == $row['parent']) {
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
                            <div class='col-md-6'><label class="form-label">Tiene páginas hijo?:</label>
                                <select class="form-select" name='sort' id='sort'>
                                    <?php
                                    $opti = array("No", "Si");

                                    foreach ($opti as $key => $val) {

                                        if ($row['sort'] == $key) {
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
                                <label class="form-label">Página Activa:</label>
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
                            <div class="col-md-6">
                                <label class="form-label">Bloque:</label>
                                <?php
                                $tpbl = "SELECT * FROM type_blocks";
                                $quertpb = $conn->query($tpbl);
                                ?> 
                                <select class="form-select" name='blockId' id='blockId'/>                                     
                                <?php
                                while ($tpb = mysqli_fetch_array($quertpb)) {
                                    if ($tpb['id'] == $row['blockId']) {
                                        ?>     
                                        <option value="<?php echo $tpb['id']; ?>" selected><?php echo $tpb['type_block']; ?></option>
                                        <?php
                                    } else {
                                        ?>     
                                        <option value="<?php echo $tpb['id']; ?>"><?php echo $tpb['type_block']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>  
                            </div> 
                            <div class="col-md-6"><label class="form-label">Bloque Activo:</label>
                                <select class="form-select" name='activeb' id='activeb'>
                                    <?php
                                    $actib = array("No", "Si");

                                    foreach ($actib as $key => $val) {

                                        if ($row['actB'] == $key) {
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
                            <div class='col-md-12'>
                                <input class="btn btn-secondary" type='submit' value='Editar Página' />
                                <input type="hidden" name="return_url" value="<?php echo $current_url; ?>" />
                                <input type='hidden' value='1' name='submitted' />
                            </div> 
                        </div>
                    </form> 
                <?php } ?> 
            </div>
        </div>
        <?php
    } elseif (w === 'addsubp') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `page` WHERE `id` = '$id' "));
                $langr = $row["languague"];

                if (isset($_POST['submitted'])) {
                    if ($_POST['startpage'] == 1) {
                        //$qr = mysqli_fetch_array($conn->query("SELECT * FROM `page` WHERE `startpage` = '1' "));

                        $st = "UPDATE `page` SET `startpage` = '0' WHERE `startpage` = '1'";
                        $conn->query($st) or die($conn->error);
                    }
                    $posrt = $id;
                    if ($posrt > 0) {
                        $st = "UPDATE `page` SET `sort` = '1' WHERE `id` = '$posrt'";
                    }
                    $sql = "INSERT INTO `page` ( `language` , `title` ,  `link` ,  `image` ,  `type` ,  `menu` ,  `content` ,  `parent` ,  `sort` ,  `active`  ) VALUES( '{$langr}', '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$id}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                    $conn->query($sql) or die($conn->error);
                    $lastId = $database->insert_id;
                    echo "Página Agregada.";

                    $sql1 = "INSERT INTO `blocks` ( `blockId` ,  `active` ,  `pageId`  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['active']}' ,  '$lastId'  ) ";
                    $conn->query($sql1) or die($conn->error);
                    echo "Bloque Agregado.<br />";

                    if ($_POST['copypage'] == 1) {
                        if ($langr == 1) {
                            $sql = "INSERT INTO `page` ( `language` , `title` ,  `link` ,  `image` ,  `type` ,  `menu` ,  `content` ,  `startpage` ,  `parent` ,  `sort` ,  `active`  ) VALUES( '2' , '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$_POST['startpage']}' ,  '{$_POST['parent']}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                            $conn->query($sql) or die($conn->error);
                            $lastId1 = $database->insert_id;
                            echo "Página Copiada.";
                            $sql1 = "INSERT INTO `blocks` ( `blockId` ,  `active` ,  `pageId`  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['activeb']}' ,  '$lastId1'  ) ";
                            $conn->query($sql1) or die($conn->error);
                            echo "Bloque Agregado.<br />";
                        } else {
                            $sql = "INSERT INTO `page` ( `language` , `title` ,  `link` ,  `image` ,  `type` ,  `menu` ,  `content` ,  `startpage` ,  `parent` ,  `sort` ,  `active`  ) VALUES( '1' , '{$_POST['title']}' ,  '{$_POST['link']}' ,  '{$_POST['image']}' ,  '{$_POST['type']}' ,  '{$_POST['menu']}' ,  '{$_POST['content']}' ,  '{$_POST['startpage']}' ,  '{$_POST['parent']}' ,  '{$_POST['sort']}' ,  '{$_POST['active']}'  ) ";
                            $conn->query($sql) or die($conn->error);
                            $lastId1 = $database->insert_id;
                            echo "Página Copiada.";
                            $sql1 = "INSERT INTO `blocks` ( `blockId` ,  `active` ,  `pageId`  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['activeb']}' ,  '$lastId1'  ) ";
                            $conn->query($sql1) or die($conn->error);
                            echo "Bloque Agregado.<br />";
                        }
                    }
                }
                ?>
                <p>
                    <a class="btn btn-secondary" href='listPage.php'>Retornar a la Lista</a> 
                </p>
                <h3>Agregar una sub página</h3> 

                <form action='' method='POST'> 
                    <div class='col-md-6'><label class="form-label">Título:</label><input type="text" class="form-control" name='title' id='title'tabindex='1' autofocus/>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#title').keyup(function () {
                                    var titl = $('#title').val().split(" ").join("-").toLowerCase();
                                    $("#link").val(titl);
                                });
                            });
                        </script> 
                    </div> 
                    <div class='col-md-6'><label class="form-label">Link de página:</label><input type="text" class="form-control" name='link' id='link'/></div>                 
                    <div class='col-md-6'><label class="form-label">Imagen de página:</label>
                        <div id="picture">
                            <span>No hay imagen? Utilice el botón para seleccionar una!</span>
                        </div>
                        <div class="container">
                            <input type="text" name='image' id='image' placeholder="Imagen Url" value='<?php echo $row['image']; ?>' readonly/>                            
                            <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                        </div>     
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
                    </div>                  
                    <div class='col-md-6'>
                        <div class="container">
                            <label class="form-label">Copiar página:</label>
                            <select class="form-select" name='copypage' id='startpage'>
                                <?php
                                $spti = array("No", "Si");
                                reset($spti);
                                while (list($key, $val) = each($spti)) {
                                    ?>     
                                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                    <?php
                                }
                                ?>     
                            </select> 
                        </div>
                        <div class="container">
                            <label class="form-label">Tipo de página:</label> 
                            <script type="text/javascript">
                                function getval(sel) {
                                    var value = $('#type option:selected').val();
                                    if (value > 0) {
                                        $("#chng").show();
                                    } else {
                                        $("#chng").hide();
                                    }
                                }
                            </script>
                            <?php
                            $stp1 = "SELECT * FROM type_page";
                            $quertp1 = $conn->query($stp1);
                            ?> 
                            <select class="form-select" name='type' id='type'/>                             
                            <option>-- Selecciona tipo -- </option>
                            <?php
                            while ($tp1 = mysqli_fetch_array($quertp1)) {
                                ?>     
                                <option value="<?php echo $tp1['id']; ?>"><?php echo $tp1['type_page']; ?></option>
                                <?php
                            }
                            ?>
                            </select>       
                        </div>       
                        <div class="container"><label class="form-label">Tipo de menu:</label>
                            <?php
                            $sqpm = "SELECT * FROM type_menu";
                            $querypm = $conn->query($sqpm);
                            ?> 
                            <select class="form-select" name='menu' id='menu'>                            
                                <option> --Selecciona menu -- </option>
                                <?php
                                while ($rpm = mysqli_fetch_array($querypm)) {
                                    ?>     
                                    <option value="<?php echo $rpm['id']; ?>"><?php echo $rpm['type_menu']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div> 
                    </div>                                 
                    <div class='col_25_1'>
                        <div id='chng'>
                            <label class="form-label">Contenido y/o Descripción:</label>
                            <textarea class="form-control" name='content' id='content'></textarea>
                            <script>
                                CKEDITOR.replace('content', {
                                    filebrowserBrowseUrl: 'elfinder/elfinder.html',
                                    filebrowserImageBrowseUrl: 'elfinder/elfinder.html',
                                    filebrowserUploadUrl: 'elfinder/elfinder.html',
                                    filebrowserImageUploadUrl: 'elfinder/elfinder.html',
                                    imageUploadUrl: 'elfinder/elfinder.html'
                                });
                            </script>
                        </div>
                    </div>                                           
                    <div class='col-md-6'><label class="form-label">Tiene páginas hijo?:</label>
                        <select class="form-select" name='sort' id='sort'>
                            <?php
                            $opti = array("No", "Si");

                            reset($opti);

                            while (list($key, $val) = each($opti)) {
                                ?>     
                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                <?php
                            }
                            ?>                                                  
                        </select>
                    </div> 
                    <div class='col-md-6'><label class="form-label">Página Activa:</label>
                        <select class="form-select" name='active' id='active'>
                            <?php
                            $acti = array("No", "Si");
                            reset($acti);
                            foreach ($acti as $key => $val) {
                                ?>     
                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                <?php
                            }
                            ?>     
                        </select>
                    </div> 
                    <div class="col-md-6"><label class="form-label">Tipo de Bloque:</label>
                        <?php
                        $stpb = "SELECT * FROM type_blocks";
                        $quertpb = $conn->query($stpb);
                        ?> 
                        <select class="form-select" name='blockId' id='blockId'/>                                     
                        <?php
                        while ($tpb = mysqli_fetch_array($quertpb)) {
                            ?>     
                            <option value="<?php echo $tpb['id']; ?>"><?php echo $tpb['type_block']; ?></option>
                            <?php
                        }
                        ?>
                        </select>  
                    </div> 
                    <div class="col-md-6"><label class="form-label">bloque Activo:</label>
                        <select class="form-select" name='activeb' id='activeb'>
                            <?php
                            $actib = array("No", "Si");
                            reset($actib);
                            foreach ($actib as $key => $val) {
                                ?>     
                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                <?php
                            }
                            ?>     
                        </select>
                    </div> 
                    <div class="container">
                        <input class="btn btn-primary" type='submit' value='Agregar Página' />
                        <input type='hidden' value='1' name='submitted' />
                    </div>                    
                </form> 
            <?php } ?>
        </div>
        <?php
    } elseif ($w === 'subp') {
        ?>

        <div class="container">  
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $rowg = mysqli_fetch_array($conn->query("SELECT * FROM `page` WHERE `id` = '$id' "));
            }
            ?>
            <p>
                <a class="btn btn-secondary" href='newmpubli.php?id=<?php echo $rowg['idGal']; ?>'>Agregar Nuevo Publicacion</a> 
            </p>
            <h3>Lista de sub páginas de <?php echo $rowg['title']; ?></h3>
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
                    document.getElementById("pag_order").value = selectedLanguage;
                }
                $('table#sort tr:last').index() + 1;
            </script>
            <?php
            if (isset($_POST["submitOrd"])) {
                $id_ary = explode(",", $_POST["pag_order"]);
                for ($i = 1; $i < count($id_ary); $i++) {
                    $conn->query("UPDATE page SET pos='$i' WHERE id='$id_ary[$i]' ");
                }
            }
            ?>
            <form action='' method='POST'> 
                <input type = "hidden" name="pag_order" id="pag_order" /> 
                <?php
                echo "<table class='table' id='sort' border=1 cellpadding=0 cellspacing=0 > \n";
                echo "<thead>";
                echo "<tr class=title>";
                echo "<th><b>Orden</b></th>";
                echo "<th><b>Título</b></th>";
                echo "<th><b>Tipo</b></th>";
                echo "<th><b>Inicio</b></th>";
                echo "<th><b>Padre</b></th>";
                echo "<th><b>Activo</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $result = $conn->query("SELECT * FROM `page` WHERE parent='$id' ORDER BY pos ") or trigger_error($conn->error);
                while ($row = $result->fetch_array()) {
                    foreach ($row AS $key => $value) {
                        $row[$key] = stripslashes($value);
                    }
                    echo "<tr id=" . $row['id'] . "> \n";
                    echo "<td valign='top'><b>" . $row['pos'] . "</b></td>";
                    echo "<td valign='top'><b>" . $row['title'] . "</b></td>";
                    echo "<td valign='top'>" . $row['type'] . "</td>";
                    echo "<td valign='top'>";
                    if ($row['startpage'] == 1) {
                        echo 'Si';
                    } else {
                        echo 'No';
                    }
                    echo "</td>";
                    $parnt = $row['parent'];
                    $rowp = mysqli_fetch_array($conn->query("SELECT * FROM `page` WHERE `id` = '$parnt' "));
                    if ($row['parent'] == $rowp['id']) {
                        echo "<td valign='top'><b>" . $rowp['title'] . "</b></td>";
                    } else {
                        echo "<td valign='top'>Página principal</td>";
                    }
                    echo "<td valign='top'>";
                    if ($row['active'] == 1) {
                        echo 'Si';
                    } else {
                        echo 'No';
                    }
                    echo "</td>";
                    echo "<td valign='top'><a href='dashboard.php?cms=pages&w=edit&id={$row['id']}'>Editar</a></td><td><a href='deletePage.php?id={$row['id']}'>Eliminar</a></td> ";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr class=title>";
                echo "<th><b>Orden</b></th>";
                echo "<th><b>Título</b></th>";
                echo "<th><b>Tipo</b></th>";
                echo "<th><b>Inicio</b></th>";
                echo "<th><b>Padre</b></th>";
                echo "<th><b>Activo</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</tfoot>";
                echo "</table>";
                ?> 
                <input type="submit" class="btnSave" name="submitOrd" value="Guardar Orden" onClick="saveOrderImg();" />
            </form>
        </div>           
        <?php
    } elseif ($w === 'delete') {
        ?> 
        <div class="container">
            <p>
                <a class="btn btn-secondary" href='listPage.php'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='newPage.php'>Nueva Página</a> 
            </p>
            <h3>Eliminado de Páginas</h3> 
            <?php
            $id = (int) $_GET['id'];
            $conn->query("DELETE FROM `page` WHERE `id` = '$id' ");
            echo "Página Eliminada.";
            header("Location: listPage.php");
            ?> 
        </div>
        <?php
    }
}
?>
       


