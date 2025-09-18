<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
    if (isset($_POST["submit"])) {
        $id_ary = explode(",", $_POST["row_order"]);
        for ($i = 1; $i < count($id_ary); $i++) {
            $conn->query("UPDATE pages SET position='" . $i . "' WHERE id=" . $id_ary[$i]);
        }
    }

    
    ?>

                <link href="<?php echo SITE_PATH; ?>assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
                <script src="<?php echo SITE_PATH; ?>assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
                <style>
                    ul {
                        list-style-type: none;
                    }
                #sortable-menu { 
                list-style: none; 
                }
                #sortable-menu li { 
                margin-bottom:4px; 
                padding:2px 10px; 
                background-color:#EEEEEE;
                cursor:move;
                }
                #sortable-menu li li { 
                margin-bottom:4px; 
                padding:2px 10px; 
                background-color:#ffffff;
                cursor:move;
                }
                #sortable-menu li.ui-state-highlight { 
                height: 20px; 
                background-color:#F0F0F0;
                border:#ccc 2px dotted;
                }
                </style>
                <script>                   
                    $(function() { 
                       var ccount = $("#sortable-menu").children('li').length;
            
                        $("ul.sortable li").addClass("parent");
                        $("ul.sortable li").find("ul").addClass("child");                   
                       $("ul.child").find("li").removeClass( "parent" );
                        $( "ul" ).sortable({
                          connectWith: ".sortable",
                          revert: true
                        });
                        $( "ul, li" ).disableSelection();
                    
                        $(".sortable").sortable({		
                            update: function(event, ui){ 
                            $(":empty").each(function () {

        // Access the empty elements
                            });
                            $(".parent").each(function () { 
                                if ( $(this).find("ul").length === 0 ){
                                   if($(".child").is(':empty')) {
                                       $(this).empty();
                                   }
                                }
                            });
                            $(".child").each(function () {                                                        
                                // Check if the element is empty
                                // and apply a style accordingly
                                if (!$($(this).length)){
                                    $(this).remove();                                    
                                } else{
                                    $(this).css("border", "2px red solid");
                                }
                            });
                            
                          }
                        });            
            	
                        $("#sortable-menu").sortable({
                            connectWith: ".sortable",
                            placeholder: "ui-state-highlight",
                            update: function (event, ui) {
                                $(".parent").children().each(function (index) {
                                    if($(this).children().length === 0){
                                        $(this).remove();
                                    }
                                     $(this).children().css("border", "2px green solid");
                                });
                            }
                        });
                    
                    
                    
                        $("ul.sortable").sortable({
                            group: 'sortable',
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

                        
                    });
                    
                    function saveOrder() {
                        var selectedLanguage = new Array();
                        $('.sortable li').each(function () {
                            selectedLanguage.push($(this).attr("id"));
                        });
                        document.getElementById("row_order").value = selectedLanguage;
                    }
                    
                    function updateOrder() {	
                        var item_order = new Array();
                        $('#sortable li').each(function() {
            		item_order.push($(this).attr("id"));
                        });
                        var order_string = 'order='+item_order;
                        $.ajax({
            		type: "POST",
            		url: "update_order.php",
            		data: order_string,
            		cache: false,
            		success: function(data){			
            		}
                        });
                    }
                    
                    
                </script>
                <div class="container">
                    <form id="menuorder" method="POST" />
                    <h4>Sort Menu.</h4>
                    <input type="hidden" name="row_order" id="row_order" /> 
                    <div class="switch-container">
                        <ul id="sortable-menu" class="sortable">   
    <?php

    function mmenu($parent) {
        global $conn;
        $menu = "";
      
        $result = $conn->query("SELECT id, language, position, title, link, image, parent, sort FROM pages WHERE parent='$parent' ORDER BY position");
        while ($rmen = $result->fetch_array()) {
            $menu .= "  <li name='menulist' id='{$rmen['id']}'><a href='" . SITE_PATH . "admin/dashboard/edit_page/{$rmen['id']}'>" . $rmen['title'] . "</a>\n";
            if ($rmen['sort'] > 0) {
                $menu .= "   <ul class='sortable'>\n";
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
                    <input type="submit" class="btn btn-primary" name="submit" value="Save menu" onClick="saveOrder();" />
                    <div class="clear"></div>
                </form> 
                </div>
    <?php
} else {
    echo '<meta http-equiv="refresh" content="0;url=' . SITE_PATH . '">' . "\n";
}
?>