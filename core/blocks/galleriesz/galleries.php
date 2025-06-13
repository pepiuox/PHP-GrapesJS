<!-- galleries block -->
<?php
$rslideg = $conn->query("SELECT idGal, name, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
$rowg = $rslideg->fetch_array();
$myG = $rowg['idGal'];
$gname = $rowg['name'];
$gdesc = $rowg['description'];
$rslidet = $conn->query("SELECT * FROM image_gal WHERE galId = '$myG' ORDER BY sort");
$rowts = array();
while ($rowt = $rslidet->fetch_array()) {
    $rowts[] = $rowt;
}
$num_ct = count($rowts);

if ($num_ct > 0) {
    ?>
    <div class="col_full">
        <div class="galleryShow">  
            <div class="row>"
                 <div class="col-md-9">

                </div>
                <div class="col-md-3">
                    <div class="controls">                    
                        <a onclick="$(document).toggleFullScreen()"><img src="<?php echo SITE_PATH; ?>assets/img/e-arrows.png" /></a>                                                 
                    </div>
                </div>
            </div>
            <div class="galleryContainer">                                 
                <div class="galleryPreviewContainer">
                    <div class="galleryPreviewImage">  
                        <ul>
                            <?php
                            foreach ($rowts as $i => $rowt) {
                                $imn = $i + 1;
                                echo '<li class="previewImage' . $imn . '">' . "\n";
                                echo '<div class="mySlides">' . "\n";
                                echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" />' . "\n";
                                echo '</div>';
                                echo '<div class="captionImag">' . "\n";
                                echo '<span>';
                                if ($lng == '1') {
                                    echo $rowt['caption_en'];
                                } else {
                                    echo $rowt['caption_es'];
                                }
                                echo '</span>' . "\n";
                                echo '</div>' . "\n";
                                echo '</li>' . "\n";
                            }
                            ?>   
                        </ul>
                    </div>
                    <div class="galleryPreviewArrows">
                        <a href="#" class="previousSlideArrow"></a>
                        <a href="#" class="stopSlideArrow"></a>
                        <a href="#" class="playSlideArrow"></a>
                        <a href="#" class="nextSlideArrow"></a>
                    </div>
                </div>   

                <div id="makeMeScrollable" class="galleryThumbnailsContainer">
                    <div style="display: block; opacity: 0.15;" class="scrollingHotSpotLeft scrollingHotSpotLeftVisible"></div>
                    <div style="opacity: 0.15;" class="scrollingHotSpotRight scrollingHotSpotRightVisible"></div>
                    <div class="scrollWrapper">
                        <div class="scrollableArea galleryThumbnails thumblist">
                            <?php
                            foreach ($rowts as $x => $rowt) {
                                $tbn = $x + 1;
                                echo '<a class="thumbSlides thumbnailsimage' . $tbn . '"><img class="scale" src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" /></a>' . "\n";
                            }
                            ?>                         
                        </div>
                    </div>
                </div>

            </div>            
            <div class="galleryContent">
                <h6><?php echo $gname; ?></h6>
                <?php echo $gdesc; ?>
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