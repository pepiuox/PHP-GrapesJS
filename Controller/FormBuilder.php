<?php

class FormBuilder {

    private $elements;

    public function addForm($formName, $formAttribute, $formAction) {
        $this->Form($formName, $formAttribute, $formAction);
    }

    public function Form($formName, $formAttribute, $formAction) {
        echo '<form ' . $this->FormAttributes($formAttribute, $formAction) . ' ' . $this->FormName($formName) . '>' . "\n";
        echo '</form>' . "\n";
    }

    public function FormName($formName) {
        return 'name="' . $formName . '"';
    }

    public function FormAttributes($formAttribute, $formAction = null) {

        if (!empty($formAction)) {
            return 'action="' . $formAction . '" method="' . $formAttribute . '"';
        } else {
            return ' method="' . $formAttribute . '"';
        }
    }

    public function FormElements($element, $inputType = '') {
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
            if ($element == 'input') {
                return 'input' . $this->InputType($inputType);
            } elseif ($element == 'select') {
                return 'select';
            } else {
                return 'Select a element';
            }
        } else {
            return 'This is not a type of element';
        }
    }

    public function ElementAttributes($name) {
        return ' name="' . $name . '" id="' . $name . '"';
    }

    public function addElements($formElement, $inputType, $inputName) {
        echo '<' . $this->FormElements($formElement, $inputType) . $this->ElementAttributes($inputName) . ' >';
    }

    public function selectElement($array) {
        $this->elements = $array;
        $element = $this->elements['form_element'];
        $form = $this->elements['elements'][$element];
        $label = $this->elements['element_label'];

        $name = $form['name'];
        $id = $form['id'];
        $class = $form['class'];

        if (!empty($label)) {
            echo '<label for="' . $label . '" class="form-label">' . ucfirst($label) . '</label>' . "\n";
        }
        echo '<' . $element;
        if (!empty($name)) {
            echo ' name="' . $name . '" ';
        }
        if (!empty($id)) {
            echo 'id="' . $id . '" ';
        }
        if (!empty($class)) {
            echo 'class="' . $class . '" ';
        }

        echo '>';
        echo '<option></option>' . "\n";
        echo '</' . $element . '>' . "\n";
    }

    public function buttonElement($array) {
        $this->elements = $array;
        $element = $this->elements['form_element'];
        $form = $this->elements['elements'][$element];
        $label = $this->elements['element_label'];
        $type = $form['type'];
        $name = $form['name'];
        $id = $form['id'];
        $class = $form['class'];
        $value = $form['value'];
        if (!empty($label)) {
            echo '<label for="' . $label . '" class="form-label">' . ucfirst($label) . '</label>' . "\n";
        }
        echo '<' . $element . ' type="' . $type . '" ';
        if (!empty($name)) {
            echo 'name="' . $name . '" ';
        }
        if (!empty($id)) {
            echo 'id="' . $id . '" ';
        }
        if (!empty($class)) {
            echo 'class="' . $class . '" ';
        }

        echo '>';
        if (!empty($value)) {
            echo '' . $value . '';
        } else {
            echo 'Add value';
        }
        echo '</' . $element . '>' . "\n";
    }

    public function inputElement($array) {
        $this->elements = $array;

        $element = $this->elements['form_element'];
        $form = $this->elements['elements'][$element];
        $label = $this->elements['element_label'];
        $type = $form['type'];
        $name = $form['name'];
        $id = $form['id'];
        $class = $form['class'];
        $placeholder = $form['placeholder'];
        if (!empty($label)) {
            echo '<label for="' . $label . '" class="form-label">' . ucfirst($label) . '</label>' . "\n";
        }
        echo '<' . $element . ' type="' . $type . '" ';
        if (!empty($name)) {
            echo 'name="' . $name . '" ';
        }
        if (!empty($id)) {
            echo 'id="' . $id . '" ';
        }
        if (!empty($class)) {
            echo 'class="' . $class . '" ';
        }
        if (!empty($placeholder)) {
            echo 'placeholder="' . $placeholder . '" ';
        }
        echo '>' . "\n";
    }

    public function textareaElement($array) {
        $this->elements = $array;

        $element = $this->elements['form_element'];
        $form = $this->elements['elements'][$element];
        $label = $this->elements['element_label'];
        $name = $form['name'];
        $id = $form['id'];
        $class = $form['class'];
        $rows = $form['rows'];
        $cols = $form['cols'];
        if (!empty($label)) {
            echo '<label for="' . $label . '" class="form-label">' . ucfirst($label) . '</label>' . "\n";
        }
        echo '<' . $element;
        if (!empty($name)) {
            echo ' name="' . $name . '" ';
        }
        if (!empty($id)) {
            echo 'id="' . $id . '" ';
        }
        if (!empty($class)) {
            echo 'class="' . $class . '" ';
        }
        if (!empty($rows)) {
            echo 'rows="' . $rows . '" ';
        }
        if (!empty($cols)) {
            echo ' cols="' . $cols . '" ';
        }
        echo '></textarea>' . "\n";
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
            return ' type="' . $type . '"';
        } else {
            return 'This is not a type of input';
        }
    }
}

$form = new FormBuilder();
echo '<!DOCTYPE html>'
 . '<html>'
 . '<head>'
 . '<style>.prueba{padding:24px;}</style>'
 . '</head>'
 . '<body>';
//$form->Elements('select', '', 'name');
$form->addForm('new', 'post', '');
$form->addElements('input', 'text', 'name');
$array = array('form_element' => 'button',
    'element_label' => 'text',
    'elements' => array(
        'button' => array(
            'type' => 'text',
            'name' => 'test',
            'id' => 'test',
            'class' => 'prueba',
            'value' => 'Send'
        )
    )
);
$form->buttonElement($array);

$input1 = array('form_element' => 'input',
    'element_label' => 'text',
    'elements' => array(
        'input' => array(
            'type' => 'text',
            'name' => 'test',
            'id' => 'test',
            'class' => '',
            'placeholder' => 'this a test'
        )
    )
);
$form->inputElement($input1);

$select1 = array('form_element' => 'select',
    'element_label' => 'text',
    'elements' => array(
        'select' => array(
            'name' => 'test',
            'id' => 'test',
            'class' => ''
        )
    )
);
$form->selectElement($select1);
$text1 = array('form_element' => 'textarea',
    'element_label' => 'text',
    'elements' => array(
        'textarea' => array(
            'name' => 'test',
            'id' => 'test',
            'class' => '',
            'rows' => '4',
            'cols' => '40'
        )
    )
);
$form->textareaElement($text1);
echo '</body></html>';
