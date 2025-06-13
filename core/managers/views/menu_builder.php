<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    if (isset($_POST["submit"])) {
        $id_ary = explode(",", $_POST["row_order"]);
        for ($i = 1; $i < count($id_ary); $i++) {
            $conn->query("UPDATE pages SET position='" . $i . "' WHERE id=" . $id_ary[$i]);
        }
    }
    ?>
    <link href="<?php echo SITE_PATH; ?>assets/css/sortablemenu.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo SITE_PATH; ?>assets/js/sortablemenu.js" type="text/javascript"></script>

    <style>
        ul {
            list-style-type: none;
        }
    #sortable-row { 
    list-style: display; 
    }
    #sortable-row li { 
    margin-bottom:4px; 
    padding:2px 10px; 
    background-color:#EEEEEE;
    cursor:move;
    }
    #sortable-row li.ui-state-highlight { 
    height: 20px; 
    background-color:#F0F0F0;
    border:#ccc 2px dotted;
    }
    </style>
    <script>
        $(function(){
            var group = $("ul.serialization").sortable({
  group: 'serialization',
  delay: 500,
  onDrop: function ($item, container, _super) {
    var data = group.sortable("serialize").get();

    var jsonString = JSON.stringify(data, null, ' ');

    $('#serialize_output2').text(jsonString);
    _super($item, container);
  }
});
        });
        
        $(function() {
        $("ul.nav").sortable({
      group: 'nav',
      nested: false,
      vertical: false,
      exclude: '.divider-vertical',
      onDragStart: function($item, container, _super) {
        $item.find('ul.dropdown-menu').sortable('disable');
        _super($item, container);
      },
      onDrop: function($item, container, _super) {
        $item.find('ul.dropdown-menu').sortable('enable');
        _super($item, container);
      }
    });

    $("ul.dropdown-menu").sortable({
      group: 'nav'
    }); 
      });
      
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
    <div class="container">
        <form name="menuorder" method="POST" />
        <h4>Sort Menu.</h4>
        <input type = "hidden" name="row_order" id="row_order" /> 
        <div class="switch-container">
            <ul id="sortable-row">   
    <?php

    function mmenu($parent) {
        global $conn;
        $menu = "";
        $result = $conn->query("SELECT id, language, position, title, link, image, parent, sort FROM pages WHERE parent='$parent' ORDER BY position");
        while ($rmen = $result->fetch_array()) {
            $menu .= "  <li name='mast' id='{$rmen['id']}'><a href='" . SITE_PATH . "admin/dashboard/edit_page/{$rmen['id']}'>" . $rmen['title'] . "</a>\n";
            if ($rmen['sort'] > 0) {
                $menu .= "   <ul>\n";
                $menu .= mmenu($rmen['id']);
                $menu .= "   </ul>\n";
            }
            $menu .= "  </li>\n";
        }
        return $menu;
    }

    echo mmenu(0);
    ?>
            </ul>
        </div>
        <input type="submit" class="btnSave" name="submit" value="Save menu" onClick="saveOrder();" />
        <div class="clear"></div>
    </form> 
    </div>
    <?php
} else {
    echo '<meta http-equiv="refresh" content="0;url=' . SITE_PATH . '">' . "\n";
}
?>