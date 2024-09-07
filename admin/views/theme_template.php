<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
	= protect($_GET['w']);
} else {

	ta http-equiv="Refresh" content="0; url='dashboard.php?cms=theme_template&w=list'" />
	hp
}
if ($w == "list") {

	v class="container">
		lass="row pt-3">
			ass="table">


						 name="addrow" title="Add" class="btn btn-primary" href="dashboard.php?cms=theme_template&amp;w=add&amp;tbl=themes">Add <i class="fa fa-plus-square"></i></a></th>
						>
						p</th>
						th>
						th>




					->query("SELECT * FROM themes ");
					->num_rows;
					{
						sult->fetch_array()) {


							"editrow" title="Edit" class="btn btn-success" href="dashboard.php?cms=theme_template&amp;w=edit&amp;tbl=theme_template&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-edit"></i></a>
							e="deleterow" title="Delete" class="btn btn-danger" href="dashboard.php?cms=theme_template&amp;w=delete&amp;tbl=theme_template&amp;id=' . $prow['theme_id'] . '"><i class="fas fa-trash-alt"></i></a>

						me_name'] . '</td>
							bootstrap'] . '</td>
						e_default'] . '</td>
						ive_theme'] . '</td>







	iv>
	hp
} elseif ($w == "add") {

	g_directory = '../themes';
	sults_array = array();

	(is_dir($log_directory)) {
		andle = opendir($log_directory)) {
			the parentheses I added:
			file = readdir($handle)) !== FALSE) {
				ay[] = $file;

			$handle);



	(isset($_POST['addtheme'])) {
		me = uniqid(rand(), false);

		_name = $_POST['theme_name'];
		 = $_POST['theme'];
		default = $_POST['base_default'];
		e_theme = $_POST['active_theme'];

		= $conn->prepare("INSERT INTO themes (theme_id, theme_name, theme, base_default, active_theme) VALUES (?,?,?,?,?)");
		>bind_param("sssss", $idtheme, $theme_name, $theme, $base_default, $active_theme);
		>execute();

		= $conn->prepare("INSERT INTO theme_base_colors (idtbc) VALUES (?)");
		>bind_param("s", $idtheme);
		>execute();

		= $conn->prepare("INSERT INTO theme_base_font (idtbf) VALUES (?)");
		>bind_param("s", $idtheme);
		>execute();

		= $conn->prepare("INSERT INTO theme_headings_font (idthf) VALUES (?)");
		>bind_param("s", $idtheme);
		>execute();

		= $conn->prepare("INSERT INTO theme_lead_font (idtlf) VALUES (?)");
		>bind_param("s", $idtheme);
		>execute();

		= $conn->prepare("INSERT INTO theme_palette (idtp) VALUES (?)");
		>bind_param("s", $idtheme);
		>execute();

		= $conn->prepare("INSERT INTO theme_settings (idts) VALUES (?)");
		>bind_param("s", $idtheme);
		>execute();
		>close();


	v class="container">
		lass="row pt-3">

			hod="post" class="row form-horizontal" role="form" id="add_themes" enctype="multipart/form-data">

				form-group">
					me_name">Theme name:</label>
					xt" class="form-control" id="theme_name" name="theme_name">

				form-group">
					me_bootstrap">Select theme bootstrap </label>
					heme_bootstrap" id="theme_bootstrap" class="form-select" aria-label="select">
						me bootstrap</option>


						array as $value) {
							 $value === '..') {


							' . $value . '">' . ucfirst($value) . '</option>';




				form-group">
					e_default">Base default:</label>
					ext" class="form-select" id="base_default" name="base_default" >
						">Yes</option>
						>No</option>


				form-group">
					ive_theme">Active theme:</label>
					ext" class="form-select" id="active_theme" name="active_theme" >
						">Yes</option>
						>No</option>


				form-group">
					ubmit" id="addtheme" name="addtheme" class="btn btn-primary"><span class="fas fa-plus-square"></span> Add theme</button>



	iv>
	hp
} elseif ($w == "edit") {
	(isset($_GET["id"])) {
		$_GET["id"];

		irectory = '../themes';
		ts_array = array();

		_dir($log_directory)) {
			le = opendir($log_directory)) {
				 parentheses I added:
				e = readdir($handle)) !== FALSE) {
					] = $file;

				ndle);



//This is temporal file only for add new row
		set($_POST['editrow'])) {

			me = $_POST["theme_name"];
			otstrap = $_POST["theme_bootstrap"];
			ault = $_POST["base_default"];
			heme = $_POST["active_theme"];

			"UPDATE themes SET theme_name = '$theme_name', theme_bootstrap = '$theme_bootstrap', base_default = '$base_default', active_theme = '$active_theme' WHERE theme_id='$id' ";
			->query($query) === TRUE) {
				uccess"] = "The data was updated correctly.";

				t>
window.onload = function() {
	ation.href = 'dashboard.php?cms=table_crud&w=list&tbl=themes';
}
</script>";

				rror"] = "Error updating data: " . $conn->error;


		 $conn->query("SELECT * FROM themes WHERE theme_id='$id'");
		$rtt->fetch_assoc();

		lass="container">
			s="row">
				form" id="edit_themes" method="POST">

					m-group">
						name" class ="control-label col-sm-3">Theme name:</label>
						 class="form-control" id="theme_name" name="theme_name" value="<?php echo $tt['theme_name']; ?>">

					m-group">
						bootstrap" class ="control-label col-sm-3">Theme bootstrap:</label>
						e_bootstrap" id="theme_bootstrap" class="form-select" aria-label="select">
							bootstrap</option>

							ootstrap'];

							ay as $value) {
								alue === '..') {



									alue . '" selected>' . ucfirst($value) . '</option>';

									alue . '">' . ucfirst($value) . '</option>';





					es('themes', 'base_default', $tt['base_default']); ?>
					es('themes', 'active_theme', $tt['active_theme']); ?>

					m-group">
						it" id="editrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>








} elseif ($w == "delete") {

}
?>
