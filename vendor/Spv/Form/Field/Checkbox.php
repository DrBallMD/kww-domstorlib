<?php

/**
 * Description of InputText
 *
 * @author pahhan
 */
class Spv_Form_Field_Checkbox extends Spv_Form_Form
{
    protected $template = 'fields/checkbox.html.php';
    protected $value_key = '1';

    public function getValue()
    {
        return $this->isChecked()?
            parent::getValue() :
            NULL;
    }

    public function getValueKey()
    {
        return $this->value_key;
    }

    public function setValueKey($value_key)
    {
        $this->value_key = $value_key;
        return $this;
    }

    public function isChecked()
    {
        return parent::getValue() == $this->getValueKey();
    }

    public function checked()
    {
        if( $this->isChecked() )
            return 'checked="checked"';
    }
}