<?php



$menu = $_POST['menu'];
for ($i = 0; $i < count($menu); $i++) {
    $database->query("UPDATE `menu` SET `sort`=" . $i . " WHERE `id`='" . $menu[$i] . "'") or die($database->error);
}
?>