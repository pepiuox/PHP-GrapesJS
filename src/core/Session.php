<?php

/**
 * Session.php
 * 
 */
class Session {

    public $form;
    public $mailer;
    protected $conn;
    private $username;     //Username given on sign-up
    private $userid;       //Random value generated on current login
    private $userlevel;    //The level to which the user pertains
    public $time;         //Time user was active (page loaded)
    public $logged_in;    //True if user is logged in, false otherwise
    public $userinfo = array();  //The array holding all user info
    public $url;          //The page url current being viewed
    public $referrer;     //Last recorded site page viewed

    /**
     * Note: referrer should really only be considered the actual
     * page referrer in process.php, any other time it may be
     * inaccurate.
     */
    /* Class constructor */

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->time = time();
        $this->startSession();
    }

    /**
     * startSession - Performs all the actions necessary to 
     * initialize this session object. Tries to determine if the
     * the user has logged in already, and sets the variables 
     * accordingly. Also takes advantage of this page load to
     * update the active visitors tables.
     */
    public function startSession() {

        session_start();   //Tell PHP to start the session

        /* Determine if user is logged in */
        $this->logged_in = $this->checkLogin();

        /**
         * Set guest value to users not logged in, and update
         * active guests table accordingly.
         */
        if (!$this->logged_in) {
            $this->username = $_SESSION['username'] = GUEST_NAME;
            $this->userlevel = GUEST_LEVEL;
            $this->conn->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
        }
        /* Update users active timestamp */ else {
            $this->conn->addActiveUser($this->username, $this->time);
        }

        /* Remove inactive visitors from database */
        $this->conn->removeInactiveUsers();
        $this->conn->removeInactiveGuests();

        /* Set referrer page */
        if (isset($_SESSION['url'])) {
            $this->referrer = $_SESSION['url'];
        } else {
            $this->referrer = "/";
        }

        /* Set current url */
        $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
    }

    /**
     * checkLogin - Checks if the user has already previously
     * logged in, and a session with the user has already been
     * established. Also checks to see if user has been remembered.
     * If so, the database is queried to make sure of the user's 
     * authenticity. Returns true if the user has logged in.
     */
    public function checkLogin() {

        /* Check if user has been remembered */
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
            $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
            $this->userid = $_SESSION['user_id'] = $_COOKIE['cookid'];
        }

        /* Username and userid have been set and not guest */
        if (isset($_SESSION['username']) && isset($_SESSION['user_id']) &&
                $_SESSION['username'] != GUEST_NAME) {
            /* Confirm that username and userid are valid */
            if ($this->conn->confirmUserID($_SESSION['username'], $_SESSION['user_id']) != 0) {
                /* Variables are incorrect, user not logged in */
                unset($_SESSION['username']);
                unset($_SESSION['user_id']);
                return false;
            }

            /* User is logged in, set class variables */
            $this->userinfo = $this->conn->getUserInfo($_SESSION['username']);
            $this->username = $this->userinfo['username'];
            $this->userid = $this->userinfo['user_id'];
            $this->userlevel = $this->userinfo['userlevel'];
            return true;
        }
        /* User not logged in */ else {
            return false;
        }
    }

    /**
     * login - The user has submitted his username and password
     * through the login form, this public function checks the authenticity
     * of that information in the database and creates the session.
     * Effectively logging in the user if all goes well.
     */
    public function login($subuser, $subpass, $subremember) {

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $this->form->setError($field, "* You did not enter the Username ");
        } else {
            /* Check if username is not alphanumeric */
            if (!preg_match("/^([0-9a-z])*$/", $subuser)) {
                $this->form->setError($field, "* Username is non-alphanumeric ");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $this->form->setError($field, "* You did not enter the password ");
        }

        /* Return if form errors exist */
        if ($this->form->num_errors > 0) {
            return false;
        }

        /* Checks that username is in database and password is correct */
        $subuser = stripslashes($subuser);
        $result = $this->conn->confirmUserPass($subuser, md5($subpass));

        /* Check error codes */
        if ($result == 1) {
            $field = "user";
            $this->form->setError($field, "* User not found ");
        } else if ($result == 2) {
            $field = "pass";
            $this->form->setError($field, "* Invalid password");
        }

        /* Return if form errors exist */
        if ($this->form->num_errors > 0) {
            return false;
        }

        /* Username and password correct, register session variables */
        $this->userinfo = $this->conn->getUserInfo($subuser);
        $this->username = $_SESSION['username'] = $this->userinfo['username'];
        $this->userid = $_SESSION['user_id'] = $this->generateRandID();
        $this->userlevel = $this->userinfo['userlevel'];

        /* Insert userid into database and update active users table */
        $this->conn->updateUserField($this->username, "user_id", $this->userid);
        $this->conn->addActiveUser($this->username, $this->time);
        $this->conn->removeActiveGuest($_SERVER['REMOTE_ADDR']);

        /**
         * This is the cool part: the user has requested that we remember that
         * he's logged in, so we set two cookies. One to hold his username,
         * and one to hold his random value userid. It expires by the time
         * specified in constants.php. Now, next time he comes to our site, we will
         * log him in automatically, but only if he didn't log out before he left.
         */
        if ($subremember) {
            setcookie("cookname", $this->username, time() + COOKIE_EXPIRE, COOKIE_PATH);
            setcookie("cookid", $this->userid, time() + COOKIE_EXPIRE, COOKIE_PATH);
        }

        /* Login completed successfully */
        return true;
    }

    /**
     * logout - Gets called when the user wants to be logged out of the
     * website. It deletes any cookies that were stored on the users
     * computer as a result of him wanting to be remembered, and also
     * unsets session variables and demotes his user level to guest.
     */
    public function logout() {

        /**
         * Delete cookies - the time must be in the past,
         * so just negate what you added when creating the
         * cookie.
         */
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
            setcookie("cookname", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
            setcookie("cookid", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
        }

        /* Unset PHP session variables */
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);

        /* Reflect fact that user has logged out */
        $this->logged_in = false;

        /**
         * Remove from active users table and add to
         * active guests tables.
         */
        $this->conn->removeActiveUser($this->username);
        $this->conn->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);

        /* Set user level to guest */
        $this->username = GUEST_NAME;
        $this->userlevel = GUEST_LEVEL;
    }

    /**
     * register - Gets called when the user has just submitted the
     * registration form. Determines if there were any errors with
     * the entry fields, if so, it records the errors and returns
     * 1. If no errors were found, it registers the new user and
     * returns 0. Returns 2 if registration failed.
     */
    public function register($subuser, $subpass, $subemail) {

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $this->form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $this->form->setError($field, "* Nombre de usuario debajo de los 5 caracteres");
            } else if (strlen($subuser) > 30) {
                $this->form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z_])+$/", $subuser)) {
                $this->form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $this->form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($this->conn->usernameTaken($subuser)) {
                $this->form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($this->conn->usernameBanned($subuser)) {
                $this->form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $this->form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 5) {
                $this->form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $this->form->setError($field, "* La contraseña no es alfanumérico");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
            $this->form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $this->form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            if ($this->conn->addNewUser($subuser, md5($subpass), $subemail)) {
                if (EMAIL_WELCOME) {
                    $this->mailer->sendWelcome($subuser, $subemail, $subpass);
                }
                return 0;  //Nueva user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }

    public function SessionMasterRegister($subuser, $subpass, $subemail) {

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $this->form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 6) {
                $this->form->setError($field, "* Nombre de usuario debajo de los 6 caracteres");
            } else if (strlen($subuser) > 30) {
                $this->form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", $subuser)) {
                $this->form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $this->form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($this->conn->usernameTaken($subuser)) {
                $this->form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($this->conn->usernameBanned($subuser)) {
                $this->form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $this->form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $this->form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $this->form->setError($field, "* La contraseña no alfanumérico");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
            $this->form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $this->form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            //THE NAME OF THE CURRENT USER THE PARENT...
            $parent = $this->username;
            if ($this->conn->addNewMaster($subuser, md5($subpass), $subemail, $parent)) {
                if (EMAIL_WELCOME) {
                    $this->mailer->sendWelcome($subuser, $subemail, $subpass);
                }
                return 0;  //Nueva user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }

    public function SessionMemberRegister($subuser, $subpass, $subemail) {

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $this->form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $this->form->setError($field, "* Nombre de usuario debajo de los 5 caracteres");
            } else if (strlen($subuser) > 30) {
                $this->form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", $subuser)) {
                $this->form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $this->form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($this->conn->usernameTaken($subuser)) {
                $this->form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($this->conn->usernameBanned($subuser)) {
                $this->form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $this->form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $this->form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $this->form->setError($field, "* La contraseña no alfanumérico");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
            $this->form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $this->form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            //THE NAME OF THE CURRENT USER THE PARENT...
            $parent = $this->username;
            if ($this->conn->addNewMember($subuser, md5($subpass), $subemail, $parent)) {
                if (EMAIL_WELCOME) {
                    $this->mailer->sendWelcome($subuser, $subemail, $subpass);
                }
                return 0;  //Nueva user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }

    public function SessionAgentRegister($subuser, $subpass, $subemail) {

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $this->form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $this->form->setError($field, "* Nombre de usuario debajo de los 5 caracteres");
            } else if (strlen($subuser) > 30) {
                $this->form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", $subuser)) {
                $this->form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $this->form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($this->conn->usernameTaken($subuser)) {
                $this->form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($this->conn->usernameBanned($subuser)) {
                $this->form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $this->form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $this->form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $this->form->setError($field, "* La contraseña no alfanumérico");
            }
            /**
             * Note: I trimmed the password only after I checked the length
             * because if you fill the password field up with spaces
             * it looks like a lot more characters than 4, so it looks
             * kind of stupid to report "password too short".
             */
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
            $this->form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $this->form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            //THE NAME OF THE CURRENT USER THE PARENT...
            $parent = $this->username;
            if ($this->conn->addNewAgent($subuser, md5($subpass), $subemail, $parent)) {
                if (EMAIL_WELCOME) {
                    $this->mailer->sendWelcome($subuser, $subemail, $subpass);
                }
                return 0;  //Nueva user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }

    /**
     * editAccount - Attempts to edit the user's account information
     * including the password, which it first makes sure is correct
     * if entered, if so and the new password is in the right
     * format, the change is made. All other fields are changed
     * automatically.
     */
    public function editAccount($subcurpass, $subnewpass, $subemail, $subname) {

        /* Nueva password entered */
        if ($subnewpass) {
            /* Current Password error checking */
            $field = "curpass";  //Use field name for current password
            if (!$subcurpass) {
                $this->form->setError($field, "* La contraseña actual no introdujo");
            } else {
                /* Check if password too short or is not alphanumeric */
                $subcurpass = stripslashes($subcurpass);
                if (strlen($subcurpass) < 4 ||
                        !preg_match("/^([0-9a-z])+$/", ($subcurpass = trim($subcurpass)))) {
                    $this->form->setError($field, "* Contraseña actual incorrecta");
                }
                /* Password entered is incorrect */
                if ($this->conn->confirmUserPass($this->username, md5($subcurpass)) != 0) {
                    $this->form->setError($field, "* Contraseña actual incorrecta");
                }
            }

            /* Nueva Password error checking */
            $field = "newpass";  //Use field name for new password
            /* Spruce up password and check length */
            $subpass = stripslashes($subnewpass);
            if (strlen($subnewpass) < 8) {
                $this->form->setError($field, "* Nueva contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subnewpass = trim($subnewpass)))) {
                $this->form->setError($field, "* La nueva contraseña no alfanumérico");
            }
        }
        /* Change password attempted */ else if ($subcurpass) {
            /* Nueva Password error reporting */
            $field = "newpass";  //Use field name for new password
            $this->form->setError($field, "* La nueva contraseña no entrodujo");
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if ($subemail && strlen($subemail = trim($subemail)) > 0) {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $this->form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($this->form->num_errors > 0) {
            return false;  //Errors with form
        }

        /* Update password since there were no errors */
        if ($subcurpass && $subnewpass) {
            $this->conn->updateUserField($this->username, "password", md5($subnewpass));
        }

        /* Change Email */
        if ($subemail) {
            $this->conn->updateUserField($this->username, "email", $subemail);
        }
        if ($subname) {
            $this->conn->updateUserField($this->username, "name", $subname);
        }

        /* Success! */
        return true;
    }

    /**
     * isAdmin - Returns true if currently logged in user is
     * an administrator, false otherwise.
     */
    public function isAdmin() {
        return ($this->userlevel == ADMIN_LEVEL ||
                $this->username == ADMIN_NAME);
    }

    public function isMaster() {
        return ($this->userlevel == MASTER_LEVEL);
    }

    public function isAgent() {
        return ($this->userlevel == AGENT_LEVEL);
    }

    public function isMember() {
        return ($this->userlevel == AGENT_MEMBER_LEVEL);
    }

    /**
     * generateRandID - Generates a string made up of randomized
     * letters (lower and upper case) and digits and returns
     * the md5 hash of it to be used as a userid.
     */
    public function generateRandID() {
        return md5($this->generateRandStr(16));
    }

    /**
     * generateRandStr - Generates a string made up of randomized
     * letters (lower and upper case) and digits, the length
     * is a specified parameter.
     */
    public function generateRandStr($length) {
        $randstr = "";
        for ($i = 0; $i < $length; $i++) {
            $randnum = mt_rand(0, 61);
            if ($randnum < 10) {
                $randstr .= chr($randnum + 48);
            } else if ($randnum < 36) {
                $randstr .= chr($randnum + 55);
            } else {
                $randstr .= chr($randnum + 61);
            }
        }
        return $randstr;
    }
}

?>
