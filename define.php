<?php

$define = $conn->query("SELECT * FROM config");
while ($def = $define->fetch_array()) {    
    return define($def['type_name'], $def['value']) . "\n";
}