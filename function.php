<?php

function protect($str) {
    global $conn;
    $str = trim($str);
    $str = stripslashes($str);
    $str = htmlentities($str, ENT_QUOTES);
    $str = mysqli_real_escape_string($conn, $str);
    return $str;
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
        $sp .= '<select class="form-control" name="parent" id="parent">' . "\n";
        $sp .= '<option>Select a parent</option>' . "\n";
        while ($row = $result->fetch_array()) {
            $sp .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>' . "\n";
        }
        $sp .= '</select>' . "\n";
    } else {
        $sp .= '<select class="form-control" name="parent" id="parent">' . "\n";
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
    $sp .= '<select class="form-control" name="parent" id="parent">';
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
    echo '<select class="form-control" name="parent" id="parent">' . "\n";
    echo '<option>Select a parent</option>' . "\n";
    while ($row = $result->fetch_array()) {
        $select = $parent == $row['id'] ? ' selected' : null;
        echo '<option value="' . $row['id'] . '"' . $select . '>' . $row['title'] . '</option>' . "\n";
    }
    echo '</select>' . "\n";
}

function action($selected) {
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
