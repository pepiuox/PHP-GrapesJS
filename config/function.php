<?php

/* Protect html content in your query
 * 
 */

function protect($str) {
    global $conn;
    $str = trim($str);
    $str = stripslashes($str);
    $str = htmlentities($str, ENT_QUOTES);
    $str = htmlspecialchars($str, ENT_QUOTES);
    $str = mysqli_real_escape_string($conn, $str);
    return $str;
}

function decodeContent($str) {
    if (!empty($str)) {
        return htmlspecialchars_decode(html_entity_decode($str, ENT_QUOTES));
    }
}

/* get number of pages
 * 
 */

function numpages() {
    global $conn;
    return $conn->query("SELECT id FROM page")->num_rows;
}

/* get number of visitor
 * 
 */

function numvisitor() {
    global $conn;
    return $conn->query("SELECT ip FROM active_guests")->num_rows;
}

/* get number of users
 * 
 */

function numusers() {
    global $conn;
    return $conn->query("SELECT verified FROM users WHERE verified='1'")->num_rows;
}

/*
 * nparent() 
 * This function gives us a list of pages, for add parent page in add.php
 */

function nparent() {
    global $conn;
    $result = $conn->query("SELECT * FROM page");
    $numr = $result->num_rows;
    $sp = "";
    if ($numr > 0) {
        $sp .= '<select class="form-select" name="parent" id="parent">' . "\n";
        $sp .= '<option>Select a parent</option>' . "\n";
        while ($row = $result->fetch_array()) {
            $sp .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>' . "\n";
        }
        $sp .= '</select>' . "\n";
    } else {
        $sp .= '<select class="form-select" name="parent" id="parent">' . "\n";
        $sp .= '<option>There are no pages yet</option>' . "\n";
        $sp .= '</select>' . "\n";
    }

    return $sp;
}

function vwparent($parent) {
    global $conn;
    if ($parent > 0) {
        $result = $conn->query("SELECT * FROM page WHERE id='$parent'");
        $row = $result->fetch_assoc();
        echo $row['title'];
    }
}

function sparent($parent) {
    global $conn;

    $result = $conn->query("SELECT * FROM page");
    $sp = "";
    $sp .= '<select class="form-select" name="parent" id="parent">';
    $sp .= '<option>Select a parent</option>';
    while ($row = $result->fetch_array()) {
        $select = $parent == $row['id'] ? ' selected' : null;
        $sp .= '<option value="' . $row['id'] . '"' . $select . '>' . $row['title'] . '</option>';
    }
    $sp .= '</select>';
    return $sp;
}

function pparent($parent) {
    global $conn;
    $result = $conn->query("SELECT * FROM page");
    echo '<select class="form-select" name="parent" id="parent">' . "\n";
    echo '<option>Select a parent</option>' . "\n";
    while ($row = $result->fetch_array()) {
        $select = $parent == $row['id'] ? ' selected' : null;
        echo '<option value="' . $row['id'] . '"' . $select . '>' . $row['title'] . '</option>' . "\n";
    }
    echo '</select>' . "\n";
}

function slmenu() {
    global $conn;
    $result = $conn->query("SELECT * FROM menu_options");
    echo '<select class="form-select" name="menu" id="menu">' . "\n";
    echo '<option>Select a menu</option>' . "\n";
    while ($row = $result->fetch_array()) {

        echo '<option value="' . $row['id'] . '">' . $row['id_menu'] . '</option>' . "\n";
    }
    echo '</select>' . "\n";
}

function menuopt($menu) {
    global $conn;
    $result = $conn->query("SELECT * FROM menu_options");
    echo '<select class="form-select" name="menu" id="menu">' . "\n";
    echo '<option>Select a menu</option>' . "\n";
    while ($row = $result->fetch_array()) {
        $select = $menu == $row['id'] ? ' selected' : null;
        echo '<option value="' . $row['id'] . '"' . $select . '>' . $row['id_menu'] . '</option>' . "\n";
    }
    echo '</select>' . "\n";
}

