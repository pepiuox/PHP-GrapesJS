<?php

if (isset($_POST['addrow'])) {
    $fecha_salida = $_POST['fecha_salida'];
    $ejecutivo = $_POST['ejecutivo'];
    $tipo_gasto = $_POST['tipo_gasto'];
    $gastos = $_POST['gastos'];
    $monto = $_POST['monto'];
    $observaciones = $_POST['observaciones'];
    $actualizado = $_POST['actualizado'];

    $sql = "INSERT INTO salidas (fecha_salida, ejecutivo, tipo_gasto, gastos, monto, observaciones, actualizado)
VALUES ('$fecha_salida', '$ejecutivo', '$tipo_gasto', '$gastos', '$monto', '$observaciones', '$actualizado')";
    if ($conn->query($sql) === TRUE) {
        echo 'Se agrego el dato correctamente';
        header('Location: index.php?w=list&tbl=salidas');
    } else {
        echo 'Error: ' . $conn->error;
    }

    $conn->close();
}
?> 
