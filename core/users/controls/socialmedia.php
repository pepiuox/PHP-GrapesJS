<div class="card mb-4 mb-lg-0">    
          <div class="card-body p-0">
			  <button type="button" id="bredes" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#chred">
  Editar redes
</button>
				<?php
				$redes = $conn->prepare(
    "SELECT * FROM users_social_media WHERE usercode = ? "
);
$redes->bind_param("s", $ucode);
$redes->execute();
$reds = $redes->get_result();
$redes->close();
$red = $reds->fetch_assoc();
if($reds->num_rows > 0){
	?>
			  <ul class="list-group list-group-flush rounded-3">
	<?php
	
	if(!empty($red['website'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-solid fa-globe fa-lg"></i>
                <p class="mb-0"><?php echo $red['website']; ?></p>
              </li>
				<?php
	}	
	if(!empty($red['whatsapp'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-brands fa-whatsapp fa-lg"></i>
                <p class="mb-0"><?php echo $red['whatsapp']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['instagram'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
				<i class="fa-brands fa-instagram fa-lg"></i>
                <p class="mb-0"><?php echo $red['instagram']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['facebook'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-brands fa-square-facebook fa-lg"></i>
                <p class="mb-0"><?php echo $red['facebook']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['telegram'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-brands fa-telegram fa-lg"></i>
                <p class="mb-0"><?php echo $red['telegram']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['youtube'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-brands fa-youtube fa-lg"></i>
                <p class="mb-0"><?php echo $red['youtube']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['twitter'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-brands fa-x-twitter fa-lg"></i>
                <p class="mb-0"><?php echo $red['twitter']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['tiktok'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-brands fa-tiktok fa-lg"></i>
                <p class="mb-0"><?php echo $red['tiktok']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['tumblr'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fa-brands fa-tumblr fa-lg"></i>
                <p class="mb-0"><?php echo $red['tumblr']; ?></p>
              </li>
				<?php
	}
	if(!empty($red['linkedin'])){
		?>
				<li class="list-group-item d-flex justify-content-between align-items-center p-3">
				<i class="fa-brands fa-linkedin fa-lg"></i>
                <p class="mb-0"><?php echo $red['linkedin']; ?></p>
              </li>
				<?php
	}
	?>
			  </ul>
		<?php
}
				?>
                    </div>
                </div> 
