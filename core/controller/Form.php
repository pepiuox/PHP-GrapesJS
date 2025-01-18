<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class Form {

    public $values = array();  //Holds submitted form field values
    public $errors = array();  //Holds submitted form error messages
    public $num_errors;   //The number of errors in submitted form

    /* Class constructor */

    public function __construct() {
        /**
         * Get form value and error arrays, used when there
         * is an error with a user-submitted form.
         */
        if (isset($_SESSION['value_array']) && isset($_SESSION['error_array'])) {
            $this->values = $_SESSION['value_array'];
            $this->errors = $_SESSION['error_array'];
            $this->num_errors = count($this->errors);

            unset($_SESSION['value_array']);
            unset($_SESSION['error_array']);
        } else {
            $this->num_errors = 0;
        }
    }

    /**
     * setValue - Records the value typed into the given
     * form field by the user.
     */
    public function setValue($field, $value) {
        $this->values[$field] = $value;
    }

    /**
     * setError - Records new form error given the form
     * field name and the error message attached to it.
     */
    public function setError($field, $errmsg) {
        $this->errors[$field] = $errmsg;
        $this->num_errors = count($this->errors);
    }

    /**
     * value - Returns the value attached to the given
     * field, if none exists, the empty string is returned.
     */
    public function value($field) {
        if (array_key_exists($field, $this->values)) {
            return htmlspecialchars(stripslashes($this->values[$field]));
        } else {
            return "";
        }
    }

    /**
     * error - Returns the error message attached to the
     * given field, if none exists, the empty string is returned.
     */
    public function error($field) {
        if (array_key_exists($field, $this->errors)) {
            return "<font size=\"2\" color=\"#ff0000\">" . $this->errors[$field] . "</font>";
        } else {
            return "";
        }
    }

    /* getErrorArray - Returns the array of error messages */

    public function getErrorArray() {
        return $this->errors;
    }
}

?>
