<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$usercode = $_POST["usercode"]; 
$mkhash = $_POST["mkhash"]; 
$avatar = $_POST["avatar"]; 
$profile_image = $_POST["profile_image"]; 
$profile_cover = $_POST["profile_cover"]; 
$public_phone = $_POST["public_phone"]; 
$public_email = $_POST["public_email"]; 
$social_media = $_POST["social_media"]; 
$profession = $_POST["profession"]; 
$occupation = $_POST["occupation"]; 
$profile_bio = $_POST["profile_bio"]; 
$language = $_POST["language"]; 
$is_active = $_POST["is_active"]; 
$banned = $_POST["banned"]; 
$created = $_POST["created"]; 
$updated = $_POST["updated"]; 

$query = "UPDATE users_profiles SET usercode = ?, mkhash = ?, avatar = ?, profile_image = ?, profile_cover = ?, public_phone = ?, public_email = ?, social_media = ?, profession = ?, occupation = ?, profile_bio = ?, language = ?, is_active = ?, banned = ?, created = ?, updated = ? WHERE idp = ?";
$updated = $conn->prepare($sql);
$updated->bind_param('ssssssssssssiissi', $usercode, $mkhash, $avatar, $profile_image, $profile_cover, $public_phone, $public_email, $social_media, $profession, $occupation, $profile_bio, $language, $is_active, $banned, $created, $updated, $id );
$updated->execute();
$updated->close();
}
?> 
 
<form role="form" id="edit_users_profiles" method="POST">
<div class="form-group">
                       <label for="usercode" class ="control-label col-md-6">Usercode:</label>
                       <input type="text" class="form-control" id="usercode" name="usercode" value="AArQneduR#63y$MH5)uBMG%oIdyDtt$&e8QUSA}4|nU2d$N(0D#sr7rU95203017">
                  </div>
<div class="form-group">
                       <label for="mkhash" class ="control-label col-md-6">Mkhash:</label>
                       <input type="text" class="form-control" id="mkhash" name="mkhash" value="5358707146524e686178357141586d4130493442693331413937727337355933">
                  </div>
<div class="form-group">
                       <label for="avatar" class ="control-label col-md-6">Avatar:</label>
                       <input type="text" class="form-control" id="avatar" name="avatar" value="">
                  </div>
<div class="form-group">
                       <label for="profile_image" class ="control-label col-md-6">Profile image:</label>
                       <input type="text" class="form-control" id="profile_image" name="profile_image" value="bolso.jpg">
                  </div>
<div class="form-group">
                       <label for="profile_cover" class ="control-label col-md-6">Profile cover:</label>
                       <input type="text" class="form-control" id="profile_cover" name="profile_cover" value="">
                  </div>
<div class="form-group">
                       <label for="public_phone" class ="control-label col-md-6">Public phone:</label>
                       <input type="text" class="form-control" id="public_phone" name="public_phone" value="999063645">
                  </div>
<div class="form-group">
                       <label for="public_email" class ="control-label col-md-6">Public email:</label>
                       <input type="text" class="form-control" id="public_email" name="public_email" value="contact@pepiuox.net">
                  </div>
<div class="form-group">
                       <label for="social_media" class ="control-label col-md-6">Social media:</label>
                       <select class="form-select" id="social_media" name="social_media" >
<option value="Yes">Yes</option>

<option value="No">No</option>

</select>
</div>
<div class="form-group">
                       <label for="profession" class ="control-label col-md-6">Profession:</label>
                       <input type="text" class="form-control" id="profession" name="profession" value="">
                  </div>
<div class="form-group">
                       <label for="occupation" class ="control-label col-md-6">Occupation:</label>
                       <input type="text" class="form-control" id="occupation" name="occupation" value="">
                  </div>
<div class="form-group">
                       <label for="profile_bio" class ="control-label col-md-6">Profile bio:</label>
                       <input type="text" class="form-control" id="profile_bio" name="profile_bio" value="">
                  </div>
<div class="form-group">
                       <label for="language" class ="control-label col-md-6">Language:</label>
                       <input type="text" class="form-control" id="language" name="language" value="">
                  </div>
<div class="form-group">
				<label for="is_active" class ="control-label col-md-6">Is active:</label> <input type="text"
					class="form-control" id="is_active" name="is_active"
					value="1">
			</div>
			
<div class="form-group">
				<label for="banned" class ="control-label col-md-6">Banned:</label> <input type="text"
					class="form-control" id="banned" name="banned"
					value="0">
			</div>
			
<div class="form-group">
                       <label for="created" class ="control-label col-md-6">Created:</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="created" name="created" value="2023-09-15 09:52:01">
                  </div>
<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#created").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#created").datepicker("setDate", new Date());
                                        });
                                    </script>
<div class="form-group">
                       <label for="updated" class ="control-label col-md-6">Updated:</label>
                       <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="updated" name="updated" value="2023-11-18 03:12:14">
                  </div>
<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#updated").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#updated").datepicker("setDate", new Date());
                                        });
                                    </script>
<div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>
    </div>
</form>
        
