<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
	ract($_POST);

	(!isset($_GET['w']) || empty($_GET['w'])) {
		('Location: dashboard.php?cms=column_manager&w=select');
		;


	= protect($_GET['w']);
	= new MyCRUD();

	ction tnmes($tbsl = '') {
		 $conn;
		esult = $conn->query("SELECT DATABASE()")) {
			esult->fetch_row();
			close();

		= "SHOW TABLES FROM $row[0]";
		ts = $conn->query($sqls);
		Count = 0;
		($row = mysqli_fetch_row($results)) {
			es[$arrayCount] = $row[0];
			nt++;

		<div class="form-group" id="vtble">
							-label" for="j_table">Select Table</label>
							">
							" name="j_table" class="form-select">
								Table</option>' . "\n";
		h ($tableNames as $tname) {
			r_replace("_", " ", $tname);
			e === $tbsl) {
				n value="' . $tname . '" selected>' . ucfirst($rem) . '</option>' . "\n";

				n value="' . $tname . '">' . ucfirst($rem) . '</option>' . "\n";


		   </select>




	($w == "select") {

		Names = '';
		t = $conn->query("SELECT * FROM table_settings");

		esult->num_rows > 0) {
			esult->fetch_assoc();
			es = explode(',', $row['table_name']);
		 {
			exists record tables ';


		set($_POST['add'])) {
			ta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">';


		lass="container">
			s="row">
				col-md-12">
					-primary">Select your table for column options</h2>

					w form-horizontal" method="post">
						roup col-md-12">
							-label" for="selecttb">Select Table</label>
							 name="selecttb" class="form-select">
								Table</option>

								 {
									ame) {
										$tname);
										e . '">' . ucfirst($remp) . '</option>' . "\n";





								erySelector('#selecttb');
								'change', function () {
									s=column_manager&w=build&tbl=' + this.value;
									);



						to py-4">
							mary mb-3" href="dashboard.php?cms=table_manager&w=add">Go to add more tables</a>






		 record
	lseif ($w == "build") {
		= protect($_GET['tbl']);
		 = ucfirst(str_replace("_", " ", $tble));

		lass="container">
			s="row">


				lass="col-12">';
				ass="text-primary">Build option ' . $tname . ' table for columns</h2>';
				';

				ertTQO($table, $column) {

					>query("SELECT table_name,col_name FROM table_column_settings WHERE table_name='$table' AND col_name='$column'");
					num_rows;
					{
						O table_column_settings (table_name,col_name) VALUES ('" . $table . "','" . $column . "')";

						qry) === TRUE) {









				conn->query("SHOW COLUMNS FROM $tble");
				);
				lt0->fetch_array();
				0];

				 = $result0->fetch_array()) {
					d'] == $idr) {


						row0['Field']);



				board.php?cms=column_manager&w=update&tbl=" . $tble . "&id=";

				CT * FROM table_column_settings WHERE table_name='$tble'";

				onn->query($sql);
				lass="col-12">';
				class="row form-horizontal" method="post">';
				lass="col-auto">';
				ss="btn btn-primary mb-3" href="dashboard.php?cms=column_manager&w=select">Select table options</a>';
				';

				 class="table">';
				>';
				lumn name</th><th>List</th><th>Add</th><th>Update</th><th>View</th><th>Edit option</th>';
				d>';
				>';
				= $result->fetch_array()) {


					. $row['col_name'] . '</b></td>'

					ist'] == 1) {




					';
					dd'] == 1) {




					';
					pdate'] == 1) {





					';
					iew'] == 1) {




					<a href="' . $lnk . $row['tqop_Id'] . '"><i class="fas fa-edit"></i> Edit options</a></td>';


				y>';
				>';
				lass="col-auto">';
				ss="btn btn-primary mb-3" href="dashboard.php?cms=column_manager&w=select">Select table options</a>';
				';

				>';
				';




	lseif ($w == "update") {

		= protect($_GET['tbl']);
		protect($_GET['id']);

		 $conn->prepare("SELECT * FROM table_column_settings WHERE tqop_Id=? AND table_name=?");
		bind_param("is", $id, $tble);
		execute();
		= $ttl->get_result();
		close();
		 $ucol->fetch_assoc();
		 $ttn['col_name'];
		 $ttn['tqop_Id'];

		 ucfirst(str_replace("_", " ", $cnm));
//
//extract($_POST);
		set($_POST['build'])) {
			ta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '">';


		set($_POST['select'])) {
			ta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=select">';


		t src="../assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>

		lass="container">
			s="row">
				col-md-12">
					w form-horizontal" method="post">

							 name="build" class="btn btn-secondary mb-3">Build options</button>
							 name="select" class="btn btn-success mb-3">Select table options</button>



				card">
					d-header p-2">
						ies and configuration</h3>
						-tabs" id="myTab" role="tablist">
							<a class="nav-link active" href="#optionsshows" data-toggle="tab">Options shows</a></li>
							<a class="nav-link" href="#properties" data-toggle="tab">Properties</a></li>
							<a class="nav-link" href="#addquery" data-toggle="tab">Add query</a></li>
							<a class="nav-link" href="#relatedtables" data-toggle="tab">Related tables</a></li>
							<a class="nav-link" href="#dependent" data-toggle="tab">Dependent dropdown</a></li>


					d-body">
						-12 py-4">
							nt">
								ane" role="tabpanel" id="optionsshows">

									) {


										col_add' => 0, 'col_update' => 0, 'col_view' => 0);

										lue) {



										) {










										settings SET $colset WHERE tqop_Id='$id' AND table_name='$tble'";
										RUE) {
											";
											content="0;url=dashboard.php?cms=column_manager&w=update&tbl=' . $tble . '&id=' . $id . '#optionsshows">';

											$conn->error;



									 echo ucfirst(str_replace("_", " ", $ttn['col_name'])) . ' from table ' . ucfirst(str_replace("_", " ", $tble)); ?></h3>
									Options for listing, adding editing and viewing</h5>
									t" enctype="multipart/form-data">

											t" class="btn btn-primary mb-3">Update Column</button>














										T * FROM table_column_settings WHERE tqop_Id=? AND table_name=?");
										$tble);




											)) {

												'</b></td>' . "\n";
												nput" type="checkbox" value="1" name="col_list" id="col_list"';




												nput" type="checkbox" value="1" name="col_add" id="col_add"';




												nput" type="checkbox" value="1" name="col_update" id="col_update"';




												nput" type="checkbox" value="1" name="col_view" id="col_view"';










			ype="submit" name="submit" class="btn btn-primary mb-3">Update column</button>
			 "\n";



								le="tabpanel" id="properties">

									])) {

										type']);

										 table_column_settings SET input_type = ? WHERE tqop_Id = ?");
										 $id);


											R_ERROR);

											" . $stmt->affected_rows;



									for the column</h3>
									lect input type to add and edit</h4>
									t" enctype="multipart/form-data">


												" for="input_type">Select Input Type for <?php echo $cln; ?></label>

													e" class="form-select">
































													e="submitin" class="btn btn-warning">Save</button>





								le="tabpanel" id="addquery">

									])) {
										);

										 table_column_settings SET where = ? WHERE tqop_Id = ?");
										$inp);


											R_ERROR);

											" . $stmt->affected_rows;



									 the column</h3>
									t" enctype="multipart/form-data">


											 "\n";
											ame'] . '">Query for ' . $cln . ':</label>' . "\n";
											="form-control" id="where" name="where">' . $ttn['where'] . '</textarea>' . "\n";




													e="submitqr" class="btn btn-warning">Save</button>





								le="tabpanel" id="relatedtables">

									])) {


										]);
										']);
										d']);
										alue']);

										 table_column_settings SET joins = ?, j_table = ?, j_id = ?, j_value = ? WHERE tqop_Id = ?");
										ins, $tb, $sr, $sv, $fid);


											R_ERROR);

											" . $stmt->affected_rows;



									umn value to different tables</h3>
									lect table to relate</h4>
									ontal" method="POST" id="queries">

											">
												ins">Select type JOIN</label>

													form-select">













































								on () {
									function (e) {

										();
										id']; ?>';
										value']; ?>';




















												" name="seltables"></div>



													e="submitrv" class="btn btn-warning">Save</button>





								le="tabpanel" id="dependent">

									])) {
										eld']);
										field']);

										 table_column_settings SET main_field = ?, lookup_field = ? WHERE tqop_Id = ?");
										 $qrl, $inp);


											R_ERROR);

											" . $stmt->affected_rows;



									 field</h3>
									t" enctype="multipart/form-data">


											 "\n";
											put" type="checkbox" id="dropdep" name="dropdep" value="1"';




											bel" for="dropdep">Parent main field:</label>' . "\n";


												n";
												 main field:</label>' . "\n";
												-control" id="main_field" name="main_field" value="' . $ttn['j_id'] . '">' . "\n";


												n";
												up filter field:</label>' . "\n";
												-control" id="lookup_field" name="lookup_field" value="' . $ttn['col_name'] . '">' . "\n";


												n";
												 main field:</label>' . "\n";
												-control" id="main_field" name="main_field" value="' . $ttn['main_field'] . '">' . "\n";


												n";
												up filter field:</label>' . "\n";
												-control" id="lookup_field" name="lookup_field" value="' . $ttn['lookup_field'] . '">' . "\n";





													e="submitdp" class="btn btn-warning">Save</button>









				col-md-12 py-4">

					w form-horizontal" method="post">

							 name="build" class="btn btn-secondary mb-3">Build options</button>
							 name="select" class="btn btn-success mb-3">Select table options</button>






	lseif ($w == "editor") {
		t($_POST);

		lass="container">
			s="row">
				ext-primary">Change options for columns</h2>


				ect($_GET['tbl']);
				W COLUMNS FROM " . $tble;
				conn->query($sql1);
				i_fetch_array($result1);
				[0];

				conn->prepare("SELECT * FROM table_column_settings WHERE table_name=?");
				nd_param("s", $tble);
				ecute();
				sult->get_result();
				sc->num_rows;


				class="row form-horizontal" method="POST" role="form" id="query_' . $tble . '">' . "\n";
				lass="form-group">
						bmit" id="updatequeries" name="updatequeries" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Save query to columns</button>
<a class="btn btn-success" href="dashboard.php?cms=table_crud&w=list&tbl=' . $tble . '">View Table</a>
</div>' . "\n";
				= $tbsc->fetch_assoc()) {
					lace("_", " ", $ttn['col_name']);
					'col_name'];

							p_Id'] . '{


						;

$(document).ready(function () {
				. $ttn['tqop_Id'] . '").hide();
				 $ttn['tqop_Id'] . '").hide();
				 $ttn['tqop_Id'] . '").on("change", function() {

					#type_' . $ttn['tqop_Id'] . ' option:selected").val();
					) {
						['tqop_Id'] . '").hide();
						'tqop_Id'] . '").hide();


					) {
						'tqop_Id'] . '").show();
						['tqop_Id'] . '").hide();
						e);

					) {
						['tqop_Id'] . '").show();
						'tqop_Id'] . '").hide();
						e);

					) {
						['tqop_Id'] . '").show();
						'tqop_Id'] . '").hide();
						e);



					"\n";

					s="form-group">
							4 control-label" for="type">Select Input Type for ' . ucfirst($remp) . '</label>
							>
							$ttn['tqop_Id'] . '" name="type_' . $ttn['tqop_Id'] . '" class="form-select">
							;
					_type'] == 1) {
						lected"';

					ption>' . "\n";
					alue="2" ';
					_type'] == 2) {
						lected"';

					a</option>' . "\n";
					alue="3" ';
					_type'] == 3) {
						lected"';

					option>' . "\n";
					alue="4" ';
					_type'] == 4) {
						lected"';

					magen</option>' . "\n";




					s="form-group">
						tton" id="btsel_' . $ttn['tqop_Id'] . '" name="btsel_' . $ttn['tqop_Id'] . '" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#Modal1">
						 Value



					ype="text/javascript">' . "\n";
					t).ready(function () {" . "\n";
					_' . $ttn['tqop_Id'] . '").on("click", function (e) {' . "\n";
					Default();
									_Id'] . "';



					" . "\n";

					s="form-group" id="text_' . $ttn['tqop_Id'] . '">
							'col_name'] . '">Query for ' . ucfirst($remp) . ':</label>
							 class="form-control" id="' . $ttn['col_name'] . '" name="' . $ttn['col_name'] . '">' . $ttn['query'] . '</textarea>


				lass="form-group">
						bmit" id="updatequeries" name="updatequeries" class="btn btn-primary"><span class = "fas fa-plus-square"></span> Save query to columns</button>
						success" href="dashboard.php?cms=crud&w=list&tbl=' . $tble . '">View Table</a>

				>' . "\n";

				mp.php';
				sts($vfile)) {



				ries($tble) {

					* FROM table_column_settings WHERE table_name='{$tble}'";
					n->query($sql);



					$qresult->fetch_assoc()) {
						Id'];
						l_name'];
						y} = \$_POST['{$query}'];";
						\n";
						query}' WHERE tqop_Id='{$id}'";
						r . ' = "UPDATE table_column_settings SET ' . $cq . ' ";' . "\n";
						ery($sql' . $r . ');' . "\n";






				addPost($tble);
				>addTtl($tble);
				>addTPost($tble);
				ifMpty($tble);

				tmp.php';
				sts($rvfile)) {
					;


				eta http-equiv="refresh" content="0;url=dashboard.php?cms=column_manager&w=build&tbl=' . $tble . '">';

				<?php' . "\n";
				'//This is temporal file only for add new row' . "\n";
				'if (isset($_POST["updatequeries"])) {' . "\n";
				queries($tble);
				'echo "<h4>Updated Query column successfully.</h4>";' . "\n";
				"echo '$redir';" . "\n";
				"} \n";
				"?> \n";

				tents($rvfile, $content, FILE_APPEND | LOCK_EX);

				 'qtmp.php';

				POST['submitrv'])) {
					$_POST['idtb']);
					($_POST['stb']);
					_POST['j_table']);
					t($_POST['joins']);
					_POST['column_id']);
					_POST['column_value']);

					prepare("UPDATE table_column_settings SET input_type = ?, joins = ?, j_table = ?, j_id = ?, j_value = ? WHERE tqop_Id = ?");
					am('issssi', $sltb, $joins, $tb, $sr, $sv, $inp);
					->execute();
					 false) {
						t->error, E_USER_ERROR);

						mn successfully" . $stmt->affected_rows;






		t>

			odal = document.getElementById('Modal');
			ns = document.getElementById('joins');

			ddEventListener('shown.bs.modal', () => {
				);


			al) {
				ventListener('show.bs.modal', event => {
					triggered the modal
					event.relatedTarget;
					 from data-bs-* attributes
					 = button.getAttribute('data-bs-whatever');
					, you could initiate an Ajax request here
					the updating in a callback.

					odal's content.
					e = myModal.querySelector('.modal-title');
					Input = myModal.querySelector('.modal-body input');

					Content = `New message to ${recipient}`;
					value = recipient;


		pt>
		lass="modal fade" id="Modal1" tabindex="-1" aria-labelledby="Modal1Label" aria-hidden="true">
			s="modal-dialog">
				modal-content">
					/assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
					al-header">
						itle" id="ModalLabel">Select a column to relate</h5>
						on" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
							ue">&times;</span>


					al-body">




			t).ready(function () {

				on("change", function (e) {
					t();
					'#table').val();








						(response) {
							response);

						 {
							 wrong!");


				change");



						orm-horizontal" method="POST" if="queries">

								dtb" name="idtb" value="" style="display: none;" />
								tb" name="stb" value="" style="display: none;" />
								id="vtble">
									" for="joins">Select type JOIN</label>

										" class="form-control">
											ion>
											JOIN</option>
											IN</option>
											JOIN</option>
											AIGHT JOIN</option>
											JOIN</option>
											RAL JOIN</option>







									seltables" name="seltables"></div>



										itrv" name="submitrv"
												>





					al-footer">
						on" class="btn btn-secondary" data-dismiss="modal">Close</button>








	hp
} else {
	der("Location: ../signin/login.php");
	t();
}
?>
