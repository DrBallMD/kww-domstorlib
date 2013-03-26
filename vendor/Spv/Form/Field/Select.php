<?php

/**
 * Description of Select
 *
 * @author pahhan
 */
class Spv_Form_Field_Select extends Spv_Form_Form
{
    protected $options;
    protected $multiple = false;

    protected $template = 'fields/select.html.php';

    /**
     *
     * @param array $options
     * @return \Spv_Form_Field_Select
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getAttrName()
    {
        $name = parent::getAttrName();

        if( $this->multiple )
            $name.= '[]';

        return $name;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setMultiple($multiple)
    {
        $this->multiple = (bool) $multiple;
        return $this;
    }

    public function isMultiple()
    {
        return $this->multiple;
    }

    public function multiple()
    {
        if( $this->isMultiple() )
            return 'multiple="multiple"';
    }

    public function getValue()
    {
        $val = parent::getValue();

        if( $this->multiple and !is_array($val))
            return array();

        return $val === ''?
                NULL :
                $val;
    }

    public function isSelected($key)
    {
        if( $this->multiple )
        {
            return in_array($key, $this->getValue());
        }
        else
        {
            return $key == $this->getValue();
        }
    }

    public function selected($key)
    {
        if( $this->isSelected($key) )
            return 'selected="selected"';
    }
}

