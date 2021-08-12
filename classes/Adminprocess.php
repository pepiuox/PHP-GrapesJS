<?php

class AdminProcess {
    /* Class constructor */

    public $session;
    private $connection;
    public $form;

    public function __construct() {
        global $session, $conn, $form;
        $this->session = $session;
        $this->connection = $conn;
        $this->form = $form;
        /* Make sure administrator is accessing page */
        if (!$this->session->isAdmin()) {
            header("Location: ../index.php");
            return;
        }

        /* Admin submitted update user valid form */
        if (isset($_POST['subjoin'])) {
            $this->procRegisterUser();
        }
        /* Admin submitted update user valid form */ else if (isset($_POST['subupdvalid'])) {
            $this->procUpdateValid();
        }
        /* Admin submitted update user level form */ else if (isset($_POST['subupdlevel'])) {
            $this->procUpdateLevel();
        }
        /* Admin submitted Eliminar Usuario form */ else if (isset($_POST['subdeluser'])) {
            $this->procDeleteUser();
        }
        /* Admin submitted delete inactive users form */ else if (isset($_POST['subdelinact'])) {
            $this->procDeleteInactive();
        }
        /* Admin submitted ban user form */ else if (isset($_POST['subbanuser'])) {
            $this->procBanUser();
        }
        /* Admin submitted delete banned user form */ else if (isset($_POST['subdelbanned'])) {
            $this->procDeleteBannedUser();
        }
        /* Should not get here, redirect to home page */ else {
            header("Location: ../index.php");
        }
    }

    public function procRegisterUser() {

        $_POST = $this->session->cleanInput($_POST);
        /* Convert username to all lowercase (by option) */
        if (ALL_LOWERCASE) {
            $_POST['user'] = strtolower($_POST['user']);
        }
        /* Registration attempt */
        $retval = $this->session->register($_POST['user'], $_POST['pass'], $_POST['email'], $_POST['name']);

        /* Registration Successful */
        if ($retval == 0) {
            $_SESSION['reguname'] = $_POST['user'];
            $_SESSION['regsuccess'] = true;
            header("Location: " . $this->session->referrer);
        }
        /* Error found with form */ else if ($retval == 1) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
        }
        /* Registration attempt failed */ else if ($retval == 2) {
            $_SESSION['reguname'] = $_POST['user'];
            $_SESSION['regsuccess'] = false;
            header("Location: " . $this->session->referrer);
        }
    }

    /**
     * procUpdateValid - If the submitted username is correct,
     * their user valid is updated according to the admin's
     * request.
     */
    public function procUpdateValid() {

        /* Username error checking */
        $subuser = $this->checkUsername("valuser");

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
        }
        /* Update user valid */ else {
            $this->connection->validUserField($subuser, "valid", (int) $_POST['updvalid']);
            header("Location: " . $this->session->referrer);
        }
    }

    /**
     * procUpdateLevel - If the submitted username is correct,
     * their user level is updated according to the admin's
     * request.
     */
    public function procUpdateLevel() {

        /* Username error checking */
        $subuser = $this->checkUsername("upduser");

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
        }
        /* Update user level */ else {
            $this->connection->updateUserField($subuser, "userlevel", (int) $_POST['updlevel']);
            header("Location: " . $this->session->referrer);
        }
    }

    /**
     * procDeleteUser - If the submitted username is correct,
     * the user is deleted from the database.
     */
    public function procDeleteUser() {

        /* Username error checking */
        $subuser = $this->checkUsername("deluser");

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
        }
        /* Eliminar Usuario from database */ else {
            $q = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
            $this->connection->query($q);
            header("Location: " . $this->session->referrer);
        }
    }

    /**
     * procDeleteInactive - All inactive users are deleted from
     * the database, not including administrators. Inactivity
     * is defined by the number of days specified that have
     * gone by that the user has not logged in.
     */
    public function procDeleteInactive() {

        $inact_time = $this->session->time - $_POST['inactdays'] * 24 * 60 * 60;
        $q = "DELETE FROM " . TBL_USERS . " WHERE timestamp < $inact_time "
                . "AND userlevel != " . ADMIN_LEVEL;
        $this->connection->query($q);
        header("Location: " . $this->session->referrer);
    }

    /**
     * procBanUser - If the submitted username is correct,
     * the user is banned from the member system, which entails
     * removing the username from the users table and adding
     * it to the banned users table.
     */
    public function procBanUser() {

        /* Username error checking */
        $subuser = $this->checkUsername("banuser");

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
        }
        /* Ban user from member system */ else {
            $q = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
            $this->connection->query($q);

            $q = "INSERT INTO " . TBL_BANNED_USERS . " VALUES ('$subuser', $this->session->time)";
            $this->connection->query($q);
            header("Location: " . $this->session->referrer);
        }
    }

    /**
     * procDeleteBannedUser - If the submitted username is correct,
     * the user is deleted from the banned users table, which
     * enables someone to register with that username again.
     */
    public function procDeleteBannedUser() {

        /* Username error checking */
        $subuser = $this->checkUsername("delbanuser", true);

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            header("Location: " . $this->session->referrer);
        }
        /* Eliminar Usuario from database */ else {
            $q = "DELETE FROM " . TBL_BANNED_USERS . " WHERE username = '$subuser'";
            $this->connection->query($q);
            header("Location: " . $this->session->referrer);
        }
    }

    /**
     * checkUsername - Helper function for the above processing,
     * it makes sure the submitted username is valid, if not,
     * it adds the appropritate error to the form.
     */
    public function checkUsername($uname, $ban = false) {

        /* Username error checking */
        $subuser = $_POST[$uname];
        $field = $uname;  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $this->form->setError($field, "* Usuario no aceptado<br>");
        } else {
            /* Make sure username is in database */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5 || strlen($subuser) > 30 ||
                    !preg_match("/^([0-9a-z])+$/i", $subuser) ||
                    (!$ban && !$this->connection->usernameTaken($subuser))) {
                $this->form->setError($field, "* El usuario no existe<br>");
            }
        }
        return $subuser;
    }

}

?>
