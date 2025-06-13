<?php
$rslideg = $conn->query("SELECT idGal, name, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
$rowg = $rslideg->fetch_array();
$myG = $rowg['idGal'];
$rslidet = $conn->query("SELECT * FROM image_gal WHERE galId = '$myG'");
$rowts = array();
while ($rowt = $rslidet->fetch_array()) {
    $rowts[] = $rowt;
}
$num_ct = count($rowts);

if ($num_ct > 0) {
    ?>
    <div class="col-md-12">      
        <div class="galleryShow">                              
            <div id="loadimage">
                <div class="mSlides">
                    <img src="" border="0" alt="'.SITE_NAME.'"/>
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
                                echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="'.SITE_NAME.'" />' . "\n";
                                echo '</div>';

                                echo '<div class="captionImag">' . "\n";
                                echo '<span>' . $rowt['caption_en'] . '</span>' . "\n";
                                echo '<div class="controls">' . "\n";
                                echo '<a class="play"><img src="' . SITE_PATH.'assets/img/play.png" /></a>' . "\n";
                                echo '<a class="pause"><img src="' . SITE_PATH.'assets/img/pause.png" /></a>' . "\n";
                                echo '</div>' . "\n";
                                echo '</div>' . "\n";

                                echo '</li>' . "\n";
                            }
                            ?>   
                        </ul>
                    </div>
                    <div class="galleryPreviewArrows">
                        <a href="#" class="previousSlideArrow"><img src="<?php echo SITE_PATH; ?>assets/img/arrow-l.png"/></a>
                        <a href="#" class="stopSlideArrow"><img src="<?php echo SITE_PATH; ?>assets/img/arrow-l.png"/></a>
                        <a href="#" class="nextSlideArrow"><img src="<?php echo SITE_PATH; ?>assets/img/arrow-r.png"/></a>
                    </div>
                </div> 
                <div class="galleryContent">
                    <h5><?php echo $rowg['name']; ?></h5>
                    <?php echo $rowg['description']; ?>
                </div>
                           
            </div>
            
        </div>    
            
    </div>    
    <?php
} else {
    echo'Faltan elementos, agregue para visualizar mejor la página';
}
?>