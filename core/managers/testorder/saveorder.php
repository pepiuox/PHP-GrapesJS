<?php

$orderlist = explode(',', $_POST['order']);
foreach ($orderlist as $k => $order) {
    echo 'Id for position ' . $k . ' = ' . $order . '<br>';
}
?>