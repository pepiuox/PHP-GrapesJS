<?php
include 'db.php';

function renderMenuItem($id, $label, $url)
{
    return '<li class="dd-item dd3-item" data-id="' . $id . '" data-label="' . $label . '" data-url="' . $url . '">' .
        '<div class="dd-handle dd3-handle" > Drag</div>' .
        '<div class="dd3-content"><span>' . $label . '</span>' .
        '<div class="item-edit">Edit</div>' .
        '</div>' .
        '<div class="item-settings d-none">' .
        '<p><label for="">Navigation Label<br><input type="text" name="navigation_label" value="' . $label . '"></label></p>' .
        '<p><label for="">Navigation Url<br><input type="text" name="navigation_url" value="' . $url . '"></label></p>' .
        '<p><a class="item-delete" href="javascript:;">Remove</a> |' .
        '<a class="item-close" href="javascript:;">Close</a></p>' .
        '</div>';

}

function menuTree($parent_id = 0)
{
    global $db;
    $items = '';
    $query = $db->query("SELECT * FROM menu WHERE parent_id = ? ORDER BY id_menu ASC", $parent_id);
    if ($query->numRows() > 0) {
        $items .= '<ol class="dd-list">';
        $result = $query->fetchAll();
        foreach ($result as $row) {
            $items .= renderMenuItem($row['id_menu'], $row['label_menu'], $row['url_menu']);
            $items .= menuTree($row['id_menu']);
            $items .= '</li>';
        }
        $items .= '</ol>';
    }
    return $items;
}

?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Nestable</title>
    <meta name="description" content="Nestable">
    <meta name="author" content="codeamend">
    <link rel="stylesheet" href="css/nestable.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>



    <form id="add-item">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="url" placeholder="Url">
        <button type="submit">Add</button>
    </form>

    <hr />

    <div class="dd" id="nestable">
        <?php
            $html_menu = menuTree();
            echo (empty($html_menu)) ? '<ol class="dd-list"></ol>' : $html_menu;
        ?>
    </div>


    <hr />
    <form action="menu.php" method="post">
        <input type="hidden" id="nestable-output" name="menu">
        <button type="submit">Save</button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Nestable/2012-10-15/jquery.nestable.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html> 
