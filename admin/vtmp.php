<?php

if ($key == 0) {
	o '<td id="' . $rw['idPrsn'] . '">' . $rw['idPrsn'] . '</td>';
} elseif ($name == 'granja_id') {
	o '<td>' . $rw['nombre_granja'] . '</td>';
} elseif ($name == 'tipo_financiera_id') {
	o '<td>' . $rw['tipo'] . '</td>';
} elseif ($name == 'banco_financiera_id') {
	o '<td>' . $rw['nombre_entidad'] . '</td>';
} elseif ($name == 'cargo_id') {
	o '<td>' . $rw['cargo'] . '</td>';
} elseif ($name != "idGrnj" && $name != "nombre_granja" && $name != "idFncr" && $name != "tipo" && $name != "idEnfc" && $name != "nombre_entidad" && $name != "idCrg" && $name != "cargo") {
	o '<td>' . $rw[$name] . '</td>';
}
?>
