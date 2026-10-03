<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
//  MIGRATED & SECURED:
//  - All HTML attributes now escaped with htmlspecialchars() (XSS prevention)
//  - Removed executable test code at the end of the file
//  - Fixed Form() method that was closing the form prematurely
//
class FormBuilder {
    private array $elements = [];

    /** Escapa HTML para prevenir XSS */
    protected function e(?string $str): string {
        return htmlspecialchars((string)($str ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /** Abre el formulario (no lo cierra) */
    public function addForm(string $formName, string $formAttribute, string $formAction): void {
        echo '<form action="' . $this->e($formAction) . '" '
        . 'method="' . $this->e($formAttribute) . '" '
        . 'name="' . $this->e($formName) . '">' . "\n";
    }

    /** Cierra el formulario */
    public function closeForm(): void {
        echo '</form>' . "\n";
    }

    /**
     * @deprecated Usa addForm() + closeForm()
     */
    public function Form(string $formName, string $formAttribute, string $formAction): void {
        $this->addForm($formName, $formAttribute, $formAction);
        $this->closeForm();
    }

    /**
     * Añade un elemento genérico al formulario.
     */
    public function addElements(string $formElement, string $inputType, string $inputName): void {
        $tagName = $this->FormElements($formElement, $inputType);
        echo '<' . $tagName . $this->ElementAttributes($inputName) . '>' . "\n";
    }

    public function FormElements(string $element, string $inputType = ''): string {
        $allowed = [
            'input','label','select','textarea','button','fieldset',
            'legend','datalist','output','option','optgroup'
        ];
        if (!in_array($element, $allowed, true)) {
            return '!-- invalid element --';
        }
        if ($element === 'input') {
            return 'input' . $this->InputType($inputType);
        }
        return $element;
    }

    public function ElementAttributes(string $name): string {
        $safe = $this->e($name);
        return ' name="' . $safe . '" id="' . $safe . '"';
    }

    public function InputType(string $type): string {
        $types = [
            'button','checkbox','color','date','datetime-local','email','file',
            'hidden','image','month','number','password','radio','range','reset',
            'search','submit','tel','text','time','url','week'
        ];
        if (in_array($type, $types, true)) {
            return ' type="' . $this->e($type) . '"';
        }
        return ' type="text"'; // fallback seguro
    }

    /* ============================================================
     *  ELEMENTOS ESPECÍFICOS (todos con escape XSS)
     * ============================================================ */

    public function inputElement(array $array): void {
        $element = $array['form_element'] ?? 'input';
        $form    = $array['elements'][$element] ?? [];
        $label   = $array['element_label'] ?? '';

        if (!empty($label)) {
            echo '<label for="' . $this->e($form['id'] ?? '') . '" class="form-label">'
            . $this->e(ucfirst($label)) . '</label>' . "\n";
        }

        echo '<input type="' . $this->e($form['type'] ?? 'text') . '"';
        if (!empty($form['name']))        echo ' name="'        . $this->e($form['name'])        . '"';
        if (!empty($form['id']))          echo ' id="'          . $this->e($form['id'])          . '"';
        if (!empty($form['class']))       echo ' class="'       . $this->e($form['class'])       . '"';
        if (!empty($form['placeholder'])) echo ' placeholder="' . $this->e($form['placeholder']) . '"';
        echo '>' . "\n";
    }

    public function selectElement(array $array): void {
        $element = $array['form_element'] ?? 'select';
        $form    = $array['elements'][$element] ?? [];
        $label   = $array['element_label'] ?? '';

        if (!empty($label)) {
            echo '<label for="' . $this->e($form['id'] ?? '') . '" class="form-label">'
            . $this->e(ucfirst($label)) . '</label>' . "\n";
        }

        echo '<select';
        if (!empty($form['name']))  echo ' name="'  . $this->e($form['name'])  . '"';
        if (!empty($form['id']))    echo ' id="'    . $this->e($form['id'])    . '"';
        if (!empty($form['class'])) echo ' class="' . $this->e($form['class']) . '"';
        echo '>' . "\n";
        echo '<option></option>' . "\n";
        echo '</select>' . "\n";
    }

    public function textareaElement(array $array): void {
        $element = $array['form_element'] ?? 'textarea';
        $form    = $array['elements'][$element] ?? [];
        $label   = $array['element_label'] ?? '';

        if (!empty($label)) {
            echo '<label for="' . $this->e($form['id'] ?? '') . '" class="form-label">'
            . $this->e(ucfirst($label)) . '</label>' . "\n";
        }

        echo '<textarea';
        if (!empty($form['name']))  echo ' name="'  . $this->e($form['name'])  . '"';
        if (!empty($form['id']))    echo ' id="'    . $this->e($form['id'])    . '"';
        if (!empty($form['class'])) echo ' class="' . $this->e($form['class']) . '"';
        if (!empty($form['rows']))  echo ' rows="'  . $this->e($form['rows'])  . '"';
        if (!empty($form['cols']))  echo ' cols="'  . $this->e($form['cols'])  . '"';
        echo '></textarea>' . "\n";
    }

    public function buttonElement(array $array): void {
        $element = $array['form_element'] ?? 'button';
        $form    = $array['elements'][$element] ?? [];
        $label   = $array['element_label'] ?? '';

        if (!empty($label)) {
            echo '<label for="' . $this->e($form['id'] ?? '') . '" class="form-label">'
            . $this->e(ucfirst($label)) . '</label>' . "\n";
        }

        echo '<button type="' . $this->e($form['type'] ?? 'button') . '"';
        if (!empty($form['name']))  echo ' name="'  . $this->e($form['name'])  . '"';
        if (!empty($form['id']))    echo ' id="'    . $this->e($form['id'])    . '"';
        if (!empty($form['class'])) echo ' class="' . $this->e($form['class']) . '"';
        echo '>' . $this->e($form['value'] ?? 'Submit') . '</button>' . "\n";
    }
}
