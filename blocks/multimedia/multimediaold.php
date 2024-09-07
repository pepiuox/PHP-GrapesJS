<!-- multimedia block -->
<?php
$rslideg = $conn->query("SELECT idGal, name, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
$rowg = $rslideg->fetch_array();
$myG = $rowg['idGal'];
$rslidet = $conn->query("SELECT id, galId,title, image, description, source, idlink FROM multimedia_gal WHERE galId = '$myG'");
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
				<div style='text-transform: uppercase; padding-left: 20px;'><?php echo $rowg['name']; ?></div>
			</div>
			<div class="col-md-6">
				<div class="controls">
					<a class="info"><img src="<?php echo SITE_PATH; ?>assets/images/info.png" /></a>
					<a class="prev"><img src="<?php echo SITE_PATH; ?>assets/images/p-left.png" /></a>
					<a class="count" >1</a>
					<a class="next"><img src="<?php echo SITE_PATH; ?>assets/images/n-right.png" /></a>
					<a class="boxes"><img src="<?php echo SITE_PATH; ?>assets/images/boxes.png" /></a>
					<a class="square"><img src="<?php echo SITE_PATH; ?>assets/images/square.png" /></a>
					<a onclick="$(document).toggleFullScreen()"><img src="<?php echo SITE_PATH; ?>assets/images/e-arrows.png" /></a>
				</div>
			</div>

			<div class="galleryContainer">
				<div class="galleryThumbnailsContainer">
					<div class="galleryThumbnails scrollpanel">
						<?php
						foreach ($rowts as $x => $rowt) {
							$tbn = $x + 1;
							echo '<a class="thumbSlides thumbnailsimage' . $tbn . '"><img src="' . $rowt['image'] . '" width="100%" alt="'.SITE_NAME.'" /></a>' . "\n";
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
								if ($rowt['source'] == 0) {
									echo '<div class="video" data-type="youtube" data-code="' . $rowt['idlink'] . '" data-width="640" data-height="360"></div>';
								} else if ($rowt['source'] == 1) {
									echo '<div class="video" data-type="vimeo" data-code="' . $rowt['idlink'] . '" data-width="640" data-height="360"></div>';
								} else {
									echo '<div class="video" data-type="dailymotion" data-code="' . $rowt['idlink'] . '" data-width="640" data-height="360"></div>';
								}
								echo '</div>';
								if ($rowt['description'] != '') {
									echo ' ';
									echo '<div class="captionImag">' . "\n";
									echo '<span>' . $rowt['description'] . '</span>' . "\n";
									echo '</div>' . "\n";
								}
								echo '</li>' . "\n";
							}
							?>
						</ul>
					</div>
				</div>
				<div class="galleryContent">
					<?php echo $rowg['description']; ?>
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
	echo'Faltan elementos, agregue para visualizar mejor la página';
}
?>