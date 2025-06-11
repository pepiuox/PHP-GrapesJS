<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {

    if ($cms === "files") {
        ?> 
<div class="container-fluid">
    <div class="row">
<!-- The filemanager content -->
        <div id="filemanager">
        <?php require URL . '/core/filemanager/elfinder.legacy.php'; ?>
        </div>   
    </div>
 </div> 
        <?php
    }
}
?>
