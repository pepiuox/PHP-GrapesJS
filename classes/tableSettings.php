<?php

class TableSettings {

	protected $connection;

	public function __construct() {
		global $conn;
		$this->connection = $conn;
	}

	public function checkList($value) {
		if ($value === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkView($value) {
		if ($value === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkAdd($value) {
		if ($value === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkUpdate($value) {
		if ($value === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkDelete($value) {
		if ($value === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkSecure($value) {
		if ($value === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function tblSettings($tname) {


		$stmt = $this->connection->prepare("SELECT * FROM table_settings WHERE table_name=?");
		$stmt->bind_param("s", $tname);
		$stmt->execute();
		$rts = $stmt->get_result();

		$nm = $rts->num_rows;

		if ($nm > 0) {
			$row = $rts->fetch_assoc();
			return json_encode($row, true);
		} else {
			echo '<meta http-equiv="refresh" content="0;url=dashboard.php?cms=table_crud&w=select">';
		}
		$stmt->close();
	}
}
?>
