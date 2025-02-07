<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
    $w = $_GET['w'];

    if ($w === 'list') {
        ?>
        <div class="container">
            <p>
                <a class="btn btn-secondary" href='dashboard/typepages/add'>Agregar Nuevo Tipo de Página</a> 
            </p>
            <h3>Lista de Tipos de Página</h3>
            <?php
            echo "<table class='table' border=1 cellpadding=0 cellspacing=0 >";
            echo "<thead>";
            echo "<tr class=title>";
            echo "<th><b>Id</b></th>";
            echo "<th><b>Tipo de Página</b></th>";
            echo "<th></th><th></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $result = $conn->query("SELECT * FROM `type_page`") or trigger_error($conn->error);
            while ($row = $result->fetch_array()) {
                foreach ($row AS $key => $value) {
                    $row[$key] = $value;
                }
                echo "<tr>";
                echo "<td valign='top'>" . $row['id'] . "</td>";
                echo "<td valign='top'>" . $row['type_page'] . "</td>";
                echo "<td valign='top'><a href='dashboard/typepages/edit&id={$row['id']}'>Editar</a></td><td><a href='dashboard/typepages/delete&?id={$row['id']}'>Eliminar</a></td> ";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "<tfoot>";
            echo "<tr class=title>";
            echo "<th><b>Id</b></th>";
            echo "<th><b>Tipo de Página</b></th>";
            echo "<th></th><th></th>";
            echo "</tr>";
            echo "</tfoot>";
            echo "</table>";
            ?> 
        </div>
    <?php } elseif ($w === 'add') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_POST['submitted'])) {
                $sql = "INSERT INTO `type_page` ( `type_page`  ) VALUES(  '{$_POST['type_page']}'  ) ";
                $conn->query($sql) or die($conn->error);
                echo "Fila Agregada.<br />";
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
            <p>
                <a class="btn btn-secondary" href='dashboard/typepages/list'>Retornar a la Lista</a> 
            </p>
            <h3>Agregar un Tipo de Página</h3> 
            <form action='' method='POST'> 
                <div class='col-md-6'>
                    <label class="form-label">Tipo de Página:</label>
                    <input type="text" class="form-control" name='type_page' id='type_page'/>
                </div> 
                <div class='col-md-6'>
                    <input class="btn btn-primary" type='submit' value='Agregar Tipo' />
                    <input type='hidden' value='1' name='submitted' />
                </div> 
            </form> 
        </div>
    <?php } elseif ($w === 'edit') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                if (isset($_POST['submitted'])) {
                    $sql = "UPDATE `type_page` SET  `type_page` =  '{$_POST['type_page']}'   WHERE `id` = '$id' ";
                    $conn->query($sql) or die($conn->error);
                    echo ($database->affected_rows) ? "Fila Editada.<br />" : "No se hizo Cambios. <br />";
                    echo '<meta http-equiv="refresh" content="0">';
                }
                $row = mysqli_fetch_array($conn->query("SELECT * FROM `type_page` WHERE `id` = '$id' "));
                ?>
                <p>
                    <a class="btn btn-secondary" href='dashboard/typepages/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/typepages/add'>Nuevo tipo de página</a> 
                </p>
                <h3>Editar tipo de página</h3> 
                <form action='' method='POST'> 
                    <div class="container">
                        <label class="form-label">Tipo de página:</label><br />
                        <input type="text" class="form-control" name='type_page' id='type_page' value='<?php echo $row['type_page']; ?>' />
                    </div> 
                    <div class="container">
                        <input class="btn btn-primary" type='submit' value='Editar Fila' />
                        <input type='hidden' value='1' name='submitted' />
                    </div> 
                </form> 
            <?php } ?> 
        </div>
    <?php } elseif ($w === 'view') {
        ?>
        <div class="container"> 
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id'];
            }
            $row = mysqli_fetch_array($conn->query("SELECT * FROM `type_page` WHERE `id` = '$id' "));
            ?>
            <p>
                <a class="btn btn-secondary" href='dashboard/typepages/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/typepages/add'>Nuevo tipo de página</a> 
            </p>
            <h3>Vista de type_page</h3> 
            <div class="container"> 
                <div class='col-md-6'>
                    <label class="form-label">Tipo de pagina:</label><?php echo $row['type_page']; ?>
                </div> 
            </div> 
        </div>
    <?php } elseif ($w === 'delete') {
        ?>
        <div class="container"> 
            <p>
                <a class="btn btn-secondary" href='dashboard/typepages/list'>Retornar a la Lista</a> - <a class="btn btn-secondary" href='dashboard/typepages/add'>Nuevo tipo de página</a> 
            </p>
            <h3>Eliminado de type_page</h3> 
            <?php
            $id = (int) $_GET['id'];
            $conn->query("DELETE FROM `type_page` WHERE `id` = '$id' ");
            echo ($database->affected_rows) ? "Fila Eliminada.<br /> " : "No se Elimino.<br /> ";
            ?> 
        </div>
        <?php
    }
}
?>
