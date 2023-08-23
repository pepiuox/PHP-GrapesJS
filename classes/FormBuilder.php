<?php

class FormBuilder {

    private $connection;
    public $inputName;
    public $inputType;
    public $formAttribute;

    public function __construct() {
        
    }

    

    public function FormElements($element) {
        $elements = array(
            "input",
            "label",
            "select",
            "textarea",
            "button",
            "fieldset",
            "legend",
            "datalist",
            "output",
            "option",
            "optgroup"
        );
        if (in_array($element, $elements)) {
            echo '<input type="' . $this->InputType($type) . '" ' . $this->InputAttributes($name) . '>';
        } else {
            echo 'This is not a type of input';
        }
    }

    public function InputType($type) {
        $types = array(
            "button",
            "checkbox",
            "color",
            "date",
            "datetime-local",
            "email",
            "file",
            "hidden",
            "image",
            "month",
            "number",
            "password",
            "radio",
            "range",
            "reset",
            "search",
            "submit",
            "tel",
            "text",
            "time",
            "url",
            "week"
        );

        if (in_array($type, $types)) {
            echo '<input type="' . $type . '" ' . $this->ElementAttributes($name) . '>';
        } else {
            echo 'This is not a type of input';
        }
    }

    public function ElementAttributes($name) {
        echo 'name="' . $name . '" id="' . $name . '"';
    }

    public function InputLabel($name) {
        echo '<label for="' . $name . '" class="form-label">' . ucfirst($name) . ':</label>';
    }
}
