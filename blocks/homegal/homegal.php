<!-- homegal block -->
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
    <div class="row">
        <div class="col-md-12">
            <div class="galleryShow">                        
                <div class="galleryContainer">                                 
                    <div class="galleryPreviewContainer">
                        <div class="galleryPreviewImage">  
                            <ul>
                                <?php
                                foreach ($rowts as $i => $rowt) {
                                    $imn = $i + 1;
                                    echo '<li class="previewImage' . $imn . '">' . "\n";
                                    echo '<div class="mySlides">' . "\n";
                                    echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="' . $rowt['caption_en'] . '" />' . "\n";
                                    echo '</div>';
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