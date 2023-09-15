<?php
            if (isset($_GET['w']) && !empty($_GET['w'])) {
                $w = $_GET['w'];

                if ($w === 'list') {
                    ?>

<div class='w-100'>
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=add'>Agregar Nuevo Componente</a> 
                </p>
                <h3>Lista de Componentes</h3>

                <?php
                echo "<table class='table' border=1 cellpadding=0 cellspacing=0 >";
                echo "<thead>";
                echo "<tr class=title>";
                echo "<th><b>Id</b></th>";
                echo "<th><b>Componente</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $result = $database->query("SELECT * FROM `type_blocks`") or trigger_error($database->error);
                while ($row = $result->fetch_array()) {
                    foreach ($row AS $key => $value) {
                        $row[$key] = stripslashes($value);
                    }
                    echo "<tr>";
                    echo "<td valign='top'>" . $row['id'] . "</td>";
                    echo "<td valign='top'>" . $row['type_block'] . "</td>";
                    echo "<td valign='top'><a href='dashboard.php?cms=typeblocks&w=edit&id={$row['id']}'>Editar</a></td><td><a href='dashboard.php?cms=typeblocks&w=delete&?id={$row['id']}'>Eliminar</a></td> ";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr class=title>";
                echo "<th><b>Id</b></th>";
                echo "<th><b>Componente</b></th>";
                echo "<th></th><th></th>";
                echo "</tr>";
                echo "</tfoot>";
                echo "</table>";
                ?> 
            </div>

            <?php }elseif ($w === 'add') {
                    ?>

  <div class='w-100'> 
                <?php
                if (isset($_POST['submitted'])) {
                    $sql = "INSERT INTO `type_blocks` ( `type_block`  ) VALUES(  '{$_POST['type_block']}'  ) ";
                    $database->query($sql) or die($database->error);
                    echo "Fila Agregada.<br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                ?>
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=list'>Retornar a la Lista</a> 
                </p>
                <h3>Agregar un Componente</h3> 

                <form action='' method='POST'> 
                    <div class='col-md-6'><label class="form-label">Componente:</label><br /><input type="text" class="form-control" name='type_block' id='type_block'/></div> 
                    <div class='col-md-6'><input class="btn btn-primary" type='submit' value='Agregar Tipo' /><input type='hidden' value='1' name='submitted' /></div> 
                    
                </form> 

            </div>
            

            <?php }elseif ($w === 'edit') {
                    ?>

 <div class='w-100'> 
                <?php
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = (int) $_GET['id'];
                    if (isset($_POST['submitted'])) {
                        $sql = "UPDATE `type_blocks` SET  `type_block` =  '{$_POST['type_block']}'   WHERE `id` = '$id' ";
                        $database->query($sql) or die($database->error);
                        echo ($database->affected_rows) ? "Fila Editada.<br />" : "No se hizo Cambios. <br />";
                        echo '<meta http-equiv="refresh" content="0">';
                    }
                    $row = mysqli_fetch_array($database->query("SELECT * FROM `type_blocks` WHERE `id` = '$id' "));
                    ?>
                    <p>
                        <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=add'>Nuevo componente</a> 
                    </p>
                    <h3>Editar componente</h3> 

                    <form action='' method='POST'> 
                        <div class='w-100'><label class="form-label">componente:</label><br /><input type="text" class="form-control" name='type_block' id='type_block' value='<?php echo $row['type_block']; ?>' /></div> 
                        <div class='w-100'><input class="btn btn-primary" type='submit' value='Editar Fila' /><input type='hidden' value='1' name='submitted' /></div> 
                        
                    </form> 

                <?php } ?> 
            </div>
            

            <?php }elseif ($w === 'view') {
                    ?>

<div class='w-100'> 
                <?php
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = (int) $_GET['id'];
                }
                $row = mysqli_fetch_array($database->query("SELECT * FROM `type_blocks` WHERE `id` = '$id' "));
                ?>
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=add'>Nuevo componente</a> 
                </p>
                <h3>Vista de componente</h3> 

                <div class='w-100'> 
                    <div class='col-md-6'><label class="form-label">componente:</label><?php echo $row['type_block']; ?></div> 
                </div> 
            </div>

            <?php }elseif ($w === 'delete') {
                    ?>

<div class='w-100'> 
                <p>
                    <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard.php?cms=typeblocks&w=add'>Nuevo componente</a> 
                </p>
                <h3>Eliminado de Componente</h3> 

                <?php
                $id = (int) $_GET['id'];
                $database->query("DELETE FROM `type_blocks` WHERE `id` = '$id' ");
                echo ($database->affected_rows) ? "Fila Eliminada.<br /> " : "No se Elimino.<br /> ";
                ?> 

            </div>

            <?php }
            }
                ?>
