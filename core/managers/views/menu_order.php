<?php

include 'db.php';

$menu = $_POST['menu'];
$array_menu = json_decode($menu, true);

$db->query('TRUNCATE TABLE menu');

function update_menu($menu,$parent = 0)
{
    global $db;

    

    if (!empty($menu)) {
        

        foreach ($menu as $value) {
            
            $label = $value['label'];
            $url = (empty($value['url'])) ? '#' : $value['url'];

            $sql = "INSERT INTO menu (label_menu, url_menu, parent_id) VALUES ('$label', '$url', $parent)";

            $db->query($sql);
            $id = $db->insertedId();

            if (array_key_exists('children', $value))
                update_menu($value['children'],$id);
        }

    }
}


update_menu($array_menu);

header("Location: index.php")
?>
