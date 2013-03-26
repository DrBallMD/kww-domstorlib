<?php

/**
 * Description of InputText
 *
 * @author pahhan
 */
class Spv_Form_Field_InputText extends Spv_Form_Form
{
    protected $template = 'fields/input_text.html.php';

    public function getValue()
    {
        return parent::getValue() === ''?
                NULL :
                parent::getValue();
    }
}