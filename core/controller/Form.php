<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
//  MIGRATED & SECURED:
//  - Removed stripslashes() (Magic Quotes removed in PHP 5.4)
//  - value() now uses modern htmlspecialchars() flags (ENT_QUOTES | ENT_HTML5, UTF-8)
//  - error() escapes the message to prevent XSS
//  - Replaced obsolete <font> tag with <span class="text-danger"> (Bootstrap 5)
//
class Form {
    public array $values = [];      // Holds submitted form field values
    public array $errors = [];      // Holds submitted form error messages
    public int $num_errors = 0;     // The number of errors in submitted form

    /**
     * Class constructor.
     * Restores values/errors from session if available.
     */
    public function __construct() {
        if (isset($_SESSION['value_array']) && isset($_SESSION['error_array'])) {
            $this->values       = (array) $_SESSION['value_array'];
            $this->errors       = (array) $_SESSION['error_array'];
            $this->num_errors   = count($this->errors);
            unset($_SESSION['value_array'], $_SESSION['error_array']);
        }
    }

    /**
     * setValue - Records the value typed into the given form field by the user.
     */
    public function setValue(string $field, mixed $value): void {
        $this->values[$field] = $value;
    }

    /**
     * setError - Records new form error given the form field name and message.
     */
    public function setError(string $field, string $errmsg): void {
        $this->errors[$field] = $errmsg;
        $this->num_errors = count($this->errors);
    }

    /**
     * value - Returns the value attached to the given field, safely escaped for HTML.
     *         If none exists, the empty string is returned.
     */
    public function value(string $field): string {
        if (array_key_exists($field, $this->values)) {
            // Sin stripslashes (obsoleto). Solo escape HTML seguro.
            return htmlspecialchars((string) $this->values[$field], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return '';
    }

    /**
     * error - Returns the error message attached to the given field, safely escaped.
     *         Uses Bootstrap classes instead of obsolete <font> tag.
     */
    public function error(string $field): string {
        if (array_key_exists($field, $this->errors)) {
            $safeMsg = htmlspecialchars((string) $this->errors[$field], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return '<span class="text-danger small">' . $safeMsg . '</span>';
        }
        return '';
    }

    /**
     * getErrorArray - Returns the array of error messages.
     */
    public function getErrorArray(): array {
        return $this->errors;
    }

    /**
     * getValueArray - Returns the array of submitted values.
     */
    public function getValueArray(): array {
        return $this->values;
    }

    /**
     * hasErrors - Returns true if there are any errors.
     */
    public function hasErrors(): bool {
        return $this->num_errors > 0;
    }
}
