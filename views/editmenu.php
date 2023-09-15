<?php
if (isset($_POST["submit"])) {
    $id_ary = explode(",", $_POST["row_order"]);
    for ($i = 1; $i < count($id_ary); $i++) {
        $database->query("UPDATE page SET pos='" . $i . "' WHERE id=" . $id_ary[$i]);
    }
}
?>
<link href="<?php echo B_URL; ?>css/sortablemenu.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo B_URL; ?>js/sortablemenu.js" type="text/javascript"></script>

<style>
    #sortable-row { list-style: none; }
    #sortable-row li { margin-bottom:4px; padding:2px 10px; background-color:#EEEEEE;cursor:move;}
    #sortable-row li.ui-state-highlight { height: 20px; background-color:#F0F0F0;border:#ccc 2px dotted;}
</style>
<script>
    $(function () {
        $("#sortable-row").sortable({
            connectWith: "#sortable-row",
            placeholder: "ui-state-highlight",
            update: function (event, ui) {
                $(this).children().each(function (index) {
                    $(this).find('ul').last().html(index + 1);
                });
            }
        });
    });

    function saveOrder() {
        var selectedLanguage = new Array();
        $('ul#sortable-row li').each(function () {
            selectedLanguage.push($(this).attr("id"));
        });
        document.getElementById("row_order").value = selectedLanguage;
    }
</script>
<div class="w-100">
    <form name="frmQA" method="POST" />
    <h4 class='title' id='title0'>Ordene el menu si es necesario.</h4>
    <input type = "hidden" name="row_order" id="row_order" /> 
    <div class="switch-container">
        <ul id="sortable-row">   
            <?php

            function mmenu($parent) {
                global $database;
                $menu = "";
                $result = $database->query("SELECT id, language, pos, title, link, image, parent, sort FROM page WHERE parent='$parent' ORDER BY pos");
                while ($rmen = $result->fetch_array()) {
                    $menu .= "  <li name='mast' id='{$rmen['id']}'><a href='dashboard.php?cms=pages&w=edit&id={$rmen['id']}&lng={$rmen['language']}'>" . $rmen['title'] . "</a>\n";
                    //if ($rmen['sort'] == 1) {
                    $menu .= "   <ul>\n" . mmenu($rmen['id']);
                    $menu .= "   </ul>\n";
                    //}
                    $menu .= "  </li>\n";
                }
                return $menu;
            }

            echo mmenu(0);
            ?>
        </ul>
    </div>
    <input type="submit" class="btnSave" name="submit" value="Guardar orden" onClick="saveOrder();" />
    
</form> 
</div>
