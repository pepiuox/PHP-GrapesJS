<?php

/**
 * Session.php
 * 
 * The Session class is meant to simplify the task of keeping
 * track of logged in users and also guests.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: September 22, 2015
 * Modified by: Arman G. de Castro, October 3, 2008
 * email: armandecastro@gmail.com
 */
$bUrl = $_SERVER['HTTP_HOST'];

define("B_URL", "http://$bUrl/");
define("SYST", "http://$bUrl/system/");

include("database.php");
include("mailer.php");
include("form.php");

$resultc = $conn->query("SELECT type_name, value FROM config");
while ($rowt = $resultc->fetch_array()) {
    define($rowt['type_name'], $rowt['value']);
}

class Session {

    var $username;     //Username given on sign-up
    var $userid;       //Random value generated on current login
    var $userlevel;    //The level to which the user pertains
    var $time;         //Time user was last active (page loaded)
    var $logged_in;    //True if user is logged in, false otherwise
    var $userinfo = array();  //The array holding all user info
    var $url;          //The page url current being viewed
    var $referrer;     //Last recorded site page viewed

    /**
     * Note: referrer should really only be considered the actual
     * page referrer in process.php, any other time it may be
     * inaccurate.
     */
    /* Class constructor */

    public function __construct() {
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
        global $conn;  //The database connection
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
            $conn->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
        }
        /* Update users last active timestamp */ else {
            $conn->addActiveUser($this->username, $this->time);
        }

        /* Remove inactive visitors from database */
        $conn->removeInactiveUsers();
        $conn->removeInactiveGuests();

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
    public  function checkLogin() {
        global $conn;  //The database connection
        /* Check if user has been remembered */
        if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
            $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
            $this->userid = $_SESSION['userid'] = $_COOKIE['cookid'];
        }

