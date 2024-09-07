<!-- video block -->
<?php
$rslider = $conn->query("SELECT idVd, pageId, description, source, idlink FROM videos WHERE active='1' AND pageId = '$bid' LIMIT 1");
$rows = array();
while ($row = $rslider->fetch_array()) {
	$rows[] = $row;
}
$num_cnt = count($rows);
if ($num_cnt > 0) {
	?>
	<div class="row">
		<div class="galleryShow">
			<div class="col-md-9">
				<div style='text-transform: uppercase; padding-left: 20px;'><?php echo $rowg['name']; ?></div>
			</div>
			<div class="col-md-3">

			</div>
			<div class="col-md-12">
				<div class="galleryContainer">
					<div class="galleryPreviewContainer">
						<div class="galleryPreviewImage">
							<ul>
								<?php
								foreach ($rows as $x => $row) {
									$tbn = $x + 1;
									echo '<li class="previewImage' . $tbn . '">' . "\n";
									echo '<div class="mySlides">' . "\n";
									if ($row['source'] == 0) {
										echo '<div class="video" data-type="youtube" data-code="' . $row['idlink'] . '" data-width="640" data-height="360"></div>';
									} else if ($row['source'] == 1) {
										echo '<div class="video" data-type="vimeo" data-code="' . $row['idlink'] . '" data-width="640" data-height="360"></div>';
									} else {
										echo '<div class="video" data-type="dailymotion" data-code="' . $row['idlink'] . '" data-width="640" data-height="360"></div>';
									}
									echo '</div>';

									echo ' ';
									echo '<div class="captionImag">' . "\n";
									if ($lng == '1') {
										echo $rowt['description_en'];
									} else {
										echo $rowt['description_es'];
									}
									echo '</div>' . "\n";

									echo '</li>' . "\n";
								}
								?>
							</ul>
						</div>
					</div>
					<div class="viewContent">
						<a href="#" class="desShow">+ <?php
							if ($lng == '1') {
								echo ' View project details';
							} else {
								echo ' Ver detalles del proyecto';
							}
							?></a>

						<a href="#" class="desHide">- <?php
							if ($lng == '1') {
								echo ' Hide project details';
							} else {
								echo ' Ocultar detalles del proyecto';
							}
							?></a>
					</div>
					<div class="galleryContent">

						<?php
						if ($row['description'] == '') {
							$crow = mysqli_fetch_array($conn->query("SELECT * FROM `page` WHERE `id` = '$bid' "));
							echo $crow['content'];
						} else {
							echo $row['description'];
						}
						?>
					</div>
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
	if ($lng == '1') {
		echo 'Missing items, add to better display page.';
	} else {
		echo'Faltan elementos, agregue para visualizar mejor la página.';
	}
}
?>