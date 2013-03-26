<?php

/**
 * Description of Select
 *
 * @author pahhan
 */
class Spv_Form_Field_RadioSet extends Spv_Form_Form
{
    protected $options;

    protected $template = 'fields/radio_set.html.php';

    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getOptionId($key)
    {
        if( !isset($this->options[$key]) ) return;

        return sprintf('%s_%s_id', $this->getId(), $key);
    }

    public function isChecked($key)
    {

        if(is_null($this->getValue()) ) return false;

        return $key == $this->getValue();
    }

    public function checked($key)
    {
        if( $this->isChecked($key) )
            return 'checked="checked"';
    }
}

