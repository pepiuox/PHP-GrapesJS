<?php
    $SESSION = new SESSION_handler();
    if($SESSION->session_expired()){
        echo "true";
    }else{
        echo "false";
    }
?>

