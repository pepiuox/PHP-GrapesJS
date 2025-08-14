<!-- Start modal edit perfil -->
<div class="modal fade" id="edprofile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
  <div class="modal-header">
<h1 class="modal-title fs-5" id="profileLabel">Perfil personal.</h1>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="modal-body">
		<?php
//This is temporal file only for add new row
if (isset($_POST['updateprofile'])) { 
 
$public_phone = protect($_POST["public_phone"]); 
$public_email = protect($_POST["public_email"]); 
$social_media = protect($_POST["social_media"]); 
$profession = protect($_POST["profession"]); 
$occupation = protect($_POST["occupation"]); 
$profile_bio = protect($_POST["profile_bio"]); 
$language = protect($_POST["language"]); 


$sql = "UPDATE users_profiles SET public_phone = ?, public_email = ?, social_media = ?, profession = ?, occupation = ?, profile_bio = ?, language = ? WHERE idp = ? AND usercode = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssssss', $public_phone, $public_email, $social_media, $profession, $occupation, $profile_bio, $language, $userid, $ucode );
$stmt->execute();
$stmt->close();
}
 
$upro = $conn->prepare("SELECT public_phone, public_email, social_media, profession, occupation, profile_bio, language FROM users_profiles WHERE idp = ? AND usercode=?");
$upro->bind_param('ss', $userid, $ucode);
$upro->execute();
$pro = $upro->get_result();
$upro->close();
$rpr = $pro->fetch_assoc();
?> 

   <form role="form" id="profile" method="POST">
<div class="row">
<div class="form-group">
   <label for="public_phone" class ="control-label col-md-6">Telefono publico:</label>
   <input type="text" class="form-control" id="public_phone" name="public_phone" value="<?php echo $rpr['public_phone']; ?>">
  </div>
<div class="form-group">
   <label for="public_email" class ="control-label col-md-6">Email publico:</label>
   <input type="text" class="form-control" id="public_email" name="public_email" value="<?php echo $rpr['public_phone']; ?>">
  </div>
<div class="form-group">
   <label for="social_media" class ="control-label col-md-6"> Redes Sociales y media:</label>
   <select class="form-select" id="social_media" name="social_media" >
<option value="Yes">Si</option>
<option value="No">No</option>
</select>
</div>
<div class="form-group">
   <label for="profession" class ="control-label col-md-6">Profesión:</label>
   <input type="text" class="form-control" id="profession" name="profession" value="<?php echo $rpr['profession']; ?>">
  </div>
<div class="form-group">
   <label for="occupation" class ="control-label col-md-6">Ocupación:</label>
   <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo $rpr['occupation']; ?>">
  </div>
<div class="form-group">
   <label for="profile_bio" class ="control-label col-md-6">Biografia de perfil:</label>
   <input type="text" class="form-control" id="profile_bio" name="profile_bio" value="<?php echo $rpr['profile_bio']; ?>">
  </div>
<div class="form-group">
   <label for="language" class ="control-label col-md-6">Idiomas:</label>
   <input type="text" class="form-control" id="language" name="language" value="<?php echo $rpr['language']; ?>">
  </div>
<div class="form-group">
<button type="submit" id="updateprofile" name="updateprofile" class="btn btn-primary"><span class = "fas fa-edit"></span> Actualizar perfil</button>
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