function enum_values($table, $field, $enum) {
    global $conn;
    $type = $conn->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")->fetch_array(MYSQLI_ASSOC)['Type'];
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    $frmp = ucfirst(str_replace("_", " ", $field));
    echo '<div class="form-group">
                       <label for="' . $field . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $field . '" name="' . $field . '" >' . "\n";
    foreach ($enum as $option) {
        $soption = '<option value="' . $option . '"';
        $soption .= ($enum === $option) ? ' SELECTED' : '';
        $soption .= '>' . $option . '</option>';
        echo $soption . "\n";
    }
    echo '</select>' . "\n";
    echo '</div>' . "\n";
}

function selType($tble, $c_nm, $enum) {
    global $conn;
    $sql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tble . "' AND COLUMN_NAME = '" . $c_nm . "'";

    $iresult = $conn->query($sql);
    $row = $iresult->fetch_array();
    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    $frmp = ucfirst(str_replace("_", " ", $c_nm));
    //
    echo '<div class="form-group">
                       <label for="' . $c_nm . '">' . $frmp . ':</label>
                       <select class="form-select" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";
    $default_value = $enum;
    foreach ($enum_list as $option) {
        $soption = '<option value="' . $option . '"';
        $soption .= ($default_value === $option) ? ' SELECTED' : '';
        $soption .= '>' . $option . '</option>';
        echo $soption . "\n";
    }
    echo '</select>' . "\n";
    echo '</div>' . "\n";
}

function enumsel($tble, $labelc, $nrow = '') {
    global $conn;
    $remp = ucfirst(str_replace("_", " ", $labelc));
    $frmp = str_replace(" id", "", $remp);

    $isql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tble . "' AND COLUMN_NAME = '" . $labelc . "'";
    $iresult = $conn->query($isql);
    $row = $iresult->fetch_array();
    $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
    $default_value = $nrow;
    //
    echo '<div class="form-group">
        <label for="' . $labelc . '" class ="control-label col-sm-3">' . $frmp . ':</label>
            <select class="form-select" id="' . $labelc . '" name="' . $labelc . '" >' . "\n";

    $options = $enum_list;
    foreach ($options as $option) {
        $soption = '<option value="' . $option . '"';
        $soption .= ($default_value === $option) ? ' SELECTED' : '';
        $soption .= '>' . $option . '</option>';
        echo $soption . "\n";
    }
    echo '</select>' . "\n";
    echo '</div>' . "\n";
}

function action($selected) {
    $acti = array([0, 'NO'], [1, 'YES']);
    foreach ($acti as list($key, $val)) {
        $select = $selected == $key ? ' selected' : null;
        echo '<option value="' . $key . '"' . $select . '>' . $val . '</option>' . "\n";
    }
}

function startpg($selected) {
    $acti = array([0, 'NO'], [1, 'YES']);
    foreach ($acti as list($key, $val)) {
        $select = $selected == $key ? ' selected' : null;
        echo '<option value="' . $key . '"' . $select . '>' . $val . '</option>' . "\n";
    }
}

function vwaction($selected) {
    $acti = array([0, 'NO'], [1, 'YES']);
    foreach ($acti as list($key, $val)) {
        if ($selected == $key) {
            echo $val;
        }
    }
}

function clean_string($string) {

    $string = trim($string);

    $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
    );

    $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
    );

    $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
    );

    $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
    );

    $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
    );

    $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
    );

    $string = str_replace(' ', '-', $string);

    /*
      $string = str_replace(
      array("\", "¨", "º", "-", "~",
      "#", "@", "|", "!", """,
      "·", "$", "%", "&", "/",
      "(", ")", "?", "'", "¡",
      "¿", "[", "^", "<code>", "]",
      "+", "}", "{", "¨", "´",
      ">", "< ", ";", ",", ":",
      ".", " "),
      '',
      $string
      );
     */

    return $string;
}

?>
