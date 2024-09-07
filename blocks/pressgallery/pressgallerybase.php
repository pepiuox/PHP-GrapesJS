<!-- press gallery block -->
<?php
$rslider = $conn->query("SELECT idPr, galId, image, title, subtitle, description, printing_date, type_press FROM press_gal LEFT JOIN (SELECT idGal,name, pageId FROM galleries)galleries ON press_gal.galId=galleries.idGal WHERE pageId = '$bid'");
$rows = array();

while ($row = $rslider->fetch_array()) {
	$rows[] = $row;
}
$num_cnt = count($rows);
if ($num_cnt > 0) {
	?>
	<div class="col-md-12">
		<div class="galleryShow">
			<div class="col-md-6">
				<div style='text-transform: uppercase; padding-left: 2px;'><?php echo $row['name']; ?></div>
			</div>
			<div class="col-md-6">
				<div class="controls">
					<a onclick="$(document).toggleFullScreen()"><img src="<?php echo SITE_PATH; ?>assets/images/e-arrows.png" /></a>
				</div>
			</div>

			<div class="galleryContainer">
				<div class="galleryThumbnailsContainer">
					<?php
					foreach ($rows as $row) {
						echo '<div class="galleryThumbnails">' . "\n";
						echo '<div class="myPress">' . "\n";
						echo '<img class="scale" data-scale="best-fit-down" data-align="center" src="' . $row['image'] . '" alt="'.SITE_NAME.'" />' . "\n";
						echo '</div>';
						if ($row['title'] != '') {
							echo '<div class="captionPress">' . "\n";
							echo '<span><b>' . $row['title'] . '</b>' . "\n";
							echo '<br />' . $row['subtitle'] . '</span><br />' . "\n";
							echo $row['printing_date'] . ' - ';
							if ($row['type_press'] == 0) {
								echo 'Entrevista' . "\n";
							} else if ($row['type_press'] == 1) {
								echo 'Articulo' . "\n";
							} else {
								echo 'Catalogo' . "\n";
							}
							echo '</div>' . "\n";
						}
						echo '</div>' . "\n";
					}
					?>
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
								echo '</li>' . "\n";
							}
							?>
						</ul>
					</div>
				</div>
			</div>

		</div>

	</div>
	<?php
} else {
	if ($language == '1') {
		echo 'Missing items, add to better display page.';
	} else {
		echo'Faltan elementos, agregue para visualizar mejor la página.';
	}
}
?>