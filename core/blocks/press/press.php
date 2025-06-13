<!-- press block -->
<?php
$rslider = $conn->query("SELECT idPr, galId, image, title, subtitle, description, printing_date, type_press FROM press_gal LEFT JOIN (SELECT idGal, pageId FROM galleries)galleries ON press_gal.galId=galleries.idGal WHERE pageId = '$bid'");
$rows = array();
while ($row = $rslider->fetch_array()) {
    $rows[] = $row;
}
$num_cnt = count($rows);
if ($num_cnt > 0) {
    ?>
<div class="row">
        <div class="galleryShow">
            <div class="col-md-9">
                <?php
                $rslideg = $conn->query("SELECT idGal, name, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
                $rowg = $rslideg->fetch_array()
                ?>
                <div style='text-transform: uppercase; padding-left: 2px;'><?php echo $rowg['name']; ?></div>
            </div>
            <div class="col-md-3">
                <div class="controls">                                
                    <a onclick="$(document).toggleFullScreen()"><img src="<?php echo SITE_PATH; ?>assets/img/e-arrows.png" /></a>                                                 
                </div>
            </div>
            <div class="col-md-12">
            <div class="galleryContainer">                             
                <div class="galleryPreviewContainer">
                    <div class="galleryPreviewImage">                      
                        <?php
                        foreach ($rows as $row) {
                            echo '<div class="previewImage">' . "\n";
                            echo '<div class="myPress">' . "\n";
                            echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $row['image'] . '" alt="'.SITE_NAME.'" />' . "\n";
                            echo '</div>';
                            if ($row['title'] != '') {
                                echo '<div class="captionPress">' . "\n";
                                echo '<span><b>' . $row['title'] . '</b>' . "\n";
                                echo '<br />' . $row['subtitle'] . '</span><br />' . "\n";
                                echo $row['printing_date'] . ' - ';
                                if ($row['type_press'] == 0) {
                                    echo 'Entrevista' . "\n";
                                } else if ($row['type_press'] == 1) {
                                    echo 'Articulo' . "\n";
                                } else {
                                    echo 'Catalogo' . "\n";
                                }
                                echo '</div>' . "\n";
                            }
                            echo '</div>' . "\n";
    }
    ?>                       
                        </div>                
                    </div>               
                </div>
                
            </div>
            </div>
    </div>    
   <?php
} else {
    if ($lng == '1') {
        echo 'Missing items, add to better display page.';
    } else {
        echo'Faltan elementos, agregue para visualizar mejor la página.';
    }
}
?>