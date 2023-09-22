<div class='row'> 
    <div class="w-100">
        <h3>Subir imagenes con administrador de archivos</h3>
        <script src="<?php echo SYST; ?>js/jquery.popupwindow.js" type="text/javascript"></script> 
        <script type="text/javascript">
            $(document).ready(function () {
                $('#imageUpload').on('click', function (event) {
                    event.preventDefault();
                    $.popupWindow('elfinder/elfinder.html', {
                        height: 460,
                        width: 960
                    });
                });
            });
        </script>
        <input type="button" id="imageUpload" value='Subir Imagenes' />
    </div>
    <div class="w-100">                     
        <h3>Subir imagenes</h3>
        <p>Cargar imagenes en jpg o png no mayores a 1600 px</p>
        <p>Usar <a href="http://www.xnview.com/en/">Xnview para reducir imagenes en volumen</a></p>
        <style>
            .gallery-bg {
                width: 100%;
                background-color: #828282;
                border-radius:4px;
            }
            #gallery{
                padding: 10px;
                text-align:center;
                font-weight: bold;
                color: #C0C0C0;
                background-color: #F0E8E0;
                overflow:auto;
                border-top-left-radius:4px;
                border-top-right-radius:4px;
            }
            #gallery img{
                padding: 10px;
            }
            #uploadFormLayer{
                padding: 10px;
            }
            .btnUpload {
                background-color: #3FA849;
                padding:5px 20px;
                border: #3FA849 1px solid;
                color: #FFFFFF;
                border-radius:4px;
            }
            .inputFile {
                padding: 4px;
                background-color: #FFFFFF;
                border-radius:4px;
            }
            .txt-subtitle {
                font-size:1.2em;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function (e) {
                $("#uploadForm").on('submit', (function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: "upload.php",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            $("#gallery").html(data);
                        },
                        error: function () {}
                    });
                }));
            });
        </script>
        <div class="gallery-bg">
            <form id="uploadForm" action="upload.php" method="post">
                <div id="gallery">No hay imagenes en galeria</div>
                <div id="uploadFormLayer">
                    <p class="txt-subtitle">Subir multiples Imagenes:</p>
                    <p><input name="userImage[]" type="file" class="inputFile" /><p>
                    <p><input name="userImage[]" type="file" class="inputFile" /><p>
                    <p><input name="userImage[]" type="file" class="inputFile" /><p>
                    <p><input name="userImage[]" type="file" class="inputFile" /><p>
                    <p><input name="userImage[]" type="file" class="inputFile" /><p>                        
                    <p><input type="submit" value="Submit" class="btnUpload" /><p>
                </div>
            </form>
        </div>
    </div>
</div>
