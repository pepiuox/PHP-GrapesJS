<?php

/*
 * This class check level from users:
 */

class AccessLevel {

    private $connection;
    private $userrole;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
    }

    /* This functions verify if exits user level in the users_roles table 
     * 
     */

    public function levels($id, $level) {

        $stmt = $this->connection->prepare("SELECT level FROM uverify WHERE iduv = ? AND level = ?");
        $stmt->bind_param("ss", $id, $level);
        $stmt->execute();

        $result = $stmt->get_result();
        $lvls = $result->fetch_assoc();
        $stmt->close();
        $this->userrole = $this->roles($level);
        $rol = $this->userrole['name'];

        if ($lvls['level'] === $rol) {
            return true;
        }
    }

    public function getRols($level) {
        $this->userrole = $this->roles($level);
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

        $stmt = $this->connection->prepare("SELECT idRol, name FROM users_roles WHERE name = ?");
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