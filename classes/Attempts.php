<?php

class Attempts {

    public $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
  
        //Checking if the attempt 3, or youcan set the no of attempt her. For now we taking only 3 fail attempted
        if ($total_count == 3) {
            $msg = "To many failed login attempts. Please login after 30 sec";
        } else {
//Getting Post Values
            $username = $_POST['username'];
            $password = md5($_POST['password']);
// Coding for login
            $res = $this->connection->query("select * from user where username='$username' and password='$password'");
            if ($res->num_rows) {
                $_SESSION['IS_LOGIN'] = 'yes';
                $this->connection->query("delete from loginlogs where IpAddress='$ip_address'");

                echo "<script>window.location.href='dashboard.php';</script>";
            } else {
                $total_count++;
                $rem_attm = 3 - $total_count;
                if ($rem_attm == 0) {
                    $msg = "To many failed login attempts. Please login after 30 sec";
                } else {
                    $msg = "Please enter valid login details.<br/>$rem_attm attempts remaining";
                }
                $try_time = time();
                $this->connection->query("insert into loginlogs(IpAddress,TryTime) values('$ip_address','$try_time')");
            }
        }
    }

}

$msg = '';
if (isset($_POST['submit'])) {
    $time = time() - 30;
    $ip_address = getIpAddr();
// Getting total count of hits on the basis of IP
    $query = $this->connection->query("select count(*) as total_count from loginlogs where TryTime > $time and IpAddress='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];
//Checking if the attempt 3, or youcan set the no of attempt her. For now we taking only 3 fail attempted
    if ($total_count == 3) {
        $msg = "To many failed login attempts. Please login after 30 sec";
    } else {
//Getting Post Values
        $username = $_POST['username'];
        $password = md5($_POST['password']);
// Coding for login
        $res = $this->connection->query("select * from user where username='$username' and password='$password'");
        if (mysqli_num_rows($res)) {
            $_SESSION['IS_LOGIN'] = 'yes';
            $this->connection->query("delete from loginlogs where IpAddress='$ip_address'");
            echo "<script>window.location.href='dashboard.php';</script>";
        } else {
            $total_count++;
            $rem_attm = 3 - $total_count;
            if ($rem_attm == 0) {
                $msg = "To many failed login attempts. Please login after 30 sec";
            } else {
                $msg = "Please enter valid login details.<br/>$rem_attm attempts remaining";
            }
            $try_time = time();
            $this->connection->query("insert into loginlogs(IpAddress,TryTime) values('$ip_address','$try_time')");
        }
    }
}

// Getting IP Address
function getIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ipAddr = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
}

?>