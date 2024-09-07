<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
	= protect($_GET['w']);
} else {

	ta http-equiv="Refresh" content="0; url='dashboard.php?cms=menu&w=list'" />
	hp
}

if ($w == "list") {
	le = 'menu_options';
	tl = ucfirst(str_replace("_", " ", $tble));

	v class="container">
		lass="row pt-3">
			ass="table">


						 name="addrow" title="Add" class="btn btn-primary" href="dashboard.php?cms=menu&w=add">Add <i class="fa fa-plus-square"></i></a></th><th>Id</th><th>Id menu</th><th>Fluid</th><th>Placement</th><th>Aligment</th><th>Background</th><th>Color</th>




					query("SELECT * FROM $tble");
					$mopt->fetch_array()) {



							"editrow" title="Edit" class="btn btn-success" href="dashboard.php?cms=menu&w=edit&id=' . $mnop['id'] . '"><i class="fas fa-edit"></i></a>
							e="deleterow" title="Delete" class="btn btn-danger" href="dashboard.php?cms=menu&w=delete&id=' . $mnop['id'] . '"><i class="fas fa-trash-alt"></i></a>

						] . '</td>
						menu'] . '</td>
						id'] . '</td>
						cement'] . '</td>
						gment'] . '</td>
						kground'] . '</td>
						or'] . '</td>







	iv>
	hp
} elseif ($w == 'add') {
	le = 'menu_options';
	(isset($_POST['addrow'])) {
		nu = $_POST['id_menu'];
		 = $_POST['fluid'];
		ment = $_POST['placement'];
		ent = $_POST['aligment'];
		round = $_POST['background'];
		 = $_POST['color'];

		 "INSERT INTO menu_options (id_menu, fluid, placement, aligment, background, color) "
				$id_menu', '$fluid', '$placement', '$aligment', '$background', '$color')";
		onn->query($sql) === TRUE) {
			['success'] = 'The data was added correctly.';
			ript> window.location.replace("dashboard.php?cms=menu&w=list"); </script>';
		 {
			['error'] = 'Error: ' . $conn->error;


		>close();


	v class="container">
		lass="row">
			s="col-md-3">
				n btn-secondary"
				hboard.php?cms=menu&w=list">Back to List </a>

			s="col-md-9">
				ext-primary">Add Menu Options </h2>

			s="card py-3">
				card-body">
					-md-12">
						-3">
							"add_menu_options" method="POST">

									="control-label col-sm-3">Id menu:</label>
									orm-control" id="id_menu" name="id_menu">


									control-label col-sm-3">Fluid:</label>
									 id="fluid" name="fluid" >
										n>




									s ="control-label col-sm-3">Placement:</label>
									 id="placement" name="placement" >
										n>
										/option>
										cky-top</option>




									 ="control-label col-sm-3">Aligment:</label>
									 id="aligment" name="aligment" >
										ption>
										/option>
										n>



									ss ="control-label col-sm-3">Background:</label>
									 id="background" name="background" >
										y</option>
										ndary</option>
										ption>
										ion>
										ion>
										s</option>
										g</option>
										/option>



									control-label col-sm-3">Color:</label>
									 id="color" name="color" >
										ption>
										ion>



									ddrow" name="addrow" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Add</button>







	iv>
	hp
} elseif ($w == "edit") {
	(isset($_GET["id"])) {
		$_GET["id"];

	le = 'menu_options';

	(isset($_POST['editrow'])) {
		nu = $_POST["id_menu"];
		 = $_POST["fluid"];
		ment = $_POST["placement"];
		ent = $_POST["aligment"];
		round = $_POST["background"];
		 = $_POST["color"];

		 = "UPDATE `$tble` SET id_menu = '$id_menu', fluid = '$fluid', placement = '$placement', aligment = '$aligment', background = '$background', color = '$color' WHERE id=$id ";
		onn->query($query) === TRUE) {
			['success'] = "The data was updated correctly.";
			ript> window.location.replace("dashboard.php?cms=menu&w=list"); </script>';
		 {
			['error'] = "Error updating data: " . $conn->error;



	pt = $conn->query("SELECT * FROM $tble WHERE id='$id'")->fetch_assoc();

	v class="container">
		lass="row">
			s="col-md-3">
				n btn-secondary"
				hboard.php?cms=menu&w=list">Back to List </a>

			s="col-md-9">
				ext-primary">Edit Menu Options </h2>

			s="card py-3">
				card-body">
					-md-12">
						-3">
							"edit_menu_options" method="POST">

									="control-label col-sm-3">Id menu:</label>
									orm-control" id="id_menu" name="id_menu" value='<?php echo $mopt['id_menu'] ?>'>


								$mopt['fluid']);
								t', $mopt['placement']);
								', $mopt['aligment']);
								nd', $mopt['background']);
								$mopt['color']);


									ditrow" name="editrow" class="btn btn-primary"><span class="fas fa-edit"></span> Edit</button>







	iv>
	hp
} elseif ($w == "delete") {
	le = 'menu_options';

	v class="container">
		lass="row">
			s="col-md-3">
				hboard.php?cms=menu&w=list">List Menu Option</a>

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
							.location.replace("dashboard.php?cms=menu&w=list"); </script>';

							"Error deleting record";



					ost">
						roup">
							 id="deleterow" name="deleterow" class="btn btn-primary"><span class="fas fa-trash-alt"></span> Delete</button>






	iv>
	hp
}
?>
