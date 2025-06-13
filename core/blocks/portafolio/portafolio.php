<!-- portafolio block -->
<?php
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
                    <a class="boxes"><img src="<?php echo SITE_PATH; ?>assets/img/boxes.png" /></a>
                    <a class="square"><img src="<?php echo SITE_PATH; ?>assets/img/square.png" /></a>
                    <a onclick="$(document).toggleFullScreen()"><img src="<?php echo SITE_PATH; ?>assets/img/e-arrows.png" /></a>                                                 
                </div>
            </div>
            <div class="col-md-12">
                <div class="galleryContainer">                 
                    <div class="galleryThumbnailsContainer">
                        <div class="galleryThumbnails scrollpanel">
                            <?php
                            $rslidet = $conn->query("SELECT idPr, galId, image FROM press_gal LEFT JOIN (SELECT idGal, pageId FROM galleries)galleries ON image_gal.galId=galleries.idGal WHERE pageId = '$bid'");

                            while ($rowt = $rslidet->fetch_array()) {
                                $rowts[] = $rowt;
                            }
                            foreach ($rowts as $rowt) {
                                echo '<a class="thumbSlides thumbnailsimage' . $rowt[0] . '"><img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" /></a>' . "\n";
                            }
                            ?> 
                        </div>
                    </div>

                    <div class="galleryPreviewContainer">
                        <div class="galleryPreviewImage">  

                            <?php
                            $rslider = $conn->query("SELECT idPr, galId, image, caption FROM press_gal LEFT JOIN (SELECT idGal, pageId FROM galleries)galleries ON image_gal.galId=galleries.idGal WHERE pageId = '$bid'");
                            $rows = array();
                            while ($row = $rslider->fetch_array()) {
                                $rows[] = $row;
                            }
                            $num_cnt = count($rows);
                            if ($num_cnt > 0) {
                                foreach ($rows as $row) {
                                    echo '<div class="previewImage">' . "\n";
                                    echo '<div class="mySlides">' . "\n";
                                    echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $row['image'] . '" alt="' . SITE_NAME . '" />' . "\n";
                                    echo '</div>';
                                    if ($row['caption'] != '') {
                                        echo '<div class="captionImag">' . "\n";
                                        echo '<span>' . $row['caption'] . '</span>' . "\n";
                                        echo '</div>' . "\n";
                                    }
                                    echo '</div>' . "\n";
                                }
                            } else {
                                echo'Faltan elementos, agregue para visualizar mejor la página';
                            }
                            ?>   

                        </div>
                        <div class="galleryPreviewArrows">
                            <a href="#" class="previousSlideArrow"></a>
                            <a href="#" class="stopSlideArrow"</a>
                            <a href="#" class="playSlideArrow"></a>
                            <a href="#" class="nextSlideArrow"></a>
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