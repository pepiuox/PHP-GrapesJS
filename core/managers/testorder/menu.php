
<?php
if ($session->logged_in) {
    ?>
    <header>
        <div class="container topbarmenu">
            <div class="col_x_12">
                <div id='headm' class='default'>                    
                    <ul class="topnav">
                        <?php

                        function mmenu($parent) {
                            global $database;
                            $menu = "";
                            $result = $database->query("SELECT id, language, post, title, link, image, parent, sort FROM page WHERE parent='$parent'");

                            while ($row = $result->fetch_array()) {
                                $menu .= "  <li><a href='" . $row['link'] . "' data-src='" . $row['image'] . "'>" . $row['label'] . "</a>\n";
                                if ($row['sort'] == 1) {
                                    $menu .= "   <ul>\n" . mmenu($row['id']);
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
            </div>
        </div>
    </header>
    <?php
}
?>
