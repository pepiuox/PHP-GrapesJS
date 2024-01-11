<?php
$rslideg = $conn->query("SELECT idGal, name, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
$rowg = $rslideg->fetch_array();
$myG = $rowg['idGal'];
$gname = $rowg['name'];
$gdesc = $rowg['description'];
$rslidet = $conn->query("SELECT * FROM image_gal WHERE galId = '$myG'");
$rowts = array();
while ($rowt = $rslidet->fetch_array()) {
    $rowts[] = $rowt;
}
$num_ct = count($rowts);

if ($num_ct > 0) {
    ?>
    <div class="col_full">
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
                                echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $rowt['image'] . '" alt="'.SITE_NAME.'" />' . "\n";
                                echo '</div>';
                                echo '</li>' . "\n";
                            }
                            ?>   
                        </ul>
                    </div>
                    <div class="galleryPreviewArrows">
                        <a href="#" class="previousSlideArrow"></a>
                        <a href="#" class="stopSlideArrow"</a>
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
                                echo '<a class="thumbSlides thumbnailsimage' . $tbn . '"><img class="scale" src="' . $rowt['image'] . '" alt="'.SITE_NAME.'" /></a>' . "\n";
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
            <div class="clear"></div>                   
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>       
    <?php
} else {
    echo'Faltan elementos, agregue para visualizar mejor la página';
}
?>
<script type="text/javascript">
	jQuery( document ).ready(function( jQuery ) {
		jQuery( '#example3_4' ).sliderPro({
			//width
						width: 1600,
						
			//height
						height: 700,
						
			//autoplay
			autoplay: false,
			autoplayDelay: 5000,
						
			arrows: true,
			buttons: false,
			smallSize: 500,
			mediumSize: 1000,
			largeSize: 3000,
			fade: true,
			
			//thumbnail
			thumbnailArrows: true,
			thumbnailWidth: 120,
			thumbnailHeight: 100,
									thumbnailsPosition: 'bottom',
									thumbnailPointer: true, 
						centerImage: true,
			allowScaleUp: true,
						startSlide: 0,
			loop: true,
			slideDistance: 5,
			autoplayDirection: 'normal',
			touchSwipe: true,
			fullScreen: true,
		});
	});
</script>
<style>

/* Example 3 */

/* border */

/* font + color */
.title-in  {
	font-family: Arial !important;
	color: #000000 !important;
	background-color: #FFFFFF !important;
	opacity: 0.7 !important;
}
.desc-in  {
	font-family: Arial !important;
	color: #FFFFFF !important;
	background-color: #000000 !important;
	opacity: 0.7 !important;
}
.sp-button  {
	border: 2px solid #FFFFFF !important;
}
.sp-selected-button  {
	background-color: #FFFFFF !important;
}
.sp-selected-thumbnail::before {
	border-bottom: 5px solid #FFFFFF !important;
}
.sp-selected-thumbnail::after {
	border-bottom: 13px solid #FFFFFF !important;
}

.sp-full-screen-button::before {
    color: #FFFFFF !important;
}

.sp-next-arrow::after, .sp-next-arrow::before {
	background-color: #FFFFFF !important;
}
.sp-previous-arrow::after, .sp-previous-arrow::before {
	background-color: #FFFFFF !important;
}


/*
#example3_ .title-in {
	font-weight: bolder;
	opacity: 0.7 !important;
	font-size: 1.2em;
	
}

#example3_ .desc-in {
	opacity: 0.7 !important;
	text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
	font-size: 1em;
	
}
*/

#example3_4 .title-in {
	color: #ffffff !important;
	font-weight: bolder;
	text-align: center;
}

#example3_4 .title-in-bg {
	background: rgba(255, 255, 255, 0.7) !important;
	white-space: unset !important;
	max-width: 90%;
	min-width: 40%;
	transform: initial !important;
	-webkit-transform: initial !important;
	font-size: 14px !important;
}

#example3_4 .desc-in {
	
	text-align: center;
}
#example3_4 .desc-in-bg {
	
	white-space: unset !important;
	width: 80% !important;
	min-width: 30%;
	transform: initial !important;
	-webkit-transform: initial !important;
	font-size: 13px !important;
}

@media (max-width: 640px) {
	#example3_4 .hide-small-screen {
		display: none;
	}
}

@media (max-width: 860px) {
	#example3_4 .sp-layer {
		font-size: 18px;
	}
	
	#example3_4 .hide-medium-screen {
		display: none;
	}
}
/* Custom CSS */
</style>
<div id="example3_4" class="slider-pro">
    <!---- slides div start ---->
    <div class="sp-slides">
        <div class="sp-slide">
            <img class="sp-image" alt="'.SITE_NAME.'" src="" data-src="<?php echo $rowt['image'];?>" />
        </div>
    </div>

    <!---- slides div end ---->
    <!-- slides thumbnails div start -->
    <div class="sp-thumbnails">
        <img class="sp-thumbnail" src=""/>
    </div>
    <!-- slides thumbnails div end -->

</div>