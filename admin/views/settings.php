<?php
$result = $conn->query("SELECT * FROM `site_configuration` WHERE `ID_Site` = '1'") or trigger_error($conn->error);
$confs = $result->fetch_assoc();

/*
 * Upload images before update table
 */

function UploadImage($image) {
	bal $conn;
	Check image using getimagesize function and get size
	if a valid number is got then uploaded file is an image
	(!empty($_FILES[$image]["name"])) {
		e = $_FILES[$image]["name"];
		set($_FILES[$image])) {

			ory name to store the uploaded image files
			hould have sufficient read/write/execute permissions
			 already exists, please create it in the root of the
			t folder
			r = "../uploads/";
			le = $targetDir . basename($_FILES[$image]["name"]);
			 = 1;
			eType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

			getimagesize($_FILES[$image]["tmp_name"]);
			k !== false) {
				['SuccessMessage'] = "File is an image - " . $check["mime"] . ".";
				1;

				['ErrorMessage'] = "File is not an image.";
				0;



		ck if the file already exists in the same path
		le_exists($targetFile)) {
			ION['ErrorMessage'] = "Sorry, file already exists.";
			 = 0;


		ck file size and throw error if it is greater than
		 predefined value, here it is 2000000
		FILES[$image]["size"] > 2000000) {
			ION['ErrorMessage'] = "Sorry, your file is too large.";
			 = 0;


		ck for uploaded file formats and allow only
		, png, jpeg and gif
		you want to allow more formats, declare it here
		mageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
			ION['ErrorMessage'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			 = 0;


		ck if $uploadOk is set to 0 by an error
		ploadOk == 0) {
			['ErrorMessage'] = "Sorry, your file was not uploaded.";
		 {
			uploaded_file($_FILES[$image]["tmp_name"], $targetFile)) {

				n->prepare("UPDATE site_configuration SET $image = ? WHERE `ID_Site` = ?");
				param("si", $nimage, $id);
				te();
				();

				uccessMessage'] = "The file " . htmlspecialchars(basename($_FILES[$image]["name"])) . " has been uploaded.";

				rrorMessage'] = "Sorry, there was an error uploading your file.";



}

extract($_POST);

if (isset($_POST['Update'])) {
	($_FILES) {
		t($_FILES);
		h ($_FILES as $k => $v) {

			ge($v);



	update table */
	each ($_POST as $k => $v) {
		POST['Update'] === $v) {


		] = "`" . $k . "` = '" . $v . "'";

	pdates = implode(", ", $vals);
	date = ("UPDATE site_configuration SET $vupdates WHERE `ID_Site` = '1'");

	($conn->query($update) === TRUE) {
		efiles = '../config/define.php';

		ION['SuccessMessage'] = "Web Site Configuration : Updated.";
		 "SELECT * FROM site_configuration WHERE `ID_Site` = '1'";

		esult = $conn->query($sql)) {
			$result->fetch_fields();
			$result->fetch_assoc();

			$fname as $val) {
				me === 'ID_Site') {

				al->name === 'CREATE') {

				al->name === 'UPDATED') {


				 "define('" . $val->name . "','" . $fdata[$val->name] . "');" . "\n";


			_exists($definefiles)) {
				hp' . "\n";
				lode(" ", $fldname);
				' . "\n";
				tents($definefiles, $ndef, FILE_APPEND | LOCK_EX);

				nefiles);
				hp' . "\n";
				lode("\n ", $fldname);
				' . "\n";
				tents($definefiles, $ndef, FILE_APPEND | LOCK_EX);


	lse {
		ION['ErrorMessage'] = "Updated settings : Error.";
		("Location: dashboard.php?cms=siteconf");
		;

}
?>
<div class="container">
	v class="row">
		lass="card">
			s="card-header p-2">
				 Configuration</h4>
				av nav-tabs" id="myTab" role="tablist">
					item"><a class="nav-link active" href="#website" data-toggle="tab">Web Site</a></li>
					item"><a class="nav-link" href="#controls" data-toggle="tab">Controls</a></li>
					item"><a class="nav-link" href="#builder" data-toggle="tab">Builder</a></li>
					item"><a class="nav-link" href="#contact" data-toggle="tab">Contact</a></li>
					item"><a class="nav-link" href="#social" data-toggle="tab">Social</a></li>
					item"><a class="nav-link" href="#emailserver" data-toggle="tab">Mail settings</a></li>
					item"><a class="nav-link" href="#security" data-toggle="tab">Security</a></li>


			s="card-body">
				col-md-12 py-4">
					</div>
					-content">
						 tab-pane" role="tabpanel" id="website">
							d="post" enctype="multipart/form-data">



									MAIN SITE:</label>
									orm-control" id="DOMAIN_SITE" name="DOMAIN_SITE" value="<?php echo $confs["DOMAIN_SITE"]; ?>">


									 NAME:</label>
									orm-control" id="SITE_NAME" name="SITE_NAME" value="<?php echo $confs["SITE_NAME"]; ?>">


									>SITE BRAND IMG:</label>
									orm-control" id="SITE_BRAND_IMG" name="SITE_BRAND_IMG" value="<?php echo $confs["SITE_BRAND_IMG"]; ?>">


									E PATH:</label>
									orm-control" id="SITE_PATH" name="SITE_PATH" value="<?php echo $confs["SITE_PATH"]; ?>">


									N">SITE DESCRIPTION:</label>
									="form-control" id="SITE_DESCRIPTION" name="SITE_DESCRIPTION"><?php echo $confs["SITE_DESCRIPTION"]; ?></textarea>


									SITE KEYWORDS:</label>
									="form-control" id="SITE_KEYWORDS" name="SITE_KEYWORDS"><?php echo $confs["SITE_KEYWORDS"]; ?></textarea>


									TION">SITE CLASSIFICATION:</label>
									="form-control" id="SITE_CLASSIFICATION" name="SITE_CLASSIFICATION"><?php echo $confs["SITE_CLASSIFICATION"]; ?></textarea>


									E EMAIL:</label>
									orm-control" id="SITE_EMAIL" name="SITE_EMAIL" value="<?php echo $confs["SITE_EMAIL"]; ?>">


									E IMAGE:</label>
									orm-control" id="SITE_IMAGE" name="SITE_IMAGE" value="<?php echo $confs["SITE_IMAGE"]; ?>">


									"Update" class="btn btn-primary">Save</button>



						ne" role="tabpanel" id="controls">




									E ADMIN:</label>
									orm-control" id="SITE_ADMIN" name="SITE_ADMIN" value="<?php echo $confs["SITE_ADMIN"]; ?>">


									ITE CONTROL:</label>
									orm-control" id="SITE_CONTROL" name="SITE_CONTROL" value="<?php echo $confs["SITE_CONTROL"]; ?>">


									TE CONFIG:</label>
									orm-control" id="SITE_CONFIG" name="SITE_CONFIG" value="<?php echo $confs["SITE_CONFIG"]; ?>">


									">SITE LANGUAGE 1:</label>
									orm-control" id="SITE_LANGUAGE_1" name="SITE_LANGUAGE_1" value="<?php echo $confs["SITE_LANGUAGE_1"]; ?>">


									">SITE LANGUAGE 2:</label>
									orm-control" id="SITE_LANGUAGE_2" name="SITE_LANGUAGE_2" value="<?php echo $confs["SITE_LANGUAGE_2"]; ?>">


									FOLDER IMAGES:</label>
									orm-control" id="FOLDER_IMAGES" name="FOLDER_IMAGES" value="<?php echo $confs["FOLDER_IMAGES"]; ?>">


									"Update" class="btn btn-primary">Save</button>



						ne" role="tabpanel" id="builder">

								h4>


									ITE CREATOR:</label>
									orm-control" id="SITE_CREATOR" name="SITE_CREATOR" value="<?php echo $confs["SITE_CREATOR"]; ?>">


									TE EDITOR:</label>
									orm-control" id="SITE_EDITOR" name="SITE_EDITOR" value="<?php echo $confs["SITE_EDITOR"]; ?>">


									ITE BUILDER:</label>
									orm-control" id="SITE_BUILDER" name="SITE_BUILDER" value="<?php echo $confs["SITE_BUILDER"]; ?>">


									 LIST:</label>
									orm-control" id="SITE_LIST" name="SITE_LIST" value="<?php echo $confs["SITE_LIST"]; ?>">


									"Update" class="btn btn-primary">Save</button>



						ne" role="tabpanel" id="contact">




									AME CONTACT:</label>
									orm-control" id="NAME_CONTACT" name="NAME_CONTACT" value="<?php echo $confs["NAME_CONTACT"]; ?>">


									PHONE CONTACT:</label>
									orm-control" id="PHONE_CONTACT" name="PHONE_CONTACT" value="<?php echo $confs["PHONE_CONTACT"]; ?>">


									EMAIL CONTACT:</label>
									orm-control" id="EMAIL_CONTACT" name="EMAIL_CONTACT" value="<?php echo $confs["EMAIL_CONTACT"]; ?>">


									S:</label>
									="form-control" id="ADDRESS" name="ADDRESS"><?php echo $confs["ADDRESS"]; ?></textarea>


									"Update" class="btn btn-primary">Save</button>



						ne" role="tabpanel" id="social">




									R:</label>
									orm-control" id="TWITTER" name="TWITTER" value="<?php echo $confs["TWITTER"]; ?>">


									EBOOKID:</label>
									orm-control" id="FACEBOOKID" name="FACEBOOKID" value="<?php echo $confs["FACEBOOKID"]; ?>">


									label>
									orm-control" id="SKYPE" name="SKYPE" value="<?php echo $confs["SKYPE"]; ?>">


									RAM:</label>
									orm-control" id="TELEGRAM" name="TELEGRAM" value="<?php echo $confs["TELEGRAM"]; ?>">


									APP:</label>
									orm-control" id="WHATSAPP" name="WHATSAPP" value="<?php echo $confs["WHATSAPP"]; ?>">


									"Update" class="btn btn-primary">Save</button>




						ne" role="tabpanel" id="emailserver">




									P Mail Server:</label>
									orm-control" id="MAILSERVER" name="MAILSERVER" value="<?php echo $confs["MAILSERVER"]; ?>">


									t Server:</label>
									orm-control" id="PORTSERVER" name="PORTSERVER" value="<?php echo $confs["PORTSERVER"]; ?>">


									l Account:</label>
									orm-control" id="USEREMAIL" name="USEREMAIL" value="<?php echo $confs["USEREMAIL"]; ?>">


									ord:</label>
									orm-control" id="PASSMAIL" name="PASSMAIL" value="<?php echo $confs["PASSMAIL"]; ?>">


									"Update" class="btn btn-primary">Save</button>



						ne" role="tabpanel" id="security">

								h4>


									">SUPERADMIN NAME:</label>
									orm-control" id="SUPERADMIN_NAME" name="SUPERADMIN_NAME" value="<?php echo $confs["SUPERADMIN_NAME"]; ?>">


									L">SUPERADMIN LEVEL:</label>
									orm-control" id="SUPERADMIN_LEVEL" name="SUPERADMIN_LEVEL" value="<?php echo $confs["SUPERADMIN_LEVEL"]; ?>">


									IN NAME:</label>
									orm-control" id="ADMIN_NAME" name="ADMIN_NAME" value="<?php echo $confs["ADMIN_NAME"]; ?>">


									MIN LEVEL:</label>
									orm-control" id="ADMIN_LEVEL" name="ADMIN_LEVEL" value="<?php echo $confs["ADMIN_LEVEL"]; ?>">


									CURE HASH:</label>
									orm-control" id="SECURE_HASH" name="SECURE_HASH" value="<?php echo $confs["SECURE_HASH"]; ?>">


									ECURE TOKEN:</label>
									orm-control" id="SECURE_TOKEN" name="SECURE_TOKEN" value="<?php echo $confs["SECURE_TOKEN"]; ?>">


									"Update" class="btn btn-primary">Save</button>







	iv>
</div>
