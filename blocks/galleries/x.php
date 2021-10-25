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

    <div class="galleryShow">  
        <div class="row">
            <div class="col-md-11">
            </div>
            <div class="col-md-1 text-right">
                <div class="controls">                    
                    <a onclick="$(document).toggleFullScreen()"><img src="<?php echo $base; ?>assets/images/e-arrows.png" /></a>                                                 
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="galleriesSlides">
                    <?php
                    foreach ($rowts as $i => $rowt) {
                        $imn = $i + 1;
                        echo '<div class="mySlides">' . "\n";
                        echo '<img src="' . $rowt['image'] . '" alt="' . SITE_NAME . '"/>' . "\n";
                        echo '<div class="text caption-container">' . "\n";
                        echo '<p>' . "\n";
                        if ($lng == '1') {
                            echo $rowt['caption_en'] . "\n";
                        } else {
                            echo $rowt['caption_es'] . "\n";
                        }
                        echo '</p>' . "\n";
                        echo '</div>' . "\n";
                        echo '</div>' . "\n";
                    }
                    ?> 
                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                    <a class="next" onclick="plusSlides(1)">❯</a>
                </div>
                <div class="row">
                    <?php
                    foreach ($rowts as $x => $rowt) {
                        $tbn = $x + 1;
                        echo '<div class="column">';
                        echo '<img class="demo cursor" src="' . $rowt['image'] . '" style="width:100%" onclick="currentSlide(' . $tbn . ')" alt="' . SITE_NAME . '">' . "\n";
                        echo '</div>';
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

    <?php
} else {
    if ($lng == '1') {
        echo 'Missing items, add to better display page.';
    } else {
        echo'Faltan elementos, agregue para visualizar mejor la página.';
    }
}
?>