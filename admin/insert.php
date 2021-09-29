<?php
//This is temporal file only for add new row
if(isset($_POST['addrow'])){
$firstname = $_POST['firstname'];
 $lastname = $_POST['lastname'];
 $gender = $_POST['gender'];
 $birthday = $_POST['birthday'];
 $age = $_POST['age'];
 $phone = $_POST['phone'];
 $email = $_POST['email'];
 $social_media = $_POST['social_media'];
 $web_blog = $_POST['web_blog'];
 $address = $_POST['address'];
 $address_line_2 = $_POST['address_line_2'];
 $city = $_POST['city'];
 $state_province_region = $_POST['state_province_region'];
 $zip_code = $_POST['zip_code'];
 $country = $_POST['country'];
 $profession = $_POST['profession'];
 $personal_interest = $_POST['personal_interest'];
 $skills = $_POST['skills'];
 $allergies = $_POST['allergies'];
 $allergy_description = $_POST['allergy_description'];
 $diseases = $_POST['diseases'];
 $disease_description = $_POST['disease_description'];
 $comments = $_POST['comments'];
 $contact_person_name = $_POST['contact_person_name'];
 $contact_person_phone = $_POST['contact_person_phone'];
 $contact_person_email = $_POST['contact_person_email'];

$sql = "INSERT INTO volunteer (firstname, lastname, gender, birthday, age, phone, email, social_media, web_blog, address, address_line_2, city, state_province_region, zip_code, country, profession, personal_interest, skills, allergies, allergy_description, diseases, disease_description, comments, contact_person_name, contact_person_phone, contact_person_email)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$insert = $conn->prepare($sql);
$insert->bind_param('isssiisssssssssssssssssssss',$firstname, $lastname, $gender, $birthday, $age, $phone, $email, $social_media, $web_blog, $address, $address_line_2, $city, $state_province_region, $zip_code, $country, $profession, $personal_interest, $skills, $allergies, $allergy_description, $diseases, $disease_description, $comments, $contact_person_name, $contact_person_phone, $contact_person_email );
$insert->execute();
$insert->close();
}
?> 
