<!-- slidega block -->
<?php
$rslideg = $conn->query("SELECT idGal, name, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
$rowg = $rslideg->fetch_array();
$myG = $rowg['idGal'];
$rslidet = $conn->query("SELECT * FROM image_gal WHERE galId = '$myG' ORDER BY sort");
$rowts = array();
while ($rowt = $rslidet->fetch_array()) {
    $rowts[] = $rowt;
}
$num_ct = count($rowts);

if ($num_ct > 0) {
    ?>
    <div class="row">      
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
                <div class="galleryContent">
                    <h5><?php echo $rowg['name']; ?></h5>
                    <?php echo $rowg['description']; ?>
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