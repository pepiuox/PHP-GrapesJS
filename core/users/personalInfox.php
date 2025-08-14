<div class="container">
 <div class="row pt-2">  
  <div class="col-md-12">
<div class="card">
 <div class="card-body">
  <?php
  $userid = $_SESSION['user_id'];
  $hash = $_SESSION['hash'];

  if (isset($_POST['editrow'])) {

$firstname = protect($_POST["firstname"]);
$lastname = protect($_POST["lastname"]);
$gender = protect($_POST["gender"]);
$age = protect($_POST["age"]);
$avatar = protect($_POST["avatar"]);
$birthday = protect($_POST["birthday"]);
$public_phone = protect($_POST["public_phone"]);
$website = protect($_POST["website"]);
$social_media = protect($_POST["social_media"]);
$profession = protect($_POST["profession"]);
$occupation = protect($_POST["occupation"]);
$public_email = protect($_POST["public_email"]);
$address = protect($_POST["address"]);
$followers_count = protect($_POST["followers_count"]);
$profile_image = protect($_POST["profile_image"]);
$profile_cover = protect($_POST["profile_cover"]);
$profile_bio = protect($_POST["profile_bio"]);
$language = protect($_POST["language"]);
$query = "UPDATE profiles SET avatar = ?, birthday = ?, public_phone = ?, website = ?, social_media = ?, profession = ?, occupation = ?, public_email = ?, address = ?, profile_image = ?, profile_cover = ?, profile_bio = ?, language = ? WHERE idp = ? AND mkhash = ? ";
$up1 = $conn->prepare($query);
$up1->bind_param("sisssssssssssss", $avatar, $birthday, $public_phone, $website, $social_media, $profession, $occupation, $public_email, $address, $profile_image, $profile_cover, $profile_bio, $language, $userid, $hash);
$up1->execute();
$inst1 = $up1->affected_rows;
$up1->close();
if ($inst1 > 0) {
 $_SESSION["success"] = "The data was updated correctly.";
 header("Location: profile.php");
 exit;
} else {
 $_SESSION["error"] = "Error updating data: " . $conn->error;
}
  }
  $respro = $conn->prepare("SELECT * FROM users_profiles WHERE idp = ? AND mkhash = ? ");
  $respro->bind_param("ss", $userid, $hash);
  $respro->execute();
  $prof = $respro->get_result();
  $rpro = $prof->fetch_assoc();
  $respro->close();
  ?> 

  <form role="form" id="edit_profiles" method="POST">
<div class="form-group">
 <label for="firstname" class ="control-label col-sm-3">Firstname:</label>
 <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $rpro['firstname']; ?>">
</div>
<div class="form-group">
 <label for="lastname" class ="control-label col-sm-3">Lastname:</label>
 <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $rpro['lastname']; ?>">
</div>
<?php
enumsel('profiles', 'gender', $rpro['gender']);
?>
<div class="form-group">
 <label for="age" class ="control-label col-sm-3">Age:</label> 
 <input type="text" class="form-control" id="age" name="age" value="<?php echo $rpro['age']; ?>">
</div>
<div class="form-group">
 <label for="avatar" class ="control-label col-sm-3">Avatar:</label>
 <input type="text" class="form-control" id="avatar" name="avatar" value="<?php echo $rpro['avatar']; ?>">
</div>
<div class="form-group">
 <label for="birthday" class ="control-label col-sm-3">Birthday:</label>
 <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="birthday" name="birthday" value="<?php echo $rpro['birthday']; ?>">
</div>
<script type="text/javascript">
 $(document).ready(function ()
 {
  $("#birthday").datepicker({
weekStart: 1,
daysOfWeekHighlighted: "6,0",
autoclose: true,
todayHighlight: true
  });
  $("#birthday").datepicker("setDate", new Date());
 });
</script>
<div class="form-group">
 <label for="phone" class ="control-label col-sm-3">Phone:</label>
 <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $rpro['public_phone']; ?>">
</div>
<div class="form-group">
 <label for="website" class ="control-label col-sm-3">Website:</label>
 <input type="text" class="form-control" id="website" name="website" value="<?php echo $rpro['website']; ?>">
</div>
<div class="form-group">
 <label for="social_media" class ="control-label col-sm-3">Social media:</label>
 <input type="text" class="form-control" id="social_media" name="social_media" value="<?php echo $rpro['social_media']; ?>">
</div>
<div class="form-group">
 <label for="profession" class ="control-label col-sm-3">Profession:</label>
 <input type="text" class="form-control" id="profession" name="profession" value="<?php echo $rpro['profession']; ?>">
</div>
<div class="form-group">
 <label for="occupation" class ="control-label col-sm-3">Occupation:</label>
 <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo $rpro['occupation']; ?>">
</div>
<div class="form-group">
 <label for="public_email" class ="control-label col-sm-3">Public email:</label>
 <input type="text" class="form-control" id="public_email" name="public_email" value="<?php echo $rpro['public_email']; ?>">
</div>
<div class="form-group">
 <label for="address" class ="control-label col-sm-3">Address:</label>
 <input type="text" class="form-control" id="address" name="address" value="<?php echo $rpro['address']; ?>">
</div>
<div class="form-group">
 <label for="profile_image" class ="control-label col-sm-3">Profile image:</label>
 <input type="text" class="form-control" id="profile_image" name="profile_image" value="<?php echo $rpro['profile_image']; ?>">
</div>
<div class="form-group">
 <label for="profile_cover" class ="control-label col-sm-3">Profile cover:</label>
 <input type="text" class="form-control" id="profile_cover" name="profile_cover" value="<?php echo $rpro['profile_cover']; ?>">
</div>
<div class="form-group">
 <label for="profile_bio" class ="control-label col-sm-3">Profile bio:</label>
 <textarea type="text" class="form-control" id="profile_bio" name="profile_bio"><?php echo $rpro['profile_bio']; ?></textarea>
</div>
<div class="form-group">
 <label for="language" class ="control-label col-sm-3">Language:</label>
 <input type="text" class="form-control" id="language" name="language" value="<?php echo $rpro['language']; ?>">
</div>

<div class="form-group">
 <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Update Profile</button>
</div>
  </form>
 </div>
</div>
  </div>
 </div>
</div>
