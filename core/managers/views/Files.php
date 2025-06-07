<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {

    if ($cms === "files") {

?>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                <label>Imagen de página:</label>  
                <script src="<?php echo SITE_PATH; ?>assets/js/jquery.popupwindow.js" type="text/javascript"></script> 
                <script type="text/javascript">
                $(document).ready(function () {
                $('#imageUpload').on('click', function (event) {
                event.preventDefault();
                $.popupWindow('<?php echo SITE_PATH; ?>core/filemanager/elfinder.legacy.php', {
                height: 520,
                width: 820
                });
                });
                });

                function processFile(file) {
                $('#picture').html('<img src="' + file + '" />');
                $('#image').val(file);
                }
                </script>
                <div id="picture">
                <img class="scale" />                            
                </div>
                <div class='col_full'>
                <input type="text" name='image' id='image' placeholder="Imagen Url" value='' readonly/>                            
                <input type="button" id="imageUpload" value='Seleccionar Imagen' />
                </div> 
                </div>
            </div>
         </div>
        <?php
    }
}
        ?>
