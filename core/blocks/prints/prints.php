<!-- prints block -->
<?php
$rowga = mysqli_fetch_array($conn->query("SELECT * FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1"));
$myG = $rowga['idGal'];
$rslidet = $conn->query("SELECT * FROM image_gal WHERE galId = '$myG' ORDER BY sort");
$rowts = array();
while ($rowt = $rslidet->fetch_array()) {
    $rowts[] = $rowt;
}
$num_ct = count($rowts);

if ($num_ct > 0) {
    ?>      

    <div class="row pb-3">
        <div class="col-md-10">
        </div>
        <div class="col-md-2">
            <div class="controls text-right">                 
                <a class="boxes"><img src="<?php echo SITE_PATH; ?>assets/img/boxes.png" /></a>
                <a class="square"><img src="<?php echo SITE_PATH; ?>assets/img/square.png" /></a>
                <a onclick="$(document).toggleFullScreen()"><img src="<?php echo SITE_PATH; ?>assets/img/e-arrows.png" /></a>                                                             
            </div>
        </div>
    </div>
    <div class="galleryThumbnailsContainer">
        <div class="row">
            <?php
            foreach ($rowts as $x => $rowt) {
                $tbn = $x + 1;
                echo '<div class="col-md-4 pb-2">' . "\n";
                echo '<a class="thumbSlides thumbnailsimage' . $tbn . '"><img class="img-responsive" src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" /></a>' . "\n";
                echo '</div>' . "\n";
            }
            ?> 
        </div>
        <div class="row pt-3">
            <div class="description">
                <?php
                echo "<h5>" . $rowga['name'] . "</h5>";
                echo $rowga['description'];
                ?>
            </div>
        </div>

    </div>
    <div class="galleryPreviewContainer">
        <div class="row pb-3"> 
            <div class="w-100 p-0">
                
                <ul class="galleryPreviewImage">
                    <?php
                    foreach ($rowts as $i => $rowt) {
                        $imn = $i + 1;
                        echo '<li class="previewImage' . $imn . '">' . "\n";
                        echo '<div class="mySlides">' . "\n";
                        echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" />' . "\n";
                        echo '</div>';

                        echo '<div class="captionImag">' . "\n";
                        echo '<div class="capt">';
                        if ($lng == '1') {
                            echo $rowt['caption_en'];
                        } else {
                            echo $rowt['caption_es'];
                        }
                        echo '</div>' . "\n";
                        echo '<div class="paypalCode">' . "\n";
                        echo $rowt['paypal_code'] . "\n";
                        echo '</div>' . "\n";
                        echo '</div>' . "\n";

                        echo '</li>' . "\n";
                    }
                    ?>   
                </ul> 
                <div class="galleryPreviewArrows">
                    <a href="#" class="previousSlideArrow"></a>                        
                    <a href="#" class="nextSlideArrow"></a>
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