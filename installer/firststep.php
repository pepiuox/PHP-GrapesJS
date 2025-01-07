<?php
$filed = '../config/domains.php';
if (file_exists($filed)) {
    unlink($filed);
}
if (isset($_POST['save'])) {
    $doma = $_POST['domain'];
    $serv = $_POST['server'];

    foreach ($doma as $key => $n) {
        $local = 'localhost';
        if (str_contains($n, $local)) {
            $sitenm = str_replace(":", "_", $n);
        } else {
            $sitenm = str_replace(".", "_", $n);
        }
        $im[] = "'" . $sitenm . "' => ['dataserver' => '" . $serv[$key] . "']";
    }

    $cont = "<?php" . "\n";
    $cont .= "return [" . "\n";
    $cont .= "'domains' => [" . "\n";
    $cont .= implode(',', $im) . "\n";
    $cont .= "]" . "\n";
    $cont .= "]" . "\n";
    $cont .= "?>" . "\n";
    file_put_contents($filed, $cont, FILE_APPEND | LOCK_EX);
}
?>
<script>
function add(){
// Clonar contenedor, eliminar ID
let new = $('#itemGroup').clone();
new.attr('id', '');
// Agregar clase para poder obtener el padre al eliminar
new.addClass('itemGroup');
new.find('input').each(function() {
// Solo establecer el valor
this.value = '';
// Dejar el nombre con corchetes, para que sea un arreglo
});
// Insertar nuevo contenedor antes del botón "Agregar"
$(new).insertBefore('#item-add');
}
// Función para eliminar
function removeThisFile(ele) {
// $(this) es el elemento que disparó el evento
// ele no es el elemento, sino el evento
// Obtener padre por clase, usando closest()
$(this).closest('.itemGroup').remove();
}
// Escuchar clic en botón Agregar
$('#item-add .button').on('click', add);
// Escuchar clic en botones para borrar
$(document.body).on('click', '.item-delete', removeThisFile);
</script>
<div class="alert alert-success" role="alert">
<h5>Admin registration </h5>
</div>
<div id="itemGroup" class="input-group mb-3">
<span class="input-group-text">Domain name</span>
<input type="text" name="domain[]" class="form-control" placeholder="Sitename" aria-label="Sitename" value="<?php echo $_SERVER['HTTP_HOST']; ?>">
<span class="input-group-text">DB reference name</span>
<input type="text" name="server[]" class="form-control" placeholder="DB Server" aria-label="DB Server" value="cms_1">
<button class="btn btn-outline-danger item-delete" type="button" name="delete" id="button">Delete</button>
</div>
<div class="col-md-2" id="item-add">
<p class="text-right" style="padding-top: 1.5rem">
<button type="button" class="btn btn-info" onclick="add();">
<i class="mi-add"></i>
Add
</button>
</p>
</div>
<div class="col-md-2" id="item-check">
<p class="text-right" style="padding-top: 1.5rem">
<button type="submit" name="save" class="btn btn-info">
   Save domains and server names
</button>
</p>
</div>

 

