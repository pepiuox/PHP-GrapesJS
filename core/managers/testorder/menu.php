
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
                            global $conn; // ✅ PDO: conexión canónica global (antes $database, inexistente).
                            $menu = "";
                            $result = $conn->prepare("SELECT id, language, position, title, link, image, parent, sort FROM pages WHERE parent=:parent");
                            $result->execute([':parent' => $parent]);

                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
