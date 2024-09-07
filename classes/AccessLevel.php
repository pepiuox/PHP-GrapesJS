<?php

/*
 * This class check level from users:
 */

class AccessLevel {

	protected $connection;
	private $userrole;
	private $user_id;
	private $level;

	public function __construct() {
		global $conn;
		$this->connection = $conn;
		if (isset($_SESSION['user_id']) && isset($_SESSION['levels'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->level = $_SESSION['levels'];
		}
	}

	/* This functions verify if exits user level in the users_roles table
	 *
	 */

	public function levels() {

		$stmt = $this->connection->prepare("SELECT iduv, level FROM uverify WHERE iduv = ? AND level = ?");
		$stmt->bind_param("ss", $this->user_id, $this->level);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		$lvls = $result->fetch_assoc();

		$this->userrole = $this->roles($this->level);
		$rol = $this->userrole['name'];
		$rold = $this->userrole['default_role'];

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

	private function roles($level) {

		$stmt = $this->connection->prepare("SELECT idRol, name, default_role FROM users_roles WHERE name = ?");
		$stmt->bind_param("s", $level);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}

	public function DefaulRoles() {
		$this->userrole = $this->roles($this->level);
		$rol = $this->userrole['default_role'];
		return $rol;
	}

	public function getRols() {
		$this->userrole = $this->roles($this->level);
		$rol = $this->userrole['idRol'];
		return $rol;
	}

	/* This functions get id and name if exits user level in the users_roles table
	 *
	 */

	private function permissions($idr) {

		$stmt = $this->connection->prepare("SELECT id, permission_id FROM role_permissions WHERE role_id = ?");
		$stmt->bind_param("s", $idr);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_array();
	}
}

?>
