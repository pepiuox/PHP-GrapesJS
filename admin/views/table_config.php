<?php
if ($login->isLoggedIn() === true && $level->levels() === 9) {
	Table = '';

	ract($_POST);
	eck_exist_qry = "SELECT * FROM table_config";
	n_qry = $conn->query($check_exist_qry);
	tal_found = $run_qry->num_rows;
	($total_found > 0) {
		lue = $run_qry->fetch_assoc();
		le = explode(',', $my_value['table_name']);


	(isset($_POST['queryb'])) {
		<meta http-equiv="refresh" content="1;url=dashboard.php?cms=column_manager&w=select" />';


	(isset($_POST['tbmngr'])) {
		<meta http-equiv="refresh" content="1;url=dashboard.php?cms=table_manager&w=list" />';


	(isset($_POST['submit'])) {
		able_value = implode(",", $_POST['tables']);
		otal_found > 0) {

			= "UPDATE table_config SET table_name='" . $all_table_value . "' WHERE tcon_Id='1'";
			 $conn->query($upd_qry);
			tup) {
				rrorMessage'] = 'There was an error updating.';

				uccessMessage'] = 'Was updated the tables in the table config';

		 {

			= "INSERT INTO table_config(table_name) VALUES('" . $all_table_value . "')";
			= $conn->query($ins_qry);
			tadd) {
				rrorMessage'] = 'There was an error adding.';

				uccessMessage'] = 'The tables was adding in the table config';


		<meta http-equiv="refresh" content="1;url=dashboard.php?cms=table_config" />';


	v class="container">
		class="row form-horizontal" method="post">
			s="col_md_12">

				= $conn->query("SELECT DATABASE()")) {
					>fetch_row();
					ault database is %s </h5>.\n", $row[0]);
					);


				ou want to view in the CRUD system</h3>
				table to view it in the CRUD system and be able to list, view, add, update and delete data</p>
				xt-danger">"Remember to add parameters for its best use and security"</p>

			s="col-md-12">
				form-group">
					ubmit" id="submit" name="submit"
							y">
						a-plus-square"></span> Save Config

					ubmit" id="tbmngr" name="tbmngr"
							s">
						a-plus-square"></span> Table Manager

					ubmit" id="queryb" name="queryb"
							ary">
						a-plus-square"></span> Column Manager


				form-group">




					->query("SHOW TABLES FROM $row[0]");
					rray();
					ysqli_fetch_row($result)) {
						ow[0];

					Names as $tname) {
						e("_", " ", $tname);
						checkbox">' . "\n";
						checkboxes-' . $i++ . '">';
						"checkbox" id="checkboxes-' . $x++ . '" name="tables[]" value="' . $tname . '" ';
						e)) {
							$myTable)) {




						) . '</label>' . "\n";
						n";



				form-group">
					ubmit" id="submit" name="submit"
							y">
						a-plus-square"></span> Save Config

					ubmit" id="tbmngr" name="tbmngr"
							s">
						a-plus-square"></span> Table Manager

					ubmit" id="queryb" name="queryb"
							ary">
						a-plus-square"></span> Column Manager



		>
	iv>
	hp
} else {
	der("Location: ../signin/login.php");
	t();
}
?>
