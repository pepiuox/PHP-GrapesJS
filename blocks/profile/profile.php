<!-- profile block -->
<div class="row">
	<div id="myProfile">
		<div class="col-md-6">
			<div class="infocontact">
				<?php
				$row = mysqli_fetch_array($conn->query("SELECT * FROM `my_info` WHERE `idPro` = '1' AND active = '1'"));
				?>
				<div class="myPhoto">
					<img class="scale" data-scale="best-fit-down" data-align="center" src="<?php echo $row['image']; ?>" />
				</div>
				<p><?php echo $row['first_name'] . ' ' . $row['last_name']; ?><br />
					<?php
					echo $row['age'] . ' / ';

					$aage = array("Mujer", "Varon");

					reset($aage);

					while (list($key, $val) = each($aage)) {
						if ($row['gender'] == $key) {
							echo $val . '<br />';
						}
					}

					$resultc = $conn->query("SELECT value FROM config");
					$array = array();
					while ($rowt = $resultc->fetch_row()) {
						$array[] = $rowt;
					}
					echo '<a href="mailto:' . SITE_EMAIL . '">' . SITE_EMAIL . '</a><br />';
					echo 'Telf: ' . PHONE_CONTACT . '<br />';
					echo 'Skype: ' . skype;
					?>
				</p>
			</div>
		</div>
		<div class="col-md-6">
			<div class='scrollpanel'>
				<div class="sconte">
					<?php
					if ($lng == 1) {
						echo $row['description_en'];
					} else {

						echo $row['description_es'];
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>