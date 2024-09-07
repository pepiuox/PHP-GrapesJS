<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
	(!isset($_GET['w']) || empty($_GET['w'])) {
		('Location: dashboard.php?cms=table_manager&w=list');
		;

	ract($_POST);
	= protect($_GET['w']);
	= new MyCRUD();

	($w == "list") {

		lass="container">
			s="row">


				POST['addtable'])) {
					p-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">';

				'dashboard.php?cms=table_manager&w=editor&tbl=';
				conn->query("SHOW COLUMNS FROM table_settings");
				);
				class="row form-horizontal" method="POST">' . "\n";
				lass="col-auto">' . "\n";
				n type="submit" name="addtable" id="addtable" class="btn btn-secondary mb-3">Add table</button>' . "\n";
				' . "\n";
				 class="table">' . "\n";
				>' . "\n";
				. "\n";
				 = $result0->fetch_array()) {

					d'] == 'IdTbset') {


						e("_", " ", $row0['Field']);
						cfirst($remp) . '</th>';


				(" \n", $bq);
				th>' . "\n";
				 . "\n";
				d>' . "\n";
				>' . "\n";
				nn->query("SELECT * FROM table_settings");
				bset->num_rows;
				> 0) {
					tbset->fetch_array()) {
						;
						tbs['table_name'] . '</b></td>' . "\n";
						;
						st'] == 1) {




						";

						;
						d'] == 1) {




						";
						;
						date'] == 1) {




						";
						;
						lete'] == 1) {




						";
						;
						ew'] == 1) {




						";
						;
						cure'] == 1) {




						";
						"' . $linkedit . $tbs['table_name'] . '"><i class="fas fa-edit"></i> Edit</a></td>' . "\n";
						";


					p-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">' . "\n";

				y>' . "\n";
				e>' . "\n";

				lass="col-auto">' . "\n";
				n type="submit" name="addtable" id="addtable" class="btn btn-secondary mb-3">Add table</button>' . "\n";
				' . "\n";
				>' . "\n";




	lseif ($w == "add") {
		Names = '';
		set($_POST['list'])) {
			ta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">' . "\n";


		set($_GET['tbl']) && !empty($_GET['tbl'])) {
			rotect($_GET['tbl']);

			'qtmp.php';
			exists($vfile)) {
				e);


			"SELECT table_name FROM table_column_settings WHERE table_name = '$tble'";
			 $conn->query($query);

			 the number of rows in result set
			lt->num_rows > 0) {
				is table has already been added in the query builder.</h4> ' . "\n";
				t>' . "\n";
				.location.href = "dashboard.php?cms=table_manager&w=editor&tbl=' . $tble . '"' . "\n";
				pt>' . "\n";


				getID($tble);

				{
					ld not connect: ' . mysqli_error());


				CT * FROM " . $tble;
				conn->query($sql);
				y = "INSERT INTO table_column_settings (table_name, col_name, col_type) VALUES' . "\n";
				y();
				>viewColumns($tble);
				lsn as $finfo) {
					e == $ncol) {


						tble . "', '" . $finfo->name . "', '" . $finfo->type . "')";


				de(", \n", $addq);


				eta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=editor&tbl=' . $tble . '">';
				<?php' . "\n";
				'//This is temporal file only for add new row' . "\n";
				"if(isset(\$_POST['addtable'])){" . "\n";
				"\$result = \$conn->query(\"SELECT table_name FROM table_column_settings WHERE table_name = '" . $tble . "'\");" . "\n";
				"if (\$result->num_rows > 0) {" . "\n";
				"echo 'This table already exists, It was already added.';" . "\n";
				"}else{" . "\n";
				$dq . "\n";
				'if ($conn->query($query) === TRUE) {' . "\n";
				"\$ins_qry = \"INSERT INTO table_settings(table_name) VALUES('" . $tble . "')\";" . "\n";
				'if ($conn->query($ins_qry) === TRUE){' . "\n";
				'echo "Record added successfully";' . "\n";
				"echo '" . $redir . "';" . "\n";
				'} else {' . "\n";
				'echo "Error added record: " . $conn->error;' . "\n";
				'}' . "\n";
				'} else {' . "\n";
				'echo "Error added record: " . $conn->error;' . "\n";
				'}' . "\n";
				'}' . "\n";
				'}' . "\n";
				"?>";

				tents($vfile, $content, FILE_APPEND | LOCK_EX);

				 'qtmp.php';



		lass="container">
			s="row">
				e to admin for option settings.</h2>

				= $conn->query("SELECT * FROM table_config")) {
					$result->num_rows;

					d > 0) {
						tch_assoc();
						de(',', $row['table_name']);



				"row form-horizontal" method="post">
					3">
						 name="addtb" class="form-control">
							ct a Table </option>

							s $tname) {
								 " ", $tname);


										e . '" selected>' . ucfirst($remp) . '</option>' . "\n";


									name . '">' . ucfirst($remp) . '</option>' . "\n";






						ent.querySelector('#addtb');
						tener('change', function () {
						rd.php?cms=table_manager&w=add&tbl=' + this.value;
						place(url);


					-auto">
						it" id="addtable" name="addtable" class="btn btn-primary">
							lus-square"></span> Add table


					-auto">
						it" name="list" class="btn btn-secondary mb-3">List table manager</button>






	lseif ($w == "editor") {
		= protect($_GET['tbl']);
		t = $conn->query("SELECT table_name FROM table_settings");
		_found = $result->num_rows;
		array();
		otal_found > 0) {
			ow = $result->fetch_array()) {
				['table_name'];


		set($_POST['list'])) {
			ta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">' . "\n";

		set($_POST['add'])) {
			ta http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=add">' . "\n";

		set($_POST['submit'])) {
			rray();
			ray();
			ay('table_list' => 0, 'table_view' => 0, 'table_add' => 0, 'table_update' => 0, 'table_delete' => 0, 'table_secure' => 0);

			$_POST as $key => $value) {
				ey;


			$cl as $key => $value) {

				($key, $cols)) {
					 "='1'";

					 "='" . $value . "'";



			 implode(", ", $col);

			"UPDATE table_settings SET $colset WHERE table_name='$tble'";
			->query($upset) === TRUE) {
				 updated successfully";
				http-equiv="refresh" content="0;url=dashboard.php?cms=table_manager&w=list">' . "\n";

				updating record: " . $conn->error;



		lass="container">
			s="row">
				col-md-12">
					e settings</h2>
					e for edit settings</h4>
					w form-horizontal" method="POST">
						y-4">
							 name="selecttb" class="form-select form-select-lg">
								a Table </option>

								{
									", $tname);
									name . '">' . ucfirst($remp) . '</option>' . "\n";




								erySelector('#selecttb');
								'change', function () {
								?cms=table_manager&w=editor&tbl=' + this.value;
								url);





				col-md-12">
					w form-horizontal" method="post">

							 name="list" class="btn btn-secondary mb-3">List settings</button>
							 name="add" class="btn btn-success mb-3">Add table settings</button>



				col-md-12 py-4">

						bel {


						ox"] {
							 green;


						ox"]:checked {
							 hotpink;


					ted - <?php echo ucfirst(str_replace("_", " ", $tble)); ?></h3>


					T);
					n->query("SHOW COLUMNS FROM table_settings");


					ss="row form-horizontal" method="POST">' . "\n";
					s="mb-3">' . "\n";
					ype="submit" name="submit" class="btn btn-primary mb-3">Update settings</button>' . "\n";
					 "\n";
					ass="table">' . "\n";
					. "\n";
					\n";
					$result0->fetch_array()) {
						 == 'IdTbset') {


							_", " ", $row0['Field']);
							rst($remp) . '</th>' . "\n";


					\n", $bq);
					"\n";
					 . "\n";
					. "\n";
					>query("SELECT * FROM table_settings WHERE table_name='$tble'");
					t->num_rows;
					) {
						et->fetch_array()) {

							['table_name'] . '</b></td>' . "\n";
							s="form-check-input" type="checkbox" value="1" name="table_list" id="table_list"';
							] == 1) {



							s="form-check-input" type="checkbox" value="1" name="table_add" id="table_add"';
							 == 1) {



							s="form-check-input" type="checkbox" value="1" name="table_update" id="table_update"';
							e'] == 1) {



							s="form-check-input" type="checkbox" value="1" name="table_delete" id="table_delete"';
							e'] == 1) {



							s="form-check-input" type="checkbox" value="1" name="table_view" id="table_view"';
							] == 1) {



							s="form-check-input" type="checkbox" value="1" name="table_secure" id="table_secure"';
							e'] == 1) {






					 . "\n";
					 . "\n";
					s="mb-3">' . "\n";
					ype="submit" name="submit" class="btn btn-primary mb-3">Update settings</button>' . "\n";
					 "\n";
					s="mb-3">' . "\n";
					ype="submit" name="list" class="btn btn-secondary mb-3">List settings</button>' . "\n";
					ype="submit" name="add" class="btn btn-success mb-3">Add table settings</button>' . "\n";
					 "\n";
					. "\n";






	lseif ($w == "set") {

		esult = $conn->query("SELECT table_name FROM table_settings")) {
			und = $result->num_rows;

			ay();
			l_found > 0) {
				= $result->fetch_array()) {
					able_name'];




		lass="container">
			s="row">
				col-md-12">
					lication tables</h3>
					w form-horizontal" method="POST">
						roup">
							 name="selecttb" class="form-control">
								a Table </option>

								{
									", $tname);
									name . '">' . ucfirst($remp) . '</option>' . "\n";




								erySelector('#selecttb');
								'change', function () {
								?cms=table_manager&w=editor&tbl=' + this.value;
								url);









}
?>
