<!-- multimedia block -->
<?php
$rslideg = $conn->query("SELECT idGal, name, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
$rowg = $rslideg->fetch_array();
$myG = $rowg['idGal'];
$rslidet = $conn->query("SELECT * FROM multimedia_gal WHERE galId = '$myG' ORDER BY sort");
$rowts = array();
while ($rowt = $rslidet->fetch_array()) {
    $rowts[] = $rowt;
}
$num_ct = count($rowts);

if ($num_ct > 0) {
    ?>
    <div class="row">
        <div class="galleryShow">   
            <div class="col-md-9">

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
                            <ul>
                                <?php
                                foreach ($rowts as $i => $rowt) {
                                    $imn = $i + 1;
                                    echo '<li class="previewImage' . $imn . '">' . "\n";
                                    echo '<div class="mySlides">' . "\n";
                                    if ($rowt['source'] == 0) {
                                        echo '<div class="video" data-type="youtube" data-code="' . $rowt['idlink'] . '" data-width="640" data-height="360"></div>';
                                    } else if ($rowt['source'] == 1) {
                                        echo '<div class="video" data-type="vimeo" data-code="' . $rowt['idlink'] . '" data-width="640" data-height="360"></div>';
                                    } else {
                                        echo '<div class="video" data-type="dailymotion" data-code="' . $rowt['idlink'] . '" data-width="640" data-height="360"></div>';
                                    }
                                    echo '</div>';
                                    echo ' ';
                                    echo '<div class="captionImag">' . "\n";
                                    if ($lng == '1') {
                                        echo $rowt['description_en'];
                                    } else {
                                        echo $rowt['description_es'];
                                    }
                                    echo '</div>' . "\n";
                                    echo '</li>' . "\n";
                                }
                                ?>   
                            </ul>
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
                                    echo '<a class="thumbSlides thumbnailsimage' . $tbn . '"><img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" /></a>' . "\n";
                                }
                                ?>                         
                            </div>
                        </div>
                    </div>

                </div>            
                <div class="galleryContent">
                    <h6><?php echo $gname; ?></h6>
                    <p><?php echo $gdesc; ?></p>
                </div>

            </div>

        </div>
    </div>
    <script src="<?php echo SITE_PATH; ?>assets/js/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="<?php echo SITE_PATH; ?>assets/js/froogaloop.js" type="text/javascript"></script>
    <script src="<?php echo SITE_PATH; ?>assets/js/jquery.dcd.video.js" type="text/javascript"></script>
    <script type="text/javascript">
                        $(function () {
                            $('.video').video();
                        });
    </script>
    <?php
} else {
    if ($lng == '1') {
        echo 'Missing items, add to better display page.';
    } else {
        echo'Faltan elementos, agregue para visualizar mejor la página.';
    }
}
?>