<?php

require_once PROJECT_ROOT_PATH . "Database.php";

class UserModel extends Database {

    public function getUsers($limit) {

        return $this->select("SELECT * FROM users ORDER BY user_id ASC LIMIT ?", ["i", $limit]);
    }
}
