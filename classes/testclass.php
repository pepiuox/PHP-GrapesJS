<?php

class testclass {

	protected $connection;
	private $user_id;
	private $level;

	public function __construct() {
		global $conn;
		$this->connection = $conn;

		if (isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
		}
		if (isset($_SESSION['levels'])) {
			$this->level = $_SESSION['levels'];
		}
	}

	/* This public functions verify if exits user level in the users_roles table
	 *
	 */

	public function roles($level) {
		$stmt = $this->connection->prepare("SELECT idRol, name, default_role FROM users_roles WHERE name = ?");
		$stmt->bind_param("s", $this->level);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function levels() {

		$stmt = $this->connection->prepare("SELECT iduv, level FROM uverify WHERE iduv = ? AND level = ?");
		$stmt->bind_param("ss", $this->user_id, $this->level);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		$lvls = $result->fetch_assoc();

		$userrole = $this->roles($this->level);
		$rol = $userrole['name'];
		$rold = $userrole['default_role'];

		if ($lvls['level'] === $rol) {
			if ($rold === 9) {
				return 9;
			} elseif ($rold === 5) {
				return 5;
			} else {
				return 1;
			}
		}
	}

	public function DefaulRoles($level) {
		$userrole = $this->roles($this->level);
		$rol = $userrole['default_role'];
		return $rol;
	}

	public function getRols($level) {
		$userrole = $this->roles($this->level);
		$rol = $userrole['idRol'];
		return $rol;
	}

	/* This public functions get id and name if exits user level in the users_roles table
	 *
	 */

	public function permissions($idr) {

		$stmt = $this->connection->prepare("SELECT id, permission_id FROM role_permissions WHERE role_id = ?");
		$stmt->bind_param("s", $idr);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_array();
	}
}
?>
