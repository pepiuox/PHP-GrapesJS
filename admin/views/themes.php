<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
	= protect($_GET['w']);
} else {

	ta http-equiv="Refresh" content="0; url='dashboard.php?cms=themes&w=list'" />
	hp
}
if ($w == "list") {

	v class="container">
		lass="row pt-3">
			ass="table">


						 name="addrow" title="Add" class="btn btn-primary" href="dashboard.php?cms=theme_template&amp;w=add&amp;tbl=themes">Add <i class="fa fa-plus-square"></i></a></th>
						>
						p</th>




					->query("SELECT * FROM themes");
					->num_rows;
					{
						sult->fetch_array()) {


							name="edittemplate" title="Edit Template" class="btn btn-success" href="dashboard.php?cms=theme_template&amp;w=list"><i class="fas fa-edit"></i> List</a>
							me="editoption" title="Edit Option" class="btn btn-primary" href="dashboard.php?cms=themes&amp;w=options&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-edit"></i> More options</a>

						me_name'] . '</td>
						me_bootstrap'] . '</td>







	iv>
	hp
} elseif ($w == "options") {
	(isset($_GET['id']) && !empty($_GET['id'])) {
		protect($_GET['id']);
	lse {

		http-equiv="Refresh" content="0; url='dashboard.php?cms=themes&w=list'" />



	- Color-Picker -->
	ript src="<?php echo SITE_PATH; ?>assets/plugins/color-picker/js/index.min.js" type="text/javascript"></script>
	v class="container">
		lass="row">

				nav nav-tabs" id="nav-tab" role="tablist">
					nav-link active" id="theme_settings-tab" data-bs-toggle="tab" data-bs-target="#theme_settings" type="button" role="tab" aria-controls="theme_settings" aria-selected="true">Theme Settings</button>
					nav-link" id="theme_palette-tab" data-bs-toggle="tab" data-bs-target="#theme_palette" type="button" role="tab" aria-controls="theme_palette" aria-selected="false">Theme Palette</button>
					nav-link" id="theme_lead_font-tab" data-bs-toggle="tab" data-bs-target="#theme_lead_font" type="button" role="tab" aria-controls="theme_lead_font" aria-selected="false">Theme Lead Font</button>
					nav-link" id="theme_headings_font-tab" data-bs-toggle="tab" data-bs-target="#theme_headings_font" type="button" role="tab" aria-controls="theme_headings_font" aria-selected="false">Theme Headings Font</button>
					nav-link" id="theme_base_font-tab" data-bs-toggle="tab" data-bs-target="#theme_base_font" type="button" role="tab" aria-controls="theme_base_font" aria-selected="false">Theme Base Font</button>
					nav-link" id="theme_base_colors-tab" data-bs-toggle="tab" data-bs-target="#theme_base_colors" type="button" role="tab" aria-controls="theme_base_colors" aria-selected="false">Theme Base Colors</button>


			s="tab-content" id="nav-tabContent">
				tab-pane fade show active" id="theme_settings" role="tabpanel" aria-labelledby="theme_settings-tab" tabindex="0">
					d">
						ody">

							primary">Theme Settings</h3>

//This is temporal file only for add new row
							me_settings'])) {
								tainer"];
								'];
								'];
								ius_sm'];
								ius_lg'];
								t_size'];

								settings` SET container = '$container', spacer = '$spacer', radius = '$radius', radius_sm = '$radius_sm', radius_lg = '$radius_lg', font_size = '$font_size' WHERE idts='$id' ";
								 === TRUE) {
									correctly.";


window.onload = function() {
	ation.href = 'dashboard.php?cms=themes&w=options&id=" . $id . "';
}
</script>";

									" . $conn->error;


							SELECT * FROM theme_settings WHERE idts='$id'");
							oc();

							-horizontal" role="form" id="add_theme_settings" method="POST">
								_settings', "container", $ts["container"]); ?>
								_settings', 'spacer', $ts['spacer']); ?>

									"control-label col-sm-3">Radius:</label>
									orm-control" id="radius" name="radius" value="<?php echo $ts['radius']; ?>">


									s ="control-label col-sm-3">Radius sm:</label>
									orm-control" id="radius_sm" name="radius_sm" value="<?php echo $ts['radius_sm']; ?>">


									s ="control-label col-sm-3">Radius lg:</label>
									orm-control" id="radius_lg" name="radius_lg" value="<?php echo $ts['radius_lg']; ?>">

								_settings', 'font_size', $ts['font_size']); ?>

									heme_settings" name="theme_settings" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>






				tab-pane fade" id="theme_palette" role="tabpanel" aria-labelledby="theme_palette-tab" tabindex="0">
					d">
						ody">

							primary">Theme Palette</h3>

//This is temporal file only for add new row
							me_palette'])) {
								ry_color'];
								ondary_color'];
								or'];
								olor'];
								or'];
								ss_color'];
								ng_color'];
								_color'];
								_color'];
								custom_light_color'];
								ustom_dark_color'];

								ette SET primary_color = ?, secondary_color = ', info_color = ?, light_color = ?, dark_color = ?, success_color = ?, warning_color = ?, danger_color = ?, custom_color = ?, custom_light_color = ?, custom_dark_color = ? WHERE idtp = ? ";
								sql);
								sssssss", $primary, $secondary, $info, $light, $dark, $success, $warning, $danger, $custom, $custom_light, $custom_dark, $id);

									>error;

									correctly.";

window.onload = function() {
	ation.href = 'dashboard.php?cms=themes&w=options&id=" . $id . "';
}
</script>";


							 theme_palette WHERE idtp=?";
							($sql);
							 $id);

							esult(); // get the mysqli result
							assoc();

							-horizontal" role="form" id="edit_theme_palette" method="POST">

									class ="control-label col-sm-3">Primary:</label>
									orm-control" id="primary_color" name="primary_color" value="<?php echo $tp['primary_color']; ?>">

										ocument.querySelector('#primary_color'));
										ction (r, g, b, a) {
											 g, b, a);




									" class ="control-label col-sm-3">Secondary:</label>
									orm-control" id="secondary_color" name="secondary_color" value="<?php echo $tp['secondary_color']; ?>">

										(document.querySelector('#secondary_color'));
										unction (r, g, b, a) {
											 g, b, a);




									ss ="control-label col-sm-3">Info:</label>
									orm-control" id="info_color" name="info_color" value="<?php echo $tp['info_color']; ?>">

										ment.querySelector('#info_color'));
										on (r, g, b, a) {
											 g, b, a);




									ass ="control-label col-sm-3">Light:</label>
									orm-control" id="light_color" name="light_color" value="<?php echo $tp['light_color']; ?>">

										ument.querySelector('#light_color'));
										ion (r, g, b, a) {
											 g, b, a);




									ss ="control-label col-sm-3">Dark:</label>
									orm-control" id="dark_color" name="dark_color" value="<?php echo $tp['dark_color']; ?>">

										ment.querySelector('#dark_color'));
										on (r, g, b, a) {
											 g, b, a);




									class ="control-label col-sm-3">Success:</label>
									orm-control" id="success_color" name="success_color" value="<?php echo $tp['success_color']; ?>">

										ocument.querySelector('#success_color'));
										ction (r, g, b, a) {
											 g, b, a);




									class ="control-label col-sm-3">Warning:</label>
									orm-control" id="warning_color" name="warning_color" value="<?php echo $tp['warning_color']; ?>">

										ocument.querySelector('#warning_color'));
										ction (r, g, b, a) {
											 g, b, a);




									lass ="control-label col-sm-3">Danger:</label>
									orm-control" id="danger_color" name="danger_color" value="<?php echo $tp['danger_color']; ?>">

										cument.querySelector('#danger_color'));
										tion (r, g, b, a) {
											 g, b, a);




									lass ="control-label col-sm-3">Custom:</label>
									orm-control" id="custom_color" name="custom_color" value="<?php echo $tp['custom_color']; ?>">

										cument.querySelector('#custom_color'));
										tion (r, g, b, a) {
											 g, b, a);




									lor" class ="control-label col-sm-3">Custom light:</label>
									orm-control" id="custom_light_color" name="custom_light_color" value="<?php echo $tp['custom_light_color']; ?>">

										 CP(document.querySelector('#custom_light_color'));
										, function (r, g, b, a) {
											 g, b, a);




									or" class ="control-label col-sm-3">Custom dark:</label>
									orm-control" id="custom_dark_color" name="custom_dark_color" value="<?php echo $tp['custom_dark_color']; ?>">

										CP(document.querySelector('#custom_dark_color'));
										 function (r, g, b, a) {
											 g, b, a);




									heme_palette" name="theme_palette" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>






				tab-pane fade" id="theme_lead_font" role="tabpanel" aria-labelledby="theme_lead_font-tab" tabindex="0">
					d">
						ody">

							primary">Theme Lead Font</h3>

//This is temporal file only for add new row
							me_lead_font'])) {

								'];

								lead_font` SET size = '$size', weight = '$weight' WHERE idtlf='$id' ";
								 === TRUE) {
									correctly.";


window.onload = function() {
	ation.href = 'dashboard.php?cms=themes&w=options&id=" . $id . "';
}
</script>";

									" . $conn->error;


							"SELECT * FROM theme_lead_font WHERE idtlf='$id'");
							ssoc();

							-horizontal" role="form" id="add_theme_lead_font" method="POST">

									ontrol-label col-sm-3">Size:</label>
									orm-control" id="size" name="size" value="<?php echo $tlf['size']; ?>">


									"control-label col-sm-3">Weight:</label>
									orm-control" id="weight" name="weight" value="<?php echo $tlf['weight']; ?>">


									heme_lead_font" name="theme_lead_font" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>






				tab-pane fade" id="theme_headings_font" role="tabpanel" aria-labelledby="theme_headings_font-tab" tabindex="0">
					d">
						ody">

							primary">Theme Headings Font</h3>

//This is temporal file only for add new row
							me_headings_font'])) {
								'];
								'];
								ine_weight'];

								headings_font` SET family = '$family', weight = '$weight', line_weight = '$line_weight' WHERE idthf='$id' ";
								 === TRUE) {
									correctly.";


window.onload = function() {
	ation.href = 'dashboard.php?cms=themes&w=options&id=" . $id . "';
}
</script>";

									" . $conn->error;


							"SELECT * FROM theme_headings_font WHERE idthf='$id'");
							ssoc();

							-horizontal" role="form" id="add_theme_headings_font" method="POST">

									"control-label col-sm-3">Family:</label>
									orm-control" id="family" name="family" value="<?php echo $thf['family']; ?>">

								_headings_font', 'weight', $thf['weight']); ?>

									ass ="control-label col-sm-3">Line weight:</label>
									orm-control" id="line_weight" name="line_weight" value="<?php echo $thf['line_weight']; ?>">


									heme_headings_font" name="theme_headings_font" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>






				tab-pane fade" id="theme_base_font" role="tabpanel" aria-labelledby="theme_base_font-tab" tabindex="0">
					d">
						ody">

							primary">Theme Base Font</h3>

							me_base_font'])) {
								'];

								'];
								ine_height'];

								base_font` SET family = '$family', size = '$size', weight = '$weight', line_height = '$line_height' WHERE idtbf='$id' ";
								 === TRUE) {
									correctly.";


window.onload = function() {
	ation.href = 'dashboard.php?cms=themes&w=options&id=" . $id . "';
}
</script>";

									" . $conn->error;


							"SELECT * FROM theme_base_font WHERE idtbf='$id'");
							ssoc();

							-horizontal" role="form" id="add_theme_base_font" method="POST">

									"control-label col-sm-3">Family:</label>
									orm-control" id="family" name="family" value="<?php echo $tbf['family']; ?>">


									ontrol-label col-sm-3">Size:</label>
									orm-control" id="size" name="size" value="<?php echo $tbf['size']; ?>">

								_base_font', 'weight', $tbf['weight']); ?>

									ass ="control-label col-sm-3">Line height:</label>
									orm-control" id="line_height" name="line_height" value="<?php echo $tbf['line_height']; ?>">


									heme_base_font" name="theme_base_font" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>





				tab-pane fade" id="theme_base_colors" role="tabpanel" aria-labelledby="theme_base_colors-tab" tabindex="0">
					d">
						ody">

							primary">Theme Base Colors</h3>

							me_base_colors'])) {
								pslashes', $_POST);




								body_color'];
								text_color'];
								'links_color'];

								base_colors` SET body_color = '$body_color', text_color = '$text_color', links_color = '$links_color' WHERE idtbc='$id'";
								 === TRUE) {
									correctly.";


window.onload = function() {
	ation.href = 'dashboard.php?cms=themes&w=options&id=" . $id . "';
}
</script>";

									" . $conn->error;


							"SELECT * FROM theme_base_colors WHERE idtbc='$id'");
							ssoc();

							-horizontal" role="form" id="add_theme_base_colors" method="POST">

									ss ="control-label col-sm-3">Body:</label>
									orm-control" id="body_color" name="body_color" value="<?php echo $tbc['body_color']; ?>">

										ment.querySelector('#body_color'));
										on (r, g, b, a) {
											 g, b, a);




									ontrol-label col-sm-3">Text:</label>
									orm-control" id="text_color" name="text_color" value="<?php echo $tbc['text_color']; ?>">

										ment.querySelector('#text_color'));
										on (r, g, b, a) {
											 g, b, a);




									control-label col-sm-3">Links:</label>
									orm-control" id="links_color" name="links_color" value="<?php echo $tbc['links_color']; ?>">

										ument.querySelector('#links_color'));
										ion (r, g, b, a) {
											 g, b, a);




									heme_base_colors" name="theme_base_colors" class="btn btn-primary"><span class = "fas fa-edit"></span> Update</button>







	iv>
<?php } ?>
