<div class="col-md-12">
	<div class="galleryShow">
		<?php
		$rslideg = $conn->query("SELECT idGal, title, description, pageId FROM galleries WHERE active='1' AND pageId = '$bid' LIMIT 1");
		$rowg = $rslideg->fetch_array();
		$idGal = $rowg['idGal'];
		?>
		<div class="col-md-6">
			<div style='text-transform: uppercase; padding-left: 20px;'><?php echo $rowg['title']; ?></div>
		</div>
		<div class="col-md-6">
			<div class="controls">
				<a class="info"><img src="img/info.png" /></a>
				<a class="prev"><img src="img/p-left.png" /></a>
				<a class="count" ></a>
				<a class="next"><img src="img/n-right.png" /></a>
				<a class="boxes"><img src="img/boxes.png" /></a>
				<a class="square"><img src="img/square.png" /></a>
				<a onclick="$(document).toggleFullScreen()"><img src="img/e-arrows.png" /></a>
			</div>
		</div>
		<div class="galleryContainer">
			<div class="galleryThumbnailsContainer">
				<div class="galleryThumbnails scrollpanel">
					<?php
					$rslidet = $conn->query("SELECT id, galId, image FROM multimedia_gal WHERE galId = '$idGal'");
					while ($rowt = $rslidet->fetch_array()) {
						$rowts[] = $rowt;
					}
					foreach ($rowts as $rowt) {
						echo '<a class="thumbSlides thumbnailsimage' . $rowt[0] . '"><img src="' . $rowt['image'] . '" width="100%" alt="'.SITE_NAME.'" /></a>' . "\n";
					}
					?>
				</div>
			</div>

			<div class="galleryPreviewContainer">
				<div class="galleryPreviewImage">
					<ul>
						<?php
						$rslider = $conn->query("SELECT id, galId, description, source, idlink FROM multimedia_gal WHERE galId = '$idGal'");
						while ($row = $rslider->fetch_array()) {
							$rows[] = $row;
						}
						foreach ($rows as $row) {
							echo '<li class="previewImage' . $row[0] . '">' . "\n";
							echo '<div class="mySlides">' . "\n";
							if ($row['source'] == 0) {
								echo '<iframe src="https://www.youtube.com/embed/' . $row['idlink'] . '" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							} else if ($row['source'] == 1)   {
								echo '<iframe src="https://player.vimeo.com/video/' . $row['idlink'] . '" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							}else{
							echo '<iframe src="//www.dailymotion.com/embed/video/' . $row['idlink'] . '" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
							}
							echo '</div>';
							if ($row['description'] != '') {
								echo '<div class="captionImag">' . "\n";
								echo '<span>' . $row['description'] . '</span>' . "\n";
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
