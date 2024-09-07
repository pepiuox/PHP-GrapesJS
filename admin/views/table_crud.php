<?php
$p = new Protect();
if (isset($_GET['w']) && !empty($_GET['w'])) {
	= $p->secureStr($_GET['w']);
}

$c = new MyCRUD();
if ($w == "select") {

	($result = $c->selectData("SELECT * FROM table_config")) {
		_found = $result->num_rows;

		otal_found > 0) {
			esult->fetch_assoc();
			es = explode(',', $row['table_name']);



	v class="container">
		lass="row pt-3">
			s="col-md-6">
				system tables </h3>

			s="col-md-6">
				="post">
					m-group">
						rol-label" for="selecttb">Select Table</label> <select
							electtb" class="form-control">
							ct Table</option>

							s)) {
								$tname) {
									", $tname);
									name . '">' . ucfirst($remp) . '</option>' . "\n";





							.querySelector('#selecttb');
							er('change', function () {
								?cms=table_crud&w=list&tbl=' + this.value;
								url);






	iv>
	hp
} elseif ($w == "list") {
	le = $p->secureStr($_GET['tbl']);
	tl = ucfirst(str_replace("_", " ", $tble));

	v class="container">
		lass="row pt-3">
			s="col-md-3">
				n btn-secondary" href="dashboard.php?cms=table_crud&w=select">Select a Table </a>
				n btn-success" href="dashboard.php?cms=table_manager&w=editor&tbl=<?php echo $tble; ?>">Edit query Table </a>

			s="col-md-9">
				ext-primary">Data List from <?php echo $titl; ?></h2>


	iv>
	v class="container">
		lass="row">

			= 'qtmp.php';
			exists($fichero)) {
				ero);


			getDatalist($tble);


	iv>
	hp
} elseif ($w == 'add') {

	le = $p->secureStr($_GET['tbl']);
	tl = ucfirst(str_replace("_", " ", $tble));

	v class="container">
		lass="row">
			s="col-md-3">
				n btn-secondary"
				hboard.php?cms=table_crud&w=list&tbl=<?php echo $tble; ?>">Back to List</a>

			s="col-md-9">
				ext-primary">Add Data to <?php echo $titl; ?> </h2>


		lass="col-md-12">

			ta($tble);


	iv>
	iv>

	hp
} elseif ($w == "edit") {
	le = $p->secureStr($_GET['tbl']);
	tl = ucfirst(str_replace("_", " ", $tble));
	(isset($_GET["id"])) {
		$_GET["id"];


	v class="container">
		lass="row">
			s="col-md-3">
				n btn-secondary"
				hboard.php?cms=table_crud&w=list&tbl=<?php echo $tble; ?>">Back to List </a>

			s="col-md-9">
				ext-primary">Edit Data from <?php echo $titl; ?></h2>


		lass="col-md-12">

			eData($tble);
			QEdit($tble, $id);


	iv>
	hp
} elseif ($w == "delete") {
	le = $p->secureStr($_GET['tbl']);
	tl = ucfirst(str_replace("_", " ", $tble));
	ol = $c->getID($tble);

	v class="container">
		lass="row">
			s="col-md-3">
				hboard.php?cms=table_crud&w=list&tbl=<?php echo $tble; ?>">List</a>

			s="col-md-9">
				ext-primary">Delete data from <?php echo $titl; ?></h2>


		lass="row">
			s="col-md-12">
				ext-primary">Are you sure you want to delete data?</h4>
	hp
	(isset($_GET["id"])) {
		$_GET["id"];


	(isset($_POST["deleterow"])) {

		->selectData("DELETE FROM $tble WHERE $ncol='$id'") === TRUE) {
			['success'] = "Record deleted successfully";
		 {
			['error'] = "Error deleting record";



	>deleteData($tble, $id);



	iv>

	hp
}
?>
