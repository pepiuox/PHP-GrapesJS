<?php
if (!isset($_SESSION)) {
    session_start();
}
include '../config/Database.php';

$link = new Database();
$conn = $link->MysqliConnection();


        if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
        }
        if(isset($_SESSION['levels'])){
           $level = $_SESSION['levels'];  
        }
       
  

    /* This functions verify if exits user level in the users_roles table 
     * 
     */

    function levels() {
global $conn, $user_id, $level;
        $stmt = $conn->prepare("SELECT iduv, level FROM uverify WHERE iduv = ? AND level = ?");
        $stmt->bind_param("ss", $user_id, $level);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $lvls = $result->fetch_assoc();
        
        $userrole = roles($level);
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
echo levels();
     function roles($level) {
global $conn;
        $stmt = $conn->prepare("SELECT idRol, name, default_role FROM users_roles WHERE name = ?");
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
  function DefaulRoles($level) {
        $userrole = roles($level);
        $rol = $userrole['default_role'];
        return $rol;
    }
echo DefaulRoles($level);
   function getRols($level) {
        $userrole = roles($level);
        $rol = $userrole['idRol'];
        return $rol;
        
    }
echo getRols($level);
    /* This functions get id and name if exits user level in the users_roles table
     * 
     */


function permissions($idr) {
global $conn;
        $stmt = $conn->prepare("SELECT id, permission_id FROM role_permissions WHERE role_id = ?");
        $stmt->bind_param("s", $idr);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_array();
    }



?>

