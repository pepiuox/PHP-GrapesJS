<?php
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://";
$baseweb = $protocol . $_SERVER["HTTP_HOST"] . "/";
?>
<!-- Section CSS -->
<!-- jQuery UI (REQUIRED) -->
<link rel="stylesheet" type="text/css" href="<?php echo $baseweb; ?>assets/plugins/jquery-ui/jquery-ui.css">
<!-- elFinder CSS (REQUIRED) -->
<link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
<link rel="stylesheet" type="text/css" href="css/theme.css">
<!-- Section JavaScript -->
<!-- jQuery and jQuery UI (REQUIRED) -->
<script src="<?php echo $baseweb; ?>assets/plugins/jquery/jquery.min.js">
</script>
<script src="<?php echo $baseweb; ?>assets/plugins/jquery-ui/jquery-ui.min.js">
</script>
<!-- elFinder JS (REQUIRED) -->
<script src="<?php echo $baseweb; ?>core/filemanager/js/elfinder.min.js"></script>
<!-- Extra contents editors (OPTIONAL) -->
<script src="<?php echo $baseweb; ?>core/filemanager/js/extras/editors.default.min.js"></script>
<!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
<!--<script src="js/extras/quicklook.googledocs.js"></script>-->
<!-- elFinder initialization (REQUIRED) -->

<script type="text/javascript" charset="utf-8">
$(document).ready(function () {
var funcNum = getUrlParam('CKEditorFuncNum');

var elf = $('#elfinder').elfinder({
url: 'php/connector.php',
getFileCallback: function (file) {
window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
elf.destroy();
window.close();
},
resizable: false
}).elfinder('instance');


$("#remove_image").hide();
$("#set_image").show();

$("#set_image").click(function (e) {
var elfinder = $("#elfinder").elfinder({
cssAutoLoad: false, // Disable CSS auto loading
baseUrl: './', // Base URL to css/*, js/*
url: '<?php echo $baseweb; ?>core/filemanager/php/connector.php', // connector URL (REQUIRED)
resizable: false,
onlyMimes: ["image"],
uiOptions: {
// toolbar configuration
toolbar: [
["reload"],
["open", "download", "getfile"],
["duplicate", "rename", "edit", "resize"],
["quicklook", "info"],
["search"],
["view", "sort"]
]
},
getfile: {
onlyURL: true,
multiple: false,
folders: false,
oncomplete: ""
},
getFileCallback: function(file) {
  if (file.mime.match(/^image\//i)) {
$("#page_image").val(file.url);
var imgPath = '<img src="'+file.url+'" id="append-image" style="width:280px;height:auto;background-size:contain;margin-bottom:.9em;background-repeat:no-repeat" />';
$("#picture").append(imgPath); //add the image to a div so you can see the selected images
$("#remove_image").show();
$("#set_image").hide();
$('#elfinder').remove();
  }
},
handlers: {
dblclick: function (event, elfinderInstance) {
fileInfo = elfinderInstance.file(event.data.file);

if (fileInfo.mime != "directory") {
var imgURL = elfinderInstance.url(event.data.file);
$("#page_image").val(imgURL);

var imgPath = '<img src="'+imgURL+'" id="append-image" style="width:280px;height:auto;background-size:contain;margin-bottom:.9em;background-repeat:no-repeat"/>';
$("#picture").append(imgPath); //add the image to a div so you can see the selected images

$("#remove_image").show();
$("#set_image").hide();

elfinderInstance.destroy();
return false; // stop elfinder
};
},
destroy: function () {
elfinder.dialog("close");

}
}
}).dialog({
title: "filemanager",
resizable: true,
width: 800,
height: 420
});
$("#remove_image").click(function () {

$("#page_image").val("");
$("#elfinder_image").find("#append-image").remove(); //remove image from div when user clicks remove image button.

$("#remove_image").hide();
$("#set_image").show();

return false;
});
});
});
</script>
<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>
<div id="elfinder_image"></div>
<div id="picture"></div>
<button type="button" id="set_image" class="btn btn-primary" style="display:none;">Set featured image</button>
<button type="button" id="remove_image" class="btn btn-primary" style="display:none;">Remove featured image</button>
<input type="hidden" class="form-control" name="page_image" id="page_image" />

  