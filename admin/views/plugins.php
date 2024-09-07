<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
	= protect($_GET['w']);
} else {

	ta http-equiv="Refresh" content="0; url='dashboard.php?cms=plugins&w=list'" />
	hp
}

if ($w == "list") {
	le = 'plugins_app';
	tl = ucfirst(str_replace("_", " ", $tble));

	v class="container">
		lass="row pt-3">
			ass="table">


						 name="addrow" title="Agregar" class="btn btn-primary" href="dashboard.php?cms=plugins&w=add">Add <i class="fas fa-plus-square"></i></a></th>


						th>



						</th>
						h>




					query("SELECT * FROM $tble");
					$mopt->fetch_array()) {



								itrow" title="Edit" class="btn btn-success" href="dashboard.php?cms=plugins&w=edit&id=' . $mnop['id'] . '"><i class="fas fa-edit"></i></a>
								deleterow" title="Delete" class="btn btn-danger" href="dashboard.php?cms=crud&w=delete&id=' . $mnop['id'] . '"><i class="fas fa-trash-alt"></i></a>

							 '</td>
							s'] . '</td>
							s_opts'] . '</td>
							'] . '</td>
							. '</td>
							s'] . '</td>
							s_script'] . '</td>
							s_css'] . '</td>






	iv>
	hp
} elseif ($w == 'add') {
	le = 'plugins_app';
	(isset($_POST['addrow'])) {
		ns = $_POST['plugins'];
		ns_opts = $_POST['plugins_opts'];
		t = $_POST['script'];
		 $_POST['css'];
		ns = $_POST['buttons'];
		ns_script = $_POST['plugins_script'];
		ns_css = $_POST['plugins_css'];

		 "INSERT INTO plugins_app (plugins, plugins_opts, script, css, buttons, plugins_script, plugins_css) "
				$plugins', '$plugins_opts', '$script', '$css', '$buttons', '$plugins_script', '$plugins_css')";
		onn->query($sql) === TRUE) {
			['success'] = 'The data was added correctly';
			ocation: dashboard.php?cms=plugins&w=list');

		 {
			['error'] = 'Error: ' . $conn->error;


		>close();


	v class="container">
		lass="row">
			s="col-md-3">
				n btn-secondary"
				hboard.php?cms=plugins&w=list">Back to List </a>

			s="col-md-9">
				ext-primary">Add Menu Options </h2>

			s="card py-3">
				card-body">
					-md-12">
						-3">
							lass="form-horizontal" role="form" id="add_plugins_app" enctype="multipart/form-data">

									s:</label>
									orm-control" id="plugins" name="plugins">


									lugins Opts:</label>
									orm-control" id="plugins_opts" name="plugins_opts">


									</label>
									orm-control" id="script" name="script">


									l>
									orm-control" id="css" name="css">


									s:</label>
									orm-control" id="buttons" name="buttons">


									>Plugins script:</label>
									orm-control" id="plugins_script" name="plugins_script">


									ugins css:</label>
									orm-control" id="plugins_css" name="plugins_css">


									ddrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square" onclick="dVals();"></span> Add</button>







	iv>
	hp
} elseif ($w == "edit") {
	(isset($_GET["id"])) {
		$_GET["id"];

	le = 'plugins_app';

	(isset($_POST['editrow'])) {
		ns = $_POST["plugins"];
		ns_opts = $_POST["plugins_opts"];
		t = $_POST["script"];
		 $_POST["css"];
		ns = $_POST["buttons"];
		ns_script = $_POST["plugins_script"];
		ns_css = $_POST["plugins_css"];

		 = "UPDATE `$tble` SET plugins = '$plugins', plugins_opts = '$plugins_opts', script = '$script', css = '$css', buttons = '$buttons', plugins_script = '$plugins_script', plugins_css = '$plugins_css' WHERE id=$id ";
		onn->query($query) === TRUE) {
			["success"] = "The data was updated correctly.";
			ocation: dashboard.php?cms=plugins&w=list");

		 {
			["error"] = "Error updating data: " . $conn->error;



	pt = $conn->query("SELECT * FROM $tble WHERE id='$id'")->fetch_assoc();

	v class="container">
		lass="row">
			s="col-md-3">
				n btn-secondary"
				hboard.php?cms=plugins&w=list">Back to List </a>

			s="col-md-9">
				ext-primary">Edit Menu Options </h2>

			s="card py-3">
				card-body">
					-md-12">
						-3">
							"add_plugins_app" method="POST">

									="control-label col-sm-3">Plugins:</label>
									orm-control" id="plugins" name="plugins" value="<?php echo $mopt['plugins']; ?>">


									lass ="control-label col-sm-3">Plugins Opts:</label>
									orm-control" id="plugins_opts" name="plugins_opts" value="<?php echo $mopt['plugins_opts']; ?>">


									"control-label col-sm-3">Script:</label>
									orm-control" id="script" name="script" value="<?php echo $mopt['script']; ?>">


									ntrol-label col-sm-3">Css:</label>
									orm-control" id="css" name="css" value="<?php echo $mopt['css']; ?>">


									="control-label col-sm-3">Buttons:</label>
									orm-control" id="buttons" name="buttons" value="<?php echo $mopt['buttons']; ?>">


									 class ="control-label col-sm-3">Plugins script:</label>
									orm-control" id="plugins_script" name="plugins_script" value="<?php echo $mopt['plugins_script']; ?>">


									ass ="control-label col-sm-3">Plugins css:</label>
									orm-control" id="plugins_css" name="plugins_css" value="<?php echo $mopt['plugins_css']; ?>">


									ditrow" name="editrow" class="btn btn-primary"><span class = "fas fa-edit"></span> Edit</button>







	iv>
	hp
} elseif ($w == "delete") {
	le = 'plugins_app';

	v class="container">
		lass="row">
			s="col-md-3">
				hboard.php?cms=plugins&w=list">List Menu Option</a>

			s="col-md-9">

					-primary">Delete info</h2>
					-primary">Are you sure you want to delete data?</h4>




		lass="card py-3">
			s="card-body">
				col-md-12">

					["id"])) {


					T["deleterow"])) {

						DELETE FROM $tble WHERE id='$id'") === TRUE) {
							= "Record deleted successfully";
							hboard.php?cms=plugins&w=list');


							"Error deleting record";



					ost">
						roup">
							 id="deleterow" name="deleterow" class="btn btn-primary"><span class="fas fa-trash-alt"></span> Delete</button>






	iv>
	hp
}
?>
