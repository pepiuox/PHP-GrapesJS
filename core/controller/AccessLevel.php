<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
/*
 * This class check level from users:
 */

class AccessLevel
{
    protected $conn;
    private $userrole;
    private $user_id;
    private $level;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        if (isset($_SESSION["user_id"]) && isset($_SESSION["levels"])) {
            $this->user_id = $_SESSION["user_id"];
            $this->level = $_SESSION["levels"];
        }
    }

    /* This functions verify if exits user level in the users_roles table
     *
     */

    public function levels()
    {
        $stmt = $this->conn->prepare(
            "SELECT iduv, level FROM uverify WHERE iduv = ? AND level = ?"
        );
        $stmt->bind_param("ss", $this->user_id, $this->level);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $lvls = $result->fetch_assoc();

        $this->userrole = $this->Roles($this->level);
        $rol = $this->userrole["name"];
        $rolrq = $this->userrole["required"];
        $rold = $this->userrole["default_role"];
        if ($rolrq === 1) {
            if ($lvls["level"] === $rol) {
                if ($rold === 9) {
                    return 9;
                } elseif ($rold === 5) {
                    return 5;
                } elseif ($rold === 3) {
                    return 3;
                } else {
                    return 1;
                }
            }
        } else {
            return 0;
        }
    }

    private function Roles($level)
    {
        $stmt = $this->conn->prepare(
            "SELECT idRol, name, required, default_role FROM users_roles WHERE name = ?"
        );
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function DefaulRoles()
    {
        $this->userrole = $this->Roles($this->level);
        $rol = $this->userrole["default_role"];
        return $rol;
    }

    public function getRols()
    {
        $this->userrole = $this->Roles($this->level);
        $rol = $this->userrole["idRol"];
        return $this->rolePermissions($rol);
    }

    /* This functions get id and name if exits user level in the users_roles table
     *
     */
    private function rolePermissions($idr)
    {
        $stmt = $this->conn->prepare(
            "SELECT idRp, permission_id FROM users_role_permissions WHERE role_id = ?"
        );
        $stmt->bind_param("s", $idr);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $rpres = $result->fetch_array();
        $idp = $rpres["permission_id"];
        return $this->Permissions($idp);
    }

    private function Permissions($idp)
    {
        $stmt = $this->conn->prepare(
            "SELECT name, description, category, required FROM users_permissions WHERE idPer = ?"
        );
        $stmt->bind_param("s", $idp);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_array();
    }
}

?>