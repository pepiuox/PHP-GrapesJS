<?php

session_start();
$clear = $_POST['clear'];

if ('clear' === $clear) {
    if (isset($_SESSION["page"])) {
        unset($_SESSION["title"]);
        unset($_SESSION["page"]);
        session_destroy();
        echo '<script>location.reload(true)</script>';
    } else {
        echo '<script>location.reload(true)</script>';
    }
    // remove PHPSESSID from browser
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time() - 3600, " / ");
        // clear session from globals
        $_SESSION = array();
        // clear session from disk
        session_destroy();
    }
}
?>
