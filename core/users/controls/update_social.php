<!-- Start modal edit social media and networks -->
<div class="modal fade" id="chred" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="socialLabel">Editar redes sociales</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		  <?php 
		  if(isset($_POST['editsm'])){
				$website = protect($_POST['website']);
				$whatsapp = protect($_POST['whatsapp']);
				$instagram = protect($_POST['instagram']);
				$facebook = protect($_POST['facebook']);
				$telegram = protect($_POST['telegram']);
				$youtube = protect($_POST['youtube']);
				$twitter = protect($_POST['twitter']);
				$tiktok = protect($_POST['tiktok']);
				$tumblr = protect($_POST['tumblr']);
				$linkedin = protect($_POST['linkedin']);
				$updsm = $uconn->prepare("UPDATE users_social_media SET website = ?, whatsapp = ?, instagram = ?, facebook = ?, telegram = ?, youtube = ?, twitter = ?, tiktok = ?, tumblr = ?, linkedin = ? WHERE usercode = ?");
				$updsm->bind_param("sssssssssss", $website, $whatsapp, $instagram, $facebook, $telegram, $youtube, $twitter, $tiktok, $tumblr, $linkedin, $ucode);
				$updsm->execute();
				$updsm->close();
		  }
		  ?>
			<form role="form" id="social_media" method="POST">
				<div class="row">
					<div class="input-group mb-3">
						<label for="website" class="control-label col-md-6">Página Web:</label>
						  <div class="input-group">
					<span class="input-group-text" id="website"><i class="fa-solid fa-globe fa-lg"></i></span>
					<input type="text" class="form-control" id="website" name="website" value="<?php echo $red['website']; ?>">
					</div>
				</div>				
				<div class="input-group mb-3">
					<label for="whatsapp" class="control-label col-md-6">Whatsapp:</label>
					<div class="input-group">
						<span class="input-group-text" id="whatsapp"><i class="fa-brands fa-whatsapp fa-lg"></i></span>			
					<input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?php echo $red['whatsapp']; ?>">
					</div>
				</div>
				<div class="input-group mb-3">
					<label for="instagram" class="control-label col-md-6">Instagram:</label>
					<div class="input-group">
						<span class="input-group-text" id="instagram"><i class="fa-brands fa-instagram fa-lg"></i></span>
					<input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo $red['instagram']; ?>">
				</div>
				</div>
				<div class="input-group mb-3">
					<label for="facebook" class ="control-label col-md-6">Facebook:</label>
					<div class="input-group">
					<span class="input-group-text" id="facebook"><i class="fa-brands fa-facebook fa-lg"></i></span>
					<input type="text" class="form-control" id="facebook" name="facebook" value="<?php echo $red['facebook']; ?>">
				</div>
				</div>
				<div class="input-group mb-3">
					<label for="telegram" class ="control-label col-md-6">Telegram:</label>
					<div class="input-group">
						<span class="input-group-text" id="telegram"><i class="fa-brands fa-telegram fa-lg"></i></span>
					<input type="text" class="form-control" id="telegram" name="telegram" value="<?php echo $red['telegram']; ?>">
				</div>
				</div>
				<div class="input-group mb-3">
					<label for="youtube" class ="control-label col-md-6">Youtube:</label>
					<div class="input-group">
						<span class="input-group-text" id="youtube"><i class="fa-brands fa-youtube fa-lg"></i></span>
					<input type="text" class="form-control" id="youtube" name="youtube" value="<?php echo $red['youtube']; ?>">
				</div>
				</div>
				<div class="input-group mb-3">
					<label for="twitter" class ="control-label col-md-6">X Twitter:</label>
					<div class="input-group">
						<span class="input-group-text" id="twitter"><i class="fa-brands fa-x-twitter fa-lg"></i></span>
					<input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo $red['twitter']; ?>">
				</div>
				</div>
				<div class="input-group mb-3">
					<label for="tiktok" class ="control-label col-md-6">Tiktok:</label>
					<div class="input-group">
						<span class="input-group-text" id="tiktok"><i class="fa-brands fa-tiktok fa-lg"></i></span>
					<input type="text" class="form-control" id="tiktok" name="tiktok" value="<?php echo $red['tiktok']; ?>">
				</div>
				</div>
				<div class="input-group mb-3">
					<label for="tumblr" class ="control-label col-md-6">Tumblr:</label>
					<div class="input-group">
						<span class="input-group-text" id="tumblr"><i class="fa-brands fa-tumblr fa-lg"></i></span>
					<input type="text" class="form-control" id="tumblr" name="tumblr" value="<?php echo $red['tumblr']; ?>">
				</div>
				</div>
				<div class="input-group mb-3">
					<label for="linkedin" class ="control-label col-md-6">Linkedin:</label>
					<div class="input-group">
						<span class="input-group-text" id="linkedin"><i class="fa-brands fa-linkedin fa-lg"></i></span>
					<input type="text" class="form-control" id="linkedin" name="linkedin" value="<?php echo $red['linkedin']; ?>">
				</div>
				</div>
				
                            <div class="form-group">
					<button type="submit" id="editsm" name="editsm" class="btn btn-primary"><span class = "fas fa-edit"></span> Actualizar redes sociales</button>
                            </div>
</div>
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>		
      </div>
    </div>
  </div>
</div>
<!-- End modal edit social media and networks -->
