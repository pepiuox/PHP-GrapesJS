<?php
if (!isset($_SESSION)) {
	sion_start();
}
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
	uire_once '../config/dbconnection.php';
	uire_once 'Autoload.php';

	th = basename($_SERVER['REQUEST_URI']);
	le = basename($path);

	leName = basename($_SERVER['PHP_SELF']);


	($file == $fileName) {
		("Location: $fileName?view=select");

	(isset($_GET['view'])) {
		= protect($_GET['view']);
	lse {
		("Location: $fileName?view=select");

	(!empty($_GET["tbl"])) {
		 protect($_GET["tbl"]);
		= ucfirst(str_replace('_', ' ', $tbl));

		bstr($tbl, -1) == 's') {
			ubstr($tbl, 0, -1);
			ucfirst(substr($tbl, 0, -1));
		 {
			tbl;
			ucfirst($tbl);


		 "SELECT * FROM $tbl";
		t = $conn->query($sql);

		 = array();
		;
		($result->field_count > $i) {
			esult->fetch_field();
			= 0) {
				m->name;
				->name . " ='$" . $nam->name . "'";
				 . $nam->name . " = \$_POST['" . $nam->name . "'];";


			 = $nam->name;
			 = "$" . $nam->name . "= \$_POST['" . $nam->name . "'];" . "\n";
			 0) {
				am->name . ' :""';
				am->name;
				= "'$" . $nam->name . "'";
				 $nam->name . " ='$" . $nam->name . "'";
				"$" . $nam->name . "= \$_POST['" . $nam->name . "'];" . "\n";

			 1;


		rt joins
		 array();
		s = array();
		 $result->field_count;
		um > 0) {
			am = $result->fetch_field()) {
				$nam->name;

			$qnames as $qname) {
				h>' . $qname . '</th>' . "\n";

		 {
			s table if it has columns or content.' . '<br>';


		on stringUf($cname) {
			first(str_replace('_', ' ', $cname));


		on getJoin($tbl, $cname) {
			onn;
			y = $conn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
			query->num_rows > 0) {
				joinquery->fetch_assoc();
				join['j_value'];
				coln)) {





				e;



		on getSelect($tbl, $cname) {
			onn;
			y = $conn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
			query->num_rows > 0) {
				joinquery->fetch_assoc();
				join['j_value'];
				coln)) {





				e;



		on optsel($jtable, $jid, $jvalue) {
			onn;
			conn->query("SELECT * FROM $jtable");
			el = $sels->fetch_array()) {
				n value="' . $sel[$jid] . '">' . $sel[$jvalue] . '</option>';



		on inpSel($tbl, $cname) {
			onn;
			onn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
			ow = $qsl->fetch_array()) {
				row['joins'])) {
					'j_table'];
					'j_value'];
					id'];
					ass="form-control" id="' . $cname . '" name="' . $cname . '" v-model="newDato.' . $cname . '">';
					 $jid, $jvalue);
					' . "\n";

					pe="text"  class="form-control" id="' . $cname . '" name="' . $cname . '" placeholder="' . stringUf($cname) . '" v-model="newDato.' . $cname . '">';




		on upSel($tbl, $cname) {
			onn;
			onn->query("SELECT * FROM table_column_settings WHERE table_name='$tbl' AND col_name='$cname'");
			ow = $qsl->fetch_array()) {
				row['joins'])) {
					'j_table'];
					'j_value'];
					id'];
					ass="form-control" id="' . $cname . '" name="' . $cname . '" v-model="clickedDato.' . $cname . '">';
					 $jid, $jvalue);
					' . "\n";

					pe="text"  class="form-control" id="' . $cname . '" name="' . $cname . '" placeholder="' . stringUf($cname) . '" v-model="clickedDato.' . $cname . '">';




		 = array();
		s = array();
		= "SELECT * FROM table_column_settings WHERE table_name='$tbl'";
		t1 = $conn->query($sql1);
		 $result1->num_rows;
		tq > 0) {
			tb = $result1->fetch_array()) {
				etJoin($tbl, $qtb['col_name']);
				tb['joins'])) {


					b['joins'] . ' ' . $qtb['j_table'] . ' ON ' . $tbl . '.' . $qtb['col_name'] . ' = ' . $qtb['j_table'] . '.' . $qtb['j_id'];




		 = implode(" ", $ljoins);
		uery = $conn->query("SELECT * FROM $tbl $rjoin");

// end joins

		 implode(" , ", $cols);
		me = implode(" , ", $varnames);
		e = implode(" , ", $upnames);
		 implode(" , ", $varc);
		 = implode(" ", $uposts);
		 = implode(" ", $cposts);

		e file app.js
		le = 'app.js';
		 = fopen("$appfile", "w") or die("Unable to open file!");
		ntent = 'var app = new Vue({


					alse,
					false,
					: false,
					 "",
					",

					cli . '},



				ction () {
					unted");
					s();


					nction () {
						?action=read")
								) {


										ta.message;

										s;




					ion () {
						.newDato);
						.toformData(app.newDato);
						p?action=create", formData)
								) {

									'};

									true) {
										ta.message;

										data.message;




					ction (dato) {
						dato;

					ction (dato) {
						.toformData(app.clickedDato);
						p?action=update", formData)
								) {




										ta.error;

										data.message;




					ction (dato) {
						.toformData(app.clickedDato);
						p?action=delete", formData)
								) {




										ta.message;

										data.message;




					ction (obj) {
						w FormData();
						j) {
							 obj[key]);




					unction () {
						 = "";
						 "";




		($myapp, $appcontent);
		($myapp);
		e file app.php
		le = 'app.php';
		 = fopen("$apifile", "w") or die("Unable to open file!");
		ntent = '
<?php
include "db.php";
$res = array("error" => false);

$action = "read";

if (isset($_GET["action"])) {
	tion = $_GET["action"];
}

if ($action == "read") {';

		tq > 0) {
			nt .= '$result = $conn->query("SELECT * FROM ' . $tbl . ' ' . $rjoin . '");
		 = array();
			ow = $result->fetch_assoc()) {
				datos, $row);


			os"] = $datos;

		 {
			nt .= ' $result = $conn->query("SELECT * FROM `' . $tbl . '`");
		 = array();
		($row = $result->fetch_assoc()) {
			h($datos, $row);


		datos"] = $datos;


		ntent .= '

}
// Create form

if ($action == "create") {

	 $cpost . '

	sult = $conn->query("INSERT INTO ' . $tbl . '(' . $col . ') VALUES (' . $varname . ')");

	($result) {
		message"] = "dato agregado exitosamente";
	lse {
		error"] = true;
		message"] = "dato no se agrego exitosamente";


	$res["datos"] =$datos;
}
// end of create form
// update form

if ($action == "update") {
	 $upost . '

	sult = $conn->query("UPDATE ' . $tbl . ' SET ' . $upname . ' WHERE ' . $whre . ' ");

	($result) {
		message"] = "dato actualizado con éxito";
	lse {
		error"] = true;
		error"] = "dato no se actualizo con éxito";

}

// end of update form

if ($action == "delete") {
	 $vpost . '

	sult = $conn->query("DELETE FROM `' . $tbl . '` WHERE ' . $whre . ' ");

	($result) {
		message"] = "dato borrado exitosamente";
	lse {
		error"] = true;
		message"] = "dato no se borro exitosamente";

	$res["datos"] =$datos;
}

$conn->close();
header("content-type:application/json");
echo json_encode($res);
die();
?>
';
		($myapi, $apicontent);
		($myapi);


   <?php include '../elements/header.php';

			rc="https://unpkg.com/vue@3/dist/vue.global.js"></script>
			rc="../assets/js/axios.min.js"></script>
			pe="text/css">





					ht;

					lder;

					;


					t

				r{
					;
					te(180deg);
					nsform linear 260ms;


				on{


				ton{

					px;

				active, .fade-leave-active {
					city .5s;
					anslateY(50%);
					ansform ease-in-out 500ms;*/

				 .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {

					ranslateY(0%);*/

				r-active {
					ce-in .5s;

				e-active {
					ce-in .5s reverse;

				ounce-in {

						);


						.3);


						);



		>


			 === "select") {
				= $conn->query("SELECT * FROM table_config")) {
					mysqli_num_rows($result);

					d > 0) {
						h_assoc($result);
						ode(',', $row['table_name']);



				container">
					 py-3">
						-6">
							a table from your database</h3>

						-6">
							p">


										 () {

											ef');
											ud&tbl=' + selecttb;
											tb);




								bel" for="selecttb">Select Table</label>
								me="selecttb" class="form-control">
									Tabla</option>

									ame) {
										$tname);
										e . '">' . ucfirst($remp) . '</option>' . "\n";








				 in the selected table */
			($view == "crud") {

				container-fluid" id="app">
					ssages -->
					 py-1">
						>
							rt-success" role="alert" v-if="successmessage">
								" >{{successmessage}}</h4>

							rt-danger" role="alert" v-if="errormessage">
								" >{{errormessage}}</h4>



					" >
						-2">
							ondary" href="index.php?view=select">Select a table</a>

						-8">
							o $tble; ?></h5>

						-2">
							 class="btn btn-primary " @click="showmodaladd=true" data-toggle="modal" data-target="#addModal">Agregar nuevo <i class="fa fa-plus" aria-hidden="true"></i></button>


					ble content -->
					">
						e table-sm">
							nfo">

									>

									 {
										$cname);
										rst(str_replace(' id', '', $remp)) . '</th>' . "\n";





								">

										tn btn-info"  @click="showmodaledit = true; selectDato(dato)" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> &nbsp;
										tn btn-danger" @click="showmodaldelete= true; selectDato(dato)" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-times" aria-hidden="true"></i></button>


									 {
										 . getJoin($tbl, $cname) . '}}</td>' . "\n";






						fade">
							e" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
								" role="document">
									v-if="showmodaladd">
										fo">
											ass="fa fa-plus" aria-hidden="true"></i></h5>
											e" data-dismiss="modal" aria-label="Close">
												wmodaladd= false"></i>



											m" action="javascript:void(0)">

												{



														me));

								 '" class="col-sm-3 col-form-label">' . $cinp . '</label>
								. "\n";



															" id="' . $cname . '" name="' . $cname . '" placeholder="' . $cinp . '" v-model="newDato.' . $cname . '">';






													fo"  @click="showmodaladd = false; saveDato()">Add</button>







						dal -->
						>
						fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
							log" role="document">
								t" v-if="showmodaledit">
									-info">
										 <i class="fa fa-pencil-square-o" aria-hidden="true"></i></h5>
										lose" data-dismiss="modal" aria-label="Close">
											showmodaledit= false"></i>



										form">

											e) {



													cname));

												ol-sm-3 col-form-label">' . $cinp . '</label>




														rol" id="' . $cname . '" name="' . $cname . '" placeholder="' . $cinp . '" v-model="clickedDato.' . $cname . '">';








													fo"  @click="showmodaledit = false;updateDato(dato) ">









						odal -->
						-->
						bounce">
							e" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
								" role="document">
									v-if="showmodaldelete">
										nfo">
											e" data-dismiss="modal" aria-label="Close">
												wmodaldelete= false"></i>



											ng to delete id {{clickedDato.<?php echo $idcol; ?>}}</p>



												-success" @click="showmodaldelete = false; deleteDato(dato)">Yes</button> &nbsp;&nbsp;&nbsp;
												-info" @click="showmodaldelete = false">No</button>






						 modal -->




				"app.js" type="text/javascript"></script>
			>

	lse {
		('Location: ../signin/login.php');
		;

	lude '../elements/footer.php';

</body>
</html>
