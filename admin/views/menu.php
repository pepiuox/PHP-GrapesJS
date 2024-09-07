<?php
if (isset($_GET['w']) && !empty($_GET['w'])) {
	$w = protect($_GET['w']);
} else {
	?>
	<meta http-equiv="Refresh" content="0; url='dashboard.php?cms=menu&w=list'" />
	<?php
}

if ($w == "list") {
	$tble = 'menu_options';
	$titl = ucfirst(str_replace("_", " ", $tble));
	?>
	<div class="container">
		<div class="row pt-3">
			<table class="table">
				<thead>
					<tr>
						<th><a id="addrow" name="addrow" title="Add" class="btn btn-primary" href="dashboard.php?cms=menu&w=add">Add <i class="fa fa-plus-square"></i></a></th><th>Id</th><th>Id menu</th><th>Fluid</th><th>Placement</th><th>Aligment</th><th>Background</th><th>Color</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$mopt = $conn->query("SELECT * FROM $tble");
					while ($mnop = $mopt->fetch_array()) {
						echo '
					 <tr>
						<td><!--Button -->
							<a id="editrow" name="editrow" title="Edit" class="btn btn-success" href="dashboard.php?cms=menu&w=edit&id=' . $mnop['id'] . '"><i class="fas fa-edit"></i></a>
							<a id="deleterow" name="deleterow" title="Delete" class="btn btn-danger" href="dashboard.php?cms=menu&w=delete&id=' . $mnop['id'] . '"><i class="fas fa-trash-alt"></i></a>
						</td>
						<td>' . $mnop['id'] . '</td>
						<td>' . $mnop['id_menu'] . '</td>
						<td>' . $mnop['fluid'] . '</td>
						<td>' . $mnop['placement'] . '</td>
						<td>' . $mnop['aligment'] . '</td>
						<td>' . $mnop['background'] . '</td>
						<td>' . $mnop['color'] . '</td>
					</tr>
						';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
} elseif ($w == 'add') {
	$tble = 'menu_options';
	if (isset($_POST['addrow'])) {
		$id_menu = $_POST['id_menu'];
		$fluid = $_POST['fluid'];
		$placement = $_POST['placement'];
		$aligment = $_POST['aligment'];
		$background = $_POST['background'];
		$color = $_POST['color'];

		$sql = "INSERT INTO menu_options (id_menu, fluid, placement, aligment, background, color) "
				. "VALUES ('$id_menu', '$fluid', '$placement', '$aligment', '$background', '$color')";
		if ($conn->query($sql) === TRUE) {
			$_SESSION['success'] = 'The data was added correctly.';
			echo '<script> window.location.replace("dashboard.php?cms=menu&w=list"); </script>';
		} else {
			$_SESSION['error'] = 'Error: ' . $conn->error;
		}

		$conn->close();
	}
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<a class="btn btn-secondary"
				   href="dashboard.php?cms=menu&w=list">Back to List </a>
			</div>
			<div class="col-md-9">
				<h2 class="text-primary">Add Menu Options </h2>
			</div>
			<div class="card py-3">
				<div class="card-body">
					<div class="col-md-12">
						<div class="row pt-3">
							<form role="form" id="add_menu_options" method="POST">
								<div class="form-group">
									<label for="id_menu" class ="control-label col-sm-3">Id menu:</label>
									<input type="text" class="form-control" id="id_menu" name="id_menu">
								</div>
								<div class="form-group">
									<label for="fluid" class ="control-label col-sm-3">Fluid:</label>
									<select class="form-select" id="fluid" name="fluid" >
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
								<div class="form-group">
									<label for="placement" class ="control-label col-sm-3">Placement:</label>
									<select class="form-select" id="placement" name="placement" >
										<option value="top">top</option>
										<option value="bottom">bottom</option>
										<option value="sticky-top">sticky-top</option>

									</select>
								</div>
								<div class="form-group">
									<label for="aligment" class ="control-label col-sm-3">Aligment:</label>
									<select class="form-select" id="aligment" name="aligment" >
										<option value="start">start</option>
										<option value="center">center</option>
										<option value="end">end</option>
									</select>
								</div>
								<div class="form-group">
									<label for="background" class ="control-label col-sm-3">Background:</label>
									<select class="form-select" id="background" name="background" >
										<option value="primary">primary</option>
										<option value="secondary">secondary</option>
										<option value="light">light</option>
										<option value="dark">dark</option>
										<option value="info">info</option>
										<option value="success">success</option>
										<option value="warning">warning</option>
										<option value="danger">danger</option>
									</select>
								</div>
								<div class="form-group">
									<label for="color" class ="control-label col-sm-3">Color:</label>
									<select class="form-select" id="color" name="color" >
										<option value="light">light</option>
										<option value="dark">dark</option>
									</select>
								</div>
								<div class="form-group">
									<button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Add</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
} elseif ($w == "edit") {
	if (isset($_GET["id"])) {
		$id = $_GET["id"];
	}
	$tble = 'menu_options';

	if (isset($_POST['editrow'])) {
		$id_menu = $_POST["id_menu"];
		$fluid = $_POST["fluid"];
		$placement = $_POST["placement"];
		$aligment = $_POST["aligment"];
		$background = $_POST["background"];
		$color = $_POST["color"];

		$query = "UPDATE `$tble` SET id_menu = '$id_menu', fluid = '$fluid', placement = '$placement', aligment = '$aligment', background = '$background', color = '$color' WHERE id=$id ";
		if ($conn->query($query) === TRUE) {
			$_SESSION['success'] = "The data was updated correctly.";
			echo '<script> window.location.replace("dashboard.php?cms=menu&w=list"); </script>';
		} else {
			$_SESSION['error'] = "Error updating data: " . $conn->error;
		}
	}

	$mopt = $conn->query("SELECT * FROM $tble WHERE id='$id'")->fetch_assoc();
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<a class="btn btn-secondary"
				   href="dashboard.php?cms=menu&w=list">Back to List </a>
			</div>
			<div class="col-md-9">
				<h2 class="text-primary">Edit Menu Options </h2>
			</div>
			<div class="card py-3">
				<div class="card-body">
					<div class="col-md-12">
						<div class="row pt-3">
							<form role="form" id="edit_menu_options" method="POST">
								<div class="form-group">
									<label for="id_menu" class ="control-label col-sm-3">Id menu:</label>
									<input type="text" class="form-control" id="id_menu" name="id_menu" value='<?php echo $mopt['id_menu'] ?>'>
								</div>
								<?php
								enumsel($tble, 'fluid', $mopt['fluid']);
								enumsel($tble, 'placement', $mopt['placement']);
								enumsel($tble, 'aligment', $mopt['aligment']);
								enumsel($tble, 'background', $mopt['background']);
								enumsel($tble, 'color', $mopt['color']);
								?>
								<div class="form-group">
									<button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class="fas fa-edit"></span> Edit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
} elseif ($w == "delete") {
	$tble = 'menu_options';
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<a href="dashboard.php?cms=menu&w=list">List Menu Option</a>
			</div>
			<div class="col-md-9">
				<center>
					<h2 class="text-primary">Delete info</h2>
					<h4 class="text-primary">Are you sure you want to delete data?</h4>
				</center>
				<hr>
			</div>
		</div>
		<div class="card py-3">
			<div class="card-body">
				<div class="col-md-12">
					<?php
					if (isset($_GET["id"])) {
						$id = $_GET["id"];
					}
					if (isset($_POST["deleterow"])) {

						if ($conn->query("DELETE FROM $tble WHERE id='$id'") === TRUE) {
							$_SESSION['success'] = "Record deleted successfully";
							echo '<script> window.location.replace("dashboard.php?cms=menu&w=list"); </script>';
						} else {
							$_SESSION['error'] = "Error deleting record";
						}
					}
					?>
					<form method="post">
						<div class="form-group">
							<button type="submit" id="deleterow" name="deleterow" class="btn btn-primary"><span class="fas fa-trash-alt"></span> Delete</button>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
