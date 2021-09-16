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
            <div class="col-md-6">
                
            </div>
            <div class="col-md-6">
                <div class="controls">        
                    <a class="info"><img src="<?php echo $base; ?>img/info.png" /></a>                  
                    <a class="play"><img src="<?php echo $base; ?>img/play.png" /></a>
                    <a class="pause"><img src="<?php echo $base; ?>img/pause.png" /></a> 
                    <a class="prev"><img src="<?php echo $base; ?>img/p-left.png" /></a>
                    <a class="count" ></a>
                    <a class="next"><img src="<?php echo $base; ?>img/n-right.png" /></a>
                    <a class="boxes"><img src="<?php echo $base; ?>img/boxes.png" /></a>
                    <a class="square"><img src="<?php echo $base; ?>img/square.png" /></a>
                    <a onclick="$(document).toggleFullScreen()"><img src="<?php echo $base; ?>img/e-arrows.png" /></a>                                                             
                </div>
            </div>
            
            <div class="galleryContainer">                 
                <div class="galleryThumbnailsContainer">
                    <div class="galleryThumbnails scrollpanel">
                        <?php
                        foreach ($rowts as $x => $rowt) {
                            $tbn = $x + 1;
                            echo '<a class="thumbSlides thumbnailsimage' . $tbn . '"><img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="'.SITE_NAME.'" /></a>' . "\n";
                        }
                        ?> 
                    </div>
                </div>

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
                                    if ($rowt['caption_en'] != '') {
                                        echo '<div class="captionImag">' . "\n";
                                        echo '<span>' . $rowt['caption_en'] . '</span>' . "\n";
                                        echo '</div>' . "\n";
                                    }
                                    echo '</li>' . "\n";
                                }                            
                            ?>   
                        </ul>
                    </div>
                    <div class="galleryPreviewArrows">
                        <a href="#" class="previousSlideArrow"></a>
                        <a href="#" class="nextSlideArrow"></a>
                    </div>
                </div>   
                
                <div class="viewContent"><a class="desShow">+ Ver detalles del proyecto</a><a class="desHide">- Ocultar detalles del proyecto</a></div>
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