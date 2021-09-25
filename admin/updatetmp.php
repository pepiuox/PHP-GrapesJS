<?php
//This is temporal file only for add new row
if (isset($_POST['editrow'])) { 
$mkhash = $_POST["mkhash"]; 
$firstname = $_POST["firstname"]; 
$lastname = $_POST["lastname"]; 
$gender = $_POST["gender"]; 
$age = $_POST["age"]; 
$avatar = $_POST["avatar"]; 
$birthday = $_POST["birthday"]; 
$phone = $_POST["phone"]; 
$website = $_POST["website"]; 
$social_media = $_POST["social_media"]; 
$profession = $_POST["profession"]; 
$occupation = $_POST["occupation"]; 
$public_email = $_POST["public_email"]; 
$address = $_POST["address"]; 
$followers_count = $_POST["followers_count"]; 
$profile_image = $_POST["profile_image"]; 
$profile_cover = $_POST["profile_cover"]; 
$profile_bio = $_POST["profile_bio"]; 
$language = $_POST["language"]; 
$active = $_POST["active"]; 
$banned = $_POST["banned"]; 
$date = $_POST["date"]; 
$update = $_POST["update"]; 

        $query="UPDATE `$tble` SET mkhash = '$mkhash', firstname = '$firstname', lastname = '$lastname', gender = '$gender', age = '$age', avatar = '$avatar', birthday = '$birthday', phone = '$phone', website = '$website', social_media = '$social_media', profession = '$profession', occupation = '$occupation', public_email = '$public_email', address = '$address', followers_count = '$followers_count', profile_image = '$profile_image', profile_cover = '$profile_cover', profile_bio = '$profile_bio', language = '$language', active = '$active', banned = '$banned', date = '$date', update = '$update' WHERE idp=$id ";
if ($this->connection->query($query) === TRUE) {
               $_SESSION["success"] = "The data was updated correctly.";
               header("Location: dashboard.php?cms=crud&w=list&tbl=profiles");
            } else {
              $_SESSION["error"] = "Error updating data: " . $this->connection->error;
            }
    } 
?> 
