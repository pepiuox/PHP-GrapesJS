<!-- single block -->
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

	<div class="galleryShow">
		<div class="row">
			<div class="col-md-11">

			</div>
			<div class="col-md-1 text-right">
				<div class="controls">
					<a onclick="$(document).toggleFullScreen()"><img src="<?php echo SITE_PATH; ?>assets/images/e-arrows.png" /></a>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="single" class="carousel slide" data-ride="carousel">
				<!-- The slideshow -->
				<div class="carousel-inner">
					<?php
					foreach ($rowts as $i => $rowt) {
						$imn = $i + 1;
						if ($imn == 1) {
							echo '<div class="carousel-item active">' . "\n";
							echo '<img src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" style="width:100%" />' . "\n";
							echo '</div>' . "\n";
						} else {
							echo '<div class="carousel-item">' . "\n";
							echo '<img src="' . $rowt['image'] . '" alt="' . SITE_NAME . '" style="width:100%" />' . "\n";
							echo '</div>' . "\n";
						}
					}
					?>
					<!-- Left and right controls -->

				</div>
				<a class="carousel-control-prev" href="#single" data-slide="prev">
					<span class="carousel-control-prev-icon"></span>
				</a>
				<a class="carousel-control-next" href="#single" data-slide="next">
					<span class="carousel-control-next-icon"></span>
				</a>
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