        /* Username and userid have been set and not guest */
        if (isset($_SESSION['username']) && isset($_SESSION['userid']) &&
                $_SESSION['username'] != GUEST_NAME) {
            /* Confirm that username and userid are valid */
            if ($conn->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0) {
                /* Variables are incorrect, user not logged in */
                unset($_SESSION['username']);
                unset($_SESSION['userid']);
                return false;
            }

            /* User is logged in, set class variables */
            $this->userinfo = $conn->getUserInfo($_SESSION['username']);
            $this->username = $this->userinfo['username'];
            $this->userid = $this->userinfo['userid'];
            $this->userlevel = $this->userinfo['userlevel'];
            return true;
        }
        /* User not logged in */ else {
            return false;
        }
    }

    /**
     * login - The user has submitted his username and password
     * through the login form, this function checks the authenticity
     * of that information in the database and creates the session.
     * Effectively logging in the user if all goes well.
     */
    public function login($subuser, $subpass, $subremember) {
        global $conn, $form;  //The database and form object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* No introdujo el Nombre de usuario");
        } else {
            /* Check if username is not alphanumeric */
            if (!preg_match("/^([0-9a-z])*$/", $subuser)) {
                $form->setError($field, "* Nombre de usuario no alfanumérico");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* No introdujo la contraseña");
        }

        /* Return if form errors exist */
        if ($form->num_errors > 0) {
            return false;
        }

        /* Checks that username is in database and password is correct */
        $subuser = stripslashes($subuser);
        $result = $conn->confirmUserPass($subuser, md5($subpass));

        /* Check error codes */
        if ($result == 1) {
            $field = "user";
            $form->setError($field, "* Usuario no encontrado");
        } else if ($result == 2) {
            $field = "pass";
            $form->setError($field, "* Contraseña no válida");
        }

        /* Return if form errors exist */
        if ($form->num_errors > 0) {
            return false;
        }

        /* Username and password correct, register session variables */
        $this->userinfo = $conn->getUserInfo($subuser);
        $this->username = $_SESSION['username'] = $this->userinfo['username'];
        $this->userid = $_SESSION['userid'] = $this->generateRandID();
        $this->userlevel = $this->userinfo['userlevel'];

        /* Insert userid into database and update active users table */
        $conn->updateUserField($this->username, "userid", $this->userid);
        $conn->addActiveUser($this->username, $this->time);
        $conn->removeActiveGuest($_SERVER['REMOTE_ADDR']);

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
        global $conn;  //The database connection
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
        unset($_SESSION['userid']);

        /* Reflect fact that user has logged out */
        $this->logged_in = false;

        /**
         * Remove from active users table and add to
         * active guests tables.
         */
        $conn->removeActiveUser($this->username);
        $conn->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);

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
        global $conn, $form, $mailer;  //The database, form and mailer object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $form->setError($field, "* Nombre de usuario debajo de los 5 caracteres");
            } else if (strlen($subuser) > 30) {
                $form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z_])+$/", $subuser)) {
                $form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($conn->usernameTaken($subuser)) {
                $form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($conn->usernameBanned($subuser)) {
                $form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 5) {
                $form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $form->setError($field, "* La contraseña no es alfanumérico");
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
            $form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            if ($conn->addNewUser($subuser, md5($subpass), $subemail)) {
                if (EMAIL_WELCOME) {
                    $mailer->sendWelcome($subuser, $subemail, $subpass);
                }
                return 0;  //Nueva user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }

    public function SessionMasterRegister($subuser, $subpass, $subemail) {

        global $conn, $form, $mailer;  //The database, form and mailer object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $form->setError($field, "* Nombre de usuario debajo de los 5 caracteres");
            } else if (strlen($subuser) > 30) {
                $form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", $subuser)) {
                $form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($conn->usernameTaken($subuser)) {
                $form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($conn->usernameBanned($subuser)) {
                $form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $form->setError($field, "* La contraseña no alfanumérico");
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
            $form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            //THE NAME OF THE CURRENT USER THE PARENT...
            $parent = $this->username;
            if ($conn->addNewMaster($subuser, md5($subpass), $subemail, $parent)) {
                if (EMAIL_WELCOME) {
                    $mailer->sendWelcome($subuser, $subemail, $subpass);
                }
                return 0;  //Nueva user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }

    public function SessionMemberRegister($subuser, $subpass, $subemail) {

        global $conn, $form, $mailer;  //The database, form and mailer object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $form->setError($field, "* Nombre de usuario debajo de los 5 caracteres");
            } else if (strlen($subuser) > 30) {
                $form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", $subuser)) {
                $form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($conn->usernameTaken($subuser)) {
                $form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($conn->usernameBanned($subuser)) {
                $form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $form->setError($field, "* La contraseña no alfanumérico");
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
            $form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            //THE NAME OF THE CURRENT USER THE PARENT...
            $parent = $this->username;
            if ($conn->addNewMember($subuser, md5($subpass), $subemail, $parent)) {
                if (EMAIL_WELCOME) {
                    $mailer->sendWelcome($subuser, $subemail, $subpass);
                }
                return 0;  //Nueva user added succesfully
            } else {
                return 2;  //Registration attempt failed
            }
        }
    }

    public function SessionAgentRegister($subuser, $subpass, $subemail) {

        global $conn, $form, $mailer;  //The database, form and mailer object

        /* Username error checking */
        $field = "user";  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Nombre de usuario no introducido");
        } else {
            /* Spruce up username, check length */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5) {
                $form->setError($field, "* Nombre de usuario debajo de los 5 caracteres");
            } else if (strlen($subuser) > 30) {
                $form->setError($field, "* Nombre de usuario por encima de 30 caracteres");
            }
            /* Check if username is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", $subuser)) {
                $form->setError($field, "* Nombre de usuario no alfanumérico");
            }
            /* Check if username is reserved */ else if (strcasecmp($subuser, GUEST_NAME) == 0) {
                $form->setError($field, "* Nombre de usuario palabra reservada");
            }
            /* Check if username is already in use */ else if ($conn->usernameTaken($subuser)) {
                $form->setError($field, "* Nombre de usuario ya está en uso");
            }
            /* Check if username is banned */ else if ($conn->usernameBanned($subuser)) {
                $form->setError($field, "* Nombre de usuario prohibido");
            }
        }

        /* Password error checking */
        $field = "pass";  //Use field name for password
        if (!$subpass) {
            $form->setError($field, "* La contraseña no entrodujo");
        } else {
            /* Spruce up password and check length */
            $subpass = stripslashes($subpass);
            if (strlen($subpass) < 4) {
                $form->setError($field, "* Contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subpass = trim($subpass)))) {
                $form->setError($field, "* La contraseña no alfanumérico");
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
            $form->setError($field, "* El correo electrónico no entró");
        } else {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return 1;  //Errors with form
        }
        /* No errors, add the new account to the */ else {
            //THE NAME OF THE CURRENT USER THE PARENT...
            $parent = $this->username;
            if ($conn->addNewAgent($subuser, md5($subpass), $subemail, $parent)) {
                if (EMAIL_WELCOME) {
                    $mailer->sendWelcome($subuser, $subemail, $subpass);
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
        global $conn, $form;  //The database and form object
        /* Nueva password entered */
        if ($subnewpass) {
            /* Current Password error checking */
            $field = "curpass";  //Use field name for current password
            if (!$subcurpass) {
                $form->setError($field, "* La contraseña actual no entrodujo");
            } else {
                /* Check if password too short or is not alphanumeric */
                $subcurpass = stripslashes($subcurpass);
                if (strlen($subcurpass) < 4 ||
                        !preg_match("/^([0-9a-z])+$/", ($subcurpass = trim($subcurpass)))) {
                    $form->setError($field, "* Contraseña actual incorrecta");
                }
                /* Password entered is incorrect */
                if ($conn->confirmUserPass($this->username, md5($subcurpass)) != 0) {
                    $form->setError($field, "* Contraseña actual incorrecta");
                }
            }

            /* Nueva Password error checking */
            $field = "newpass";  //Use field name for new password
            /* Spruce up password and check length */
            $subpass = stripslashes($subnewpass);
            if (strlen($subnewpass) < 4) {
                $form->setError($field, "* Nueva contraseña demasiado corta");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("/^([0-9a-z])+$/", ($subnewpass = trim($subnewpass)))) {
                $form->setError($field, "* La nueva contraseña no alfanumérico");
            }
        }
        /* Change password attempted */ else if ($subcurpass) {
            /* Nueva Password error reporting */
            $field = "newpass";  //Use field name for new password
            $form->setError($field, "* La nueva contraseña no entrodujo");
        }

        /* Email error checking */
        $field = "email";  //Use field name for email
        if ($subemail && strlen($subemail = trim($subemail)) > 0) {
            /* Check if valid email address */
            $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                    . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                    . "\.([a-z]{2,}){1}$/";
            if (!preg_match($regex, $subemail)) {
                $form->setError($field, "* El correo electrónico no es válido");
            }
            $subemail = stripslashes($subemail);
        }

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            return false;  //Errors with form
        }

        /* Update password since there were no errors */
        if ($subcurpass && $subnewpass) {
            $conn->updateUserField($this->username, "password", md5($subnewpass));
        }

        /* Change Email */
        if ($subemail) {
            $conn->updateUserField($this->username, "email", $subemail);
        }
        if ($subname) {
            $conn->updateUserField($this->username, "name", $subname);
        }

        /* Success! */
        return true;
    }

    /**
     * isAdmin - Returns true if currently logged in user is
     * an administrator, false otherwise.
     */
    function isAdmin() {
        return ($this->userlevel == ADMIN_LEVEL ||
                $this->username == ADMIN_NAME);
    }

    function isMaster() {
        return ($this->userlevel == MASTER_LEVEL);
    }

    function isAgent() {
        return ($this->userlevel == AGENT_LEVEL);
    }

    function isMember() {
        return ($this->userlevel == AGENT_MEMBER_LEVEL);
    }

    /**
     * generateRandID - Generates a string made up of randomized
     * letters (lower and upper case) and digits and returns
     * the md5 hash of it to be used as a userid.
     */
    function generateRandID() {
        return md5($this->generateRandStr(16));
    }

    /**
     * generateRandStr - Generates a string made up of randomized
     * letters (lower and upper case) and digits, the length
     * is a specified parameter.
     */
    function generateRandStr($length) {
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

/**
 * Initialize session object - This must be initialized before
 * the form object because the form uses session variables,
 * which cannot be accessed unless the session has started.
 */
$session = new Session;

/* Initialize form object */
$form = new Form;
?>
