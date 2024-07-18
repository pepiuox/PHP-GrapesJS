<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $_GET['w'];
    if ($w === 'list') {
        ?>
        <div class="container"> 
            <p>
                <a class="btn btn-secondary" href='dashboard.php?cms=blocks&w=add'>Agregar Nuevo Bloque</a> 
            </p>
            <h3>Lista de bloques </h3>
            <?php
            echo "<table class='table' border=1 >";
            echo "<thead>";
            echo "<tr class=title>";
            echo "<th><b>Bloque</b></th>";
            echo "<th><b>Activo</b></th>";
            echo "<th><b>Página</b></th>";
            echo "<th></th><th></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $result = $conn->query("SELECT * FROM `blocks` LEFT JOIN `type_blocks` ON blocks.blockId = type_blocks.id LEFT JOIN (SELECT id AS npage , title FROM pages)`page` ON blocks.pageId=page.npage");
            while ($row = $result->fetch_array()) {
                foreach ($row AS $key => $value) {
                    $row[$key] = $value;
                }
                echo "<tr>";
                echo "<td valign='top'>" . $row['type_block'] . "</td>";
                echo "<td valign='top'>";
                if ($row['active'] == 1) {
                    echo 'Si';
                } else {
                    echo 'No';
                }
                echo "</td>";
                echo "<td valign='top'>" . $row['title'] . "</td>";
                echo "<td valign='top'><a href=dashboard.php?cms=blocks&w=edit&idB={$row['idB']}>Editar</a></td><td><a href=dashboard.php?cms=blocks&w=delete&idB={$row['idB']}>Eliminar</a></td> ";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr class=title>";
            echo "<th><b>Bloque</b></th>";
            echo "<th><b>Activo</b></th>";
            echo "<th><b>Página</b></th>";
            echo "<th></th><th></th>";
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
                $sql = "INSERT INTO `blocks` ( `blockId` ,  `active` ,  `pageId`  ) VALUES(  '{$_POST['blockId']}' ,  '{$_POST['active']}' ,  '{$_POST['pageId']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Bloque Agregado.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
            <form action='' method='POST'> 
                <div class="container">
                    <label class="form-label">Tipo de Bloque:</label>
                    <?php
                    $stp1 = "SELECT * FROM type_blocks";
                    $quertp1 = $conn->query($stp1);
                    ?> 
                    <select class="form-select" name='blockId' id='blockId'/>                                     
                    <?php
                    while ($tp1 = mysqli_fetch_array($quertp1)) {
                        ?>     
                        <option value="<?php echo $tp1['id']; ?>"><?php echo $tp1['type_block']; ?></option>
                        <?php
                    }
                    ?>
                    </select>  
                </div> 
                <div class="container">
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
                <div class="container">
                    <label class="form-label">Página:</label>
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
                <div class="container">
                    <input class="btn btn-primary" type='submit' value='Agregar Bloque' />
                    <input type='hidden' value='1' name='submitted' />
                </div> 
            </form> 
        </div>
        <?php
    } elseif ($w === 'edit') {
        ?> 
        <div class="container"> 
            <?php
            if (isset($_GET['idB'])) {
                $idB = (int) $_GET['idB'];
                if (isset($_POST['submitted'])) {

                    $sql = "UPDATE `blocks` SET  `blockId` =  '{$_POST['blockId']}' ,  `active` =  '{$_POST['active']}' ,  `pageId` =  '{$_POST['pageId']}'   WHERE `idB` = '$idB' ";
                    $conn->query($sql) or die($conn->error);
                    echo "Bloque Editado.<br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                $row = $conn->query("SELECT * FROM `blocks` WHERE `idB` = '$idB' ")->fetch_assoc();
                ?>
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=blocks&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='Block.php?w=add'>Nuevo Bloque</a> 
                </p>
                <form action='' method='POST'> 
                    <div class="container">
                        <label class="form-label">Bloque:</label>
                        <label class="form-label">Tipo de Bloque:</label>
                        <?php
                        $stp1 = "SELECT * FROM type_blocks";
                        $quertp1 = $conn->query($stp1);
                        ?> 
                        <select class="form-select" name='blockId' id='blockId'/>                                     
                        <?php
                        while ($tp1 = mysqli_fetch_array($quertp1)) {
                            if ($tp1['id'] == $row['blockId']) {
                                ?>     
                                <option value="<?php echo $tp1['id']; ?>" selected><?php echo $tp1['type_block']; ?></option>
                                <?php
                            } else {
                                ?>     
                                <option value="<?php echo $tp1['id']; ?>"><?php echo $tp1['type_block']; ?></option>
                                <?php
                            }
                        }
                        ?>
                        </select>  
                    </div> 
                    <div class="container">
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
                    <div class="container"><label class="form-label">Página:</label>
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
                    <div class="container">
                        <input class="btn btn-primary" type='submit' value='Editar Bloque' />
                        <input type='hidden' value='1' name='submitted' />
                    </div> 
                </form> 
            <?php } ?> 
        </div>
        <?php
    } elseif ($w === 'delete') {
        ?> 
        <div class="container"> 
            <?php
            $idB = (int) $_GET['idB'];
            $conn->query("DELETE FROM `blocks` WHERE `idB` = '$idB' ");
            //echo "Bloque Eliminado.<br /> ";
            header("Location: listBlock.php");
            ?> 
        </div>
        <?php
    } else {
        header('Location: dashboard.php');
        exit();
    }
}
?> 
