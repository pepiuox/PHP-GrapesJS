<?php

/*
 * This class check level from users:
 */

class AccessLevel {

    private $connection;
    private $userrole;
    private $user_id;
    private $level;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
        $this->user_id = $_SESSION['user_id'];
        $this->level = $_SESSION['levels'];
    }

    /* This functions verify if exits user level in the users_roles table 
     * 
     */

    public function levels() {

        $stmt = $this->connection->prepare("SELECT level FROM uverify WHERE iduv = ? AND level = ?");
        $stmt->bind_param("ss", $this->user_id, $this->level);
        $stmt->execute();
        $result = $stmt->get_result();
        $lvls = $result->fetch_assoc();
        $stmt->close();
        $this->userrole = $this->roles($this->level);
        $rol = $this->userrole['name'];
        $rold = $this->userrole['default_role'];
        if ($rold === 9 || $rold === 5) {
            if ($lvls['level'] === $rol) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
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
        /*
          $list = $this->permissions($rol);
          return $list;

         */
    }

    /* This functions get id and name if exits user level in the users_roles table
     * 
     */

    private function roles($level) {

        $stmt = $this->connection->prepare("SELECT idRol, name, default_role FROM users_roles WHERE name = ?");
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    private function permissions($idr) {

        $stmt = $this->connection->prepare("SELECT id, permission_id FROM role_permissions WHERE role_id = ?");
        $stmt->bind_param("s", $idr);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_array();
    }

}

?>